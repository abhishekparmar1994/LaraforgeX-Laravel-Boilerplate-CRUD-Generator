<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing Client accounts and Client 360 overview.
 */
class ClientController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display client list.
     */
    public function index(Request $request): View
    {
        $query = Client::with(['company', 'primaryContact', 'projects', 'payments'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('client_code', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->paginate(15);
        $companies = Company::orderBy('name')->get();
        $contacts = Contact::orderBy('first_name')->get();

        return view('admin.clients.index', compact('clients', 'companies', 'contacts'));
    }

    /**
     * Store new client.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:crm_companies,id',
            'primary_contact_id' => 'nullable|exists:crm_contacts,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'industry' => 'nullable|string|max:100',
            'client_since' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $count = Client::count() + 1001;
        $validated['client_code'] = 'CLI-' . $count;

        $client = Client::create($validated);

        $this->activityLogger->execute($client, 'note', "Client account '{$client->name}' ({$client->client_code}) established.");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Client created successfully', 'client' => $client]);
        }

        return redirect()->back()->with('success', 'Client created successfully!');
    }

    /**
     * Display client 360 profile.
     */
    public function show(Client $client): View
    {
        $client->load([
            'company.contacts',
            'primaryContact',
            'projects.manager',
            'projects.developers',
            'websites.credentials',
            'payments',
            'followUps.assignedTo',
            'activities.user',
            'notes.user',
        ]);

        $companies = Company::orderBy('name')->get();
        $contacts = Contact::where('company_id', $client->company_id)->get();

        return view('admin.clients.show', compact('client', 'companies', 'contacts'));
    }

    /**
     * Update client details.
     */
    public function update(Request $request, Client $client): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:crm_companies,id',
            'primary_contact_id' => 'nullable|exists:crm_contacts,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'industry' => 'nullable|string|max:100',
            'client_since' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Client updated successfully', 'client' => $client]);
        }

        return redirect()->back()->with('success', 'Client updated successfully!');
    }

    /**
     * Delete client.
     */
    public function destroy(Client $client): RedirectResponse|JsonResponse
    {
        $name = $client->name;
        $client->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Client '{$name}' removed."]);
        }

        return redirect('/admin/clients')->with('success', "Client '{$name}' deleted.");
    }
}

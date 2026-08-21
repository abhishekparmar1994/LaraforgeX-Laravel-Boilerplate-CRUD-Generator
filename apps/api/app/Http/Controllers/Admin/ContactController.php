<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing individual client and company contacts.
 */
class ContactController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display contacts directory.
     */
    public function index(Request $request): View
    {
        $query = Contact::with('company')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                    ->orWhere('last_name', 'like', "%{$s}%")
                    ->orWhere('job_title', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $contacts = $query->paginate(15);
        $companies = Company::orderBy('name')->get();

        return view('admin.contacts.index', compact('contacts', 'companies'));
    }

    /**
     * Store new contact.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:crm_companies,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'linkedin' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'preferred_contact_method' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $contact = Contact::create($validated);

        $this->activityLogger->execute($contact, 'note', "Contact '{$contact->name}' added.");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Contact created successfully', 'contact' => $contact]);
        }

        return redirect()->back()->with('success', 'Contact created successfully!');
    }

    /**
     * Display contact details.
     */
    public function show(Contact $contact): View
    {
        $contact->load(['company.projects', 'followUps.assignedTo', 'activities.user', 'notes.user']);
        $companies = Company::orderBy('name')->get();

        return view('admin.contacts.show', compact('contact', 'companies'));
    }

    /**
     * Update contact details.
     */
    public function update(Request $request, Contact $contact): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:crm_companies,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'linkedin' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'preferred_contact_method' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $contact->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Contact updated successfully', 'contact' => $contact]);
        }

        return redirect()->back()->with('success', 'Contact updated successfully!');
    }

    /**
     * Delete contact.
     */
    public function destroy(Contact $contact): RedirectResponse|JsonResponse
    {
        $name = $contact->name;
        $contact->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Contact '{$name}' deleted."]);
        }

        return redirect('/admin/contacts')->with('success', "Contact '{$name}' deleted.");
    }
}

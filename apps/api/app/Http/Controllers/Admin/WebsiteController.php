<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Project;
use App\Models\Website;
use App\Models\WebsiteCredential;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing client websites, technical infrastructure, and secure credentials vault.
 */
class WebsiteController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display websites directory.
     */
    public function index(Request $request): View
    {
        $query = Website::with(['client', 'company', 'project'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('domain', 'like', "%{$s}%")
                    ->orWhere('technology', 'like', "%{$s}%")
                    ->orWhere('hosting_provider', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('filter')) {
            if ($request->filter === 'domain_expiring') {
                $query->whereNotNull('domain_expiry_date')
                    ->whereBetween('domain_expiry_date', [now(), now()->addDays(30)]);
            } elseif ($request->filter === 'hosting_expiring') {
                $query->whereNotNull('hosting_expiry_date')
                    ->whereBetween('hosting_expiry_date', [now(), now()->addDays(30)]);
            }
        }

        $websites = $query->paginate(15);
        $clients = Client::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('admin.websites.index', compact('websites', 'clients', 'projects'));
    }

    /**
     * Store new website record.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'client_id' => 'nullable|exists:crm_clients,id',
            'project_id' => 'nullable|exists:crm_projects,id',
            'title' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'domain_provider' => 'nullable|string|max:100',
            'domain_expiry_date' => 'nullable|date',
            'hosting_provider' => 'nullable|string|max:100',
            'hosting_expiry_date' => 'nullable|date',
            'technology' => 'nullable|string|max:100',
            'framework' => 'nullable|string|max:100',
            'cms' => 'nullable|string|max:100',
            'repository_url' => 'nullable|string|max:255',
            'github_url' => 'nullable|string|max:255',
            'server_ip' => 'nullable|string|max:50',
            'deployment_platform' => 'nullable|string|max:100',
            'database_type' => 'nullable|string|max:100',
            'ssl_status' => 'nullable|in:active,expiring_soon,expired,not_installed',
            'ssl_expiry_date' => 'nullable|date',
            'status' => 'nullable|in:planning,design,development,testing,client_review,live,maintenance,suspended,completed',
            'design_progress' => 'nullable|integer|min:0|max:100',
            'dev_progress' => 'nullable|integer|min:0|max:100',
            'testing_progress' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['url']) && !empty($validated['domain'])) {
            $validated['url'] = \Illuminate\Support\Str::startsWith($validated['domain'], ['http://', 'https://']) 
                ? $validated['domain'] 
                : 'https://' . $validated['domain'];
        }
        if (empty($validated['status'])) {
            $validated['status'] = 'development';
        }
        if (empty($validated['ssl_status'])) {
            $validated['ssl_status'] = 'active';
        }

        $client = !empty($validated['client_id']) ? Client::find($validated['client_id']) : null;
        $validated['company_id'] = $client ? $client->company_id : null;

        $website = Website::create($validated);

        $this->activityLogger->execute($website, 'deployment', "Website '{$website->name}' registered in system.");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Website registered successfully', 'website' => $website]);
        }

        return redirect()->back()->with('success', 'Website registered successfully!');
    }

    /**
     * Display website detail dashboard.
     */
    public function show(Website $website): View
    {
        $website->load([
            'client.company',
            'project.manager',
            'project.developers',
            'credentials',
            'tasks.assignedTo',
            'activities.user',
            'notes.user',
        ]);

        $clients = Client::orderBy('name')->get();
        $projects = Project::where('client_id', $website->client_id)->get();

        return view('admin.websites.show', compact('website', 'clients', 'projects'));
    }

    /**
     * Update website details.
     */
    public function update(Request $request, Website $website): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'client_id' => 'nullable|exists:crm_clients,id',
            'project_id' => 'nullable|exists:crm_projects,id',
            'title' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'domain_provider' => 'nullable|string|max:100',
            'domain_expiry_date' => 'nullable|date',
            'hosting_provider' => 'nullable|string|max:100',
            'hosting_expiry_date' => 'nullable|date',
            'technology' => 'nullable|string|max:100',
            'framework' => 'nullable|string|max:100',
            'cms' => 'nullable|string|max:100',
            'repository_url' => 'nullable|string|max:255',
            'github_url' => 'nullable|string|max:255',
            'server_ip' => 'nullable|string|max:50',
            'deployment_platform' => 'nullable|string|max:100',
            'database_type' => 'nullable|string|max:100',
            'ssl_status' => 'nullable|in:active,expiring_soon,expired,not_installed',
            'ssl_expiry_date' => 'nullable|date',
            'status' => 'nullable|in:planning,design,development,testing,client_review,live,maintenance,suspended,completed',
            'design_progress' => 'nullable|integer|min:0|max:100',
            'dev_progress' => 'nullable|integer|min:0|max:100',
            'testing_progress' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $website->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Website updated successfully', 'website' => $website]);
        }

        return redirect()->back()->with('success', 'Website updated successfully!');
    }

    /**
     * Add credential to website vault.
     */
    public function storeCredential(Request $request, Website $website): JsonResponse
    {
        $validated = $request->validate([
            'credential_type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'password_raw' => 'required|string',
            'host_or_url' => 'nullable|string|max:255',
            'port' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $credential = WebsiteCredential::create([
            'website_id' => $website->id,
            'credential_type' => $validated['credential_type'],
            'title' => $validated['title'] ?? $validated['credential_type'],
            'username' => $validated['username'],
            'password_encrypted' => encrypt($validated['password_raw']),
            'host_or_url' => $validated['host_or_url'],
            'port' => $validated['port'],
            'notes' => $validated['notes'],
        ]);

        $this->activityLogger->execute(
            $website,
            'note',
            "New {$credential->credential_type} credential added to vault."
        );

        return response()->json(['success' => true, 'message' => 'Credential stored securely in vault.', 'credential' => $credential]);
    }

    /**
     * Reveal encrypted password for authorized user.
     */
    public function revealCredential(WebsiteCredential $credential): JsonResponse
    {
        try {
            $decrypted = decrypt($credential->password_encrypted);
        } catch (\Throwable $e) {
            $decrypted = $credential->password_encrypted; // fallback if plain
        }

        return response()->json([
            'success' => true,
            'credential_id' => $credential->id,
            'password' => $decrypted,
        ]);
    }

    /**
     * Delete website credential.
     */
    public function destroyCredential(WebsiteCredential $credential): JsonResponse
    {
        $credential->delete();
        return response()->json(['success' => true, 'message' => 'Credential removed from vault.']);
    }

    /**
     * Delete website.
     */
    public function destroy(Website $website): RedirectResponse|JsonResponse
    {
        $name = $website->name;
        $website->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Website '{$name}' deleted."]);
        }

        return redirect('/admin/websites')->with('success', "Website '{$name}' deleted.");
    }
}

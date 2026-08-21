<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller for instant global search across all CRM entities.
 */
class SearchController extends Controller
{
    /**
     * Execute instant search and return grouped results.
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // 1. Leads
        $leads = Lead::where('company_name', 'like', "%{$q}%")
            ->orWhere('contact_person', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(4)
            ->get()
            ->map(fn($item) => [
                'type' => 'Lead',
                'title' => $item->company_name,
                'subtitle' => "{$item->contact_person} • $" . number_format($item->estimated_budget, 0),
                'url' => "/admin/leads/{$item->id}",
                'icon' => 'fa-solid fa-filter-dollar',
                'badge' => ucfirst($item->status),
            ]);
        if ($leads->isNotEmpty()) $results['Leads'] = $leads;

        // 2. Clients
        $clients = Client::where('name', 'like', "%{$q}%")
            ->orWhere('client_code', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(4)
            ->get()
            ->map(fn($item) => [
                'type' => 'Client',
                'title' => $item->name,
                'subtitle' => "{$item->client_code} • {$item->email}",
                'url' => "/admin/clients/{$item->id}",
                'icon' => 'fa-solid fa-user-tie',
                'badge' => ucfirst($item->status),
            ]);
        if ($clients->isNotEmpty()) $results['Clients'] = $clients;

        // 3. Companies
        $companies = Company::where('name', 'like', "%{$q}%")
            ->orWhere('industry', 'like', "%{$q}%")
            ->limit(4)
            ->get()
            ->map(fn($item) => [
                'type' => 'Company',
                'title' => $item->name,
                'subtitle' => "{$item->industry} • {$item->city}",
                'url' => "/admin/companies/{$item->id}",
                'icon' => 'fa-solid fa-building',
                'badge' => $item->company_type,
            ]);
        if ($companies->isNotEmpty()) $results['Companies'] = $companies;

        // 4. Contacts
        $contacts = Contact::with('company')
            ->where('first_name', 'like', "%{$q}%")
            ->orWhere('last_name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(4)
            ->get()
            ->map(fn($item) => [
                'type' => 'Contact',
                'title' => $item->name,
                'subtitle' => "{$item->job_title} at " . ($item->company ? $item->company->name : 'N/A'),
                'url' => "/admin/contacts/{$item->id}",
                'icon' => 'fa-solid fa-address-book',
                'badge' => $item->preferred_contact_method,
            ]);
        if ($contacts->isNotEmpty()) $results['Contacts'] = $contacts;

        // 5. Websites
        $websites = Website::where('name', 'like', "%{$q}%")
            ->orWhere('domain', 'like', "%{$q}%")
            ->orWhere('technology', 'like', "%{$q}%")
            ->limit(4)
            ->get()
            ->map(fn($item) => [
                'type' => 'Website',
                'title' => $item->name,
                'subtitle' => ($item->domain ?? 'No domain') . " • {$item->technology}",
                'url' => "/admin/websites/{$item->id}",
                'icon' => 'fa-solid fa-globe',
                'badge' => ucfirst($item->status),
            ]);
        if ($websites->isNotEmpty()) $results['Websites'] = $websites;

        // 6. Projects
        $projects = Project::where('name', 'like', "%{$q}%")
            ->orWhere('project_type', 'like', "%{$q}%")
            ->limit(4)
            ->get()
            ->map(fn($item) => [
                'type' => 'Project',
                'title' => $item->name,
                'subtitle' => "{$item->project_type} • {$item->progress}% complete",
                'url' => "/admin/projects/{$item->id}",
                'icon' => 'fa-solid fa-diagram-project',
                'badge' => ucfirst($item->status),
            ]);
        if ($projects->isNotEmpty()) $results['Projects'] = $projects;

        // 7. Tasks
        $tasks = Task::where('title', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->limit(4)
            ->get()
            ->map(fn($item) => [
                'type' => 'Task',
                'title' => $item->title,
                'subtitle' => "Priority: " . ucfirst($item->priority) . " • Status: " . ucfirst(str_replace('_', ' ', $item->status)),
                'url' => "/admin/tasks",
                'icon' => 'fa-solid fa-list-check',
                'badge' => ucfirst($item->priority),
            ]);
        if ($tasks->isNotEmpty()) $results['Tasks'] = $tasks;

        return response()->json([
            'query' => $q,
            'groups' => $results,
        ]);
    }
}

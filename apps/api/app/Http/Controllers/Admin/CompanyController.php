<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing client companies and business organizations.
 */
class CompanyController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display company directory.
     */
    public function index(Request $request): View
    {
        $query = Company::withCount(['contacts', 'projects', 'websites', 'clients'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('industry', 'like', "%{$s}%")
                    ->orWhere('city', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $companies = $query->paginate(15);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Store new company.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gst_vat_number' => 'nullable|string|max:100',
            'company_type' => 'required|string',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $company = Company::create($validated);

        $this->activityLogger->execute($company, 'note', "Company '{$company->name}' created.");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Company created successfully', 'company' => $company]);
        }

        return redirect()->back()->with('success', 'Company created successfully!');
    }

    /**
     * Display company 360 profile.
     */
    public function show(Company $company): View
    {
        $company->load([
            'contacts',
            'clients.projects',
            'projects.manager',
            'websites',
            'activities.user',
            'notes.user',
        ]);

        return view('admin.companies.show', compact('company'));
    }

    /**
     * Update company details.
     */
    public function update(Request $request, Company $company): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gst_vat_number' => 'nullable|string|max:100',
            'company_type' => 'required|string',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $company->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Company updated successfully', 'company' => $company]);
        }

        return redirect()->back()->with('success', 'Company updated successfully!');
    }

    /**
     * Delete company.
     */
    public function destroy(Company $company): RedirectResponse|JsonResponse
    {
        $name = $company->name;
        $company->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Company '{$name}' deleted."]);
        }

        return redirect('/admin/companies')->with('success', "Company '{$name}' deleted.");
    }
}

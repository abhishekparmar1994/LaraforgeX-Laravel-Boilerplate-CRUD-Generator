<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ConvertLeadToClientAction;
use App\Actions\LogActivityAction;
use App\Domains\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use App\Services\LeadImportService;
use App\Services\SampleTemplateService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller for managing CRM Leads and Kanban pipeline.
 */
class LeadController extends Controller
{
    protected ConvertLeadToClientAction $convertAction;
    protected LogActivityAction $activityLogger;
    protected LeadImportService $importService;
    protected SampleTemplateService $templateService;

    public function __construct(
        ConvertLeadToClientAction $convertAction,
        LogActivityAction $activityLogger,
        LeadImportService $importService,
        SampleTemplateService $templateService
    ) {
        $this->convertAction = $convertAction;
        $this->activityLogger = $activityLogger;
        $this->importService = $importService;
        $this->templateService = $templateService;
    }

    /**
     * Purpose: Display the leads list with advanced filters or return paginated JSON for AJAX DataTable.
     * Parameters: Request $request
     * Return: View|JsonResponse
     * Side Effects: Executes database read queries.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = $this->buildLeadListQuery($request);
        $perPage = (int) $request->get('per_page', 10);
        $perPage = ($perPage < 5 || $perPage > 500) ? 10 : $perPage;

        $leads = $query->paginate($perPage)->withQueryString();

        if ($request->get('format') === 'json' || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $leads->items(),
                'pagination' => [
                    'total' => $leads->total(),
                    'per_page' => $leads->perPage(),
                    'current_page' => $leads->currentPage(),
                    'last_page' => $leads->lastPage(),
                    'from' => $leads->firstItem(),
                    'to' => $leads->lastItem(),
                ],
            ]);
        }

        $developers = User::all();
        $cities = ['Vadodara', 'Ahmedabad', 'Rajkot', 'Pune', 'Surat', 'Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Indore', 'Jaipur'];
        $areas = Lead::whereNotNull('area_locality')->where('area_locality', '!=', '')->distinct()->orderBy('area_locality')->pluck('area_locality');
        $industries = Lead::whereNotNull('industry')->where('industry', '!=', '')->distinct()->orderBy('industry')->pluck('industry');

        return view('admin.leads.index', compact('leads', 'developers', 'cities', 'areas', 'industries'));
    }

    /**
     * Purpose: Construct the base query with eager loads, search filters, and sorting.
     * Parameters: Request $request
     * Return: Builder
     */
    private function buildLeadListQuery(Request $request): Builder
    {
        $query = Lead::with('assignedDeveloper');
        $this->applyLeadSearchFilters($query, $request);
        $this->applyLeadCategoryFilters($query, $request);
        $this->applyLeadSorting($query, $request);

        return $query;
    }

    /**
     * Purpose: Apply free-text multi-field search and location filters to the query.
     * Parameters: Builder $query, Request $request
     * Return: void
     */
    private function applyLeadSearchFilters(Builder $query, Request $request): void
    {
        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $query->where(function (Builder $q) use ($s) {
                $q->where('company_name', 'like', "%{$s}%")
                    ->orWhere('contact_person', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('website', 'like', "%{$s}%")
                    ->orWhere('industry', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%")
                    ->orWhere('area_locality', 'like', "%{$s}%")
                    ->orWhere('full_address', 'like', "%{$s}%");
            });
        }

        if ($request->filled('city')) {
            $city = trim((string) $request->city);
            $query->where(fn(Builder $q) => $q->where('location', 'like', "%{$city}%")->orWhere('area_locality', 'like', "%{$city}%")->orWhere('full_address', 'like', "%{$city}%"));
        }

        if ($request->filled('area')) {
            $area = trim((string) $request->area);
            $query->where(fn(Builder $q) => $q->where('area_locality', 'like', "%{$area}%")->orWhere('location', 'like', "%{$area}%")->orWhere('full_address', 'like', "%{$area}%"));
        }
    }

    /**
     * Purpose: Apply categorical dropdown filters (website, industry, status, priority, etc.).
     * Parameters: Builder $query, Request $request
     * Return: void
     */
    private function applyLeadCategoryFilters(Builder $query, Request $request): void
    {
        if ($request->filled('has_website')) {
            $webStatus = strtoupper(trim((string) $request->has_website));
            if ($webStatus === 'NO') {
                $query->where(fn(Builder $q) => $q->where('has_website', 'NO')->orWhereNull('website')->orWhere('website', '')->orWhere('website', 'N/A')->orWhere('website', 'None'));
            } elseif ($webStatus === 'YES') {
                $query->where('has_website', 'YES')->whereNotNull('website')->where('website', '!=', '')->where('website', '!=', 'N/A')->where('website', '!=', 'None');
            }
        }

        if ($request->filled('industry'))
            $query->where('industry', $request->industry);
        if ($request->filled('status'))
            $query->where('status', $request->status);
        if ($request->filled('priority'))
            $query->where('priority', $request->priority);
        if ($request->filled('source'))
            $query->where('lead_source', $request->source);
        if ($request->filled('developer_id'))
            $query->where('assigned_to_user_id', $request->developer_id);
    }

    /**
     * Purpose: Apply column ordering to the leads query.
     * Parameters: Builder $query, Request $request
     * Return: void
     */
    private function applyLeadSorting(Builder $query, Request $request): void
    {
        $sortBy = (string) $request->get('sort_by', 'created_at');
        $sortDir = (string) $request->get('sort_dir', 'desc');
        $allowed = ['company_name', 'has_website', 'industry', 'phone', 'location', 'area_locality', 'rating', 'created_at', 'status', 'priority'];

        if (in_array($sortBy, $allowed, true)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
            return;
        }

        $query->latest();
    }

    /**
     * Display the visual Kanban pipeline board with filters.
     *
     * @param Request $request Filter parameters and optional AJAX stage pagination
     * @return View|JsonResponse Kanban board view or AJAX stage leads payload
     * @throws \Throwable If rendering fails
     * @sideeffects Reads lead and user records, executes aggregate queries
     */
    public function kanban(Request $request): View|JsonResponse
    {
        $stages = [
            'new' => '1. New Solar Lead',
            'contacted' => '2. Site Survey Scheduled',
            'qualified' => '3. Feasibility Approved',
            'proposal_sent' => '4. Solar Proposal Sent',
            'negotiation' => '5. Subsidy / Net Metering',
            'won' => '6. Commissioned (Won)',
            'lost' => '7. Lost / Drop',
        ];

        $baseQuery = $this->buildKanbanBaseQuery($request);

        // Instant AJAX Stage Leads Pagination (Infinite scroll / Load More)
        if ($request->ajax() && $request->filled('stage')) {
            return $this->loadStageLeadsAjax($request, $baseQuery);
        }

        $leadsByStage = [];
        $totalBudgetByStage = [];
        $stageCounts = [];
        $initialCardLimit = 35;

        foreach (array_keys($stages) as $stage) {
            $stageBase = (clone $baseQuery)->where('status', $stage);
            $stageCounts[$stage] = (clone $stageBase)->count();
            $totalBudgetByStage[$stage] = (float) ((clone $stageBase)->sum('estimated_budget') ?? 0);
            $leadsByStage[$stage] = (clone $stageBase)->latest()->limit($initialCardLimit)->get();
        }

        $developers = User::all();
        $cities = ['Vadodara', 'Ahmedabad', 'Rajkot', 'Pune', 'Surat', 'Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Indore', 'Jaipur'];
        $areas = Lead::whereNotNull('area_locality')->where('area_locality', '!=', '')->distinct()->orderBy('area_locality')->pluck('area_locality');
        $industries = Lead::whereNotNull('industry')->where('industry', '!=', '')->distinct()->orderBy('industry')->pluck('industry');

        return view('admin.leads.kanban', compact(
            'stages',
            'leadsByStage',
            'totalBudgetByStage',
            'stageCounts',
            'developers',
            'cities',
            'areas',
            'industries'
        ));
    }

    /**
     * Build scoped query with active search, category, location, and assignee filters.
     *
     * @param Request $request Active filter values
     * @return Builder Scoped Lead Eloquent builder
     * @sideeffects Prepares query conditions
     */
    private function buildKanbanBaseQuery(Request $request): Builder
    {
        $baseQuery = Lead::with('assignedDeveloper');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $baseQuery->where(function ($q) use ($s) {
                $q->where('company_name', 'like', "%{$s}%")
                    ->orWhere('contact_person', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('website', 'like', "%{$s}%")
                    ->orWhere('industry', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%")
                    ->orWhere('area_locality', 'like', "%{$s}%")
                    ->orWhere('full_address', 'like', "%{$s}%");
            });
        }

        if ($request->filled('has_website')) {
            $webStatus = strtoupper(trim($request->has_website));
            if ($webStatus === 'NO') {
                $baseQuery->where(function ($q) {
                    $q->where('has_website', 'NO')
                        ->orWhereNull('website')
                        ->orWhere('website', '')
                        ->orWhere('website', 'N/A')
                        ->orWhere('website', 'None');
                });
            } elseif ($webStatus === 'YES') {
                $baseQuery->where('has_website', 'YES')
                    ->whereNotNull('website')
                    ->where('website', '!=', '')
                    ->where('website', '!=', 'N/A')
                    ->where('website', '!=', 'None');
            }
        }

        if ($request->filled('city')) {
            $city = trim($request->city);
            $baseQuery->where(function ($q) use ($city) {
                $q->where('location', 'like', "%{$city}%")
                    ->orWhere('area_locality', 'like', "%{$city}%")
                    ->orWhere('full_address', 'like', "%{$city}%");
            });
        }

        if ($request->filled('area')) {
            $area = trim($request->area);
            $baseQuery->where(function ($q) use ($area) {
                $q->where('area_locality', 'like', "%{$area}%")
                    ->orWhere('location', 'like', "%{$area}%")
                    ->orWhere('full_address', 'like', "%{$area}%");
            });
        }

        if ($request->filled('industry')) {
            $baseQuery->where('industry', $request->industry);
        }

        if ($request->filled('priority')) {
            $baseQuery->where('priority', $request->priority);
        }

        if ($request->filled('developer_id')) {
            $baseQuery->where('assigned_to_user_id', $request->developer_id);
        }

        return $baseQuery;
    }

    /**
     * Fetch subsequent paginated cards for a Kanban column via AJAX.
     *
     * @param Request $request Contains stage, offset, limit
     * @param Builder $baseQuery Scoped query with active filters
     * @return JsonResponse Rendered HTML cards and pagination metadata
     * @sideeffects Queries next batch of cards and renders blade view
     */
    private function loadStageLeadsAjax(Request $request, Builder $baseQuery): JsonResponse
    {
        $stage = (string) $request->get('stage');
        $offset = (int) $request->get('offset', 0);
        $limit = (int) $request->get('limit', 35);

        $stageBase = (clone $baseQuery)->where('status', $stage);
        $totalCount = (clone $stageBase)->count();
        $leads = (clone $stageBase)->latest()->skip($offset)->take($limit)->get();

        $html = '';
        foreach ($leads as $lead) {
            $html .= view('admin.leads.partials.kanban-card', compact('lead'))->render();
        }

        $loadedSoFar = $offset + $leads->count();

        return response()->json([
            'success' => true,
            'html' => $html,
            'loaded' => $leads->count(),
            'total' => $totalCount,
            'remaining' => max(0, $totalCount - $loadedSoFar),
            'has_more' => $totalCount > $loadedSoFar,
        ]);
    }

    /**
     * Purpose: Store a new solar lead into the system.
     * Parameters: StoreLeadRequest $request
     * Return: RedirectResponse|JsonResponse
     * Side Effects: Inserts new lead record and logs activity.
     */
    public function store(StoreLeadRequest $request): RedirectResponse|JsonResponse
    {
        $lead = Lead::create($request->validated());

        $this->activityLogger->execute(
            $lead,
            'note',
            "New Solar Lead registered: '{$lead->company_name}' ({$lead->contact_person})."
        );

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solar lead created successfully',
                'lead' => $lead,
            ]);
        }

        return redirect()->back()->with('success', 'Solar lead registered successfully!');
    }

    /**
     * Display a single lead's 360 CRM profile.
     */
    public function show(Lead $lead): View
    {
        $lead->load(['assignedDeveloper', 'followUps.assignedTo', 'activities.user', 'leadNotes.user']);
        $developers = User::all();

        return view('admin.leads.show', compact('lead', 'developers'));
    }

    /**
     * Update lead details.
     */
    public function update(Request $request, Lead $lead): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'company_name' => 'sometimes|required|string|max:255',
            'contact_person' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'consumer_number' => 'nullable|string|max:100',
            'discom' => 'nullable|string|max:100',
            'sanctioned_load' => 'nullable|numeric|min:0',
            'monthly_bill' => 'nullable|numeric|min:0',
            'solar_capacity_kw' => 'nullable|numeric|min:0',
            'subsidy_amount' => 'nullable|numeric|min:0',
            'product_group' => 'nullable|string|max:100',
            'customer_group' => 'nullable|string|max:100',
            'lead_potential' => 'nullable|string|max:50',
            'lead_type' => 'nullable|string|max:100',
            'society_name' => 'nullable|string|max:255',
            'approval_status' => 'nullable|string|max:100',
            'franchise' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'lead_source' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
            'requirement' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'status' => 'nullable|in:new,contacted,qualified,proposal_sent,negotiation,won,lost',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'last_contact_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $lead->status;
        $lead->update(array_filter($validated, fn($v) => $v !== null));

        if ($oldStatus !== $lead->status) {
            $this->activityLogger->execute(
                $lead,
                'status_change',
                "Lead stage moved from '{$oldStatus}' to '{$lead->status}'."
            );
        }

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead updated successfully', 'lead' => $lead]);
        }

        return redirect()->back()->with('success', 'Lead updated successfully!');
    }

    /**
     * Update lead stage via drag & drop Kanban board (AJAX).
     */
    public function updateStage(Request $request, Lead $lead): JsonResponse
    {
        $newStatus = $request->input('status') ?: $request->input('stage');
        $allowed = ['new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won', 'lost'];

        if (!$newStatus || !in_array($newStatus, $allowed, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing stage/status parameter: ' . json_encode($newStatus)
            ], 422);
        }

        $oldStatus = $lead->status;
        $lead->update(['status' => $newStatus]);

        $this->activityLogger->execute(
            $lead,
            'status_change',
            "Pipeline stage dragged from '{$oldStatus}' to '{$lead->status}'."
        );

        return response()->json([
            'success' => true,
            'message' => "Lead stage updated to {$lead->status}",
            'lead' => $lead,
        ]);
    }

    /**
     * Convert a won lead to a client account.
     */
    public function convert(Request $request, Lead $lead): RedirectResponse|JsonResponse
    {
        $options = $request->only(['job_title', 'create_project', 'project_name', 'project_type', 'create_website']);
        $client = $this->convertAction->execute($lead, $options);

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead converted to Client successfully!',
                'redirect_url' => "/admin/clients/{$client->id}",
            ]);
        }

        return redirect("/admin/clients/{$client->id}")
            ->with('success', "Lead successfully converted to Client {$client->name} ({$client->client_code})!");
    }

    /**
     * Import leads from uploaded Excel (.xlsx) or CSV file and skip duplicates.
     */
    public function import(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:51200', // max 50MB
            'imported_by' => 'nullable|string|max:100',
        ]);

        try {
            $importedBy = $request->input('imported_by') ?: (auth()->user()?->name ?? 'Excel Import');
            $result = $this->importService->import($request->file('file'), $importedBy);

            $this->activityLogger->execute(
                null,
                'note',
                "Excel Lead Import: {$result['imported']} new leads added, {$result['skipped']} duplicates skipped."
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'stats' => $result
                ]);
            }

            return redirect()->back()->with('success', $result['message']);
        } catch (\Throwable $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Purpose: Download pre-formatted sample Excel/CSV template for bulk solar lead import.
     * Parameters: None
     * Return: StreamedResponse
     * Errors: None
     * Side Effects: Streams CSV file download to client browser.
     */
    public function downloadSampleTemplate(): StreamedResponse
    {
        $csv = $this->templateService->getSolarLeadsSampleCsv();
        $filename = 'solar_leads_sample_template.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    /**
     * Delete a lead.
     */
    public function destroy(Lead $lead): RedirectResponse|JsonResponse
    {
        $companyName = $lead->company_name;
        $lead->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Lead '{$companyName}' removed."]);
        }

        return redirect('/admin/leads')->with('success', "Lead '{$companyName}' deleted successfully.");
    }
}

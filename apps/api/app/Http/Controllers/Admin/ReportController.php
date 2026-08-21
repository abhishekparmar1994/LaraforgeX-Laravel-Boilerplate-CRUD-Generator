<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * Controller for agency business, financial, and pipeline reports.
 */
class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index(Request $request): View
    {
        $totalRevenue = (float) Payment::where('status', 'paid')->sum('amount');
        $pendingRevenue = (float) Payment::whereIn('status', ['pending', 'partial', 'overdue'])->sum('amount');
        $totalProjects = Project::count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalLeads = Lead::count();
        $wonLeads = Lead::where('status', 'won')->count();
        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 1) : 0;

        $projectTypes = Project::selectRaw('project_type, count(*) as count, sum(budget) as total_budget')
            ->groupBy('project_type')
            ->get();

        $leadSources = Lead::selectRaw('lead_source, count(*) as count, sum(estimated_budget) as total_budget')
            ->groupBy('lead_source')
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'pendingRevenue',
            'totalProjects',
            'completedProjects',
            'totalLeads',
            'wonLeads',
            'conversionRate',
            'projectTypes',
            'leadSources'
        ));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Client;
use App\Models\Website;
use App\Models\Project;
use App\Models\Task;
use App\Models\Payment;
use App\Models\FollowUp;
use App\Models\Activity;
use App\Domains\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

/**
 * Controller for the Agency CRM & Project Management Dashboard.
 */
class CrmDashboardController extends Controller
{
    /**
     * Display the main Agency CRM Dashboard.
     */
    public function index(Request $request): View
    {
        $kpi = $this->calculateKpis();
        $attentionAlerts = $this->getAttentionAlerts();
        $upcomingFollowups = FollowUp::with(['lead', 'client', 'contact', 'assignedTo'])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->orderBy('scheduled_at', 'asc')
            ->limit(5)
            ->get();
        $recentActivities = Activity::with('user')
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'kpi',
            'attentionAlerts',
            'upcomingFollowups',
            'recentActivities'
        ));
    }

    /**
     * Get real-time dashboard analytics payload for charts (AJAX).
     */
    public function analytics(): JsonResponse
    {
        $kpi = $this->calculateKpis();
        $pipelineData = $this->getPipelineChartData();
        $monthlyRevenue = $this->getMonthlyRevenueData();
        $projectStatusData = $this->getProjectStatusDistribution();
        $taskOverview = $this->getTaskStatusDistribution();
        $alerts = $this->getAttentionAlerts();

        return response()->json([
            'kpi' => $kpi,
            'pipeline' => $pipelineData,
            'monthly_revenue' => $monthlyRevenue,
            'project_statuses' => $projectStatusData,
            'task_overview' => $taskOverview,
            'alerts' => $alerts,
        ]);
    }

    /**
     * Calculate 9 core KPI metrics.
     */
    protected function calculateKpis(): array
    {
        $totalLeads = Lead::count();
        $newLeads = Lead::where('status', 'new')->count();
        $activeClients = Client::where('status', 'active')->count();
        $activeWebsites = Website::whereIn('status', ['development', 'live', 'maintenance'])->count();
        $projectsInProgress = Project::whereIn('status', ['planning', 'design', 'development', 'testing', 'client_review'])->count();
        $pendingTasks = Task::whereIn('status', ['todo', 'in_progress', 'review'])->count();
        $overdueTasks = Task::whereIn('status', ['todo', 'in_progress', 'review'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->startOfDay())
            ->count();
        $pendingPayments = (float) Payment::whereIn('status', ['pending', 'partial', 'overdue'])->sum('amount');
        $totalRevenue = (float) Payment::where('status', 'paid')->sum('amount');

        return [
            'total_leads' => $totalLeads,
            'new_leads' => $newLeads,
            'active_clients' => $activeClients,
            'active_websites' => $activeWebsites,
            'projects_in_progress' => $projectsInProgress,
            'pending_tasks' => $pendingTasks,
            'overdue_tasks' => $overdueTasks,
            'pending_payments' => $pendingPayments,
            'total_revenue' => $totalRevenue,
        ];
    }

    /**
     * Gather attention-required alerts.
     */
    protected function getAttentionAlerts(): array
    {
        $alerts = [];

        // 1. Overdue Tasks
        $overdueTasksCount = Task::whereIn('status', ['todo', 'in_progress', 'review'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->startOfDay())
            ->count();
        if ($overdueTasksCount > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fa-solid fa-triangle-exclamation',
                'title' => "{$overdueTasksCount} overdue tasks require immediate action",
                'link' => '/admin/tasks?filter=overdue',
            ];
        }

        // 2. Pending Follow-ups Today
        $todayFollowups = FollowUp::where('status', 'scheduled')
            ->whereDate('scheduled_at', Carbon::today())
            ->count();
        if ($todayFollowups > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fa-solid fa-phone-volume',
                'title' => "{$todayFollowups} client/lead follow-ups scheduled for today",
                'link' => '/admin/followups?filter=today',
            ];
        }

        // 3. Domains Expiring within 14 Days
        $expiringDomains = Website::whereNotNull('domain_expiry_date')
            ->whereBetween('domain_expiry_date', [now(), now()->addDays(14)])
            ->count();
        if ($expiringDomains > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fa-solid fa-globe',
                'title' => "{$expiringDomains} website domain(s) expiring within 14 days",
                'link' => '/admin/websites?filter=domain_expiring',
            ];
        }

        // 4. Overdue Invoices
        $overdueInvoices = Payment::where('status', 'overdue')
            ->orWhere(function ($query) {
                $query->whereIn('status', ['pending', 'partial'])
                    ->whereNotNull('due_date')
                    ->where('due_date', '<', now()->startOfDay());
            })->count();
        if ($overdueInvoices > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fa-solid fa-file-invoice-dollar',
                'title' => "{$overdueInvoices} client payment invoices are past due",
                'link' => '/admin/payments?status=overdue',
            ];
        }

        return $alerts;
    }

    /**
     * Aggregate lead pipeline stages.
     */
    protected function getPipelineChartData(): array
    {
        $stages = ['new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won', 'lost'];
        $counts = [];
        $budgets = [];

        foreach ($stages as $stage) {
            $leads = Lead::where('status', $stage)->get();
            $counts[] = $leads->count();
            $budgets[] = (float) $leads->sum('estimated_budget');
        }

        return [
            'labels' => ['New', 'Contacted', 'Qualified', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'],
            'counts' => $counts,
            'budgets' => $budgets,
        ];
    }

    /**
     * Monthly revenue trend data.
     */
    protected function getMonthlyRevenueData(): array
    {
        $months = [];
        $totals = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthLabel = $monthDate->format('M Y');
            $months[] = $monthLabel;

            $sum = Payment::where('status', 'paid')
                ->whereYear('payment_date', $monthDate->year)
                ->whereMonth('payment_date', $monthDate->month)
                ->sum('amount');
            $totals[] = (float) $sum;
        }

        return [
            'months' => $months,
            'totals' => $totals,
        ];
    }

    /**
     * Project status distribution.
     */
    protected function getProjectStatusDistribution(): array
    {
        $statuses = ['planning', 'design', 'development', 'testing', 'client_review', 'launch', 'completed', 'on_hold'];
        $labels = ['Planning', 'Design', 'Development', 'Testing', 'Client Review', 'Launch', 'Completed', 'On Hold'];
        $counts = [];

        foreach ($statuses as $status) {
            $counts[] = Project::where('status', $status)->count();
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }

    /**
     * Task status distribution.
     */
    protected function getTaskStatusDistribution(): array
    {
        return [
            'todo' => Task::where('status', 'todo')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'review' => Task::where('status', 'review')->count(),
            'blocked' => Task::where('status', 'blocked')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::whereIn('status', ['todo', 'in_progress', 'review'])
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->startOfDay())
                ->count(),
        ];
    }
}

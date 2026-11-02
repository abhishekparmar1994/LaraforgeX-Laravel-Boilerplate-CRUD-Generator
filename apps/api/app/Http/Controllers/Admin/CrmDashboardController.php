<?php

namespace App\Http\Controllers\Admin;

use App\Actions\GetSolarDashboardMetricsAction;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Purpose: Controller for the SolarPulse CRM & Solar EPC Executive Dashboard.
 */
class CrmDashboardController extends Controller
{
    protected GetSolarDashboardMetricsAction $metricsAction;

    public function __construct(GetSolarDashboardMetricsAction $metricsAction)
    {
        $this->metricsAction = $metricsAction;
    }

    /**
     * Purpose: Display the enhanced Solar EPC Executive Dashboard with full widgets.
     * Parameters: Request $request
     * Return: View
     * Side Effects: Executes database read queries.
     */
    public function index(Request $request): View
    {
        $metrics = $this->metricsAction->execute();

        return view('admin.dashboard', $metrics);
    }

    /**
     * Purpose: Get real-time dashboard analytics payload for charts (AJAX).
     * Parameters: none
     * Return: JsonResponse
     */
    public function analytics(): JsonResponse
    {
        $metrics = $this->metricsAction->execute();

        return response()->json([
            'kpi' => $metrics['kpi'],
            'solar_metrics' => $metrics['solar_metrics'],
            'pipeline' => $this->getPipelineChartData(),
            'monthly_revenue' => $this->getMonthlyRevenueData(),
            'project_statuses' => $this->getProjectStatusDistribution(),
            'sector_distribution' => $metrics['sector_distribution'],
            'discom_distribution' => $metrics['discom_distribution'],
            'active_plants' => $metrics['active_plants'],
            'alerts' => $metrics['attention_alerts'],
        ]);
    }

    /**
     * Purpose: Aggregate lead pipeline stages for funnel visualization.
     * Return: array
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
     * Purpose: Calculate 6-month historical revenue trend.
     * Return: array
     */
    protected function getMonthlyRevenueData(): array
    {
        $months = [];
        $totals = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $months[] = $monthDate->format('M Y');
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
     * Purpose: Calculate active project lifecycle distribution.
     * Return: array
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
}

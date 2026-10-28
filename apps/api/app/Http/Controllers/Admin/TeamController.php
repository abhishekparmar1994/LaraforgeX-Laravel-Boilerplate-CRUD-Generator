<?php

namespace App\Http\Controllers\Admin;

use App\Domains\User\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\Lead;
use App\Models\FollowUp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing the 4 agency developers and team performance analytics.
 */
class TeamController extends Controller
{
    /**
     * Display team directory with workload metrics.
     */
    public function index(): View
    {
        $developers = User::all()->map(function ($user) {
            $assignedProjectsCount = Project::whereHas('developers', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->count();

            $pendingTasksCount = Task::where('assigned_to_user_id', $user->id)
                ->whereIn('status', ['todo', 'in_progress', 'review'])
                ->count();

            $completedTasksCount = Task::where('assigned_to_user_id', $user->id)
                ->where('status', 'completed')
                ->count();

            $overdueTasksCount = Task::where('assigned_to_user_id', $user->id)
                ->whereIn('status', ['todo', 'in_progress', 'review'])
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->startOfDay())
                ->count();

            $totalHours = Task::where('assigned_to_user_id', $user->id)->sum('actual_hours');

            $user->metrics = [
                'assigned_projects' => $assignedProjectsCount,
                'pending_tasks' => $pendingTasksCount,
                'completed_tasks' => $completedTasksCount,
                'overdue_tasks' => $overdueTasksCount,
                'total_hours' => (float) $totalHours,
                'workload_score' => min(100, $pendingTasksCount * 15),
            ];

            return $user;
        });

        return view('admin.team.index', compact('developers'));
    }

    /**
     * Display individual developer profile and activity dashboard.
     */
    public function show(User $user): View
    {
        $assignedProjects = Project::whereHas('developers', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->with('client')->get();

        $activeTasks = Task::with('project')
            ->where('assigned_to_user_id', $user->id)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->orderBy('due_date', 'asc')
            ->get();

        $completedTasks = Task::with('project')
            ->where('assigned_to_user_id', $user->id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->limit(15)
            ->get();

        return view('admin.team.show', compact('user', 'assignedProjects', 'activeTasks', 'completedTasks'));
    }

    /**
     * Purpose: Render Solar Team Performance Leaderboard ranked by conversions.
     * Parameters: Request $request
     * Return: View
     */
    public function performance(Request $request): View
    {
        $users = User::all()->map(function ($user) {
            $leadsCount = Lead::where('assigned_to_user_id', $user->id)->count();
            $wonCount = Lead::where('assigned_to_user_id', $user->id)->where('status', 'won')->count();
            $lostCount = Lead::where('assigned_to_user_id', $user->id)->where('status', 'lost')->count();
            $activeCount = Lead::where('assigned_to_user_id', $user->id)->whereNotIn('status', ['won', 'lost'])->count();
            $activitiesCount = FollowUp::where('assigned_to_user_id', $user->id)->count();
            $pipelineVal = (float) Lead::where('assigned_to_user_id', $user->id)->sum('estimated_budget');
            $conversionRate = $leadsCount > 0 ? round(($wonCount / $leadsCount) * 100, 1) : 0;

            $user->stats = [
                'leads' => $leadsCount,
                'active' => $activeCount,
                'won' => $wonCount,
                'lost' => $lostCount,
                'rate' => $conversionRate,
                'activities' => $activitiesCount,
                'pipeline_value' => $pipelineVal,
            ];
            return $user;
        })->sortByDesc(fn($u) => $u->stats['won'] * 100 + $u->stats['rate'])->values();

        return view('admin.team.performance', compact('users'));
    }

    /**
     * Team performance comparison data (AJAX).
     */
    public function performanceData(): JsonResponse
    {
        $developers = User::all();
        $names = [];
        $completed = [];
        $pending = [];
        $overdue = [];
        $hours = [];

        foreach ($developers as $dev) {
            $names[] = $dev->name;
            $completed[] = Task::where('assigned_to_user_id', $dev->id)->where('status', 'completed')->count();
            $pending[] = Task::where('assigned_to_user_id', $dev->id)->whereIn('status', ['todo', 'in_progress', 'review'])->count();
            $overdue[] = Task::where('assigned_to_user_id', $dev->id)
                ->whereIn('status', ['todo', 'in_progress', 'review'])
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->startOfDay())
                ->count();
            $hours[] = (float) Task::where('assigned_to_user_id', $dev->id)->sum('actual_hours');
        }

        return response()->json([
            'names' => $names,
            'completed_tasks' => $completed,
            'pending_tasks' => $pending,
            'overdue_tasks' => $overdue,
            'hours_logged' => $hours,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Domains\User\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * Controller for managing tasks, Kanban boards, and developer personal todo lists.
 */
class TaskController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display task list.
     */
    public function index(Request $request): View
    {
        $query = Task::with(['project', 'website', 'client', 'assignedTo'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('developer_id')) {
            $query->where('assigned_to_user_id', $request->developer_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filter === 'overdue') {
            $query->whereIn('status', ['todo', 'in_progress', 'review'])
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->startOfDay());
        }

        $tasks = $query->paginate(20);
        $projects = Project::orderBy('name')->get();
        $developers = User::all();
        $clients = Client::orderBy('name')->get();
        $websites = Website::orderBy('name')->get();

        return view('admin.tasks.index', compact('tasks', 'projects', 'developers', 'clients', 'websites'));
    }

    /**
     * Display Trello-style Task Kanban Board.
     */
    public function kanban(): View
    {
        $statuses = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'blocked' => 'Blocked',
            'completed' => 'Completed',
        ];

        $tasksByStatus = [];
        foreach (array_keys($statuses) as $status) {
            $tasksByStatus[$status] = Task::with(['project', 'assignedTo', 'client'])
                ->where('status', $status)
                ->latest()
                ->get();
        }

        $projects = Project::orderBy('name')->get();
        $developers = User::all();
        $clients = Client::orderBy('name')->get();
        $websites = Website::orderBy('name')->get();

        return view('admin.tasks.kanban', compact('statuses', 'tasksByStatus', 'projects', 'developers', 'clients', 'websites'));
    }

    /**
     * Display Developer's Personal Todo List.
     */
    public function todo(Request $request): View
    {
        $userId = $request->developer_id ?? (Auth::check() ? Auth::id() : User::first()->id);
        $selectedDeveloper = User::find($userId);

        $today = Carbon::today();

        $todayTasks = Task::with(['project', 'client'])
            ->where('assigned_to_user_id', $userId)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->whereDate('due_date', $today)
            ->orderBy('priority', 'desc')
            ->get();

        $upcomingTasks = Task::with(['project', 'client'])
            ->where('assigned_to_user_id', $userId)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->where(function ($q) use ($today) {
                $q->where('due_date', '>', $today)
                    ->orWhereNull('due_date');
            })
            ->orderBy('due_date', 'asc')
            ->get();

        $overdueTasks = Task::with(['project', 'client'])
            ->where('assigned_to_user_id', $userId)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today)
            ->orderBy('due_date', 'asc')
            ->get();

        $completedTasks = Task::with(['project', 'client'])
            ->where('assigned_to_user_id', $userId)
            ->where('status', 'completed')
            ->latest('updated_at')
            ->limit(10)
            ->get();

        $developers = User::all();
        $projects = Project::orderBy('name')->get();

        return view('admin.tasks.todo', compact(
            'todayTasks',
            'upcomingTasks',
            'overdueTasks',
            'completedTasks',
            'selectedDeveloper',
            'developers',
            'projects'
        ));
    }

    /**
     * Store new task.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:crm_projects,id',
            'website_id' => 'nullable|exists:crm_websites,id',
            'client_id' => 'nullable|exists:crm_clients,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,blocked,completed',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['project_id']) && empty($validated['client_id'])) {
            $project = Project::find($validated['project_id']);
            $validated['client_id'] = $project ? $project->client_id : null;
        }

        $validated['created_by_user_id'] = Auth::check() ? Auth::id() : null;
        if ($validated['status'] === 'completed') {
            $validated['completed_at'] = now();
        }

        $task = Task::create($validated);

        $this->activityLogger->execute($task, 'task_update', "Task '{$task->title}' created.");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Task created successfully', 'task' => $task]);
        }

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    /**
     * Update task status via Kanban drag-and-drop (AJAX).
     */
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,blocked,completed',
        ]);

        $oldStatus = $task->status;
        $isCompleted = ($request->status === 'completed');

        $task->update([
            'status' => $request->status,
            'completed_at' => $isCompleted ? now() : null,
        ]);

        $this->activityLogger->execute(
            $task,
            'task_update',
            "Task status moved from '{$oldStatus}' to '{$task->status}'."
        );

        return response()->json(['success' => true, 'task' => $task]);
    }

    /**
     * Fast inline toggle for personal todo checkbox.
     */
    public function toggleComplete(Task $task): JsonResponse
    {
        $newStatus = ($task->status === 'completed') ? 'todo' : 'completed';
        $task->update([
            'status' => $newStatus,
            'completed_at' => ($newStatus === 'completed') ? now() : null,
        ]);

        $this->activityLogger->execute(
            $task,
            'task_update',
            ($newStatus === 'completed') ? "Task '{$task->title}' marked as completed." : "Task '{$task->title}' re-opened."
        );

        return response()->json(['success' => true, 'status' => $newStatus, 'task' => $task]);
    }

    /**
     * Update full task details.
     */
    public function update(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:crm_projects,id',
            'website_id' => 'nullable|exists:crm_websites,id',
            'client_id' => 'nullable|exists:crm_clients,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,blocked,completed',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Task updated successfully', 'task' => $task]);
        }

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    /**
     * Delete task.
     */
    public function destroy(Task $task): RedirectResponse|JsonResponse
    {
        $title = $task->title;
        $task->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Task '{$title}' removed."]);
        }

        return redirect()->back()->with('success', "Task '{$title}' deleted.");
    }
}

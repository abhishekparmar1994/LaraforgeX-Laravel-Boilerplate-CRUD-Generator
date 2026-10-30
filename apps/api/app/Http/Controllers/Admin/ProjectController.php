<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Domains\User\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectMilestone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing agency projects, milestones, and developer assignments.
 */
class ProjectController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display projects list.
     */
    public function index(Request $request): View
    {
        $query = Project::with(['client', 'manager', 'developers'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('project_type', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('developer_id')) {
            $devId = $request->developer_id;
            $query->whereHas('developers', function ($q) use ($devId) {
                $q->where('users.id', $devId);
            });
        }

        $projects = $query->paginate(15);
        $clients = Client::orderBy('name')->get();
        $developers = User::all();

        return view('admin.projects.index', compact('projects', 'clients', 'developers'));
    }

    /**
     * Store new project.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:crm_clients,id',
            'name' => 'required|string|max:255',
            'project_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:planning,design,development,testing,client_review,launch,completed,on_hold',
            'priority' => 'required|in:low,medium,high,urgent',
            'project_manager_id' => 'nullable|exists:users,id',
            'progress' => 'nullable|integer|min:0|max:100',
            'repository' => 'nullable|string|max:255',
            'staging_url' => 'nullable|string|max:255',
            'production_url' => 'nullable|string|max:255',
            'developer_ids' => 'nullable|array',
            'developer_ids.*' => 'exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $client = Client::find($validated['client_id']);
        $validated['company_id'] = $client ? $client->company_id : null;

        $project = Project::create($validated);

        if (!empty($request->developer_ids)) {
            $project->developers()->sync($request->developer_ids);
        }

        $this->activityLogger->execute($project, 'status_change', "Project '{$project->name}' created.");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Project created successfully', 'project' => $project]);
        }

        return redirect()->back()->with('success', 'Project created successfully!');
    }

    /**
     * Display project dashboard and details.
     */
    public function show(Project $project): View
    {
        $project->load([
            'client.company',
            'website.credentials',
            'manager',
            'developers',
            'milestones',
            'tasks.assignedTo',
            'payments',
            'activities.user',
            'notes.user',
            'bom.material.category',
            'bom.material.company',
        ]);

        $clients = Client::orderBy('name')->get();
        $developers = User::all();

        return view('admin.projects.show', compact('project', 'clients', 'developers'));
    }

    /**
     * Update project details.
     */
    public function update(Request $request, Project $project): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:crm_clients,id',
            'name' => 'required|string|max:255',
            'project_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:planning,design,development,testing,client_review,launch,completed,on_hold',
            'priority' => 'required|in:low,medium,high,urgent',
            'project_manager_id' => 'nullable|exists:users,id',
            'progress' => 'nullable|integer|min:0|max:100',
            'repository' => 'nullable|string|max:255',
            'staging_url' => 'nullable|string|max:255',
            'production_url' => 'nullable|string|max:255',
            'developer_ids' => 'nullable|array',
            'developer_ids.*' => 'exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $project->update($validated);

        if (isset($request->developer_ids)) {
            $project->developers()->sync($request->developer_ids);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Project updated successfully', 'project' => $project]);
        }

        return redirect()->back()->with('success', 'Project updated successfully!');
    }

    /**
     * Add milestone to project.
     */
    public function storeMilestone(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $milestone = $project->milestones()->create($validated);

        $this->activityLogger->execute($project, 'task_update', "Milestone '{$milestone->title}' added.");

        return response()->json(['success' => true, 'milestone' => $milestone]);
    }

    /**
     * Toggle milestone completion.
     */
    public function toggleMilestone(ProjectMilestone $milestone): JsonResponse
    {
        $milestone->update([
            'is_completed' => !$milestone->is_completed,
            'completed_at' => !$milestone->is_completed ? now() : null,
        ]);

        return response()->json(['success' => true, 'milestone' => $milestone]);
    }

    /**
     * Delete project.
     */
    public function destroy(Project $project): RedirectResponse|JsonResponse
    {
        $name = $project->name;
        $project->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Project '{$name}' deleted."]);
        }

        return redirect('/admin/projects')->with('success', "Project '{$name}' deleted.");
    }
}

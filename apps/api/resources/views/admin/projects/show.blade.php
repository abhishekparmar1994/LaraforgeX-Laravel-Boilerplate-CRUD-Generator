@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Project: ' . $project->name)

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/projects" class="hover:text-brand-600 transition">Projects</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">{{ $project->name }}</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- Header Card -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-start gap-4 min-w-0">
        <div class="h-14 w-14 rounded-2xl bg-purple-50 border border-purple-100 text-purple-600 font-extrabold text-xl flex items-center justify-center shrink-0">
          <i class="fa-solid fa-diagram-project"></i>
        </div>
        <div class="min-w-0">
          <div class="flex items-center gap-2.5 flex-wrap">
            <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $project->name }}</h2>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border bg-purple-50 text-purple-700 border-purple-200">
              {{ ucfirst(str_replace('_', ' ', $project->status)) }}
            </span>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $project->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600' }}">
              {{ $project->priority }} Priority
            </span>
          </div>
          <p class="text-xs text-slate-500 font-medium mt-1">
            Client: 
            @if($project->client)
              <a href="/admin/clients/{{ $project->client_id }}" class="text-brand-600 font-bold hover:underline">{{ $project->client->name }}</a>
            @else
              <span>N/A</span>
            @endif
            • {{ $project->project_type }}
            @if($project->deadline) • Deadline: <span class="font-bold {{ $project->is_overdue ? 'text-rose-600' : 'text-slate-700' }}">{{ $project->deadline->format('M d, Y') }}</span> @endif
          </p>
        </div>
      </div>

      <!-- Quick Action Buttons -->
      <div class="flex items-center gap-2">
        <button onclick="$('#modal-add-project-task').removeClass('hidden')" class="h-9 px-3.5 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm shadow-brand-600/20 transition cursor-pointer">
          <i class="fa-solid fa-plus"></i> Add Task
        </button>
      </div>
    </div>

    <!-- Progress Bar & Specs -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-3 border-t border-slate-100 text-xs">
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Project Progress</span>
        <div class="flex items-center gap-2">
          <div class="flex-1 bg-slate-200 h-2.5 rounded-full overflow-hidden">
            <div class="bg-purple-600 h-full" style="width: {{ $project->progress }}%"></div>
          </div>
          <span class="font-extrabold text-purple-700">{{ $project->progress }}%</span>
        </div>
      </div>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Budget Allocation</span>
        <span class="font-extrabold text-slate-900 text-sm">${{ number_format($project->budget, 0) }}</span>
      </div>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Logged Hours</span>
        <span class="font-bold text-slate-800">{{ $project->actual_hours }}h / {{ $project->estimated_hours }}h</span>
      </div>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Team Members</span>
        <div class="flex items-center -space-x-1.5 overflow-hidden">
          @forelse($project->developers as $dev)
            <span class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-brand-600 text-white text-[9px] font-bold flex items-center justify-center" title="{{ $dev->name }}">
              {{ substr($dev->name, 0, 1) }}
            </span>
          @empty
            <span class="text-slate-400 italic">None</span>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column: Tasks & Milestones -->
    <div class="space-y-6 col-span-1 lg:col-span-2">

      <!-- Tasks List -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="font-extrabold text-sm text-slate-900">Project Tasks ({{ count($project->tasks) }})</h3>
          <button onclick="$('#modal-add-project-task').removeClass('hidden')" class="text-xs font-bold text-brand-600 hover:underline">+ New Task</button>
        </div>

        <div class="space-y-2.5">
          @forelse($project->tasks as $task)
            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-3 text-xs">
              <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleTaskCompletion('{{ $task->id }}')" class="text-base text-slate-400 hover:text-emerald-600 transition">
                  <i class="fa-{{ $task->status === 'completed' ? 'solid fa-circle-check text-emerald-600' : 'regular fa-circle' }}"></i>
                </button>
                <div class="min-w-0">
                  <span class="font-extrabold text-slate-800 {{ $task->status === 'completed' ? 'line-through text-slate-400' : '' }} block truncate">{{ $task->title }}</span>
                  <span class="text-[11px] text-slate-400">Assigned: {{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }} • Due: {{ $task->due_date ? $task->due_date->format('M d') : '—' }}</span>
                </div>
              </div>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase shrink-0 {{ $task->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-700' }}">{{ $task->priority }}</span>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic py-2">No tasks added to this project yet.</p>
          @endforelse
        </div>
      </div>

      <!-- Milestones Checklist -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
        <h3 class="font-extrabold text-sm text-slate-900">Delivery Milestones</h3>
        <div class="space-y-2 text-xs">
          @forelse($project->milestones as $milestone)
            <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between gap-3">
              <div class="flex items-center gap-2.5">
                <button onclick="toggleMilestone('{{ $milestone->id }}')" class="text-base text-slate-400 hover:text-purple-600 transition">
                  <i class="fa-{{ $milestone->is_completed ? 'solid fa-square-check text-purple-600' : 'regular fa-square' }}"></i>
                </button>
                <span class="font-bold text-slate-800 {{ $milestone->is_completed ? 'line-through text-slate-400' : '' }}">{{ $milestone->title }}</span>
              </div>
              <span class="text-[11px] text-slate-400">{{ $milestone->due_date ? $milestone->due_date->format('M d, Y') : '—' }}</span>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic">No milestones defined.</p>
          @endforelse
        </div>
      </div>

    </div>

    <!-- Right Column: Links & Activities -->
    <div class="space-y-6 col-span-1">

      <!-- URLs & Repository -->
      <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
        <h3 class="font-extrabold text-sm text-slate-900">Environments & Code</h3>
        <div class="space-y-2 text-xs">
          @if($project->staging_url)
            <a href="{{ $project->staging_url }}" target="_blank" class="p-2.5 bg-slate-50 rounded-xl flex items-center justify-between text-slate-700 hover:bg-slate-100 font-bold transition">
              <span><i class="fa-solid fa-flask mr-2 text-amber-500"></i> Staging URL</span>
              <i class="fa-solid fa-arrow-up-right-from-square text-slate-400 text-[10px]"></i>
            </a>
          @endif
          @if($project->production_url)
            <a href="{{ $project->production_url }}" target="_blank" class="p-2.5 bg-slate-50 rounded-xl flex items-center justify-between text-slate-700 hover:bg-slate-100 font-bold transition">
              <span><i class="fa-solid fa-bolt mr-2 text-emerald-500"></i> Production URL</span>
              <i class="fa-solid fa-arrow-up-right-from-square text-slate-400 text-[10px]"></i>
            </a>
          @endif
          @if($project->repository)
            <a href="{{ $project->repository }}" target="_blank" class="p-2.5 bg-slate-50 rounded-xl flex items-center justify-between text-slate-700 hover:bg-slate-100 font-bold transition">
              <span><i class="fa-brands fa-github mr-2 text-slate-900"></i> Source Code Repo</span>
              <i class="fa-solid fa-arrow-up-right-from-square text-slate-400 text-[10px]"></i>
            </a>
          @endif
        </div>
      </div>

      <!-- Activity Timeline -->
      <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
        <h3 class="font-extrabold text-sm text-slate-900">Project History</h3>
        <div class="space-y-2.5 max-h-72 overflow-y-auto text-xs">
          @forelse($project->activities as $act)
            <div class="flex items-start gap-2.5">
              <div class="h-6 w-6 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                <i class="fa-solid fa-clock-rotate-left"></i>
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-slate-700 font-medium">{{ $act->description }}</p>
                <span class="text-[10px] text-slate-400">{{ $act->created_at->diffForHumans() }}</span>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic">No activity recorded.</p>
          @endforelse
        </div>
      </div>

    </div>

  </div>

</div>

<!-- Modal: Add Project Task -->
<div id="modal-add-project-task" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-add-project-task').addClass('hidden')"></div>
    <form action="/admin/tasks" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <input type="hidden" name="project_id" value="{{ $project->id }}">
      <input type="hidden" name="client_id" value="{{ $project->client_id }}">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Create Task</h3>
        <button type="button" onclick="$('#modal-add-project-task').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="space-y-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Task Title *</label>
          <input type="text" name="title" required placeholder="e.g. Implement payment gateway webhook" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Assign Developer</label>
          <select name="assigned_to_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            @foreach($developers as $d)
              <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="font-bold text-slate-700 block mb-1">Priority</label>
            <select name="priority" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
              <option value="low">Low</option>
              <option value="medium" selected>Medium</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
          <div>
            <label class="font-bold text-slate-700 block mb-1">Due Date</label>
            <input type="date" name="due_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
        </div>
        <input type="hidden" name="status" value="todo">
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-add-project-task').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save Task</button>
      </div>
    </form>
  </div>
</div>

<script>
  async function toggleTaskCompletion(taskId) {
    try {
      await axios.post(`/admin/tasks/${taskId}/toggle`);
      location.reload();
    } catch(e) {
      alert('Error updating task');
    }
  }

  async function toggleMilestone(milestoneId) {
    try {
      await axios.post(`/admin/milestones/${milestoneId}/toggle`);
      location.reload();
    } catch(e) {
      alert('Error toggling milestone');
    }
  }
</script>
@endsection

@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Developer Personal Todo List')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/tasks" class="hover:text-brand-600 transition">Tasks</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Developer Todo</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans max-w-5xl">

  <!-- Header Card & Developer Switcher -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-amber-500"></i>
        <span>Developer Action List</span>
      </h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Focused personal checklist grouped by urgency and deadline.</p>
    </div>

    <!-- Switch Developer Filter -->
    <div class="flex items-center gap-2">
      <label class="text-xs font-bold text-slate-400">View for:</label>
      <select onchange="window.location.href='/admin/tasks/todo?developer_id='+this.value" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
        @foreach($developers as $dev)
          <option value="{{ $dev->id }}" {{ $selectedDeveloper && $selectedDeveloper->id == $dev->id ? 'selected' : '' }}>
            {{ $dev->name }} ({{ $dev->email }})
          </option>
        @endforeach
      </select>
    </div>
  </div>

  <!-- ── 1. OVERDUE TASKS (HIGH URGENCY) ───────────────────────── -->
  @if(count($overdueTasks) > 0)
  <div class="bg-rose-50/70 border border-rose-200 rounded-3xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2 text-rose-700 font-extrabold text-xs uppercase tracking-wider">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>Overdue Tasks (Past Deadline)</span>
      </div>
      <span class="px-2 py-0.5 bg-rose-200 text-rose-800 text-[10px] font-extrabold rounded-full">{{ count($overdueTasks) }} Overdue</span>
    </div>
    <div class="space-y-2">
      @foreach($overdueTasks as $task)
        <div class="p-3.5 bg-white border border-rose-200/80 rounded-2xl flex items-center justify-between gap-3 text-xs shadow-2xs">
          <div class="flex items-center gap-3 min-w-0">
            <button onclick="toggleTodoTask('{{ $task->id }}')" class="text-lg text-slate-300 hover:text-emerald-600 transition">
              <i class="fa-regular fa-circle"></i>
            </button>
            <div class="min-w-0">
              <span class="font-extrabold text-slate-900 block truncate">{{ $task->title }}</span>
              <span class="text-[11px] text-rose-600 font-bold">
                Due: {{ $task->due_date->format('M d, Y') }} • {{ $task->project ? $task->project->name : 'General' }}
              </span>
            </div>
          </div>
          <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded bg-rose-100 text-rose-700 shrink-0">{{ $task->priority }}</span>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  <!-- ── 2. TODAY'S TASKS ───────────────────────────────────────── -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2 text-slate-900 font-extrabold text-sm">
        <i class="fa-solid fa-calendar-day text-brand-600"></i>
        <span>Today's Deliverables</span>
      </div>
      <span class="text-xs font-bold text-slate-400">{{ count($todayTasks) }} Tasks</span>
    </div>

    <div class="space-y-2.5">
      @forelse($todayTasks as $task)
        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-3 text-xs">
          <div class="flex items-center gap-3 min-w-0">
            <button onclick="toggleTodoTask('{{ $task->id }}')" class="text-lg text-slate-300 hover:text-emerald-600 transition">
              <i class="fa-regular fa-circle"></i>
            </button>
            <div class="min-w-0">
              <span class="font-extrabold text-slate-900 block truncate">{{ $task->title }}</span>
              <span class="text-[11px] text-slate-400">{{ $task->project ? $task->project->name : 'General' }} • {{ ucfirst($task->status) }}</span>
            </div>
          </div>
          <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-amber-100 text-amber-800 shrink-0">{{ $task->priority }}</span>
        </div>
      @empty
        <p class="text-xs text-slate-400 italic py-2">No tasks scheduled for today. Great job!</p>
      @endforelse
    </div>
  </div>

  <!-- ── 3. UPCOMING TASKS ──────────────────────────────────────── -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2 text-slate-900 font-extrabold text-sm">
        <i class="fa-solid fa-clock text-indigo-500"></i>
        <span>Upcoming Tasks</span>
      </div>
      <span class="text-xs font-bold text-slate-400">{{ count($upcomingTasks) }} Tasks</span>
    </div>

    <div class="space-y-2.5">
      @forelse($upcomingTasks as $task)
        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-3 text-xs">
          <div class="flex items-center gap-3 min-w-0">
            <button onclick="toggleTodoTask('{{ $task->id }}')" class="text-lg text-slate-300 hover:text-emerald-600 transition">
              <i class="fa-regular fa-circle"></i>
            </button>
            <div class="min-w-0">
              <span class="font-bold text-slate-800 block truncate">{{ $task->title }}</span>
              <span class="text-[11px] text-slate-400">{{ $task->project ? $task->project->name : 'General' }} • Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No date' }}</span>
            </div>
          </div>
          <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-slate-200 text-slate-700 shrink-0">{{ $task->priority }}</span>
        </div>
      @empty
        <p class="text-xs text-slate-400 italic py-2">No upcoming backlog tasks.</p>
      @endforelse
    </div>
  </div>

  <!-- ── 4. RECENTLY COMPLETED ─────────────────────────────────── -->
  @if(count($completedTasks) > 0)
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4 opacity-75">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2 text-slate-700 font-extrabold text-sm">
        <i class="fa-solid fa-circle-check text-emerald-600"></i>
        <span>Recently Completed</span>
      </div>
      <span class="text-xs font-bold text-slate-400">{{ count($completedTasks) }} Completed</span>
    </div>

    <div class="space-y-2">
      @foreach($completedTasks as $task)
        <div class="p-3 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-3 text-xs">
          <div class="flex items-center gap-3 min-w-0">
            <button onclick="toggleTodoTask('{{ $task->id }}')" class="text-lg text-emerald-600 hover:text-slate-400 transition">
              <i class="fa-solid fa-circle-check"></i>
            </button>
            <span class="line-through text-slate-400 font-medium truncate">{{ $task->title }}</span>
          </div>
          <span class="text-[10px] text-slate-400">{{ $task->completed_at ? $task->completed_at->diffForHumans() : 'Done' }}</span>
        </div>
      @endforeach
    </div>
  </div>
  @endif

</div>

<script>
  async function toggleTodoTask(taskId) {
    try {
      await axios.post(`/admin/tasks/${taskId}/toggle`);
      location.reload();
    } catch(e) {
      alert('Error updating task');
    }
  }
</script>
@endsection

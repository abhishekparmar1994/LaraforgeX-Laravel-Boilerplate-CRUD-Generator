@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Tasks Management')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Tasks</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Solar Tasks & Deliverables</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Track executive action items, project backlog, and solar deliverables.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="/admin/tasks/kanban" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
        <i class="fa-solid fa-trello text-slate-500"></i> Kanban
      </a>
      <a href="/admin/tasks/todo" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs transition">
        <i class="fa-solid fa-circle-check text-amber-600"></i> My Todo List
      </a>
      <button onclick="$('#modal-create-task').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
        <i class="fa-solid fa-plus"></i> New Task
      </button>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
    <form method="GET" action="/admin/tasks" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
      <div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search task title..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Statuses</option>
          <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
          <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
          <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review</option>
          <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
          <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
      </div>
      <div>
        <select name="priority" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Priorities</option>
          <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
          <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
          <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
          <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
        </select>
      </div>
      <div>
        <select name="developer_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Executives</option>
          @foreach($developers as $dev)
            <option value="{{ $dev->id }}" {{ request('developer_id') == $dev->id ? 'selected' : '' }}>{{ $dev->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex items-center gap-2">
        <button type="submit" class="flex-1 bg-slate-900 text-white font-bold text-xs py-2 rounded-xl hover:bg-slate-800 transition">Filter</button>
        <a href="/admin/tasks" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
      </div>
    </form>
  </div>

  <!-- Tasks Table -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="datatable w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5 w-10">Done</th>
            <th class="px-4 py-3.5">Task Title & Project</th>
            <th class="px-4 py-3.5">Status</th>
            <th class="px-4 py-3.5">Priority</th>
            <th class="px-4 py-3.5">Assigned Executive</th>
            <th class="px-4 py-3.5">Due Date</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($tasks as $task)
            <tr class="hover:bg-slate-50/70 transition {{ $task->status === 'completed' ? 'opacity-60 bg-slate-50/40' : '' }}">
              <td class="px-4 py-3.5">
                <button onclick="toggleTask('{{ $task->id }}')" class="text-base text-slate-400 hover:text-emerald-600 transition">
                  <i class="fa-{{ $task->status === 'completed' ? 'solid fa-circle-check text-emerald-600' : 'regular fa-circle' }}"></i>
                </button>
              </td>
              <td class="px-4 py-3.5">
                <span class="font-bold text-slate-900 {{ $task->status === 'completed' ? 'line-through' : '' }} block">
                  {{ $task->title }}
                </span>
                <span class="text-[11px] text-slate-400 block mt-0.5">
                  {{ $task->project ? $task->project->name : ($task->website ? $task->website->name : 'General Task') }}
                </span>
              </td>
              <td class="px-4 py-3.5">
                @php
                  $taskColors = [
                    'todo' => 'bg-slate-100 text-slate-700 border-slate-200',
                    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'review' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'blocked' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                  ];
                @endphp
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $taskColors[$task->status] ?? 'bg-slate-100' }}">
                  {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
              </td>
              <td class="px-4 py-3.5">
                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $task->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : ($task->priority === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                  {{ $task->priority }}
                </span>
              </td>
              <td class="px-4 py-3.5">
                @if($task->assignedTo)
                  <span class="inline-flex items-center gap-1.5 font-bold text-slate-800">
                    <span class="h-5 w-5 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center text-[9px] font-extrabold">{{ substr($task->assignedTo->name, 0, 1) }}</span>
                    {{ $task->assignedTo->name }}
                  </span>
                @else
                  <span class="text-slate-400 italic">Unassigned</span>
                @endif
              </td>
              <td class="px-4 py-3.5">
                @if($task->due_date)
                  <span class="text-[11px] font-semibold {{ $task->is_overdue ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                    {{ $task->due_date->format('M d, Y') }}
                  </span>
                @else
                  <span class="text-slate-400 italic">—</span>
                @endif
              </td>
              <td class="px-4 py-3.5 text-right space-x-2">
                <button onclick="confirmDeleteTask('{{ $task->id }}')" class="text-slate-400 hover:text-rose-600 transition">
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="p-8 text-center text-slate-400">No tasks found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100">
      {{ $tasks->withQueryString()->links() }}
    </div>
  </div>

</div>

<!-- Create Task Modal -->
<div id="modal-create-task" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-create-task').addClass('hidden')"></div>
    <form action="/admin/tasks" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Create New Task</h3>
        <button type="button" onclick="$('#modal-create-task').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="space-y-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Task Title *</label>
          <input type="text" name="title" required placeholder="e.g. Implement user login API" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="font-bold text-slate-700 block mb-1">Project</label>
            <select name="project_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
              <option value="">-- Standalone / Select --</option>
              @foreach($projects as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="font-bold text-slate-700 block mb-1">Assign Executive</label>
            <select name="assigned_to_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
              @foreach($developers as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
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
        <div>
          <label class="font-bold text-slate-700 block mb-1">Description</label>
          <textarea name="description" rows="2" placeholder="Task requirements, deliverables..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold"></textarea>
        </div>
        <input type="hidden" name="status" value="todo">
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-task').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save Task</button>
      </div>
    </form>
  </div>
</div>

<script>
  async function toggleTask(taskId) {
    try {
      const res = await axios.post(`/admin/tasks/${taskId}/toggle`, {}, { baseURL: '' });
      if (res.data && res.data.success) {
        if (typeof window.showToast === 'function') {
          window.showToast('success', res.data.status === 'completed' ? 'Task marked as completed' : 'Task re-opened');
        }
      }
      setTimeout(() => location.reload(), 300);
    } catch(e) {
      console.error('Error toggling task status:', e);
      if (typeof window.showToast === 'function') {
        window.showToast('error', 'Error toggling task status');
      } else {
        alert('Error toggling task status');
      }
    }
  }

  /**
   * Purpose: SweetAlert2 confirmation dialog to delete task.
   * Parameters: string taskId
   */
  function confirmDeleteTask(taskId) {
    window.confirmDelete({
      title: 'Delete this task?',
      text: 'This operation task will be permanently removed.'
    }, async () => {
      try {
        await axios.delete(`/admin/tasks/${taskId}`, { baseURL: '' });
        if (typeof window.showToast === 'function') window.showToast('success', 'Task deleted');
        setTimeout(() => location.reload(), 300);
      } catch(e) {
        window.handleAjaxError(e, 'Failed to delete task');
      }
    });
  }
</script>
@endsection

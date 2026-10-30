@extends('admin.layouts.app')

@section('title', 'WebForge CRM - Task Kanban Board')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/tasks" class="hover:text-brand-600 transition">Tasks</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Kanban Board</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-5 font-sans">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h2 class="text-xl font-extrabold text-slate-900">Task Kanban Board</h2>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Trello-style drag & drop board across sprint workflow stages.
        </p>
      </div>
      <div class="flex items-center gap-2">
        <a href="/admin/tasks"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
          <i class="fa-solid fa-list"></i> Table View
        </a>
        <a href="/admin/tasks/todo"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs transition">
          <i class="fa-solid fa-circle-check text-amber-600"></i> My Todo List
        </a>
        <button onclick="$('#modal-create-task').removeClass('hidden')"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
          <i class="fa-solid fa-plus"></i> New Task
        </button>
      </div>
    </div>

    <!-- ── KANBAN BOARD ─────────────────────────────────────────── -->
    <div class="overflow-x-auto pb-4">
      <div class="flex gap-4 min-w-[1100px]">
        @foreach($statuses as $statusKey => $statusLabel)
          <div
            class="w-72 shrink-0 bg-slate-100/70 border border-slate-200/80 rounded-2xl p-3 flex flex-col max-h-[calc(100vh-220px)]">

            <!-- Column Header -->
            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200/60">
              <span class="font-extrabold text-xs text-slate-800">{{ $statusLabel }}</span>
              <span
                class="h-5 px-2 rounded-full bg-white text-slate-700 font-extrabold text-[10px] flex items-center justify-center border border-slate-200 shadow-2xs">
                {{ count($tasksByStatus[$statusKey]) }}
              </span>
            </div>

            <!-- Cards Drop Zone -->
            <div class="task-kanban-column flex-1 overflow-y-auto space-y-2.5 min-h-[150px] p-0.5"
              data-status="{{ $statusKey }}">
              @forelse($tasksByStatus[$statusKey] as $task)
                <div
                  class="task-card bg-white border {{ $task->is_overdue ? 'border-rose-300 bg-rose-50/10' : 'border-slate-200/90' }} rounded-xl p-3.5 shadow-xs hover:shadow-md transition cursor-grab active:cursor-grabbing group relative"
                  draggable="true" data-task-id="{{ $task->id }}">

                  <!-- Priority & Project -->
                  <div class="flex items-start justify-between gap-2">
                    <span
                      class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded {{ $task->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : ($task->priority === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                      {{ $task->priority }}
                    </span>
                    @if($task->project)
                      <span
                        class="text-[10px] text-purple-700 font-bold bg-purple-50 px-1.5 py-0.2 rounded truncate max-w-[120px]">
                        {{ $task->project->name }}
                      </span>
                    @endif
                  </div>

                  <!-- Title -->
                  <p class="font-bold text-xs text-slate-900 mt-2 leading-snug">{{ $task->title }}</p>

                  <!-- Footer: Due Date & Assignee -->
                  <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100 text-[10px]">
                    <div class="flex items-center gap-1.5">
                      @if($task->assignedTo)
                        <span
                          class="h-5 w-5 rounded-full bg-brand-50 text-brand-700 border border-brand-200 flex items-center justify-center font-bold text-[9px]"
                          title="{{ $task->assignedTo->name }}">
                          {{ substr($task->assignedTo->name, 0, 1) }}
                        </span>
                        <span class="font-semibold text-slate-700 truncate max-w-[80px]">{{ $task->assignedTo->name }}</span>
                      @else
                        <span class="text-slate-400 italic">Unassigned</span>
                      @endif
                    </div>
                    @if($task->due_date)
                      <span class="font-semibold {{ $task->is_overdue ? 'text-rose-600 font-bold' : 'text-slate-400' }}">
                        {{ $task->is_overdue ? '⚠ ' : '' }}{{ $task->due_date->format('M d') }}
                      </span>
                    @endif
                  </div>

                </div>
              @empty
                <div class="h-24 flex items-center justify-center text-center text-[11px] text-slate-400 italic">
                  Drop tasks here
                </div>
              @endforelse
            </div>

          </div>
        @endforeach
      </div>
    </div>

  </div>

  <!-- Create Task Modal -->
  <div id="modal-create-task" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
      <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-create-task').addClass('hidden')">
      </div>
      <form action="/admin/tasks" method="POST"
        class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4 font-sans text-xs">
        @csrf
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <h3 class="font-extrabold text-base text-slate-900">Create Task</h3>
          <button type="button" onclick="$('#modal-create-task').addClass('hidden')"
            class="text-slate-400 hover:text-slate-600 text-lg">×</button>
        </div>
        <div class="space-y-3">
          <div>
            <label class="font-bold text-slate-700 block mb-1">Task Title *</label>
            <input type="text" name="title" required placeholder="e.g. Test checkout Stripe payment"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="font-bold text-slate-700 block mb-1">Project</label>
              <select name="project_id"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
                <option value="">-- None --</option>
                @foreach($projects as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="font-bold text-slate-700 block mb-1">Assign Executive</label>
              <select name="assigned_to_user_id"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
                @foreach($developers as $d)
                  <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="font-bold text-slate-700 block mb-1">Priority</label>
              <select name="priority"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
            <div>
              <label class="font-bold text-slate-700 block mb-1">Due Date</label>
              <input type="date" name="due_date"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            </div>
          </div>
          <input type="hidden" name="status" value="todo">
        </div>
        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
          <button type="button" onclick="$('#modal-create-task').addClass('hidden')"
            class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
          <button type="submit"
            class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save Task</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    let draggedTask = null;

    document.querySelectorAll('.task-card').forEach(card => {
      card.addEventListener('dragstart', function (e) {
        draggedTask = this;
        this.classList.add('opacity-40');
        e.dataTransfer.setData('text/plain', this.dataset.taskId);
      });

      card.addEventListener('dragend', function () {
        this.classList.remove('opacity-40');
      });
    });

    document.querySelectorAll('.task-kanban-column').forEach(column => {
      column.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.classList.add('bg-brand-50/50');
      });

      column.addEventListener('dragleave', function () {
        this.classList.remove('bg-brand-50/50');
      });

      column.addEventListener('drop', async function (e) {
        e.preventDefault();
        this.classList.remove('bg-brand-50/50');

        if (!draggedTask) return;

        const taskId = draggedTask.dataset.taskId;
        const targetStatus = this.dataset.status;

        this.appendChild(draggedTask);

        try {
          await axios.post(`/admin/tasks/${taskId}/status`, {
            status: targetStatus
          }, { baseURL: '' });
          if (typeof showToast === 'function') {
            showToast('success', `Task status updated to ${targetStatus}`);
          }
        } catch (err) {
          console.error('Failed to update task status:', err);
          if (typeof showToast === 'function') {
            showToast('error', 'Failed to update task status.');
          } else {
            alert('Failed to update task status.');
          }
          location.reload();
        }
      });
    });
  </script>
@endsection
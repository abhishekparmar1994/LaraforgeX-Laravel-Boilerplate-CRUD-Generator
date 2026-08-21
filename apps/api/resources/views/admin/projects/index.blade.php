@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Projects Management')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Projects</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Projects Management</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Manage web deliverables, developer team assignments, project milestones, and delivery deadlines.</p>
    </div>
    <button onclick="$('#modal-create-project').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
      <i class="fa-solid fa-plus"></i> New Project
    </button>
  </div>

  <!-- Filter Bar -->
  <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
    <form method="GET" action="/admin/projects" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
      <div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search project name, type..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Statuses</option>
          <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>Planning</option>
          <option value="design" {{ request('status') === 'design' ? 'selected' : '' }}>Design</option>
          <option value="development" {{ request('status') === 'development' ? 'selected' : '' }}>Development</option>
          <option value="testing" {{ request('status') === 'testing' ? 'selected' : '' }}>Testing</option>
          <option value="client_review" {{ request('status') === 'client_review' ? 'selected' : '' }}>Client Review</option>
          <option value="launch" {{ request('status') === 'launch' ? 'selected' : '' }}>Launch</option>
          <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
        </select>
      </div>
      <div>
        <select name="developer_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Assigned Developers</option>
          @foreach($developers as $dev)
            <option value="{{ $dev->id }}" {{ request('developer_id') == $dev->id ? 'selected' : '' }}>{{ $dev->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex items-center gap-2">
        <button type="submit" class="flex-1 bg-slate-900 text-white font-bold text-xs py-2 rounded-xl hover:bg-slate-800 transition">Filter</button>
        <a href="/admin/projects" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
      </div>
    </form>
  </div>

  <!-- Projects Table -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5">Project Name & Client</th>
            <th class="px-4 py-3.5">Status</th>
            <th class="px-4 py-3.5">Progress</th>
            <th class="px-4 py-3.5">Budget</th>
            <th class="px-4 py-3.5">Assigned Developers</th>
            <th class="px-4 py-3.5">Deadline</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($projects as $project)
            <tr class="hover:bg-slate-50/70 transition">
              <td class="px-4 py-3.5">
                <a href="/admin/projects/{{ $project->id }}" class="font-bold text-slate-900 hover:text-brand-600 block text-xs">
                  {{ $project->name }}
                </a>
                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $project->client ? $project->client->name : 'N/A' }} • {{ $project->project_type }}</span>
              </td>
              <td class="px-4 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border bg-purple-50 text-purple-700 border-purple-200">
                  {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </span>
              </td>
              <td class="px-4 py-3.5">
                <div class="flex items-center gap-2">
                  <div class="w-16 bg-slate-200 h-2 rounded-full overflow-hidden">
                    <div class="bg-brand-600 h-full" style="width: {{ $project->progress }}%"></div>
                  </div>
                  <span class="text-[11px] font-bold text-slate-800">{{ $project->progress }}%</span>
                </div>
              </td>
              <td class="px-4 py-3.5 font-extrabold text-slate-900">
                ${{ number_format($project->budget, 0) }}
              </td>
              <td class="px-4 py-3.5">
                <div class="flex items-center -space-x-1.5 overflow-hidden">
                  @forelse($project->developers as $dev)
                    <span class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-brand-600 text-white text-[9px] font-bold flex items-center justify-center" title="{{ $dev->name }}">
                      {{ substr($dev->name, 0, 1) }}
                    </span>
                  @empty
                    <span class="text-slate-300 italic text-[11px]">None assigned</span>
                  @endforelse
                </div>
              </td>
              <td class="px-4 py-3.5">
                @if($project->deadline)
                  <span class="text-[11px] font-semibold {{ $project->is_overdue ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                    {{ $project->deadline->format('M d, Y') }}
                  </span>
                @else
                  <span class="text-slate-400 italic">No deadline</span>
                @endif
              </td>
              <td class="px-4 py-3.5 text-right space-x-2">
                <a href="/admin/projects/{{ $project->id }}" class="text-brand-600 font-bold hover:underline">Dashboard</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="p-8 text-center text-slate-400">No projects found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100">
      {{ $projects->withQueryString()->links() }}
    </div>
  </div>

</div>

<!-- Create Project Modal -->
<div id="modal-create-project" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-create-project').addClass('hidden')"></div>
    <form action="/admin/projects" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-xl p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Create New Project</h3>
        <button type="button" onclick="$('#modal-create-project').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Client *</label>
          <select name="client_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            @foreach($clients as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Project Type</label>
          <select name="project_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="Corporate Website">Corporate Website</option>
            <option value="Custom Web App">Custom Web App</option>
            <option value="E-commerce">E-commerce Store</option>
            <option value="SaaS MVP">SaaS MVP</option>
            <option value="Landing Page">Landing Page</option>
            <option value="Redesign">Website Redesign</option>
          </select>
        </div>
        <div class="sm:col-span-2">
          <label class="font-bold text-slate-700 block mb-1">Project Title *</label>
          <input type="text" name="name" required placeholder="e.g. Acme Enterprise Redesign" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Budget ($)</label>
          <input type="number" name="budget" step="100" value="8000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Delivery Deadline</label>
          <input type="date" name="deadline" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Status</label>
          <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="planning" selected>Planning</option>
            <option value="design">Design</option>
            <option value="development">Development</option>
            <option value="testing">Testing</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Priority</label>
          <select name="priority" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
            <option value="urgent">Urgent</option>
          </select>
        </div>
        <div class="sm:col-span-2">
          <label class="font-bold text-slate-700 block mb-1">Assign Developers (Select multiple)</label>
          <select name="developer_ids[]" multiple class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2 font-semibold h-20">
            @foreach($developers as $dev)
              <option value="{{ $dev->id }}">{{ $dev->name }} ({{ $dev->email }})</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-project').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Create Project</button>
      </div>
    </form>
  </div>
</div>
@endsection

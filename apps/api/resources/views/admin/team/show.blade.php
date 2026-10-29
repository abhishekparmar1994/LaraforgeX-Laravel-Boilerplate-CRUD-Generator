@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — Executive Profile: ' . $user->name)

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/team" class="hover:text-brand-600 transition">Executive Team</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">{{ $user->name }}</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- Header Card -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex items-start justify-between gap-4">
      <div class="flex items-start gap-4">
        <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-md">
          {{ substr($user->name, 0, 2) }}
        </div>
        <div>
          <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $user->name }}</h2>
          <p class="text-xs text-slate-500 font-medium mt-1">
            {{ $user->email }} • Role: <span class="font-bold text-slate-700">{{ $user->roles->first() ? $user->roles->first()->name : 'Solar Executive' }}</span>
          </p>
        </div>
      </div>
      <a href="/admin/tasks/todo?developer_id={{ $user->id }}" class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
        <i class="fa-solid fa-circle-check"></i> View Action List
      </a>
    </div>
  </div>

  <!-- Projects & Tasks -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Assigned Projects -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
      <h3 class="font-extrabold text-sm text-slate-900">Active Solar EPC Projects ({{ count($assignedProjects) }})</h3>
      <div class="space-y-3">
        @forelse($assignedProjects as $proj)
          <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-3 text-xs">
            <div>
              <a href="/admin/projects/{{ $proj->id }}" class="font-bold text-slate-900 hover:text-brand-600">{{ $proj->name }}</a>
              <p class="text-[11px] text-slate-400 mt-0.5">{{ $proj->client ? $proj->client->name : 'N/A' }} • {{ $proj->project_type }}</p>
            </div>
            <span class="font-extrabold text-xs text-purple-700 bg-purple-50 px-2.5 py-1 rounded-full">{{ $proj->progress }}%</span>
          </div>
        @empty
          <p class="text-xs text-slate-400 italic">No assigned projects.</p>
        @endforelse
      </div>
    </div>

    <!-- Active Tasks -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
      <h3 class="font-extrabold text-sm text-slate-900">Pending Tasks ({{ count($activeTasks) }})</h3>
      <div class="space-y-2.5 max-h-80 overflow-y-auto">
        @forelse($activeTasks as $task)
          <div class="p-3 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-3 text-xs">
            <div>
              <span class="font-bold text-slate-800 block truncate max-w-[200px]">{{ $task->title }}</span>
              <span class="text-[10px] text-slate-400">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No date' }}</span>
            </div>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $task->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-700' }}">{{ $task->priority }}</span>
          </div>
        @empty
          <p class="text-xs text-slate-400 italic">No pending tasks.</p>
        @endforelse
      </div>
    </div>

  </div>

</div>
@endsection

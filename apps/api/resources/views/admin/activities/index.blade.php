@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Activity Stream')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Activity Stream</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans max-w-4xl">

  <!-- Header -->
  <div>
    <h2 class="text-xl font-extrabold text-slate-900">Unified Agency Activity Stream</h2>
    <p class="text-xs text-slate-500 font-medium mt-0.5">Real-time audit log of client interactions, task progressions, payments, and deployment milestones.</p>
  </div>

  <!-- Activity Timeline Container -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="divide-y divide-slate-100">
      @forelse($activities as $act)
        <div class="py-3.5 flex items-start gap-3.5 text-xs">
          <div class="h-8 w-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xs shrink-0 mt-0.5 shadow-2xs">
            <i class="fa-solid {{ $act->type === 'status_change' ? 'fa-arrow-right-arrow-left text-brand-600' : ($act->type === 'payment' ? 'fa-dollar-sign text-emerald-600' : ($act->type === 'deployment' ? 'fa-cloud-arrow-up text-purple-600' : ($act->type === 'call' ? 'fa-phone text-blue-600' : 'fa-comment-dots text-amber-600'))) }}"></i>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between gap-2">
              <span class="font-extrabold text-slate-900 capitalize">{{ $act->user ? $act->user->name : 'System Agent' }}</span>
              <span class="text-[10px] text-slate-400 font-semibold">{{ $act->created_at->diffForHumans() }} ({{ $act->created_at->format('M d, H:i') }})</span>
            </div>
            <p class="text-slate-700 font-medium mt-1 leading-snug">{{ $act->description }}</p>
          </div>
        </div>
      @empty
        <div class="py-8 text-center text-slate-400 text-xs">No activity entries logged yet.</div>
      @endforelse
    </div>

    <div class="pt-4 border-t border-slate-100">
      {{ $activities->links() }}
    </div>
  </div>

</div>
@endsection

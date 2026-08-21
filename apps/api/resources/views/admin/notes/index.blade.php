@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Notes Repository')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Notes</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Notes & Knowledge Repository</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Quick internal memos, technical specifications, and client briefing notes.</p>
    </div>
  </div>

  <!-- Notes Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($notes as $note)
      <div class="p-5 bg-white border border-slate-200 rounded-3xl shadow-xs space-y-3 flex flex-col justify-between relative group hover:shadow-md transition">
        <div>
          <div class="flex items-start justify-between gap-2">
            <h3 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $note->title ?: 'Quick Note' }}</h3>
            @if($note->is_pinned)
              <span class="text-amber-500 text-xs" title="Pinned Note"><i class="fa-solid fa-thumbtack"></i></span>
            @endif
          </div>
          <p class="text-xs text-slate-600 font-medium mt-2 leading-relaxed whitespace-pre-line">{{ $note->content }}</p>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
          <span>By <b>{{ $note->user ? $note->user->name : 'Agency' }}</b></span>
          <span>{{ $note->created_at->diffForHumans() }}</span>
        </div>
      </div>
    @empty
      <div class="col-span-3 p-12 text-center text-slate-400 bg-white border border-slate-200 rounded-3xl">
        <i class="fa-regular fa-note-sticky text-3xl text-slate-300 block mb-2"></i>
        No notes recorded in the repository.
      </div>
    @endforelse
  </div>

  <div class="pt-2">
    {{ $notes->links() }}
  </div>

</div>
@endsection

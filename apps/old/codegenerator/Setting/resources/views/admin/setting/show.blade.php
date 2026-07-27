@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Setting Details')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <a href="/admin/setting" class="hover:text-brand-600 transition">Setting</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Details #{{ $record->id }}</span>
</nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6 font-sans">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Setting Details</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Viewing full record information for ID #{{ $record->id }}.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="/admin/setting" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition inline-flex items-center gap-1.5">
        <i class="fa-solid fa-arrow-left"></i> Back to List
      </a>
      <a href="/admin/setting/{{ $record->id }}/edit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-500 transition shadow-md shadow-brand-600/20 inline-flex items-center gap-1.5">
        <i class="fa-solid fa-pen"></i> Edit Record
      </a>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

    </div>
  </div>
</div>
@endsection
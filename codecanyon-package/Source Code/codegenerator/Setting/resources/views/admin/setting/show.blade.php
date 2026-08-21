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
<div class="space-y-6 font-sans max-w-3xl">

  <!-- Page Header -->
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Setting Details</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Viewing record information for ID #{{ $record->id }}.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="/admin/setting" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition inline-flex items-center gap-1.5">
        <i class="fa-solid fa-arrow-left"></i> Back to List
      </a>
      <a href="/admin/setting/{{ $record->id }}/edit" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm shadow-brand-600/20 inline-flex items-center gap-1.5">
        <i class="fa-solid fa-pen"></i> Edit Record
      </a>
    </div>
  </div>

  <!-- Profile Style View Card Container -->
  <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    
    <!-- Cover gradient header bar -->
    <div class="h-28 bg-gradient-to-r from-brand-500 via-indigo-600 to-violet-500"></div>

    <!-- Header Avatar / Icon Badge -->
    <div class="px-6 pb-6">
      <div class="flex items-end justify-between -mt-10 mb-4">
        <div class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-brand-500 to-violet-500 border-4 border-white shadow-lg flex items-center justify-center text-white font-extrabold text-2xl uppercase shrink-0">
          <i class="fa-solid fa-eye"></i>
        </div>
      </div>

      <div class="space-y-1 mb-6">
        <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">Setting Record #{{ $record->id }}</h3>
        <p class="text-xs font-semibold text-slate-500">Full record attributes breakdown.</p>
      </div>

      <!-- Security Stats Style Attribute Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

      </div>
    </div>

  </div>

</div>
@endsection
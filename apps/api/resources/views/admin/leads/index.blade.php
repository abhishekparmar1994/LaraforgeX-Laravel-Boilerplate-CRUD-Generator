@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — Solar Energy & Rooftop Pipeline')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Solar Leads Pipeline</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">
  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <div class="flex items-center gap-2.5">
        <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
          <i class="fa-solid fa-solar-panel text-amber-500"></i> Solar Energy & Rooftop Leads
        </h2>
        <span id="leads-total-badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
          {{ number_format($leads->total()) }} Solar Leads
        </span>
      </div>
      <p id="leads-summary-text" class="text-xs text-slate-500 font-medium mt-0.5">
        Showing {{ $leads->firstItem() ?? 0 }} to {{ $leads->lastItem() ?? 0 }} of {{ number_format($leads->total()) }} commercial, industrial & residential solar inquiries
      </p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
      <!-- Triple View Switcher: Card | Table | Kanban -->
      <div class="inline-flex rounded-xl bg-slate-100 p-0.5 border border-slate-200">
        <button type="button" id="btn-view-card" onclick="switchLeadsView('card')"
          class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer text-slate-600 hover:text-slate-900">
          <i class="fa-solid fa-grip mr-1"></i> Card
        </button>
        <button type="button" id="btn-view-table" onclick="switchLeadsView('table')"
          class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer bg-white text-emerald-700 shadow-2xs">
          <i class="fa-solid fa-table-list mr-1"></i> Table
        </button>
        <a href="/admin/leads/kanban"
          class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition flex items-center">
          <i class="fa-solid fa-table-columns mr-1"></i> Kanban
        </a>
      </div>

      <button type="button" onclick="openImportModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/20 transition cursor-pointer">
        <i class="fa-solid fa-file-excel"></i> Import Solar Leads
      </button>
      <button type="button" onclick="openCreateLeadModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm shadow-amber-500/20 transition cursor-pointer">
        <i class="fa-solid fa-plus"></i> Add Solar Lead
      </button>
    </div>
  </div>

  @include('admin.leads.partials.filter-bar')
  @include('admin.leads.partials.cards')
  @include('admin.leads.partials.table')
</div>

@include('admin.leads.partials.import-modal')
@include('admin.leads.partials.create-modal')
@include('admin.leads.partials.prompt-modal')

@endsection

@section('scripts')
  @include('admin.leads.partials.scripts')
@endsection

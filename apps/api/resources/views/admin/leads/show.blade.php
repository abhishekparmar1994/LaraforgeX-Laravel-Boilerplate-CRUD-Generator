@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — Solar EPC Lead: ' . $lead->company_name)

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/leads" class="hover:text-brand-600 transition">Solar Leads</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">{{ $lead->company_name }}</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- Flash Messages -->
  @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-sm leading-none">&times;</button>
    </div>
  @endif

  @if(session('error'))
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
        <span>{{ session('error') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold text-sm leading-none">&times;</button>
    </div>
  @endif

  <!-- ── 1. HEADER PROFILE CARD ─────────────────────────────────── -->
  @include('admin.leads.partials.show.header-card')

  <!-- ── 2. TABS & CONTENT ──────────────────────────────────────── -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Solar Sizing, Discovery & Notes -->
    <div class="space-y-6 col-span-1 lg:col-span-2">
      <!-- Solar Rooftop Sizing & Subsidy Proposal Card -->
      @include('admin.leads.partials.show.solar-proposal-card')

      <!-- Site Location & Registry Intelligence Card -->
      @include('admin.leads.partials.show.discovery-card')

      <!-- Internal Team Notes -->
      @include('admin.leads.partials.show.notes-card')
    </div>

    <!-- Right: Scheduled Follow-ups & Activity History -->
    <div class="space-y-6 col-span-1">
      @include('admin.leads.partials.show.followup-card')
    </div>

  </div>

</div>

<!-- ── MODALS ────────────────────────────────────────────────── -->
@include('admin.leads.partials.show.modals')

<!-- ── SCRIPTS ───────────────────────────────────────────────── -->
@include('admin.leads.partials.show.scripts')

@endsection

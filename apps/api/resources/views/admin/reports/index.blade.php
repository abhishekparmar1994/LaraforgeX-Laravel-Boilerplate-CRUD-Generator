@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Business Analytics & Reports')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Business Reports</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- Header -->
  <div>
    <h2 class="text-xl font-extrabold text-slate-900">Agency Business & Revenue Analytics</h2>
    <p class="text-xs text-slate-500 font-medium mt-0.5">High-level financial summaries, sales conversion rates, and project delivery distribution.</p>
  </div>

  <!-- KPI Cards -->
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="bg-white border border-slate-200 p-5 rounded-3xl shadow-xs">
      <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Total Collected Revenue</span>
      <h3 class="text-2xl font-extrabold text-emerald-700 mt-1">${{ number_format($totalRevenue, 0) }}</h3>
    </div>
    <div class="bg-white border border-slate-200 p-5 rounded-3xl shadow-xs">
      <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">Pending Collections</span>
      <h3 class="text-2xl font-extrabold text-amber-700 mt-1">${{ number_format($pendingRevenue, 0) }}</h3>
    </div>
    <div class="bg-white border border-slate-200 p-5 rounded-3xl shadow-xs">
      <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block">Pipeline Conversion Rate</span>
      <h3 class="text-2xl font-extrabold text-brand-700 mt-1">{{ $conversionRate }}%</h3>
      <span class="text-[10px] text-slate-400 font-medium block mt-0.5">{{ $wonLeads }} won out of {{ $totalLeads }} leads</span>
    </div>
    <div class="bg-white border border-slate-200 p-5 rounded-3xl shadow-xs">
      <span class="text-[10px] font-bold text-purple-600 uppercase tracking-wider block">Delivery Rate</span>
      <h3 class="text-2xl font-extrabold text-purple-700 mt-1">{{ $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100, 0) : 0 }}%</h3>
      <span class="text-[10px] text-slate-400 font-medium block mt-0.5">{{ $completedProjects }} / {{ $totalProjects }} projects delivered</span>
    </div>
  </div>

  <!-- Tables Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Project Types Breakdown -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
      <h3 class="font-extrabold text-sm text-slate-900">Project Types & Total Budget Value</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
            <tr>
              <th class="px-3 py-2.5">Type</th>
              <th class="px-3 py-2.5">Projects Count</th>
              <th class="px-3 py-2.5 text-right">Cumulative Value</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
            @forelse($projectTypes as $pt)
              <tr>
                <td class="px-3 py-2.5 font-bold text-slate-900">{{ $pt->project_type }}</td>
                <td class="px-3 py-2.5">{{ $pt->count }}</td>
                <td class="px-3 py-2.5 text-right font-extrabold text-emerald-600">${{ number_format($pt->total_budget, 0) }}</td>
              </tr>
            @empty
              <tr><td colspan="3" class="p-4 text-center text-slate-400">No project type data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Lead Sources Breakdown -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
      <h3 class="font-extrabold text-sm text-slate-900">Lead Acquisition Channels</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
            <tr>
              <th class="px-3 py-2.5">Channel / Source</th>
              <th class="px-3 py-2.5">Leads Acquired</th>
              <th class="px-3 py-2.5 text-right">Pipeline Potential</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
            @forelse($leadSources as $ls)
              <tr>
                <td class="px-3 py-2.5 font-bold text-slate-900">{{ $ls->lead_source }}</td>
                <td class="px-3 py-2.5">{{ $ls->count }}</td>
                <td class="px-3 py-2.5 text-right font-extrabold text-slate-800">${{ number_format($ls->total_budget, 0) }}</td>
              </tr>
            @empty
              <tr><td colspan="3" class="p-4 text-center text-slate-400">No lead source data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

</div>
@endsection

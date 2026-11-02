@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — Solar EPC Executive Dashboard')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <span class="text-slate-700">Solar EPC Dashboard</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-6 font-sans">

    {{-- 1. Attention Required Alerts --}}
    @include('admin.dashboard.partials.alerts')

    {{-- 2. Solar EPC Capacity & Generation Hero Banner --}}
    @include('admin.dashboard.partials.solar-capacity-bar')

    {{-- 3. Executive Launchpad (Quick Actions) --}}
    @include('admin.dashboard.partials.quick-actions')

    {{-- 4. 9 Core Solar & CRM KPI Cards --}}
    @include('admin.dashboard.partials.kpis')

    {{-- 5. Active Solar Plant Installation Tracker --}}
    @include('admin.dashboard.partials.active-plants')

    {{-- 6. Solar Sector Classification & DISCOM Interconnection Charts --}}
    @include('admin.dashboard.partials.sector-discom')

    {{-- 7. Pipeline Funnel & Revenue Collections Growth --}}
    @include('admin.dashboard.partials.pipeline-revenue')

    {{-- 8. Top Solar Executives Leaderboard & High-Priority Engineering Tasks --}}
    @include('admin.dashboard.partials.leaderboard-tasks')

    {{-- 9. Project Lifecycles, Upcoming Site Surveys & Real-Time Activity Feed --}}
    @include('admin.dashboard.partials.followups-activity')

  </div>

  {{-- 10. ApexCharts & Interactive Telemetry Engine --}}
  @include('admin.dashboard.partials.scripts')
@endsection

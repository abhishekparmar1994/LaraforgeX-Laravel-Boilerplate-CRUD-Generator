@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — Solar EPC Executive Dashboard')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <span class="text-slate-700">Solar EPC Dashboard</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- ── 1. ATTENTION REQUIRED ALERT BAR ────────────────────────── -->
  @if(!empty($attentionAlerts) && count($attentionAlerts) > 0)
  <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 shadow-xs">
    <div class="flex items-center justify-between gap-3 mb-2.5">
      <div class="flex items-center gap-2 text-amber-900 font-extrabold text-xs uppercase tracking-wider">
        <i class="fa-solid fa-bell-concierge text-amber-600 animate-bounce"></i>
        <span>Attention Required Today</span>
      </div>
      <span class="text-[10px] font-bold bg-amber-200/60 text-amber-800 px-2 py-0.5 rounded-full">{{ count($attentionAlerts) }} Items</span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
      @foreach($attentionAlerts as $alert)
        <a href="{{ $alert['link'] }}" class="flex items-center gap-2.5 p-2.5 bg-white/90 hover:bg-white border border-amber-200/60 rounded-xl transition shadow-2xs group">
          <div class="h-7 w-7 rounded-lg {{ $alert['type'] === 'danger' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} flex items-center justify-center text-xs shrink-0 group-hover:scale-110 transition-transform">
            <i class="{{ $alert['icon'] }}"></i>
          </div>
          <p class="text-xs font-bold text-slate-800 leading-snug group-hover:text-brand-600 transition truncate">{{ $alert['title'] }}</p>
        </a>
      @endforeach
    </div>
  </div>
  @endif

  <!-- ── 2. 9 CORE KPI CARDS ────────────────────────────────────── -->
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-9 gap-3">
    <!-- Total Leads -->
    <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Leads</p>
        <i class="fa-solid fa-filter-dollar text-brand-500 text-xs"></i>
      </div>
      <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['total_leads'] }}</h3>
      <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1 mt-0.5">
        <i class="fa-solid fa-arrow-trend-up text-[8px]"></i> +14% MoM
      </span>
    </div>

    <!-- New Leads -->
    <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">New Leads</p>
        <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
      </div>
      <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['new_leads'] }}</h3>
      <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">Stage: Inbound</span>
    </div>

    <!-- Active Clients -->
    <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Clients</p>
        <i class="fa-solid fa-user-tie text-indigo-500 text-xs"></i>
      </div>
      <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['active_clients'] }}</h3>
      <span class="text-[10px] text-indigo-600 font-bold mt-0.5 block">100% Retained</span>
    </div>

    <!-- Active Solar Sites -->
    <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Solar Sites</p>
        <i class="fa-solid fa-solar-panel text-amber-500 text-xs"></i>
      </div>
      <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['active_websites'] }}</h3>
      <span class="text-[10px] text-amber-600 font-bold mt-0.5 block">Active Plants</span>
    </div>

    <!-- Projects in Progress -->
    <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Projects</p>
        <i class="fa-solid fa-diagram-project text-purple-500 text-xs"></i>
      </div>
      <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['projects_in_progress'] }}</h3>
      <span class="text-[10px] text-purple-600 font-bold mt-0.5 block">In Delivery</span>
    </div>

    <!-- Pending Tasks -->
    <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Tasks</p>
        <i class="fa-solid fa-list-check text-amber-500 text-xs"></i>
      </div>
      <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['pending_tasks'] }}</h3>
      <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">Assigned to team</span>
    </div>

    <!-- Overdue Tasks -->
    <div class="bg-white border {{ $kpi['overdue_tasks'] > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200' }} p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold {{ $kpi['overdue_tasks'] > 0 ? 'text-rose-600' : 'text-slate-400' }} uppercase tracking-wider">Overdue Tasks</p>
        <i class="fa-solid fa-triangle-exclamation {{ $kpi['overdue_tasks'] > 0 ? 'text-rose-500' : 'text-slate-300' }} text-xs"></i>
      </div>
      <h3 class="text-xl font-extrabold {{ $kpi['overdue_tasks'] > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">{{ $kpi['overdue_tasks'] }}</h3>
      <span class="text-[10px] {{ $kpi['overdue_tasks'] > 0 ? 'text-rose-600 font-bold' : 'text-slate-400' }} mt-0.5 block">Needs attention</span>
    </div>

    <!-- Pending Payments -->
    <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Rev</p>
        <i class="fa-solid fa-clock text-amber-500 text-xs"></i>
      </div>
      <h3 class="text-lg font-extrabold text-slate-900 mt-1">₹{{ number_format($kpi['pending_payments'], 0) }}</h3>
      <span class="text-[10px] text-amber-600 font-bold mt-0.5 block">Invoices pending</span>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1 xl:col-span-1">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Revenue</p>
        <i class="fa-solid fa-sack-dollar text-emerald-500 text-xs"></i>
      </div>
      <h3 class="text-lg font-extrabold text-emerald-700 mt-1">₹{{ number_format($kpi['total_revenue'], 0) }}</h3>
      <span class="text-[10px] text-emerald-600 font-bold mt-0.5 block">Collected</span>
    </div>
  </div>

  <!-- ── 3. CHARTS GRID ─────────────────────────────────────────── -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Chart 1: Sales Lead Pipeline Funnel -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3 col-span-1 lg:col-span-2">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Lead Pipeline & Deal Value</h3>
          <p class="text-xs text-slate-400 font-medium">Stage progression and cumulative pipeline budget</p>
        </div>
        <a href="/admin/leads/kanban" class="text-xs font-bold text-brand-600 hover:text-brand-500 flex items-center gap-1">
          <span>Open Pipeline</span>
          <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
      </div>
      <div id="pipeline-chart" class="min-h-[260px]"></div>
    </div>

    <!-- Chart 2: Monthly Revenue Curve -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3 col-span-1">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Revenue Growth</h3>
          <p class="text-xs text-slate-400 font-medium">Collected project invoices</p>
        </div>
        <a href="/admin/payments" class="text-xs font-bold text-brand-600 hover:text-brand-500 flex items-center gap-1">
          <span>Billing</span>
          <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
      </div>
      <div id="revenue-chart" class="min-h-[260px]"></div>
    </div>

  </div>

  <!-- ── 4. SECOND ROW CHARTS & WORKLOAD ────────────────────────── -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Chart 3: Project Status Distribution -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
      <div class="flex items-center justify-between">
        <h3 class="font-extrabold text-sm text-slate-900">Project Lifecycles</h3>
        <a href="/admin/projects" class="text-xs font-bold text-brand-600 hover:underline">View All</a>
      </div>
      <div id="project-status-chart" class="min-h-[220px]"></div>
    </div>

    <!-- Upcoming Follow-ups -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
      <div class="flex items-center justify-between">
        <h3 class="font-extrabold text-sm text-slate-900">Upcoming Follow-ups</h3>
        <a href="/admin/followups" class="text-xs font-bold text-brand-600 hover:underline">View Schedule</a>
      </div>
      <div class="divide-y divide-slate-100">
        @forelse($upcomingFollowups as $f)
          <div class="py-2.5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
              <div class="h-8 w-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs shrink-0">
                <i class="fa-solid {{ $f->follow_up_type === 'phone_call' ? 'fa-phone' : ($f->follow_up_type === 'email' ? 'fa-envelope' : ($f->follow_up_type === 'whatsapp' ? 'fa-brands fa-whatsapp' : 'fa-handshake')) }}"></i>
              </div>
              <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 truncate">
                  {{ $f->lead ? $f->lead->company_name : ($f->client ? $f->client->name : 'Follow-up') }}
                </p>
                <p class="text-[11px] text-slate-400 truncate">
                  {{ $f->scheduled_at->format('M d, H:i') }} • {{ $f->assignedTo ? $f->assignedTo->name : 'Unassigned' }}
                </p>
              </div>
            </div>
            <button onclick="completeFollowup('{{ $f->id }}')" class="text-[10px] bg-slate-100 hover:bg-emerald-50 hover:text-emerald-600 text-slate-600 font-bold px-2 py-1 rounded-lg transition shrink-0">
              Done
            </button>
          </div>
        @empty
          <div class="py-6 text-center text-slate-400 text-xs">No pending follow-ups scheduled</div>
        @endforelse
      </div>
    </div>

    <!-- Recent Activity Timeline -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
      <div class="flex items-center justify-between">
        <h3 class="font-extrabold text-sm text-slate-900">Recent Solar Activity</h3>
        <a href="/admin/activities" class="text-xs font-bold text-brand-600 hover:underline">Full Feed</a>
      </div>
      <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
        @forelse($recentActivities as $act)
          <div class="py-2.5 flex items-start gap-2.5">
            <div class="h-6 w-6 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5">
              <i class="fa-solid {{ $act->type === 'status_change' ? 'fa-arrow-right-arrow-left' : ($act->type === 'payment' ? 'fa-dollar-sign' : ($act->type === 'deployment' ? 'fa-cloud-arrow-up' : 'fa-comment-dots')) }}"></i>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs text-slate-700 leading-snug">{{ $act->description }}</p>
              <span class="text-[10px] text-slate-400 font-medium block mt-0.5">{{ $act->created_at->diffForHumans() }}</span>
            </div>
          </div>
        @empty
          <div class="py-6 text-center text-slate-400 text-xs">No recent activity logged</div>
        @endforelse
      </div>
    </div>

  </div>

</div>

<!-- ── CHARTS INITIALIZATION SCRIPT ──────────────────────────────── -->
<script>
  $(document).ready(async function () {
    try {
      const res = await axios.get('/admin/crm/analytics', { baseURL: '' });
      const data = res.data;

      // 1. Pipeline Bar Chart
      if (document.querySelector("#pipeline-chart")) {
        const pipelineOptions = {
          series: [{
            name: 'Leads Count',
            data: data.pipeline.counts
          }],
          chart: {
            type: 'bar',
            height: 250,
            toolbar: { show: false }
          },
          colors: ['#2b47ff'],
          plotOptions: {
            bar: {
              borderRadius: 6,
              columnWidth: '45%',
              distributed: false,
            }
          },
          dataLabels: { enabled: true },
          xaxis: {
            categories: data.pipeline.labels,
            labels: { style: { fontSize: '11px', fontWeight: 600 } }
          },
          grid: { borderColor: '#f1f5f9' },
        };
        new ApexCharts(document.querySelector("#pipeline-chart"), pipelineOptions).render();
      }

      // 2. Revenue Area Chart
      if (document.querySelector("#revenue-chart")) {
        const revenueOptions = {
          series: [{
            name: 'Collected Revenue (₹)',
            data: data.monthly_revenue.totals
          }],
          chart: {
            type: 'area',
            height: 250,
            toolbar: { show: false }
          },
          colors: ['#10b981'],
          stroke: { curve: 'smooth', width: 2.5 },
          fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 90, 100] }
          },
          xaxis: {
            categories: data.monthly_revenue.months,
            labels: { style: { fontSize: '10px' } }
          },
          grid: { borderColor: '#f1f5f9' },
        };
        new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions).render();
      }

      // 3. Project Status Donut Chart
      if (document.querySelector("#project-status-chart")) {
        const projectOptions = {
          series: data.project_statuses.counts,
          chart: {
            type: 'donut',
            height: 220
          },
          labels: data.project_statuses.labels,
          colors: ['#64748b', '#8b5cf6', '#2b47ff', '#06b6d4', '#f59e0b', '#10b981', '#059669', '#ef4444'],
          legend: { position: 'bottom', fontSize: '11px' },
          dataLabels: { enabled: false }
        };
        new ApexCharts(document.querySelector("#project-status-chart"), projectOptions).render();
      }

    } catch(err) {
      console.error("Dashboard charts error:", err);
    }
  });

  async function completeFollowup(id) {
    try {
      await axios.post(`/admin/followups/${id}/complete`, {}, { baseURL: '' });
      location.reload();
    } catch(e) {
      alert('Error updating follow-up');
    }
  }
</script>
@endsection
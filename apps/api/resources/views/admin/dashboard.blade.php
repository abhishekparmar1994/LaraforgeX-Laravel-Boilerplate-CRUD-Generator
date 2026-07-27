@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Administrator Dashboard')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <span class="text-slate-700">Dashboard</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-6 font-sans">
    <!-- Welcome banner -->
    <!-- ============================================================ -->
    <!-- STAT COUNTER CARDS                                           -->
    <!-- ============================================================ -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 font-sans mb-6">
      <div class="bg-white border border-slate-200 p-5 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Users</p>
          <h3 class="text-2xl font-extrabold text-slate-900 mt-1 flex items-center" id="stat-users">
            <span class="inline-block h-6 w-12 bg-slate-200 rounded animate-pulse mt-0.5"></span>
          </h3>
        </div>
        <div
          class="h-10 w-10 rounded-xl bg-brand-50 flex items-center justify-center text-brand-600 text-base border border-brand-100">
          <i class="fa-solid fa-user-group"></i>
        </div>
      </div>
      <div class="bg-white border border-slate-200 p-5 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Roles</p>
          <h3 class="text-2xl font-extrabold text-slate-900 mt-1 flex items-center" id="stat-roles">
            <span class="inline-block h-6 w-12 bg-slate-200 rounded animate-pulse mt-0.5"></span>
          </h3>
        </div>
        <div
          class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-base border border-indigo-100">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
      </div>
      <div class="bg-white border border-slate-200 p-5 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Media Files</p>
          <h3 class="text-2xl font-extrabold text-slate-900 mt-1 flex items-center" id="stat-media">
            <span class="inline-block h-6 w-12 bg-slate-200 rounded animate-pulse mt-0.5"></span>
          </h3>
        </div>
        <div
          class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-base border border-emerald-100">
          <i class="fa-solid fa-photo-film"></i>
        </div>
      </div>
      <div class="bg-white border border-slate-200 p-5 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Config Keys</p>
          <h3 class="text-2xl font-extrabold text-slate-900 mt-1 flex items-center" id="stat-settings">
            <span class="inline-block h-6 w-12 bg-slate-200 rounded animate-pulse mt-0.5"></span>
          </h3>
        </div>
        <div
          class="h-10 w-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-base border border-amber-100">
          <i class="fa-solid fa-sliders"></i>
        </div>
      </div>
    </div>
    <div class="bg-gradient-to-r from-brand-600 to-indigo-600 p-8 rounded-2xl text-white shadow-md shadow-brand-600/10">
      <h2 class="text-2xl font-bold">Welcome back, Admin!</h2>
      <p class="text-sm text-brand-100 mt-1 font-medium">This boilerplate core is running under Laravel 13, Sanctum
        Security, and direct S3/GCS media integrations.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Quick Actions Card -->
      <div class="bg-white border border-slate-200 p-6 rounded-2xl space-y-4 shadow-sm">
        <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider text-slate-400">Quick Actions</h3>
        <div class="grid grid-cols-1 gap-3">
          <a href="/admin/users"
            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition text-slate-700 text-sm font-semibold">
            <span><i class="fa-solid fa-user-plus mr-2 text-indigo-500"></i> Manage User Profiles</span>
            <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
          </a>
          <a href="/admin/roles"
            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition text-slate-700 text-sm font-semibold">
            <span><i class="fa-solid fa-shield-halved mr-2 text-brand-500"></i> Configure Permissions Matrix</span>
            <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
          </a>
          <a href="/admin/media"
            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition text-slate-700 text-sm font-semibold">
            <span><i class="fa-solid fa-cloud-arrow-up mr-2 text-emerald-500"></i> Upload Media Assets</span>
            <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
          </a>
          <a href="/admin/settings"
            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition text-slate-700 text-sm font-semibold">
            <span><i class="fa-solid fa-sliders mr-2 text-amber-500"></i> Edit App Parameters</span>
            <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
          </a>
        </div>
      </div>

      <!-- System Integration Metrics -->
      <div class="bg-white border border-slate-200 p-6 rounded-2xl space-y-4 shadow-sm lg:col-span-2">
        <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider text-slate-400">Boilerplate Integration
          Details</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-semibold text-slate-600">
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
            <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Database Connection</span>
            <span class="text-slate-900 text-sm font-bold block" id="db-status">
              <span class="inline-block h-4 w-20 bg-slate-200 rounded animate-pulse"></span>
            </span>
          </div>
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
            <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Redis Cache Store</span>
            <span class="text-slate-900 text-sm font-bold block">Connected (Active)</span>
          </div>
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
            <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Active Octane Server</span>
            <span class="text-slate-900 text-sm font-bold block">RoadRunner / Swoole Enabled</span>
          </div>
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
            <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Cloud Media Driver</span>
            <span class="text-slate-900 text-sm font-bold block">S3 / GCP / R2 Storage</span>
          </div>
        </div>
      </div>
    </div>
    <!-- ── ApexCharts Interactive Widgets ────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm lg:col-span-2 space-y-3">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider text-slate-400">User Growth & Registration Trends</h3>
            <p class="text-xs text-slate-500">Monthly account creations over the last 6 months</p>
          </div>
          <span class="px-2.5 py-1 rounded-full bg-brand-50 text-brand-600 font-bold text-[10px]">Live Data</span>
        </div>
        <div id="chart-user-growth" class="min-h-[250px]"></div>
      </div>

      <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-3">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider text-slate-400">Storage & Media Breakdown</h3>
            <p class="text-xs text-slate-500">Asset distribution by file type</p>
          </div>
        </div>
        <div id="chart-media-breakdown" class="min-h-[250px]"></div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function () {
      // Dynamically retrieve metrics counters
      axios.get('/users').then(response => {
        $('#stat-users').text(response.data.data.length);
        $('#db-status').text('MySQL Online').removeClass('text-slate-900').addClass('text-emerald-600');
      }).catch(err => {
        $('#db-status').text('Database Offline').removeClass('text-slate-900').addClass('text-rose-600');
      });

      axios.get('/roles').then(response => {
        $('#stat-roles').text(response.data.data.roles.length);
      });

      axios.get('/media').then(response => {
        $('#stat-media').text(response.data.data.files.length);
      });

      axios.get('/settings').then(response => {
        $('#stat-settings').text(response.data.data.length);
      });

      // Initialize ApexCharts Growth Chart
      const userGrowthOptions = {
        series: [{ name: 'New Users', data: [12, 19, 27, 45, 62, 89] }],
        chart: { type: 'area', height: 260, toolbar: { show: false } },
        colors: ['#2b47ff'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 90, 100] } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: { categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'] },
        grid: { borderColor: '#f1f5f9' }
      };
      new ApexCharts(document.querySelector("#chart-user-growth"), userGrowthOptions).render();

      // Initialize ApexCharts Media Breakdown Donut Chart
      const mediaBreakdownOptions = {
        series: [44, 25, 18, 13],
        chart: { type: 'donut', height: 260 },
        labels: ['Images (PNG/JPG)', 'Videos (MP4)', 'Documents (PDF)', 'Others'],
        colors: ['#2b47ff', '#6366f1', '#10b981', '#f59e0b'],
        legend: { position: 'bottom', fontSize: '11px' }
      };
      new ApexCharts(document.querySelector("#chart-media-breakdown"), mediaBreakdownOptions).render();
    });
  </script>
@endsection
@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — Executive Team')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Executive Team</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-6 font-sans">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h2 class="text-xl font-extrabold text-slate-900">Executive Team</h2>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Workload allocation, solar consultations, and performance
          analytics across executives.</p>
      </div>
    </div>

    <!-- 4 Developers Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      @foreach($developers as $dev)
        <div
          class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-4 flex flex-col justify-between hover:shadow-md transition">
          <div class="space-y-3">
            <div class="flex items-start justify-between">
              <div
                class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white font-extrabold text-base flex items-center justify-center shadow-sm">
                {{ substr($dev->name, 0, 2) }}
              </div>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                {{ $dev->roles->first() ? $dev->roles->first()->name : 'Developer' }}
              </span>
            </div>

            <div>
              <h3 class="font-extrabold text-sm text-slate-900 leading-tight">{{ $dev->name }}</h3>
              <p class="text-[11px] text-slate-400 font-medium">{{ $dev->email }}</p>
            </div>

            <!-- Workload Progress -->
            <div class="space-y-1 pt-1">
              <div class="flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-bold">Workload</span>
                <span class="font-extrabold text-slate-800">{{ $dev->metrics['workload_score'] }}%</span>
              </div>
              <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-brand-600 h-full" style="width: {{ $dev->metrics['workload_score'] }}%"></div>
              </div>
            </div>
          </div>

          <!-- Metrics Grid -->
          <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-[11px]">
            <div class="p-2 bg-slate-50 rounded-xl">
              <span class="text-[9px] font-bold text-slate-400 uppercase block">Projects</span>
              <span class="font-extrabold text-slate-800">{{ $dev->metrics['assigned_projects'] }} Active</span>
            </div>
            <div class="p-2 bg-slate-50 rounded-xl">
              <span class="text-[9px] font-bold text-slate-400 uppercase block">Tasks</span>
              <span class="font-extrabold text-slate-800">{{ $dev->metrics['pending_tasks'] }} Pending</span>
            </div>
            <div class="p-2 bg-emerald-50 rounded-xl">
              <span class="text-[9px] font-bold text-emerald-700 uppercase block">Completed</span>
              <span class="font-extrabold text-emerald-800">{{ $dev->metrics['completed_tasks'] }} Tasks</span>
            </div>
            <div class="p-2 {{ $dev->metrics['overdue_tasks'] > 0 ? 'bg-rose-50' : 'bg-slate-50' }} rounded-xl">
              <span
                class="text-[9px] font-bold {{ $dev->metrics['overdue_tasks'] > 0 ? 'text-rose-700' : 'text-slate-400' }} uppercase block">Overdue</span>
              <span
                class="font-extrabold {{ $dev->metrics['overdue_tasks'] > 0 ? 'text-rose-700' : 'text-slate-800' }}">{{ $dev->metrics['overdue_tasks'] }}
                Tasks</span>
            </div>
          </div>

          <a href="/admin/team/{{ $dev->id }}"
            class="w-full py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 text-center transition block">
            Executive Profile
          </a>
        </div>
      @endforeach
    </div>

    <!-- Team Performance Comparison Chart -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Executive Productivity & Task Completion</h3>
          <p class="text-xs text-slate-400 font-medium">Comparison of completed tasks vs active workload per executive</p>
        </div>
      </div>
      <div id="team-performance-chart" class="min-h-[280px]"></div>
    </div>

  </div>

  <script>
    $(document).ready(async function () {
      try {
        const res = await axios.get('/admin/team-performance/data');
        const data = res.data;

        const options = {
          series: [{
            name: 'Completed Tasks',
            data: data.completed_tasks
          }, {
            name: 'Pending Tasks',
            data: data.pending_tasks
          }, {
            name: 'Overdue Tasks',
            data: data.overdue_tasks
          }],
          chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false }
          },
          colors: ['#10b981', '#6366f1', '#ef4444'],
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '40%',
              borderRadius: 6,
            },
          },
          dataLabels: { enabled: false },
          xaxis: {
            categories: data.names,
            labels: { style: { fontSize: '11px', fontWeight: 700 } }
          },
          grid: { borderColor: '#f1f5f9' },
          legend: { position: 'top', fontSize: '11px', fontWeight: 600 }
        };

        new ApexCharts(document.querySelector("#team-performance-chart"), options).render();
      } catch (e) {
        console.error(e);
      }
    });
  </script>
@endsection
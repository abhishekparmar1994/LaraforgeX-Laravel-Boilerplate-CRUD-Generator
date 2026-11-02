{{-- ── 4. 9 CORE SOLAR & CRM KPI CARDS ────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-9 gap-3">
  {{-- Total Leads --}}
  <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Leads</p>
      <i class="fa-solid fa-filter-dollar text-brand-500 text-xs"></i>
    </div>
    <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['total_leads'] }}</h3>
    <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1 mt-0.5">
      <i class="fa-solid fa-arrow-trend-up text-[8px]"></i> +14% MoM
    </span>
  </div>

  {{-- New Leads --}}
  <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">New Leads</p>
      <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
    </div>
    <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['new_leads'] }}</h3>
    <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">Stage: Inbound</span>
  </div>

  {{-- Active Clients --}}
  <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Clients</p>
      <i class="fa-solid fa-user-tie text-indigo-500 text-xs"></i>
    </div>
    <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['active_clients'] }}</h3>
    <span class="text-[10px] text-indigo-600 font-bold mt-0.5 block">100% Retained</span>
  </div>

  {{-- Active Solar Sites --}}
  <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Solar Sites</p>
      <i class="fa-solid fa-solar-panel text-amber-500 text-xs"></i>
    </div>
    <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['active_websites'] }}</h3>
    <span class="text-[10px] text-amber-600 font-bold mt-0.5 block">Active Plants</span>
  </div>

  {{-- Projects in Progress --}}
  <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Projects</p>
      <i class="fa-solid fa-diagram-project text-purple-500 text-xs"></i>
    </div>
    <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['projects_in_progress'] }}</h3>
    <span class="text-[10px] text-purple-600 font-bold mt-0.5 block">In Delivery</span>
  </div>

  {{-- Pending Tasks --}}
  <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Tasks</p>
      <i class="fa-solid fa-list-check text-amber-500 text-xs"></i>
    </div>
    <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $kpi['pending_tasks'] }}</h3>
    <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">Assigned to team</span>
  </div>

  {{-- Overdue Tasks --}}
  <div class="bg-white border {{ $kpi['overdue_tasks'] > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200' }} p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold {{ $kpi['overdue_tasks'] > 0 ? 'text-rose-600' : 'text-slate-400' }} uppercase tracking-wider">Overdue Tasks</p>
      <i class="fa-solid fa-triangle-exclamation {{ $kpi['overdue_tasks'] > 0 ? 'text-rose-500' : 'text-slate-300' }} text-xs"></i>
    </div>
    <h3 class="text-xl font-extrabold {{ $kpi['overdue_tasks'] > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">{{ $kpi['overdue_tasks'] }}</h3>
    <span class="text-[10px] {{ $kpi['overdue_tasks'] > 0 ? 'text-rose-600 font-bold' : 'text-slate-400' }} mt-0.5 block">Needs action</span>
  </div>

  {{-- Pending Payments --}}
  <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Rev</p>
      <i class="fa-solid fa-clock text-amber-500 text-xs"></i>
    </div>
    <h3 class="text-lg font-extrabold text-slate-900 mt-1">₹{{ number_format($kpi['pending_payments'], 0) }}</h3>
    <span class="text-[10px] text-amber-600 font-bold mt-0.5 block">Invoices pending</span>
  </div>

  {{-- Total Revenue --}}
  <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs col-span-1">
    <div class="flex items-center justify-between">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Revenue</p>
      <i class="fa-solid fa-sack-dollar text-emerald-500 text-xs"></i>
    </div>
    <h3 class="text-lg font-extrabold text-emerald-700 mt-1">₹{{ number_format($kpi['total_revenue'], 0) }}</h3>
    <span class="text-[10px] text-emerald-600 font-bold mt-0.5 block">Collected</span>
  </div>
</div>


{{-- ── 7. PIPELINE FUNNEL & REVENUE GROWTH CHARTS ──────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- Chart: Lead Pipeline Funnel --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3 col-span-1 lg:col-span-2">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Solar Pipeline & Stage Progression</h3>
        <p class="text-xs text-slate-400 font-medium">Inquiry flow from initial survey to commissioned plant</p>
      </div>
      <a href="/admin/leads/kanban" class="text-xs font-bold text-brand-600 hover:text-brand-500 flex items-center gap-1">
        <span>Open Kanban</span>
        <i class="fa-solid fa-arrow-right text-[10px]"></i>
      </a>
    </div>
    <div id="pipeline-chart" class="min-h-[260px]"></div>
  </div>

  {{-- Chart: Monthly Revenue Curve --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3 col-span-1">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Revenue Collections</h3>
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


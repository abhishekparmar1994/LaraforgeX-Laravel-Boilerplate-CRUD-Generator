{{-- ── 2. SOLAR EPC CAPACITY & REVENUE HERO BANNER ──────────────── --}}
<div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950 rounded-2xl p-5 text-white shadow-md">
  <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

  <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
    <div class="space-y-1">
      <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
          <i class="fa-solid fa-bolt text-amber-400"></i> Solar EPC Generation Pulse
        </span>
        <span class="text-xs text-slate-400">Western India EPC Grid</span>
      </div>
      <h2 class="text-lg lg:text-xl font-black text-white tracking-tight">
        Gujarat Rooftop & C&I Solar Deployment Hub
      </h2>
      <p class="text-xs text-slate-300 max-w-xl">
        Live telemetry tracking active EPC engineering, Discom synchronization, and PM Surya Ghar subsidy pipelines.
      </p>
    </div>

    {{-- 4 Core Solar Capacity Metrics --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 shrink-0">
      <div class="bg-white/10 backdrop-blur-xs border border-white/10 rounded-xl p-3 text-center sm:text-left min-w-[130px]">
        <p class="text-[10px] font-bold text-amber-300 uppercase tracking-wider">Active Delivery</p>
        <p class="text-lg font-black text-white mt-0.5">{{ $solar_metrics['active_plant_kw'] }} <span class="text-xs font-semibold text-slate-300">kWp</span></p>
        <span class="text-[9px] text-emerald-400 font-bold block">6 EPC Plants</span>
      </div>

      <div class="bg-white/10 backdrop-blur-xs border border-white/10 rounded-xl p-3 text-center sm:text-left min-w-[130px]">
        <p class="text-[10px] font-bold text-sky-300 uppercase tracking-wider">Pipeline Load</p>
        <p class="text-lg font-black text-white mt-0.5">{{ number_format($solar_metrics['pipeline_kw'] / 1000, 2) }} <span class="text-xs font-semibold text-slate-300">MWp</span></p>
        <span class="text-[9px] text-sky-300 font-bold block">{{ $solar_metrics['total_inquiries'] }} Qualified Inquiries</span>
      </div>

      <div class="bg-white/10 backdrop-blur-xs border border-white/10 rounded-xl p-3 text-center sm:text-left min-w-[130px]">
        <p class="text-[10px] font-bold text-amber-300 uppercase tracking-wider">Pipeline Value</p>
        <p class="text-lg font-black text-amber-400 mt-0.5">₹{{ number_format($solar_metrics['pipeline_value'] / 10000000, 2) }} <span class="text-xs font-semibold text-amber-200">Cr</span></p>
        <span class="text-[9px] text-slate-300 font-medium block">Avg Deal: ₹22.3L</span>
      </div>

      <div class="bg-white/10 backdrop-blur-xs border border-white/10 rounded-xl p-3 text-center sm:text-left min-w-[130px]">
        <p class="text-[10px] font-bold text-emerald-300 uppercase tracking-wider">Surya Ghar Subsidy</p>
        <p class="text-lg font-black text-emerald-400 mt-0.5">₹{{ number_format($solar_metrics['subsidy_pool'] / 100000, 2) }} <span class="text-xs font-semibold text-emerald-200">L</span></p>
        <span class="text-[9px] text-emerald-300 font-bold block">{{ $solar_metrics['subsidy_leads'] }} Govt Eligible</span>
      </div>
    </div>
  </div>
</div>


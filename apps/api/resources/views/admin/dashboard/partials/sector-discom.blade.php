{{-- ── 6. SOLAR SECTORS & DISCOM GRID DISTRIBUTION ────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  {{-- Chart: Solar Sector & Product Group Share --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Solar Product Classification</h3>
        <p class="text-xs text-slate-400 font-medium">Residential, C&I Industrial, and PM Surya Ghar breakdown</p>
      </div>
      <a href="/admin/leads" class="text-xs font-bold text-brand-600 hover:text-brand-500 flex items-center gap-1">
        <span>Leads ({{ $solar_metrics['total_inquiries'] }})</span>
        <i class="fa-solid fa-arrow-right text-[10px]"></i>
      </a>
    </div>
    <div id="sector-chart" class="min-h-[250px]"></div>
  </div>

  {{-- Chart: Gujarat DISCOM Grid Distribution --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">DISCOM Grid Interconnections</h3>
        <p class="text-xs text-slate-400 font-medium">Inquiry density across Gujarat electricity distribution utilities</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="https://pmsuryaghar.gov.in" target="_blank" rel="noopener" class="text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 px-2 py-0.5 rounded-lg border border-amber-200/80 transition flex items-center gap-1">
          <span>Surya Ghar</span>
          <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
        </a>
      </div>
    </div>
    <div id="discom-chart" class="min-h-[250px]"></div>
  </div>

</div>


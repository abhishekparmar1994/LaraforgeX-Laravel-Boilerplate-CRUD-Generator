{{-- Solar Rooftop Sizing & PM Surya Ghar Subsidy Summary Card --}}
@php
  $estBudget = (float) ($lead->estimated_budget ?? 0);
  $kw = $lead->solar_capacity_kw ?: ($estBudget > 0 ? max(1.0, round($estBudget / 55000, 1)) : 3.0);
  $totalCost = $estBudget > 0 ? $estBudget : ($kw * 55000);

  // PM Surya Ghar Subsidy Matrix
  if ($kw <= 1.0) {
    $subsidy = 30000;
  } elseif ($kw <= 2.0) {
    $subsidy = 60000;
  } else {
    $subsidy = 78000; // Max central DBT subsidy under PM Surya Ghar
  }

  $netCustomerCost = max(0, $totalCost - $subsidy);
  $annualUnits = round($kw * 1400);
  $annualSavings = round($annualUnits * 7.5);
  $paybackYears = $annualSavings > 0 ? round($netCustomerCost / $annualSavings, 1) : 3.2;
  $rooftopArea = round($kw * 95);
@endphp

<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4 font-sans">
  <div class="flex items-center justify-between border-b border-slate-100 pb-3">
    <div class="flex items-center gap-2.5">
      <div
        class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm shadow-2xs">
        <i class="fa-solid fa-solar-panel"></i>
      </div>
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Solar Sizing & PM Surya Ghar Subsidy Feasibility</h3>
        <p class="text-[11px] text-slate-400 font-medium">Standardized engineering estimates for On-Grid Net Metering
        </p>
      </div>
    </div>
    <span
      class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
      <i class="fa-solid fa-check-circle text-emerald-500 mr-1"></i> Feasibility Ready
    </span>
  </div>

  <!-- Sizing Metrics Grid -->
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <div class="p-3.5 bg-amber-50/60 border border-amber-200/80 rounded-2xl">
      <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Recommended System</span>
      <span class="text-lg font-black text-amber-900 block mt-0.5">{{ $kw }} kW On-Grid</span>
      <span class="text-[10px] text-amber-600 font-medium">~{{ $rooftopArea }} sq.ft. shadow-free</span>
    </div>

    <div class="p-3.5 bg-emerald-50/60 border border-emerald-200/80 rounded-2xl">
      <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Central DBT Subsidy</span>
      <span class="text-lg font-black text-emerald-800 block mt-0.5">₹{{ number_format($subsidy) }}</span>
      <span class="text-[10px] text-emerald-600 font-medium">Direct Bank Transfer</span>
    </div>

    <div class="p-3.5 bg-blue-50/60 border border-blue-200/80 rounded-2xl">
      <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">Net Customer Cost</span>
      <span class="text-lg font-black text-blue-900 block mt-0.5">₹{{ number_format($netCustomerCost) }}</span>
      <span class="text-[10px] text-blue-600 font-medium">After DBT credit</span>
    </div>

    <div class="p-3.5 bg-purple-50/60 border border-purple-200/80 rounded-2xl">
      <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Annual Bill Savings</span>
      <span class="text-lg font-black text-purple-900 block mt-0.5">₹{{ number_format($annualSavings) }}/yr</span>
      <span class="text-[10px] text-purple-600 font-medium">~{{ $paybackYears }} yrs payback</span>
    </div>
  </div>

  <!-- Technical Specs Pill Bar -->
  <div
    class="p-3 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between flex-wrap gap-2 text-xs">
    <div class="flex items-center gap-1.5 text-slate-700 font-bold">
      <i class="fa-solid fa-layer-group text-amber-500"></i>
      <span>Modules: Monocrystalline Bifacial 550 Wp</span>
    </div>
    <div class="flex items-center gap-1.5 text-slate-700 font-bold">
      <i class="fa-solid fa-bolt text-amber-500"></i>
      <span>Inverter: On-Grid String (Net-Meter Ready)</span>
    </div>
    <div class="flex items-center gap-1.5 text-slate-700 font-bold">
      <i class="fa-solid fa-shield-halved text-emerald-600"></i>
      <span>25-Year Performance Warranty</span>
    </div>
  </div>
</div>
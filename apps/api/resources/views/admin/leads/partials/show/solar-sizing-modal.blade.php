{{-- Modal: Edit Solar Sizing & PM Surya Ghar Subsidy Feasibility --}}
<div id="modal-edit-solar-sizing" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-xl p-6 shadow-2xl space-y-4 font-sans max-h-[92vh] overflow-y-auto">
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2.5">
        <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base shadow-2xs">
          <i class="fa-solid fa-solar-panel"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-base text-slate-900">Update Solar Sizing & Feasibility</h3>
          <p class="text-xs text-slate-400 font-medium">Configure plant capacity, project budget, subsidy and DISCOM parameters.</p>
        </div>
      </div>
      <button type="button" onclick="closeSolarSizingModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition cursor-pointer">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Quick Presets -->
    <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-3 space-y-1.5">
      <span class="text-[10px] font-black text-amber-800 uppercase tracking-wider block">Quick Presets (PM Surya Ghar & C&I)</span>
      <div class="flex items-center gap-1.5 flex-wrap">
        <button type="button" onclick="applySizingPreset(3, 165000, 78000)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold border border-amber-200 shadow-2xs cursor-pointer">
          ⚡ 3 kW (₹78k Subsidy)
        </button>
        <button type="button" onclick="applySizingPreset(5, 260000, 78000)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold border border-amber-200 shadow-2xs cursor-pointer">
          ⚡ 5 kW (₹78k Subsidy)
        </button>
        <button type="button" onclick="applySizingPreset(10, 480000, 78000)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold border border-amber-200 shadow-2xs cursor-pointer">
          🏢 10 kW Society
        </button>
        <button type="button" onclick="applySizingPreset(25, 1150000, 0)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold border border-amber-200 shadow-2xs cursor-pointer">
          🏭 25 kW Commercial
        </button>
        <button type="button" onclick="applySizingPreset(50, 2150000, 0)" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold border border-amber-200 shadow-2xs cursor-pointer">
          🏭 50 kW Industrial
        </button>
      </div>
    </div>

    <!-- Edit Form -->
    <form id="solarSizingForm" onsubmit="saveSolarSizing(event)" class="space-y-4 text-xs">
      
      <!-- Row 1: Capacity & Budget -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Recommended Solar Capacity (kW) *</label>
          <div class="relative">
            <input type="number" step="0.1" min="0.5" max="5000" id="modal_kw" name="solar_capacity_kw" 
              value="{{ $lead->solar_capacity_kw ?? 3.0 }}" required oninput="calcSolarMetrics()"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 font-bold text-slate-900 focus:outline-none focus:border-amber-500 pr-10">
            <span class="absolute right-3 top-2.5 text-slate-400 font-bold">kW</span>
          </div>
        </div>

        <div>
          <label class="font-bold text-slate-700 block mb-1">Gross Project Cost / Budget (₹) *</label>
          <div class="relative">
            <input type="number" step="500" min="0" id="modal_budget" name="estimated_budget" 
              value="{{ (float)($lead->estimated_budget > 0 ? $lead->estimated_budget : 165000) }}" required oninput="calcSolarMetrics()"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 font-bold text-slate-900 focus:outline-none focus:border-amber-500 pr-8">
            <span class="absolute right-3 top-2.5 text-slate-400 font-bold">₹</span>
          </div>
        </div>
      </div>

      <!-- Row 2: Subsidy & Net Cost Preview -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Central DBT Subsidy (₹)</label>
          <div class="relative">
            <input type="number" step="500" min="0" id="modal_subsidy" name="subsidy_amount" 
              value="{{ (float)($lead->subsidy_amount ?? 78000) }}" oninput="calcSolarMetrics()"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 font-bold text-emerald-800 focus:outline-none focus:border-emerald-500 pr-8">
            <span class="absolute right-3 top-2.5 text-slate-400 font-bold">₹</span>
          </div>
          <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">Default: ₹78k max for 3kW+ residential</span>
        </div>

        <div>
          <label class="font-bold text-slate-700 block mb-1">Estimated Net Customer Cost (₹)</label>
          <div class="relative">
            <input type="text" id="modal_net_preview" readonly
              class="w-full bg-blue-50/70 border border-blue-200 rounded-xl px-3 py-2.5 font-black text-blue-900 cursor-not-allowed">
            <span class="absolute right-3 top-2.5 text-blue-400 font-bold">₹</span>
          </div>
          <span class="text-[10px] text-blue-600 font-semibold mt-0.5 block">Gross Cost minus DBT Subsidy</span>
        </div>
      </div>

      <!-- Row 3: Grid Details: Sanctioned Load, Monthly Bill, DISCOM -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Monthly Bill (₹)</label>
          <input type="number" step="100" min="0" id="modal_monthly_bill" name="monthly_bill" 
            value="{{ (float)($lead->monthly_bill ?? 4500) }}"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
        </div>

        <div>
          <label class="font-bold text-slate-700 block mb-1">Sanctioned Load (kW)</label>
          <input type="number" step="0.5" min="0" id="modal_sanctioned_load" name="sanctioned_load" 
            value="{{ (float)($lead->sanctioned_load ?? 5.0) }}"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
        </div>

        <div>
          <label class="font-bold text-slate-700 block mb-1">DISCOM</label>
          <select id="modal_discom" name="discom" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
            <option value="MGVCL" {{ ($lead->discom ?? 'MGVCL') === 'MGVCL' ? 'selected' : '' }}>MGVCL (Madhya Gujarat)</option>
            <option value="DGVCL" {{ ($lead->discom ?? '') === 'DGVCL' ? 'selected' : '' }}>DGVCL (Dakshin Gujarat)</option>
            <option value="UGVCL" {{ ($lead->discom ?? '') === 'UGVCL' ? 'selected' : '' }}>UGVCL (Uttar Gujarat)</option>
            <option value="PGVCL" {{ ($lead->discom ?? '') === 'PGVCL' ? 'selected' : '' }}>PGVCL (Paschim Gujarat)</option>
            <option value="Torrent Power" {{ ($lead->discom ?? '') === 'Torrent Power' ? 'selected' : '' }}>Torrent Power</option>
            <option value="MSEDCL" {{ ($lead->discom ?? '') === 'MSEDCL' ? 'selected' : '' }}>MSEDCL</option>
          </select>
        </div>
      </div>

      <!-- Row 4: Consumer Number -->
      <div>
        <label class="font-bold text-slate-700 block mb-1">DISCOM Consumer Number</label>
        <input type="text" id="modal_consumer_number" name="consumer_number" value="{{ $lead->consumer_number ?? '' }}" placeholder="e.g. 08402/19482/3"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
      </div>

      <!-- Real-time Engineering Projections -->
      <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3 grid grid-cols-3 gap-2 text-center">
        <div>
          <span class="text-[10px] text-slate-400 font-bold block">Annual Generation</span>
          <span class="text-xs font-black text-slate-800" id="preview_units">4,200 Units</span>
        </div>
        <div>
          <span class="text-[10px] text-slate-400 font-bold block">Annual Savings</span>
          <span class="text-xs font-black text-purple-700" id="preview_savings">₹31,500/yr</span>
        </div>
        <div>
          <span class="text-[10px] text-slate-400 font-bold block">Estimated Payback</span>
          <span class="text-xs font-black text-emerald-700" id="preview_payback">~2.8 Years</span>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeSolarSizingModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-600 transition cursor-pointer">
          Cancel
        </button>
        <button type="submit" id="btnSaveSizing" class="px-5 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-bold shadow-sm shadow-amber-600/20 transition cursor-pointer flex items-center gap-1.5">
          <i class="fa-solid fa-floppy-disk"></i> Save Sizing & Update
        </button>
      </div>
    </form>

  </div>
</div>

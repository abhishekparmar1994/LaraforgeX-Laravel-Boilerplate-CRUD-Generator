{{-- ── MATERIAL ITEM SPECIFICATIONS MODAL ───────────────────────── --}}
<div id="modal-view-material" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
  <div class="bg-white rounded-3xl max-w-xl w-full p-5 sm:p-6 shadow-2xl space-y-4 font-sans text-xs max-h-[90vh] overflow-y-auto">
    
    {{-- Modal Header --}}
    <div class="flex items-start justify-between pb-3 border-b border-slate-100 gap-3">
      <div class="min-w-0 flex-1">
        <div class="flex items-center gap-2 mb-1 flex-wrap">
          <span id="vm-category-badge" class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-extrabold text-[10px] border border-blue-200">
            Category
          </span>
          <span id="vm-brand-badge" class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 font-extrabold text-[10px] border border-emerald-200">
            Brand Tier
          </span>
          <span id="vm-stock-badge" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
            In Stock
          </span>
        </div>
        <h3 id="vm-name" class="font-black text-slate-900 text-sm sm:text-base leading-snug">
          Item Name
        </h3>
        <p id="vm-model" class="text-[11px] text-slate-400 font-mono mt-0.5">
          Model: N/A
        </p>
      </div>
      <button onclick="$('#modal-view-material').addClass('hidden')" class="text-slate-400 hover:text-slate-600 transition p-1">
        <i class="fa-solid fa-xmark text-sm"></i>
      </button>
    </div>

    {{-- Specs & Details Card --}}
    <div class="space-y-3">
      {{-- Technical Specs Box --}}
      <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
          Technical Specifications & Engineering Standards
        </span>
        <p id="vm-specs" class="text-xs font-semibold text-slate-800 leading-relaxed">
          Specifications details
        </p>
      </div>

      {{-- Pricing & Commercial Matrix --}}
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Purchase Rate</span>
          <span id="vm-purchase" class="text-xs font-extrabold text-slate-700 block mt-0.5">₹0.00</span>
          <span class="text-[9px] text-slate-400">Excl. GST</span>
        </div>
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Selling Rate</span>
          <span id="vm-selling" class="text-xs font-extrabold text-slate-900 block mt-0.5">₹0.00</span>
          <span class="text-[9px] text-slate-400">Excl. GST</span>
        </div>
        <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-100">
          <span class="text-[10px] font-bold text-amber-800 uppercase block">Margin</span>
          <span id="vm-margin" class="text-xs font-black text-amber-700 block mt-0.5">₹0.00</span>
          <span id="vm-margin-pct" class="text-[9px] text-amber-600 font-bold block">+0%</span>
        </div>
        <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100">
          <span class="text-[10px] font-bold text-emerald-800 uppercase block">Incl. GST (Total)</span>
          <span id="vm-total-selling" class="text-xs font-black text-emerald-700 block mt-0.5">₹0.00</span>
          <span id="vm-gst" class="text-[9px] text-emerald-600 font-bold block">12% GST</span>
        </div>
      </div>

      {{-- Logistics & Inventory Meta --}}
      <div class="grid grid-cols-3 gap-2.5 pt-1">
        <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50/50">
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Warehouse Stock</span>
          <span id="vm-stock" class="text-xs font-bold text-slate-800 block mt-0.5">0 NOS</span>
        </div>
        <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50/50">
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Manufacturer Warranty</span>
          <span id="vm-warranty" class="text-xs font-bold text-slate-800 block mt-0.5">0 Years</span>
        </div>
        <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50/50">
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Billing Unit</span>
          <span id="vm-unit" class="text-xs font-bold text-slate-800 block mt-0.5">NOS</span>
        </div>
      </div>
    </div>

    {{-- Footer Actions --}}
    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
      <button type="button" id="vm-btn-delete" class="px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 font-bold text-xs transition cursor-pointer flex items-center gap-1.5">
        <i class="fa-regular fa-trash-can"></i> Delete Item
      </button>

      <div class="flex items-center gap-2">
        <button type="button" onclick="$('#modal-view-material').addClass('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 font-bold text-slate-600 hover:bg-slate-200 transition">
          Close
        </button>
      </div>
    </div>

  </div>
</div>


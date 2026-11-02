{{-- ── CREATE MATERIAL ITEM MODAL ──────────────────────────────── --}}
<div id="modal-create-material" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <h3 class="font-extrabold text-slate-900 text-sm">Add Material to Catalog</h3>
      <button onclick="$('#modal-create-material').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-sm"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="/admin/materials" method="POST" class="space-y-3 text-xs">
      @csrf
      <div>
        <label class="font-bold text-slate-700 block mb-1">Item Title *</label>
        <input type="text" name="name" required placeholder="e.g. Waaree 550Wp Mono-PERC Bifacial" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:border-brand-500">
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Category *</label>
          <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->title }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Brand / Manufacturer</label>
          <select name="company_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
            <option value="">Generic / No Brand</option>
            @foreach($companies as $comp)
              <option value="{{ $comp->id }}">{{ $comp->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Unit *</label>
          <input type="text" name="unit" required value="NOS" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Purchase Price (₹) *</label>
          <input type="number" step="0.01" name="purchase_price" required placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Selling Price (₹) *</label>
          <input type="number" step="0.01" name="selling_price" required placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
        </div>
      </div>
      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">GST % *</label>
          <input type="number" step="0.01" name="gst_percent" required value="12.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Warranty (Yrs)</label>
          <input type="number" name="warranty_years" required value="10" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Initial Stock</label>
          <input type="number" name="in_stock" required value="100" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
        </div>
      </div>
      <div>
        <label class="font-bold text-slate-700 block mb-1">Model Number</label>
        <input type="text" name="model_number" placeholder="e.g. WAA-550-BIF" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
      </div>
      <div>
        <label class="font-bold text-slate-700 block mb-1">Technical Specifications</label>
        <input type="text" name="specifications" placeholder="e.g. 550Wp | 144 Half-cut Cells | ALMM / DCR" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
      </div>
      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-material').addClass('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 font-bold text-slate-600 hover:bg-slate-200 transition">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 font-bold text-white hover:bg-brand-500 transition">Add to Catalog</button>
      </div>
    </form>
  </div>
</div>


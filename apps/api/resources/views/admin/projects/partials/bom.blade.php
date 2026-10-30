{{-- SolarMitra Project Bill of Materials (BOM) Component --}}
@php
  $totalBomCost = $project->bom->sum('total_cost');
  $margin = $project->budget > 0 ? round((($project->budget - $totalBomCost) / $project->budget) * 100, 1) : 0;
  $materialsList = \App\Models\SolarMaterial::with('category', 'company')->where('is_active', 1)->orderBy('name')->get();
@endphp

<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
    <div>
      <div class="flex items-center gap-2">
        <h3 class="font-extrabold text-sm text-slate-900">Solar Bill of Materials (BOM)</h3>
        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-extrabold text-[10px] border border-amber-200">
          Kit Allocation
        </span>
      </div>
      <p class="text-[11px] text-slate-400 font-medium mt-0.5">Itemized solar panels, inverters, structures, cables and switchgear allocated to this plant.</p>
    </div>
    <div class="flex items-center gap-2">
      <button type="button" onclick="$('#modal-add-bom-item').removeClass('hidden')" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-2xs transition cursor-pointer flex items-center gap-1.5">
        <i class="fa-solid fa-plus text-[10px]"></i> Allocate Equipment
      </button>
    </div>
  </div>

  <!-- Financial KPI Metrics -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-slate-50/70 border border-slate-100 rounded-2xl text-xs">
    <div>
      <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Allocated BOM</span>
      <span class="font-extrabold text-slate-900 text-sm">₹{{ number_format($totalBomCost, 2) }}</span>
    </div>
    <div>
      <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Plant Project Value</span>
      <span class="font-extrabold text-slate-900 text-sm">₹{{ number_format($project->budget, 2) }}</span>
    </div>
    <div>
      <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Project Gross Margin</span>
      <span class="font-extrabold text-sm {{ $margin >= 20 ? 'text-emerald-600' : ($margin > 0 ? 'text-amber-600' : 'text-rose-600') }}">
        {{ $margin }}% (₹{{ number_format($project->budget - $totalBomCost, 0) }})
      </span>
    </div>
  </div>

  <!-- BOM DataTable -->
  <div class="overflow-x-auto -mx-6 sm:mx-0">
    <table class="datatable w-full text-left text-xs">
      <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
        <tr>
          <th class="px-4 py-3">Category</th>
          <th class="px-4 py-3">Equipment Details</th>
          <th class="px-4 py-3">Brand</th>
          <th class="px-4 py-3 text-center">Qty</th>
          <th class="px-4 py-3 text-right">Unit Rate</th>
          <th class="px-4 py-3 text-center">GST</th>
          <th class="px-4 py-3 text-right">Total (Inc. GST)</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
        @forelse($project->bom as $item)
          <tr class="hover:bg-slate-50/70 transition">
            <td class="px-4 py-3">
              <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-bold text-[10px] border border-blue-200">
                {{ $item->material && $item->material->category ? $item->material->category->title : 'General' }}
              </span>
            </td>
            <td class="px-4 py-3">
              <span class="font-bold text-slate-900 block">{{ $item->material ? $item->material->name : 'Custom Item' }}</span>
              <span class="text-[10px] text-slate-400 font-mono block mt-0.5">
                {{ $item->serial_numbers ?: ($item->material ? $item->material->specifications : '—') }}
              </span>
            </td>
            <td class="px-4 py-3 font-semibold text-slate-700">
              {{ $item->material && $item->material->company ? $item->material->company->name : 'Tier-1' }}
            </td>
            <td class="px-4 py-3 text-center font-bold text-slate-800" data-sort="{{ $item->quantity }}">
              {{ $item->quantity }} {{ $item->material ? $item->material->unit : 'NOS' }}
            </td>
            <td class="px-4 py-3 text-right font-semibold text-slate-700" data-sort="{{ $item->unit_rate }}">
              ₹{{ number_format($item->unit_rate, 2) }}
            </td>
            <td class="px-4 py-3 text-center font-bold text-slate-500" data-sort="{{ $item->gst_percent }}">
              {{ $item->gst_percent }}%
            </td>
            <td class="px-4 py-3 text-right font-extrabold text-slate-900" data-sort="{{ $item->total_cost }}">
              ₹{{ number_format($item->total_cost, 2) }}
            </td>
            <td class="px-4 py-3 text-center">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $item->status === 'installed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                {{ ucfirst($item->status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-right space-x-1">
              <button type="button" onclick="deleteBomItem('{{ $item->id }}')" class="text-slate-400 hover:text-rose-600 transition" title="Remove item">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="p-6 text-center text-slate-400 font-medium">No materials allocated yet. Click "Allocate Equipment" to add panels, inverters or BOS.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Add Item to BOM -->
<div id="modal-add-bom-item" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-add-bom-item').addClass('hidden')"></div>
    <form action="/admin/projects/{{ $project->id }}/bom" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Allocate Equipment to Project</h3>
          <span class="text-[11px] text-slate-400">{{ $project->name }}</span>
        </div>
        <button type="button" onclick="$('#modal-add-bom-item').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="space-y-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Select Catalog Material *</label>
          <select name="material_id" id="bom-mat-select" required onchange="updateBomPricing(this)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="">-- Choose Equipment / BOS Item --</option>
            @foreach($materialsList as $mat)
              <option value="{{ $mat->id }}" data-rate="{{ $mat->selling_price }}" data-gst="{{ $mat->gst_percent }}" data-unit="{{ $mat->unit }}">
                [{{ $mat->category ? $mat->category->title : 'Item' }}] {{ $mat->name }} ({{ $mat->company ? $mat->company->name : 'Universal' }}) — ₹{{ number_format($mat->selling_price, 2) }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="grid grid-cols-3 gap-2">
          <div>
            <label class="font-bold text-slate-700 block mb-1">Quantity *</label>
            <input type="number" step="0.01" name="quantity" id="bom-qty-input" required value="1" min="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
          <div>
            <label class="font-bold text-slate-700 block mb-1">Unit Rate (₹) *</label>
            <input type="number" step="0.01" name="unit_rate" id="bom-rate-input" required value="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
          <div>
            <label class="font-bold text-slate-700 block mb-1">GST % *</label>
            <input type="number" step="0.01" name="gst_percent" id="bom-gst-input" required value="12.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="font-bold text-slate-700 block mb-1">Allocation Status</label>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
              <option value="allocated" selected>Allocated / Reserved</option>
              <option value="ordered">Ordered from Supplier</option>
              <option value="dispatched">Dispatched to Site</option>
              <option value="installed">Installed & Connected</option>
            </select>
          </div>
          <div>
            <label class="font-bold text-slate-700 block mb-1">Serial / Barcode Numbers</label>
            <input type="text" name="serial_numbers" placeholder="e.g. WAR-550-01..WAR-550-10" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-add-bom-item').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save to Project BOM</button>
      </div>
    </form>
  </div>
</div>

<script>
  function updateBomPricing(selectEl) {
    var opt = selectEl.options[selectEl.selectedIndex];
    if (opt && opt.dataset.rate) {
      document.getElementById('bom-rate-input').value = opt.dataset.rate;
      document.getElementById('bom-gst-input').value = opt.dataset.gst || '12.00';
    }
  }

  function deleteBomItem(bomId) {
    window.confirmDelete({
      title: 'Remove Equipment?',
      text: 'Remove this equipment from project Bill of Materials?'
    }, async () => {
      try {
        await axios.delete('/admin/bom/' + bomId, { baseURL: '' });
        location.reload();
      } catch(e) {
        window.handleAjaxError(e, 'Failed to remove BOM item.');
      }
    });
  }
</script>

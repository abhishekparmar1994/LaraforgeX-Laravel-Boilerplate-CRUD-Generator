@extends('admin.layouts.app')

@section('title', 'Solar Materials & BOM Catalog — SolarPulse')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Solar Materials & BOM</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <div class="flex items-center gap-2">
        <h2 class="text-xl font-extrabold text-slate-900">Solar Equipment & Materials Catalog</h2>
        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-extrabold text-[10px] border border-amber-200">
          SolarMitra BOM Standard
        </span>
      </div>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Manage module specifications, tier-1 inverters, structures, cables, and default procurement rates.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="/admin/materials/categories" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-2xs transition">
        <i class="fa-solid fa-layer-group text-slate-400"></i> Categories ({{ count($categories) }})
      </a>
      <button onclick="$('#modal-create-material').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
        <i class="fa-solid fa-plus"></i> Add Material Item
      </button>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
    <form method="GET" action="/admin/materials" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
      <div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, model, specs..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Material Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <select name="company_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Brands / Manufacturers</option>
          @foreach($companies as $comp)
            <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }} ({{ $comp->brand_tier }})</option>
          @endforeach
        </select>
      </div>
      <div class="flex items-center gap-2">
        <button type="submit" class="flex-1 bg-slate-900 text-white font-bold text-xs py-2 rounded-xl hover:bg-slate-800 transition">Filter</button>
        <a href="/admin/materials" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
      </div>
    </form>
  </div>

  <!-- Materials DataTable -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="datatable w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5 text-center w-12">#</th>
            <th class="px-4 py-3.5">Equipment / Item Details</th>
            <th class="px-4 py-3.5">Category</th>
            <th class="px-4 py-3.5">Brand / Tier</th>
            <th class="px-4 py-3.5 text-center">Unit</th>
            <th class="px-4 py-3.5 text-right">Purchase Rate</th>
            <th class="px-4 py-3.5 text-right">Selling Rate</th>
            <th class="px-4 py-3.5 text-center">GST</th>
            <th class="px-4 py-3.5 text-center">Stock</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($materials as $idx => $m)
            <tr class="hover:bg-slate-50/70 transition">
              <td class="px-4 py-3.5 text-center text-slate-400 font-mono text-[11px]">{{ $idx + 1 }}</td>
              <td class="px-4 py-3.5">
                <span class="font-extrabold text-slate-900 block text-xs">{{ $m->name }}</span>
                <span class="text-[10px] text-slate-400 font-mono block mt-0.5">{{ $m->model_number ?: 'Model: N/A' }} • {{ $m->specifications }}</span>
              </td>
              <td class="px-4 py-3.5">
                <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-extrabold text-[10px] border border-blue-200">
                  {{ $m->category ? $m->category->title : 'General' }}
                </span>
              </td>
              <td class="px-4 py-3.5">
                <span class="font-bold text-slate-800 block">{{ $m->company ? $m->company->name : 'Universal' }}</span>
                <span class="text-[10px] text-emerald-600 font-bold block">{{ $m->company ? $m->company->brand_tier : 'Standard' }}</span>
              </td>
              <td class="px-4 py-3.5 text-center font-bold text-slate-600">{{ $m->unit }}</td>
              <td class="px-4 py-3.5 text-right font-bold text-slate-700" data-sort="{{ $m->purchase_price }}">
                ₹{{ number_format($m->purchase_price, 2) }}
              </td>
              <td class="px-4 py-3.5 text-right font-extrabold text-slate-900" data-sort="{{ $m->selling_price }}">
                ₹{{ number_format($m->selling_price, 2) }}
              </td>
              <td class="px-4 py-3.5 text-center font-bold text-slate-500" data-sort="{{ $m->gst_percent }}">
                {{ $m->gst_percent }}%
              </td>
              <td class="px-4 py-3.5 text-center" data-sort="{{ $m->in_stock }}">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $m->in_stock > 20 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                  {{ $m->in_stock }} {{ $m->unit }}
                </span>
              </td>
              <td class="px-4 py-3.5 text-right space-x-2">
                <button type="button" class="text-slate-400 hover:text-brand-600 transition" title="Item specs">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="p-8 text-center text-slate-400 font-medium">No materials found matching filters.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Create Material Modal -->
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

</div>
@endsection

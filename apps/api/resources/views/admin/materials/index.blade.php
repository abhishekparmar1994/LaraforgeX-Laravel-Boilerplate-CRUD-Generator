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
              <td class="px-4 py-3.5 text-right space-x-1.5 whitespace-nowrap">
                <button type="button" onclick='openViewMaterialModal(@json($m))' class="text-slate-400 hover:text-brand-600 transition cursor-pointer p-1 rounded-lg hover:bg-slate-100" title="View Specifications">
                  <i class="fa-regular fa-eye"></i>
                </button>
                <button type="button" onclick="confirmDeleteMaterial('{{ $m->id }}', '{{ addslashes($m->name) }}')" class="text-slate-400 hover:text-rose-600 transition cursor-pointer p-1 rounded-lg hover:bg-rose-50" title="Delete Item">
                  <i class="fa-regular fa-trash-can text-xs"></i>
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

  <!-- Modals -->
  @include('admin.materials.partials.create-modal')
  @include('admin.materials.partials.view-modal')

</div>

@include('admin.materials.partials.scripts')
@endsection

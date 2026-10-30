@extends('admin.layouts.app')

@section('title', 'Solar Material Categories — SolarPulse')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/materials" class="hover:text-brand-600 transition">Solar Materials</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Categories</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Solar Material Categories</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Classification of equipment for Bill of Materials (BOM) allocation, quotations, and DISCOM submissions.</p>
    </div>
    <a href="/admin/materials" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-2xs transition">
      <i class="fa-solid fa-arrow-left text-slate-400"></i> Back to Materials Catalog
    </a>
  </div>

  <!-- Categories Table -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="datatable w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5 text-center w-12">#</th>
            <th class="px-4 py-3.5">Category Title</th>
            <th class="px-4 py-3.5">Default Unit</th>
            <th class="px-4 py-3.5 text-center">Items Count</th>
            <th class="px-4 py-3.5 text-center">Default GST</th>
            <th class="px-4 py-3.5 text-center">Solar Kit Ready</th>
            <th class="px-4 py-3.5 text-center">Display on Invoice</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($categories as $idx => $cat)
            <tr class="hover:bg-slate-50/70 transition">
              <td class="px-4 py-3.5 text-center text-slate-400 font-mono text-[11px]">{{ $idx + 1 }}</td>
              <td class="px-4 py-3.5">
                <a href="/admin/materials?category_id={{ $cat->id }}" class="font-extrabold text-slate-900 hover:text-brand-600 block text-xs">
                  {{ $cat->title }}
                </a>
                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $cat->description }}</span>
              </td>
              <td class="px-4 py-3.5 font-bold text-slate-700">{{ $cat->unit }}</td>
              <td class="px-4 py-3.5 text-center">
                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-extrabold text-[10px]">
                  {{ $cat->materials_count }} items
                </span>
              </td>
              <td class="px-4 py-3.5 text-center font-bold text-slate-600">{{ $cat->default_gst_percent }}%</td>
              <td class="px-4 py-3.5 text-center">
                @if($cat->include_in_solar_kit)
                  <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px]">Yes</span>
                @else
                  <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-400 font-bold text-[10px]">No</span>
                @endif
              </td>
              <td class="px-4 py-3.5 text-center">
                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-bold text-[10px]">Yes</span>
              </td>
              <td class="px-4 py-3.5 text-right">
                <a href="/admin/materials?category_id={{ $cat->id }}" class="text-xs font-bold text-brand-600 hover:text-brand-500">
                  View Items <i class="fa-solid fa-chevron-right text-[9px] ml-1"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="p-8 text-center text-slate-400 font-medium">No categories created yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

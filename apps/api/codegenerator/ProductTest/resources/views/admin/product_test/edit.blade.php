@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Edit Product Test')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <a href="/admin/product_test" class="hover:text-brand-600 transition">Product Test</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Edit #{{ $record->id }}</span>
</nav>
@endsection

@section('content')
<div class="space-y-6 font-sans w-full">

  <!-- Page Header -->
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Edit ProductTest</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Update record details for ID #{{ $record->id }}.</p>
    </div>
    <a href="/admin/product_test" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition inline-flex items-center gap-1.5">
      <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>
  </div>

  <!-- Profile Style Card Container -->
  <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    
    <!-- Cover gradient header bar -->
    <div class="h-28 bg-gradient-to-r from-brand-500 via-indigo-600 to-violet-500"></div>

    <!-- Header Avatar / Icon Badge -->
    <div class="px-6 pb-6">
      <div class="flex items-end justify-between -mt-10 mb-4">
        <div class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-brand-500 to-violet-500 border-4 border-white shadow-lg flex items-center justify-center text-white font-extrabold text-2xl uppercase shrink-0">
          <i class="fa-solid fa-pen"></i>
        </div>
      </div>

      <div class="space-y-1 mb-6">
        <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">ProductTest Record #{{ $record->id }}</h3>
        <p class="text-xs font-semibold text-slate-500">Modify attributes and save changes.</p>
      </div>

      <!-- Form Body -->
      <form action="/admin/product_test/{{ $record->id }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-1">
          <label for="input-name" class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Product Name 
          </label>
          <input type="text" name="name" id="input-name" value="{{ old('name', $record->name ?? '') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition" placeholder="Enter Product Name" >
          @error('name')
            <p class="text-xs font-medium text-rose-500 mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="space-y-1">
          <label for="input-price" class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Price 
          </label>
          <input type="number" name="price" id="input-price" value="{{ old('price', $record->price ?? '') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition" placeholder="Enter Price" >
          @error('price')
            <p class="text-xs font-medium text-rose-500 mt-1">{{ $message }}</p>
          @enderror
        </div>
        </div>

        <div class="flex justify-end pt-3">
          <button type="submit"
                  class="px-6 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm shadow-brand-600/20 inline-flex items-center gap-1.5">
            <i class="fa-solid fa-floppy-disk"></i>Save Changes
          </button>
        </div>
      </form>
    </div>

  </div>

</div>
@endsection
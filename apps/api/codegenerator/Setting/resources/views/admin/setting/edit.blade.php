@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Edit Setting')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <a href="/admin/setting" class="hover:text-brand-600 transition">Setting</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Edit #{{ $record->id }}</span>
</nav>
@endsection

@section('content')
<div class="space-y-6 font-sans w-full">

  <!-- Page Header -->
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Edit Setting</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Update record details for ID #{{ $record->id }}.</p>
    </div>
    <a href="/admin/setting" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition inline-flex items-center gap-1.5">
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
        <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">Setting Record #{{ $record->id }}</h3>
        <p class="text-xs font-semibold text-slate-500">Modify attributes and save changes.</p>
      </div>

      <!-- Form Body -->
      <form action="/admin/setting/{{ $record->id }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-1">
          <label for="input-key" class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Key 
          </label>
          <input type="text" name="key" id="input-key" value="{{ old('key', $record->key ?? '') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition" placeholder="Enter Key" >
          @error('key')
            <p class="text-xs font-medium text-rose-500 mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="space-y-1">
          <label for="input-value" class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Value 
          </label>
          <textarea name="value" id="input-value" rows="3" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition" placeholder="Enter Value" >{{ old('value', $record->value ?? '') }}</textarea>
          @error('value')
            <p class="text-xs font-medium text-rose-500 mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="space-y-1">
          <label for="input-group" class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Group 
          </label>
          <input type="text" name="group" id="input-group" value="{{ old('group', $record->group ?? '') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition" placeholder="Enter Group" >
          @error('group')
            <p class="text-xs font-medium text-rose-500 mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="space-y-1">
          <label for="input-is_encrypted" class="text-xs font-semibold uppercase tracking-wider text-slate-500">
            Is Encrypted 
          </label>
          
          <label class="inline-flex items-center gap-2 cursor-pointer mt-1">
            <input type="checkbox" name="is_encrypted" value="1" {{ old('is_encrypted', $record->is_encrypted ?? false) ? 'checked' : '' }} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 h-4 w-4">
            <span class="text-xs font-semibold text-slate-700">Enable Is Encrypted</span>
          </label>
          @error('is_encrypted')
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
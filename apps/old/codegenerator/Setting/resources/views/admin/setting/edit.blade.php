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
<div class="max-w-4xl mx-auto space-y-6 font-sans">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Edit Setting</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Update record details for ID #{{ $record->id }}.</p>
    </div>
    <a href="/admin/setting" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition inline-flex items-center gap-1.5">
      <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>
  </div>

  <form action="/admin/setting/{{ $record->id }}" method="POST" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
      <div class="space-y-1.5">
        <label for="input-key" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
          Key 
        </label>
        <input type="text" name="key" id="input-key" value="{{ old('key', $record->key ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition" placeholder="Enter Key" >
        @error('key')
          <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <div class="space-y-1.5">
        <label for="input-value" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
          Value 
        </label>
        <textarea name="value" id="input-value" rows="3" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition" placeholder="Enter Value" >{{ old('value', $record->value ?? '') }}</textarea>
        @error('value')
          <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <div class="space-y-1.5">
        <label for="input-group" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
          Group 
        </label>
        <input type="text" name="group" id="input-group" value="{{ old('group', $record->group ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition" placeholder="Enter Group" >
        @error('group')
          <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <div class="space-y-1.5">
        <label for="input-is_encrypted" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
          Is Encrypted 
        </label>
        
        <label class="inline-flex items-center gap-2 cursor-pointer mt-1">
          <input type="checkbox" name="is_encrypted" value="1" {{ old('is_encrypted', $record->is_encrypted ?? false) ? 'checked' : '' }} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 h-4 w-4">
          <span class="text-xs font-semibold text-slate-700">Enable Is Encrypted</span>
        </label>
        @error('is_encrypted')
          <p class="text-[11px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
      <a href="/admin/setting" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition">Cancel</a>
      <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-500 transition shadow-md shadow-brand-600/20 inline-flex items-center gap-1.5">
        <i class="fa-solid fa-arrows-rotate"></i> Update Setting
      </button>
    </div>
  </form>
</div>
@endsection
@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Companies Directory')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Companies</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Companies & Organizations</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Manage client organizations, linked contacts, websites, and business profiles.</p>
    </div>
    <button onclick="$('#modal-create-company').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
      <i class="fa-solid fa-plus"></i> Add Company
    </button>
  </div>

  <!-- Filter Bar -->
  <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
    <form method="GET" action="/admin/companies" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company name, industry, city..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Statuses</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <div class="flex items-center gap-2">
        <button type="submit" class="flex-1 bg-slate-900 text-white font-bold text-xs py-2 rounded-xl hover:bg-slate-800 transition">Filter</button>
        <a href="/admin/companies" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
      </div>
    </form>
  </div>

  <!-- Companies Grid / Table -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5">Company</th>
            <th class="px-4 py-3.5">Industry</th>
            <th class="px-4 py-3.5">Location</th>
            <th class="px-4 py-3.5">Contacts</th>
            <th class="px-4 py-3.5">Projects</th>
            <th class="px-4 py-3.5">Websites</th>
            <th class="px-4 py-3.5">Status</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($companies as $company)
            <tr class="hover:bg-slate-50/70 transition">
              <td class="px-4 py-3.5">
                <a href="/admin/companies/{{ $company->id }}" class="font-bold text-slate-900 hover:text-brand-600 block text-xs">
                  {{ $company->name }}
                </a>
                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $company->website ?? $company->email }}</span>
              </td>
              <td class="px-4 py-3.5 text-slate-600 font-semibold">
                {{ $company->industry ?: '—' }}
              </td>
              <td class="px-4 py-3.5 text-slate-500">
                {{ $company->city ? "{$company->city}, {$company->state}" : ($company->country ?: '—') }}
              </td>
              <td class="px-4 py-3.5 font-bold text-slate-800">
                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-[10px]">{{ $company->contacts_count }} Contacts</span>
              </td>
              <td class="px-4 py-3.5 font-bold text-slate-800">
                <span class="px-2 py-0.5 rounded-full bg-brand-50 text-brand-700 text-[10px]">{{ $company->projects_count }} Projects</span>
              </td>
              <td class="px-4 py-3.5 font-bold text-slate-800">
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px]">{{ $company->websites_count }} Websites</span>
              </td>
              <td class="px-4 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $company->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                  {{ ucfirst($company->status) }}
                </span>
              </td>
              <td class="px-4 py-3.5 text-right space-x-2">
                <a href="/admin/companies/{{ $company->id }}" class="text-brand-600 font-bold hover:underline">View 360</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="p-8 text-center text-slate-400">No companies recorded.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100">
      {{ $companies->withQueryString()->links() }}
    </div>
  </div>

</div>

<!-- Create Company Modal -->
<div id="modal-create-company" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-create-company').addClass('hidden')"></div>
    <form action="/admin/companies" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Add Company</h3>
        <button type="button" onclick="$('#modal-create-company').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2">
          <label class="font-bold text-slate-700 block mb-1">Company Name *</label>
          <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Industry</label>
          <input type="text" name="industry" placeholder="e.g. Healthcare, SaaS" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Website URL</label>
          <input type="url" name="website" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Email</label>
          <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Phone</label>
          <input type="text" name="phone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">City</label>
          <input type="text" name="city" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">State / Province</label>
          <input type="text" name="state" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <input type="hidden" name="status" value="active">
        <input type="hidden" name="company_type" value="Client">
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-company').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save Company</button>
      </div>
    </form>
  </div>
</div>
@endsection

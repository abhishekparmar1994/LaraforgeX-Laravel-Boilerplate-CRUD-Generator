@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Company: ' . $company->name)

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/companies" class="hover:text-brand-600 transition">Companies</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">{{ $company->name }}</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- Header Card -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-start gap-4">
        <div class="h-14 w-14 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 font-extrabold text-xl flex items-center justify-center shrink-0">
          <i class="fa-solid fa-building"></i>
        </div>
        <div>
          <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $company->name }}</h2>
          <p class="text-xs text-slate-500 font-medium mt-1">
            {{ $company->industry ?? 'Company' }} • {{ $company->city ? "{$company->city}, {$company->state}" : 'USA' }}
            @if($company->website) • <a href="{{ $company->website }}" target="_blank" class="text-brand-600 hover:underline font-semibold">{{ $company->website }}</a> @endif
          </p>
        </div>
      </div>
      <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $company->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
        {{ ucfirst($company->status) }}
      </span>
    </div>
  </div>

  <!-- Contacts & Projects Tabs -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Contacts & Websites -->
    <div class="space-y-6 col-span-1 lg:col-span-2">
      <!-- Contacts List -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="font-extrabold text-sm text-slate-900">Stakeholder Contacts ({{ count($company->contacts) }})</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          @forelse($company->contacts as $contact)
            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs space-y-1">
              <p class="font-extrabold text-slate-900">{{ $contact->name }}</p>
              <p class="text-slate-500 font-semibold text-[11px]">{{ $contact->job_title ?? 'Contact' }}</p>
              <div class="pt-2 flex items-center gap-3 text-[11px] text-brand-600 font-bold">
                @if($contact->email) <a href="mailto:{{ $contact->email }}" class="hover:underline"><i class="fa-solid fa-envelope mr-1"></i> Email</a> @endif
                @if($contact->phone) <a href="tel:{{ $contact->phone }}" class="hover:underline"><i class="fa-solid fa-phone mr-1"></i> Call</a> @endif
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic py-2">No contacts registered.</p>
          @endforelse
        </div>
      </div>

      <!-- Projects List -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
        <h3 class="font-extrabold text-sm text-slate-900">Associated Projects ({{ count($company->projects) }})</h3>
        <div class="space-y-3">
          @forelse($company->projects as $proj)
            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-3 text-xs">
              <div>
                <a href="/admin/projects/{{ $proj->id }}" class="font-bold text-slate-900 hover:text-brand-600">{{ $proj->name }}</a>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ $proj->project_type }} • Manager: {{ $proj->manager ? $proj->manager->name : 'Unassigned' }}</p>
              </div>
              <span class="font-extrabold text-xs text-purple-700 bg-purple-50 px-2.5 py-1 rounded-full">{{ $proj->progress }}% Done</span>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic">No projects found.</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Right: Websites & Activities -->
    <div class="space-y-6 col-span-1">
      <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
        <h3 class="font-extrabold text-sm text-slate-900">Websites ({{ count($company->websites) }})</h3>
        <div class="space-y-2">
          @forelse($company->websites as $web)
            <div class="p-3 rounded-xl border border-slate-100 bg-slate-50 text-xs">
              <a href="/admin/websites/{{ $web->id }}" class="font-bold text-slate-900 hover:text-brand-600">{{ $web->name }}</a>
              <p class="text-[10px] text-slate-400 mt-0.5">{{ $web->domain ?? 'No Domain' }} • {{ $web->hosting_provider ?? 'Cloud' }}</p>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic">No websites registered.</p>
          @endforelse
        </div>
      </div>
    </div>

  </div>

</div>
@endsection

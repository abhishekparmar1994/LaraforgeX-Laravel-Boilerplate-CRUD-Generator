@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Contact: ' . $contact->name)

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/contacts" class="hover:text-brand-600 transition">Contacts</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">{{ $contact->name }}</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans max-w-4xl">

  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex items-start justify-between gap-4">
      <div class="flex items-start gap-4">
        <div class="h-14 w-14 rounded-2xl bg-brand-50 border border-brand-100 text-brand-600 font-extrabold text-xl flex items-center justify-center shrink-0">
          {{ substr($contact->first_name, 0, 1) }}{{ substr($contact->last_name ?? '', 0, 1) }}
        </div>
        <div>
          <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $contact->name }}</h2>
          <p class="text-xs text-slate-500 font-medium mt-1">
            {{ $contact->job_title ?? 'Stakeholder' }} at 
            @if($contact->company)
              <a href="/admin/companies/{{ $contact->company_id }}" class="text-brand-600 hover:underline font-bold">{{ $contact->company->name }}</a>
            @else
              <span>Individual</span>
            @endif
          </p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        @if($contact->phone)
          <a href="tel:{{ $contact->phone }}" class="h-9 px-3 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs flex items-center gap-1.5 hover:bg-slate-200 transition"><i class="fa-solid fa-phone"></i> Call</a>
        @endif
        @if($contact->email)
          <a href="mailto:{{ $contact->email }}" class="h-9 px-3 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs flex items-center gap-1.5 hover:bg-slate-200 transition"><i class="fa-solid fa-envelope"></i> Email</a>
        @endif
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-4 border-t border-slate-100 text-xs">
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email Address</span>
        <span class="font-bold text-slate-800">{{ $contact->email ?? '—' }}</span>
      </div>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone Number</span>
        <span class="font-bold text-slate-800 font-mono">{{ $contact->phone ?? '—' }}</span>
      </div>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Preferred Contact Method</span>
        <span class="font-bold text-slate-800">{{ $contact->preferred_contact_method }}</span>
      </div>
    </div>
  </div>

</div>
@endsection

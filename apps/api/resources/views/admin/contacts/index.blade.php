@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Contacts Directory')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Contacts</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Contacts Directory</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Manage key decision makers, technical leads, and stakeholders across client companies.</p>
    </div>
    <button onclick="$('#modal-create-contact').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
      <i class="fa-solid fa-plus"></i> Add Contact
    </button>
  </div>

  <!-- Filter Bar -->
  <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
    <form method="GET" action="/admin/contacts" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, job title, email, phone..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <select name="company_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Companies</option>
          @foreach($companies as $comp)
            <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex items-center gap-2">
        <button type="submit" class="flex-1 bg-slate-900 text-white font-bold text-xs py-2 rounded-xl hover:bg-slate-800 transition">Filter</button>
        <a href="/admin/contacts" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
      </div>
    </form>
  </div>

  <!-- Contacts Table -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5">Name & Job Title</th>
            <th class="px-4 py-3.5">Company</th>
            <th class="px-4 py-3.5">Email</th>
            <th class="px-4 py-3.5">Phone / WhatsApp</th>
            <th class="px-4 py-3.5">Preferred Method</th>
            <th class="px-4 py-3.5 text-right">Quick Connect</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($contacts as $contact)
            <tr class="hover:bg-slate-50/70 transition">
              <td class="px-4 py-3.5">
                <a href="/admin/contacts/{{ $contact->id }}" class="font-bold text-slate-900 hover:text-brand-600 block text-xs">
                  {{ $contact->name }}
                </a>
                <span class="text-[11px] text-slate-400 font-semibold block mt-0.5">{{ $contact->job_title ?: 'Stakeholder' }}</span>
              </td>
              <td class="px-4 py-3.5 font-bold text-slate-800">
                {{ $contact->company ? $contact->company->name : 'Independent' }}
              </td>
              <td class="px-4 py-3.5 text-slate-600">
                {{ $contact->email ?: '—' }}
              </td>
              <td class="px-4 py-3.5 text-slate-600 font-mono">
                {{ $contact->phone ?: ($contact->whatsapp ?: '—') }}
              </td>
              <td class="px-4 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                  {{ $contact->preferred_contact_method }}
                </span>
              </td>
              <td class="px-4 py-3.5 text-right space-x-1.5">
                @if($contact->phone)
                  <a href="tel:{{ $contact->phone }}" class="inline-flex h-7 w-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 items-center justify-center transition" title="Call">
                    <i class="fa-solid fa-phone text-[10px]"></i>
                  </a>
                @endif
                @if($contact->whatsapp || $contact->phone)
                  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp ?? $contact->phone) }}" target="_blank" class="inline-flex h-7 w-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 items-center justify-center transition" title="WhatsApp">
                    <i class="fa-brands fa-whatsapp text-[11px]"></i>
                  </a>
                @endif
                @if($contact->email)
                  <a href="mailto:{{ $contact->email }}" class="inline-flex h-7 w-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 items-center justify-center transition" title="Email">
                    <i class="fa-solid fa-envelope text-[10px]"></i>
                  </a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="p-8 text-center text-slate-400">No contacts registered.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100">
      {{ $contacts->withQueryString()->links() }}
    </div>
  </div>

</div>

<!-- Create Contact Modal -->
<div id="modal-create-contact" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-create-contact').addClass('hidden')"></div>
    <form action="/admin/contacts" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Add New Contact</h3>
        <button type="button" onclick="$('#modal-create-contact').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2">
          <label class="font-bold text-slate-700 block mb-1">Company *</label>
          <select name="company_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            @foreach($companies as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">First Name *</label>
          <input type="text" name="first_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Last Name</label>
          <input type="text" name="last_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Job Title</label>
          <input type="text" name="job_title" placeholder="e.g. Marketing Director" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
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
          <label class="font-bold text-slate-700 block mb-1">Preferred Method</label>
          <select name="preferred_contact_method" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="Email">Email</option>
            <option value="Phone">Phone</option>
            <option value="WhatsApp">WhatsApp</option>
            <option value="Meeting">Meeting</option>
          </select>
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-contact').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save Contact</button>
      </div>
    </form>
  </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Client 360: ' . $client->name)

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/clients" class="hover:text-brand-600 transition">Clients</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">{{ $client->name }}</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans" x-data="{ activeTab: 'overview' }">

  <!-- Header Card -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-start gap-4 min-w-0">
        <div class="h-14 w-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-extrabold text-xl flex items-center justify-center shadow-md shrink-0">
          <i class="fa-solid fa-user-tie"></i>
        </div>
        <div class="min-w-0">
          <div class="flex items-center gap-2.5 flex-wrap">
            <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $client->name }}</h2>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-slate-100 text-slate-700">{{ $client->client_code }}</span>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $client->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
              {{ ucfirst($client->status) }}
            </span>
          </div>
          <p class="text-xs text-slate-500 font-medium mt-1">
            {{ $client->industry ?: 'Client Account' }} • Client Since: {{ $client->client_since ? $client->client_since->format('M Y') : '2026' }}
            @if($client->email) • <span class="text-slate-700">{{ $client->email }}</span> @endif
          </p>
        </div>
      </div>

      <!-- Financial Metrics Pills -->
      <div class="flex items-center gap-3">
        <div class="p-3 bg-emerald-50/70 border border-emerald-100 rounded-2xl text-right">
          <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Total Revenue</span>
          <span class="font-extrabold text-emerald-800 text-sm">₹{{ number_format($client->total_revenue, 0) }}</span>
        </div>
        <div class="p-3 bg-amber-50/70 border border-amber-100 rounded-2xl text-right">
          <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Pending Balance</span>
          <span class="font-extrabold text-amber-800 text-sm">₹{{ number_format($client->pending_payment, 0) }}</span>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 pt-3 border-t border-slate-100 overflow-x-auto text-xs font-bold">
      <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-brand-50 text-brand-600 border-brand-500' : 'text-slate-500 hover:text-slate-900 border-transparent'" class="px-3.5 py-2 rounded-xl border transition">
        Overview
      </button>
      <button @click="activeTab = 'projects'" :class="activeTab === 'projects' ? 'bg-brand-50 text-brand-600 border-brand-500' : 'text-slate-500 hover:text-slate-900 border-transparent'" class="px-3.5 py-2 rounded-xl border transition flex items-center gap-1.5">
        Projects <span class="px-1.5 py-0.2 rounded-full bg-slate-100 text-[10px]">{{ count($client->projects) }}</span>
      </button>
      <button @click="activeTab = 'websites'" :class="activeTab === 'websites' ? 'bg-brand-50 text-brand-600 border-brand-500' : 'text-slate-500 hover:text-slate-900 border-transparent'" class="px-3.5 py-2 rounded-xl border transition flex items-center gap-1.5">
        Websites <span class="px-1.5 py-0.2 rounded-full bg-slate-100 text-[10px]">{{ count($client->websites) }}</span>
      </button>
      <button @click="activeTab = 'payments'" :class="activeTab === 'payments' ? 'bg-brand-50 text-brand-600 border-brand-500' : 'text-slate-500 hover:text-slate-900 border-transparent'" class="px-3.5 py-2 rounded-xl border transition flex items-center gap-1.5">
        Invoices & Payments <span class="px-1.5 py-0.2 rounded-full bg-slate-100 text-[10px]">{{ count($client->payments) }}</span>
      </button>
      <button @click="activeTab = 'activities'" :class="activeTab === 'activities' ? 'bg-brand-50 text-brand-600 border-brand-500' : 'text-slate-500 hover:text-slate-900 border-transparent'" class="px-3.5 py-2 rounded-xl border transition">
        Activities
      </button>
    </div>
  </div>

  <!-- Tab 1: Overview -->
  <div x-show="activeTab === 'overview'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4 col-span-1 lg:col-span-2">
      <h3 class="font-extrabold text-sm text-slate-900">Account Summary</h3>
      <div class="grid grid-cols-2 gap-4 text-xs">
        <div>
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Company</span>
          <span class="font-bold text-slate-800">{{ $client->company ? $client->company->name : 'N/A' }}</span>
        </div>
        <div>
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Primary Contact</span>
          <span class="font-bold text-slate-800">{{ $client->primaryContact ? $client->primaryContact->name : 'N/A' }}</span>
        </div>
        <div>
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Email Address</span>
          <span class="font-bold text-slate-800">{{ $client->email ?: '—' }}</span>
        </div>
        <div>
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Phone / WhatsApp</span>
          <span class="font-bold text-slate-800 font-mono">{{ $client->phone ?: ($client->whatsapp ?: '—') }}</span>
        </div>
        <div class="col-span-2">
          <span class="text-[10px] font-bold text-slate-400 uppercase block">Billing Address</span>
          <span class="font-medium text-slate-700">{{ $client->address ?: 'No billing address specified.' }}</span>
        </div>
      </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-3 col-span-1">
      <h3 class="font-extrabold text-sm text-slate-900">Client Quick Actions</h3>
      <div class="space-y-2 text-xs">
        <a href="/admin/projects" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-bold text-slate-700 transition">
          <span><i class="fa-solid fa-plus mr-2 text-brand-600"></i> New Project</span>
          <i class="fa-solid fa-chevron-right text-slate-400 text-[10px]"></i>
        </a>
        <a href="/admin/websites" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-bold text-slate-700 transition">
          <span><i class="fa-solid fa-globe mr-2 text-emerald-600"></i> Register Website</span>
          <i class="fa-solid fa-chevron-right text-slate-400 text-[10px]"></i>
        </a>
        <a href="/admin/payments" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-bold text-slate-700 transition">
          <span><i class="fa-solid fa-receipt mr-2 text-purple-600"></i> Record Payment</span>
          <i class="fa-solid fa-chevron-right text-slate-400 text-[10px]"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- Tab 2: Projects -->
  <div x-show="activeTab === 'projects'" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <h3 class="font-extrabold text-sm text-slate-900">Assigned Website Projects</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      @forelse($client->projects as $project)
        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl space-y-2.5 text-xs">
          <div class="flex items-start justify-between">
            <a href="/admin/projects/{{ $project->id }}" class="font-extrabold text-slate-900 hover:text-brand-600 text-sm leading-snug">{{ $project->name }}</a>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-700">{{ $project->status }}</span>
          </div>
          <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
            <div class="bg-brand-600 h-full" style="width: {{ $project->progress }}%"></div>
          </div>
          <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
            <span>{{ $project->project_type }}</span>
            <span class="font-bold text-slate-800">₹{{ number_format($project->budget, 0) }}</span>
          </div>
        </div>
      @empty
        <p class="text-xs text-slate-400 italic">No projects assigned yet.</p>
      @endforelse
    </div>
  </div>

  <!-- Tab 3: Websites -->
  <div x-show="activeTab === 'websites'" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <h3 class="font-extrabold text-sm text-slate-900">Client Websites</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      @forelse($client->websites as $web)
        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl space-y-2 text-xs">
          <a href="/admin/websites/{{ $web->id }}" class="font-extrabold text-slate-900 hover:text-brand-600 text-sm">{{ $web->name }}</a>
          <p class="text-slate-500 text-[11px]">{{ $web->domain ?? 'No Domain' }} • Hosting: {{ $web->hosting_provider ?? 'Cloud' }}</p>
          <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $web->status }}</span>
        </div>
      @empty
        <p class="text-xs text-slate-400 italic">No websites found.</p>
      @endforelse
    </div>
  </div>

  <!-- Tab 4: Payments -->
  <div x-show="activeTab === 'payments'" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <h3 class="font-extrabold text-sm text-slate-900">Invoices & Payment Records</h3>
    <div class="divide-y divide-slate-100 text-xs">
      @forelse($client->payments as $pay)
        <div class="py-3 flex items-center justify-between">
          <div>
            <span class="font-extrabold text-slate-900 block">#{{ $pay->invoice_number }}</span>
            <span class="text-slate-400 text-[11px]">{{ $pay->payment_method }} • Due: {{ $pay->due_date ? $pay->due_date->format('M d, Y') : '—' }}</span>
          </div>
          <div class="text-right">
            <span class="font-extrabold text-slate-900 text-sm block">₹{{ number_format($pay->amount, 0) }}</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pay->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ ucfirst($pay->status) }}</span>
          </div>
        </div>
      @empty
        <p class="text-xs text-slate-400 italic py-2">No invoices recorded.</p>
      @endforelse
    </div>
  </div>

  <!-- Tab 5: Activities -->
  <div x-show="activeTab === 'activities'" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-3">
    <h3 class="font-extrabold text-sm text-slate-900">Activity Stream</h3>
    <div class="space-y-3">
      @forelse($client->activities as $act)
        <div class="flex items-start gap-2.5 text-xs">
          <div class="h-6 w-6 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5">
            <i class="fa-solid fa-clock-rotate-left"></i>
          </div>
          <div>
            <p class="text-slate-700 font-medium">{{ $act->description }}</p>
            <span class="text-[10px] text-slate-400">{{ $act->created_at->diffForHumans() }}</span>
          </div>
        </div>
      @empty
        <p class="text-xs text-slate-400 italic">No activity history.</p>
      @endforelse
    </div>
  </div>

</div>
@endsection

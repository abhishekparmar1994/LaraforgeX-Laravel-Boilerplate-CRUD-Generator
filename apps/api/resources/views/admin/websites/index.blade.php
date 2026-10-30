@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Websites Management')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Websites</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Website Infrastructure Management</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Track hosting/domain expiries, technical frameworks, deployment repos, and encrypted credentials.</p>
    </div>
    <button onclick="$('#modal-create-website').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
      <i class="fa-solid fa-plus"></i> Register Website
    </button>
  </div>

  <!-- Filter Bar -->
  <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
    <form method="GET" action="/admin/websites" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
      <div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search website name, domain, hosting, tech..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Statuses</option>
          <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>Planning</option>
          <option value="design" {{ request('status') === 'design' ? 'selected' : '' }}>Design</option>
          <option value="development" {{ request('status') === 'development' ? 'selected' : '' }}>Development</option>
          <option value="testing" {{ request('status') === 'testing' ? 'selected' : '' }}>Testing</option>
          <option value="client_review" {{ request('status') === 'client_review' ? 'selected' : '' }}>Client Review</option>
          <option value="live" {{ request('status') === 'live' ? 'selected' : '' }}>Live</option>
          <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
          <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
      </div>
      <div>
        <select name="filter" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
          <option value="">All Expirations</option>
          <option value="domain_expiring" {{ request('filter') === 'domain_expiring' ? 'selected' : '' }}>Domain Expiring (&lt; 30 Days)</option>
          <option value="hosting_expiring" {{ request('filter') === 'hosting_expiring' ? 'selected' : '' }}>Hosting Expiring (&lt; 30 Days)</option>
        </select>
      </div>
      <div class="flex items-center gap-2">
        <button type="submit" class="flex-1 bg-slate-900 text-white font-bold text-xs py-2 rounded-xl hover:bg-slate-800 transition">Filter</button>
        <a href="/admin/websites" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
      </div>
    </form>
  </div>

  <!-- Websites Table -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="datatable w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5">Website & Client</th>
            <th class="px-4 py-3.5">Domain & Expiry</th>
            <th class="px-4 py-3.5">Hosting Provider</th>
            <th class="px-4 py-3.5">Tech Stack</th>
            <th class="px-4 py-3.5">Progress</th>
            <th class="px-4 py-3.5">Status</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($websites as $web)
            <tr class="hover:bg-slate-50/70 transition">
              <td class="px-4 py-3.5">
                <a href="/admin/websites/{{ $web->id }}" class="font-bold text-slate-900 hover:text-brand-600 block text-xs">
                  {{ $web->name }}
                </a>
                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $web->client ? $web->client->name : 'N/A' }}</span>
              </td>
              <td class="px-4 py-3.5">
                @if($web->domain)
                  <a href="{{ $web->url ?: 'https://' . $web->domain }}" target="_blank" class="font-bold text-brand-600 hover:underline flex items-center gap-1">
                    <span>{{ $web->domain }}</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                  </a>
                  @if($web->domain_expiry_date)
                    @php $isExp = $web->isDomainExpiringSoon(14); @endphp
                    <span class="text-[10px] font-bold block mt-0.5 {{ $isExp ? 'text-rose-600 bg-rose-50 px-1.5 py-0.2 rounded w-fit' : 'text-slate-400' }}">
                      {{ $isExp ? '⚠ Exp: ' : 'Exp: ' }}{{ $web->domain_expiry_date->format('M d, Y') }}
                    </span>
                  @endif
                @else
                  <span class="text-slate-400 italic">No domain</span>
                @endif
              </td>
              <td class="px-4 py-3.5">
                <span class="font-bold text-slate-800 block">{{ $web->hosting_provider ?: 'Cloud' }}</span>
                @if($web->server_ip)
                  <span class="text-[10px] text-slate-400 font-mono block">{{ $web->server_ip }}</span>
                @endif
              </td>
              <td class="px-4 py-3.5">
                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[10px]">{{ $web->technology ?: 'Custom' }}</span>
              </td>
              <td class="px-4 py-3.5">
                <div class="flex items-center gap-2">
                  <div class="w-16 bg-slate-200 h-2 rounded-full overflow-hidden">
                    <div class="bg-brand-600 h-full" style="width: {{ $web->overall_progress }}%"></div>
                  </div>
                  <span class="text-[11px] font-bold text-slate-800">{{ $web->overall_progress }}%</span>
                </div>
              </td>
              <td class="px-4 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $web->status === 'live' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-brand-50 text-brand-700 border-brand-200' }}">
                  {{ ucfirst($web->status) }}
                </span>
              </td>
              <td class="px-4 py-3.5 text-right space-x-2">
                <a href="/admin/websites/{{ $web->id }}" class="text-brand-600 font-bold hover:underline">Dashboard</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="p-8 text-center text-slate-400">No websites registered.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100">
      {{ $websites->withQueryString()->links() }}
    </div>
  </div>

</div>

<!-- Create Website Modal -->
<div id="modal-create-website" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-create-website').addClass('hidden')"></div>
    <form action="/admin/websites" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-xl p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Register New Website</h3>
        <button type="button" onclick="$('#modal-create-website').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Website Name *</label>
          <input type="text" name="name" required placeholder="e.g. Acme Online Store" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Website URL / Domain *</label>
          <input type="text" name="domain" required placeholder="e.g. acme.com or https://acme.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Client <span class="text-slate-400 font-normal">(Optional)</span></label>
          <select name="client_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="">-- Independent / None --</option>
            @foreach($clients as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Linked Project <span class="text-slate-400 font-normal">(Optional)</span></label>
          <select name="project_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="">-- None / Select --</option>
            @foreach($projects as $p)
              <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Domain Provider</label>
          <input type="text" name="domain_provider" placeholder="Cloudflare, Namecheap" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Domain Expiry Date</label>
          <input type="date" name="domain_expiry_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Hosting Provider</label>
          <input type="text" name="hosting_provider" placeholder="AWS, DigitalOcean, Vercel" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Hosting Expiry Date</label>
          <input type="date" name="hosting_expiry_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Technology / CMS</label>
          <input type="text" name="technology" placeholder="Laravel 13, Nuxt 4, Statamic" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Server IP</label>
          <input type="text" name="server_ip" placeholder="192.0.2.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Status</label>
          <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="planning">Planning</option>
            <option value="design">Design</option>
            <option value="development" selected>Development</option>
            <option value="testing">Testing</option>
            <option value="live">Live</option>
            <option value="maintenance">Maintenance</option>
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">SSL Status</label>
          <select name="ssl_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="active" selected>Active (Let's Encrypt / Cloudflare)</option>
            <option value="expiring_soon">Expiring Soon</option>
            <option value="not_installed">Not Installed</option>
          </select>
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-website').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save Website</button>
      </div>
    </form>
  </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Leads Management')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Leads</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Flash Messages -->
  @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-sm leading-none">&times;</button>
    </div>
  @endif

  @if(session('error'))
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
        <span>{{ session('error') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold text-sm leading-none">&times;</button>
    </div>
  @endif

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <div class="flex items-center gap-2.5">
        <h2 class="text-xl font-extrabold text-slate-900">Discovered Business Leads</h2>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
          {{ number_format($leads->total()) }} Total Leads
        </span>
      </div>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Showing {{ $leads->firstItem() ?? 0 }} to {{ $leads->lastItem() ?? 0 }} of {{ number_format($leads->total()) }} leads</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="/admin/leads/kanban" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
        <i class="fa-solid fa-table-columns text-slate-500"></i> Kanban Board
      </a>
      <button type="button" onclick="openImportModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/20 transition cursor-pointer">
        <i class="fa-solid fa-file-excel"></i> Import Excel / CSV
      </button>
      <button type="button" onclick="openCreateLeadModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
        <i class="fa-solid fa-plus"></i> Add New Lead
      </button>
    </div>
  </div>

  <!-- Filter & Search Control Center -->
  <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
    <form method="GET" action="/admin/leads" id="leadsFilterForm" class="space-y-3">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5">
        <!-- Search Input -->
        <div class="relative lg:col-span-2">
          <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search business, phone, category, address..."
            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        </div>

        <!-- City Dropdown -->
        <div class="relative">
          <select name="city" onchange="document.getElementById('leadsFilterForm').submit()" 
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">🏙️ All Cities</option>
            @foreach($cities as $c)
              <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
          </select>
        </div>

        <!-- Area Dropdown -->
        <div class="relative">
          <select name="area" onchange="document.getElementById('leadsFilterForm').submit()" 
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">📍 All Areas ({{ count($areas) }})</option>
            @foreach($areas as $ar)
              <option value="{{ $ar }}" {{ request('area') === $ar ? 'selected' : '' }}>{{ $ar }}</option>
            @endforeach
          </select>
        </div>

        <!-- Category Dropdown -->
        <div class="relative">
          <select name="industry" onchange="document.getElementById('leadsFilterForm').submit()" 
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">🏢 All Categories ({{ count($industries) }})</option>
            @foreach($industries as $ind)
              <option value="{{ $ind }}" {{ request('industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
            @endforeach
          </select>
        </div>

        <!-- Website Status Dropdown -->
        <div class="relative">
          <select name="has_website" onchange="document.getElementById('leadsFilterForm').submit()" 
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">🌐 All Website Statuses</option>
            <option value="NO" {{ request('has_website') === 'NO' ? 'selected' : '' }}>🔴 NO Website (Hot)</option>
            <option value="YES" {{ request('has_website') === 'YES' ? 'selected' : '' }}>🟢 Has Website</option>
          </select>
        </div>
      </div>

      <!-- Filter Controls Bar: Stage, Priority, Assignee, Page Length & Actions -->
      <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100 flex-wrap">
        <div class="flex items-center gap-2.5 flex-wrap">
          <!-- Pipeline Stage -->
          <select name="status" onchange="document.getElementById('leadsFilterForm').submit()" 
                  class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
            <option value="">📊 All Pipeline Stages</option>
            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>1. New</option>
            <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>2. Contacted</option>
            <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>3. Qualified</option>
            <option value="proposal_sent" {{ request('status') === 'proposal_sent' ? 'selected' : '' }}>4. Proposal Sent</option>
            <option value="negotiation" {{ request('status') === 'negotiation' ? 'selected' : '' }}>5. Negotiation</option>
            <option value="won" {{ request('status') === 'won' ? 'selected' : '' }}>6. Won</option>
            <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>7. Lost</option>
          </select>

          <!-- Priority Filter -->
          <select name="priority" onchange="document.getElementById('leadsFilterForm').submit()" 
                  class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
            <option value="">⚡ All Priorities</option>
            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🔥 Urgent Priority</option>
            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High Priority</option>
            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium Priority</option>
            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low Priority</option>
          </select>

          <!-- Assignee Filter -->
          <select name="developer_id" onchange="document.getElementById('leadsFilterForm').submit()" 
                  class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
            <option value="">👤 All Assignees</option>
            @foreach($developers as $dev)
              <option value="{{ $dev->id }}" {{ request('developer_id') == $dev->id ? 'selected' : '' }}>👤 {{ $dev->name }}</option>
            @endforeach
          </select>

          <!-- Page Length / Rows Selector -->
          <div class="flex items-center gap-1.5 pl-2 border-l border-slate-200">
            <span class="text-[11px] font-bold text-slate-400">Show</span>
            <select name="per_page" onchange="document.getElementById('leadsFilterForm').submit()" 
                    class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-bold text-slate-700 focus:outline-none cursor-pointer">
              <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
              <option value="25" {{ request('per_page', '25') == '25' ? 'selected' : '' }}>25</option>
              <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
              <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
              <option value="250" {{ request('per_page') == '250' ? 'selected' : '' }}>250</option>
              <option value="500" {{ request('per_page') == '500' ? 'selected' : '' }}>500</option>
            </select>
            <span class="text-[11px] font-bold text-slate-400">rows</span>
          </div>
        </div>

        <!-- Filter Buttons -->
        <div class="flex items-center gap-2">
          @if(request()->anyFilled(['search', 'has_website', 'city', 'area', 'industry', 'status', 'priority', 'developer_id']))
            <a href="/admin/leads" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition flex items-center gap-1">
              <i class="fa-solid fa-xmark text-[10px]"></i> Clear Filters
            </a>
          @endif
          <button type="submit" class="px-5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
            <i class="fa-solid fa-filter text-[11px]"></i> Apply Filters
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Table Card -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-3.5 py-3.5 text-center w-12">#</th>
            <th class="px-4 py-3.5">Company / Business Name</th>
            <th class="px-4 py-3.5 text-center">Website Status</th>
            <th class="px-4 py-3.5">Industry Category</th>
            <th class="px-4 py-3.5">Contact Phone</th>
            <th class="px-4 py-3.5">Area / Locality</th>
            <th class="px-4 py-3.5 text-center">Rating</th>
            <th class="px-4 py-3.5 text-center">AI Website Prompt</th>
            <th class="px-3 py-3.5 text-center">Maps</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($leads as $index => $lead)
            @php
              $isNoWebsite = strtoupper($lead->has_website) === 'NO' || empty($lead->website) || $lead->website === 'N/A' || $lead->website === 'None';
              $rawPhone = preg_replace('/\D/', '', $lead->phone ?? '');
              $waPhone = strlen($rawPhone) == 10 ? '91' . $rawPhone : $rawPhone;
            @endphp
            <tr class="hover:bg-slate-50/80 transition {{ $isNoWebsite ? 'bg-rose-50/20' : '' }}">
              <!-- Index Number -->
              <td class="px-3.5 py-3.5 text-center font-bold text-slate-400">
                {{ ($leads->currentPage() - 1) * $leads->perPage() + $index + 1 }}
              </td>

              <!-- Company Name -->
              <td class="px-4 py-3.5">
                <a href="/admin/leads/{{ $lead->id }}" class="font-bold text-slate-900 hover:text-brand-600 block text-xs">
                  {{ $lead->company_name }}
                </a>
                <span class="text-[11px] text-slate-400 block mt-0.5">
                  {{ $lead->contact_person ?? 'Owner' }} @if($lead->email && $lead->email !== 'N/A') • {{ $lead->email }} @endif
                </span>
              </td>

              <!-- Website Status Badge -->
              <td class="px-4 py-3.5 text-center">
                @if($isNoWebsite)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-700 border border-rose-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> NO Website
                  </span>
                @else
                  <a href="{{ Str::startsWith($lead->website, ['http://', 'https://']) ? $lead->website : 'https://' . $lead->website }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 hover:bg-emerald-200 transition" title="{{ $lead->website }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Has Website <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                  </a>
                @endif
              </td>

              <!-- Industry Category -->
              <td class="px-4 py-3.5">
                <span class="font-semibold text-slate-800">{{ $lead->industry ?? 'General Business' }}</span>
              </td>

              <!-- Phone + WhatsApp -->
              <td class="px-4 py-3.5 whitespace-nowrap">
                @if($lead->phone && $lead->phone !== 'N/A')
                  <div class="flex items-center gap-1.5 font-bold text-slate-900">
                    <a href="tel:{{ $lead->phone }}" class="hover:text-brand-600">{{ $lead->phone }}</a>
                    @if($waPhone)
                      <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener" class="text-emerald-500 hover:text-emerald-600 transition" title="Chat on WhatsApp">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                      </a>
                    @endif
                  </div>
                @else
                  <span class="text-slate-300 font-semibold italic">N/A</span>
                @endif
              </td>

              <!-- Area / Locality -->
              <td class="px-4 py-3.5 text-slate-600">
                <span>{{ $lead->area_locality ?? $lead->location ?? 'Vadodara' }}</span>
              </td>

              <!-- Rating -->
              <td class="px-4 py-3.5 text-center whitespace-nowrap">
                @if($lead->rating && $lead->rating !== 'N/A')
                  <span class="inline-flex items-center gap-1 font-bold text-amber-600 text-xs">
                    <i class="fa-solid fa-star text-[10px]"></i> {{ $lead->rating }}
                    @if($lead->total_reviews && $lead->total_reviews !== '0')
                      <span class="text-slate-400 font-normal text-[10px]">({{ $lead->total_reviews }})</span>
                    @endif
                  </span>
                @else
                  <span class="text-slate-300">-</span>
                @endif
              </td>

              <!-- AI Website Prompt Button -->
              <td class="px-4 py-3.5 text-center">
                @if($lead->website_build_prompt || $lead->requirement)
                  @php
                    $promptText = $lead->website_build_prompt ?: $lead->requirement;
                  @endphp
                  <button type="button" onclick="openPromptModal({{ json_encode($lead->company_name) }}, {{ json_encode($promptText) }})"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-700 text-slate-700 font-bold text-[11px] border border-slate-200 transition cursor-pointer">
                    <i class="fa-solid fa-eye text-slate-400"></i> View Prompt
                  </button>
                @else
                  <span class="text-slate-300">-</span>
                @endif
              </td>

              <!-- Google Maps Link -->
              <td class="px-3 py-3.5 text-center">
                @if($lead->google_maps_link)
                  <a href="{{ $lead->google_maps_link }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-brand-600 transition" title="Open in Google Maps">
                    <i class="fa-solid fa-location-dot text-sm text-rose-500"></i>
                  </a>
                @else
                  <span class="text-slate-300">-</span>
                @endif
              </td>

              <!-- Actions -->
              <td class="px-4 py-3.5 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-1.5">
                  <a href="/admin/leads/{{ $lead->id }}" class="p-1.5 text-slate-400 hover:text-brand-600 rounded-lg hover:bg-slate-100 transition" title="View 360 Profile">
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                <p class="font-bold text-sm">No business leads found</p>
                <p class="text-xs mt-0.5">Try adjusting your filters or search keywords.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination Footer -->
    @if($leads->hasPages())
      <div class="px-4 py-3.5 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
        <p class="text-xs font-semibold text-slate-500">
          Showing <span class="font-bold text-slate-800">{{ $leads->firstItem() }}</span> to <span class="font-bold text-slate-800">{{ $leads->lastItem() }}</span> of <span class="font-bold text-slate-800">{{ number_format($leads->total()) }}</span> entries
        </p>
        <div>
          {{ $leads->links('pagination::tailwind') }}
        </div>
      </div>
    @endif
  </div>
</div>

<!-- Modal: Import Leads Excel/CSV -->
<div id="modal-import-leads" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-file-excel"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Import Leads from Excel / CSV</h3>
          <p class="text-[11px] text-slate-400 font-medium">Automatic deduplication & qualification</p>
        </div>
      </div>
      <button type="button" onclick="closeImportModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form action="/admin/leads/import" method="POST" enctype="multipart/form-data" class="space-y-3.5">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Select Excel (.xlsx) or CSV File</label>
        <input type="file" name="file" accept=".xlsx,.csv,.txt" required
          class="w-full text-xs font-semibold text-slate-700 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer bg-slate-50 border border-slate-200 rounded-2xl p-2">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Import Tag / User</label>
        <input type="text" name="imported_by" value="{{ auth()->user()?->name ?? 'Excel Import' }}"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>

      <div class="p-3 bg-slate-50 border border-slate-200/70 rounded-2xl space-y-1">
        <div class="flex items-center gap-1.5 text-slate-700 font-bold text-xs">
          <i class="fa-solid fa-shield-halved text-emerald-600"></i> Smart Duplicate Protection
        </div>
        <p class="text-[11px] text-slate-500 leading-relaxed">
          Leads with existing phone numbers or matching company names in the same location are automatically skipped.
        </p>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          <i class="fa-solid fa-upload mr-1"></i> Start Import
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Create New Lead -->
<div id="modal-create-lead" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-user-plus"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Add New Business Lead</h3>
          <p class="text-[11px] text-slate-400 font-medium">Create and qualify a prospective client</p>
        </div>
      </div>
      <button type="button" onclick="closeCreateLeadModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form action="/admin/leads" method="POST" class="space-y-3">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Company / Business Name *</label>
        <input type="text" name="company_name" required placeholder="e.g. Acme Solar Solutions"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>

      <div class="grid grid-cols-2 gap-2.5">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Contact Person *</label>
          <input type="text" name="contact_person" required placeholder="Full Name"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
          <input type="text" name="phone" placeholder="e.g. 098765 43210"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-2.5">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
          <input type="email" name="email" placeholder="contact@example.com"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Existing Website URL</label>
          <input type="text" name="website" placeholder="https://example.com"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-2.5">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Industry / Category</label>
          <input type="text" name="industry" placeholder="e.g. Solar Energy"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Area / Locality</label>
          <input type="text" name="location" placeholder="e.g. Alkapuri, Vadodara"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        </div>
      </div>

      <div class="grid grid-cols-3 gap-2.5">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Priority</label>
          <select name="priority" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
            <option value="urgent">Urgent (Hot)</option>
            <option value="high">High</option>
            <option value="medium" selected>Medium</option>
            <option value="low">Low</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
          <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
            <option value="new" selected>New</option>
            <option value="contacted">Contacted</option>
            <option value="qualified">Qualified</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Lead Source</label>
          <input type="text" name="lead_source" value="Manual Entry"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800">
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Requirement / AI Website Prompt</label>
        <textarea name="requirement" rows="3" placeholder="Describe client requirements..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeCreateLeadModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          Create Lead
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: AI Website Build Prompt -->
<div id="modal-view-prompt" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">✨</span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900" id="promptModalTitle">AI Website Build Prompt</h3>
          <p class="text-[11px] text-slate-400 font-medium">Ready-to-use static website generation prompt</p>
        </div>
      </div>
      <button type="button" onclick="closePromptModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <div class="relative">
      <textarea id="promptModalText" rows="10" readonly class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-mono text-slate-800 focus:outline-none select-all"></textarea>
    </div>

    <div class="flex items-center justify-between pt-2">
      <span class="text-[11px] font-bold text-slate-400">Copy and paste into ChatGPT, Claude, Lovable, or Antigravity</span>
      <div class="flex items-center gap-2">
        <button type="button" onclick="copyPromptToClipboard()" id="btnCopyPrompt" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          <i class="fa-regular fa-copy mr-1"></i> Copy Prompt
        </button>
        <button type="button" onclick="closePromptModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function openImportModal() {
  var el = document.getElementById('modal-import-leads');
  if (el) el.classList.remove('hidden');
}

function closeImportModal() {
  var el = document.getElementById('modal-import-leads');
  if (el) el.classList.add('hidden');
}

function openCreateLeadModal() {
  var el = document.getElementById('modal-create-lead');
  if (el) el.classList.remove('hidden');
}

function closeCreateLeadModal() {
  var el = document.getElementById('modal-create-lead');
  if (el) el.classList.add('hidden');
}

function openPromptModal(title, prompt) {
  var titleEl = document.getElementById('promptModalTitle');
  var textEl = document.getElementById('promptModalText');
  var modalEl = document.getElementById('modal-view-prompt');
  if (titleEl) titleEl.textContent = 'AI Website Prompt — ' + title;
  if (textEl) textEl.value = prompt;
  if (modalEl) modalEl.classList.remove('hidden');
}

function closePromptModal() {
  var modalEl = document.getElementById('modal-view-prompt');
  if (modalEl) modalEl.classList.add('hidden');
}

function copyPromptToClipboard() {
  var text = document.getElementById('promptModalText').value;
  navigator.clipboard.writeText(text).then(function() {
    var btn = document.getElementById('btnCopyPrompt');
    var orig = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Copied!';
    btn.classList.add('bg-emerald-600');
    setTimeout(function() {
      btn.innerHTML = orig;
      btn.classList.remove('bg-emerald-600');
    }, 2000);
  });
}
</script>
@endsection

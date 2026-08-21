@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Sales Pipeline Kanban')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/leads" class="hover:text-brand-600 transition">Leads</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Pipeline Kanban</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <div class="flex items-center gap-2.5">
        <h2 class="text-xl font-extrabold text-slate-900">Sales Pipeline Kanban</h2>
        @php
          $totalCount = array_sum(array_map('count', $leadsByStage));
          $totalVal = array_sum($totalBudgetByStage);
        @endphp
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
          {{ number_format($totalCount) }} Leads (₹{{ number_format($totalVal, 0) }})
        </span>
      </div>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Drag and drop leads between pipeline deal stages. Live updates & total value calculations.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="/admin/leads" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
        <i class="fa-solid fa-list"></i> Table View
      </a>
      <button onclick="$('#modal-import-leads').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/20 transition cursor-pointer">
        <i class="fa-solid fa-file-excel"></i> Import Excel
      </button>
      <button onclick="$('#modal-create-lead').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
        <i class="fa-solid fa-plus"></i> New Lead
      </button>
    </div>
  </div>

  <!-- Filter & Search Control Center -->
  <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">

    <!-- Row 2: Filter Form -->
    <form method="GET" action="/admin/leads/kanban" id="kanbanFilterForm" class="space-y-3">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5">
        <!-- Search Bar -->
        <div class="relative lg:col-span-2">
          <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search business, phone, category..."
            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        </div>

        <!-- City Filter -->
        <div>
          <select name="city" onchange="document.getElementById('kanbanFilterForm').submit()" 
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">🏙️ All Cities</option>
            @foreach($cities as $c)
              <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
          </select>
        </div>

        <!-- Area / Locality Filter -->
        <div>
          <select name="area" onchange="document.getElementById('kanbanFilterForm').submit()" 
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">📍 All Areas ({{ count($areas) }})</option>
            @foreach($areas as $ar)
              <option value="{{ $ar }}" {{ request('area') === $ar ? 'selected' : '' }}>{{ $ar }}</option>
            @endforeach
          </select>
        </div>

        <!-- Industry / Category Type Filter -->
        <div>
          <select name="industry" onchange="document.getElementById('kanbanFilterForm').submit()" 
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">🏢 All Categories ({{ count($industries) }})</option>
            @foreach($industries as $ind)
              <option value="{{ $ind }}" {{ request('industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
            @endforeach
          </select>
        </div>

        <!-- Website Status Filter -->
        <div>
          <select name="has_website" onchange="document.getElementById('kanbanFilterForm').submit()" 
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">🌐 All Website Statuses</option>
            <option value="NO" {{ request('has_website') === 'NO' ? 'selected' : '' }}>🔴 NO Website (Hot)</option>
            <option value="YES" {{ request('has_website') === 'YES' ? 'selected' : '' }}>🟢 Has Website</option>
          </select>
        </div>
      </div>

      <!-- Bottom Controls: Priority, Assignee & Actions -->
      <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100 flex-wrap">
        <div class="flex items-center gap-2.5 flex-wrap">
          <!-- Priority Filter -->
          <select name="priority" onchange="document.getElementById('kanbanFilterForm').submit()" 
                  class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
            <option value="">⚡ All Priorities</option>
            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🔥 Urgent Priority</option>
            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High Priority</option>
            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium Priority</option>
            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low Priority</option>
          </select>

          <!-- Assignee Filter -->
          <select name="developer_id" onchange="document.getElementById('kanbanFilterForm').submit()" 
                  class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
            <option value="">👤 All Assignees</option>
            @foreach($developers as $dev)
              <option value="{{ $dev->id }}" {{ request('developer_id') == $dev->id ? 'selected' : '' }}>👤 {{ $dev->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
          @if(request()->anyFilled(['search', 'has_website', 'city', 'area', 'industry', 'priority', 'developer_id']))
            <a href="/admin/leads/kanban" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition flex items-center gap-1">
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

  <!-- ── KANBAN BOARD WRAPPER ──────────────────────────────────── -->
  <div class="overflow-x-auto pb-4">
    <div class="flex gap-4 min-w-[1450px]">
      @foreach($stages as $stageKey => $stageName)
        <div class="w-80 shrink-0 bg-slate-100/70 border border-slate-200/80 rounded-2xl p-3 flex flex-col max-h-[calc(100vh-250px)]">

          <!-- Column Header -->
          <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200/60">
            <div>
              <span class="font-extrabold text-xs text-slate-800">{{ $stageName }}</span>
              <span class="text-[10px] font-bold text-slate-400 block mt-0.5">₹{{ number_format($totalBudgetByStage[$stageKey], 0) }}</span>
            </div>
            <span class="h-5 px-2 rounded-full bg-white text-slate-700 font-extrabold text-[10px] flex items-center justify-center border border-slate-200 shadow-2xs">
              {{ count($leadsByStage[$stageKey]) }}
            </span>
          </div>

          <!-- Cards Drop Zone -->
          <div class="kanban-column flex-1 overflow-y-auto space-y-2.5 min-h-[150px] p-0.5" data-stage="{{ $stageKey }}">
            @forelse($leadsByStage[$stageKey] as $lead)
              @php
                $isNoWeb = strtoupper($lead->has_website) === 'NO' || empty($lead->website) || $lead->website === 'N/A';
              @endphp
              <div class="kanban-card bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-xs hover:shadow-md transition cursor-grab active:cursor-grabbing group relative {{ $isNoWeb ? 'border-l-4 border-l-rose-500' : 'border-l-4 border-l-emerald-500' }}"
                   draggable="true" data-lead-id="{{ $lead->id }}">
                
                <!-- Top: Company & Priority -->
                <div class="flex items-start justify-between gap-2">
                  <a href="/admin/leads/{{ $lead->id }}" class="font-bold text-xs text-slate-900 group-hover:text-brand-600 transition leading-snug line-clamp-1" title="{{ $lead->company_name }}">
                    {{ $lead->company_name }}
                  </a>
                  <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded shrink-0 {{ $lead->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : ($lead->priority === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                    {{ $lead->priority }}
                  </span>
                </div>

                <!-- Sub-info: Industry & Locality -->
                <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                  <span class="truncate max-w-[150px] font-medium text-slate-600">{{ $lead->industry ?? 'General' }}</span>
                  @if($lead->rating && $lead->rating !== 'N/A')
                    <span class="font-bold text-amber-600"><i class="fa-solid fa-star text-[8px]"></i> {{ $lead->rating }}</span>
                  @endif
                </div>

                <!-- Website Status Indicator -->
                <div class="mt-2 flex items-center justify-between">
                  @if($isNoWeb)
                    <span class="inline-flex items-center gap-1 text-[9px] font-extrabold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100">
                      <span class="w-1 h-1 rounded-full bg-rose-500"></span> NO Website (Hot Lead)
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                      <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Has Website
                    </span>
                  @endif
                  @if($lead->area_locality)
                    <span class="text-[9px] text-slate-400 truncate max-w-[100px]">{{ $lead->area_locality }}</span>
                  @endif
                </div>

                <!-- Value & Pipeline Days -->
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100">
                  <span class="font-extrabold text-xs text-emerald-600">₹{{ number_format($lead->estimated_budget, 0) }}</span>
                  <span class="text-[10px] text-slate-400 font-medium">
                    <i class="fa-regular fa-clock text-[9px]"></i> {{ $lead->days_in_pipeline }}d in pipe
                  </span>
                </div>

                <!-- Footer: Dev Avatar & Follow-up -->
                <div class="flex items-center justify-between mt-2 text-[10px] text-slate-400">
                  <div class="flex items-center gap-1.5">
                    @if($lead->assignedDeveloper)
                      <span class="h-5 w-5 rounded-full bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center font-bold text-[9px]" title="{{ $lead->assignedDeveloper->name }}">
                        {{ substr($lead->assignedDeveloper->name, 0, 1) }}
                      </span>
                      <span class="truncate max-w-[80px] font-semibold text-slate-700">{{ $lead->assignedDeveloper->name }}</span>
                    @else
                      <span class="italic text-slate-300">Unassigned</span>
                    @endif
                  </div>
                  @if($lead->phone && $lead->phone !== 'N/A')
                    <a href="tel:{{ $lead->phone }}" class="text-[10px] font-bold text-slate-700 hover:text-brand-600" title="{{ $lead->phone }}">
                      <i class="fa-solid fa-phone text-[8px] text-slate-400"></i> {{ substr($lead->phone, -10) }}
                    </a>
                  @endif
                </div>

              </div>
            @empty
              <div class="h-24 flex items-center justify-center text-center text-[11px] text-slate-400 italic">
                Drop leads here
              </div>
            @endforelse
          </div>

        </div>
      @endforeach
    </div>
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
          <p class="text-[11px] text-slate-400 font-medium">Automatic deduplication & lead qualification</p>
        </div>
      </div>
      <button onclick="$('#modal-import-leads').addClass('hidden')" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
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
          Leads with existing phone numbers or matching company names in the same location are automatically skipped to keep your database 100% clean.
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

document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.kanban-card');
  const columns = document.querySelectorAll('.kanban-column');

  function updateColumnCounters() {
    columns.forEach(col => {
      const stage = col.dataset.stage;
      const count = col.querySelectorAll('.kanban-card').length;
      const counterEl = col.closest('.shrink-0')?.querySelector('.rounded-full');
      if (counterEl) counterEl.textContent = count;
    });
  }

  cards.forEach(card => {
    card.addEventListener('dragstart', (e) => {
      e.dataTransfer.setData('text/plain', card.dataset.leadId);
      card.classList.add('opacity-50', 'scale-95');
    });
    card.addEventListener('dragend', () => {
      card.classList.remove('opacity-50', 'scale-95');
    });
  });

  columns.forEach(col => {
    col.addEventListener('dragover', (e) => {
      e.preventDefault();
      col.classList.add('bg-brand-50/50', 'border-brand-300');
    });
    col.addEventListener('dragleave', () => {
      col.classList.remove('bg-brand-50/50', 'border-brand-300');
    });
    col.addEventListener('drop', (e) => {
      e.preventDefault();
      col.classList.remove('bg-brand-50/50', 'border-brand-300');
      const leadId = e.dataTransfer.getData('text/plain');
      const newStage = col.dataset.stage;
      const card = document.querySelector(`[data-lead-id="${leadId}"]`);

      if (card && col) {
        const prevColumn = card.parentElement;
        col.appendChild(card);
        updateColumnCounters();

        // 1. Sleek Card Loading State Overlay
        const overlay = document.createElement('div');
        overlay.className = 'card-loading-overlay absolute inset-0 bg-white/85 backdrop-blur-[1px] rounded-xl flex items-center justify-center gap-2 text-xs font-bold text-brand-600 shadow-sm z-30 transition-all animate-pulse';
        overlay.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-brand-600 text-sm"></i><span class="text-[11px] font-extrabold tracking-tight">Updating Stage...</span>';
        card.classList.add('relative', 'ring-2', 'ring-brand-500/40');
        card.appendChild(overlay);

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const url = "{{ url('/admin/leads') }}/" + leadId + "/stage";

        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({ status: newStage, stage: newStage })
        }).then(r => r.json()).then(data => {
          overlay.remove();
          card.classList.remove('ring-2', 'ring-brand-500/40');

          if (data.success) {
            // Success flash indicator
            card.classList.add('ring-2', 'ring-emerald-500');
            setTimeout(() => card.classList.remove('ring-2', 'ring-emerald-500'), 700);

            const stageLabel = col.closest('.shrink-0')?.querySelector('.font-extrabold')?.textContent?.trim() || newStage;
            const companyTitle = card.querySelector('a')?.textContent?.trim() || 'Lead';
            if (typeof window.showToast === 'function') {
              window.showToast('success', `${companyTitle} moved to "${stageLabel}"`);
            }
          } else {
            // Revert on error
            if (prevColumn) prevColumn.appendChild(card);
            updateColumnCounters();
            if (typeof window.showToast === 'function') {
              window.showToast('error', data.message || 'Failed to update stage');
            }
          }
        }).catch(err => {
          console.error('Error moving lead stage:', err);
          overlay.remove();
          card.classList.remove('ring-2', 'ring-brand-500/40');
          if (prevColumn) prevColumn.appendChild(card);
          updateColumnCounters();
          if (typeof window.showToast === 'function') {
            window.showToast('error', 'Network error moving lead');
          }
        });
      }
    });
  });
});
</script>
@endsection

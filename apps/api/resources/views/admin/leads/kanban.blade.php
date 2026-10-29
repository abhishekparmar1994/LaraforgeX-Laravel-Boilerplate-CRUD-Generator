@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — EPC Solar Pipeline Kanban')

@push('styles')
  <style>
    /* High-Performance Kanban Card Virtualization */
    .kanban-card {
      content-visibility: auto;
      contain-intrinsic-size: auto 140px;
      contain: layout style;
    }

    /* Prevent nested elements from firing unnecessary dragenter/dragleave events */
    .kanban-card * {
      pointer-events: none;
    }

    .kanban-card a,
    .kanban-card button {
      pointer-events: auto;
    }

    /* Instant 60 FPS Drag Optimization: disable layout thrashing & transitions during active drag */
    body.is-kanban-dragging * {
      cursor: grabbing !important;
    }

    body.is-kanban-dragging .kanban-card {
      transition: none !important;
      animation: none !important;
    }

    /* High performance drop-target highlight */
    .kanban-column.drag-hover {
      background-color: rgba(238, 242, 255, 0.8) !important;
      border-color: #6366f1 !important;
      outline: 2px dashed #6366f1;
      outline-offset: -2px;
    }
  </style>
@endpush

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
          <h2 class="text-xl font-extrabold text-slate-900">EPC Solar Pipeline Kanban</h2>
          @php
            $totalCount = array_sum($stageCounts ?? array_map('count', $leadsByStage));
            $totalVal = array_sum($totalBudgetByStage);
          @endphp
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
            {{ number_format($totalCount) }} Leads (₹{{ number_format($totalVal, 0) }})
          </span>
        </div>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Drag and drop leads between pipeline deal stages. Live
          updates & total value calculations.</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="/admin/leads"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
          <i class="fa-solid fa-list"></i> Table View
        </a>
        <button onclick="$('#modal-import-leads').removeClass('hidden')"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/20 transition cursor-pointer">
          <i class="fa-solid fa-file-excel"></i> Import Excel
        </button>
        <button onclick="$('#modal-create-lead').removeClass('hidden')"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
          <i class="fa-solid fa-plus"></i> New Lead
        </button>
      </div>
    </div>

    <!-- Filter & Search Control Center -->
    <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">

      <!-- Filter Form -->
      <form method="GET" action="/admin/leads/kanban" id="kanbanFilterForm" class="space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5">
          <!-- Search Bar -->
          <div class="relative lg:col-span-2">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
              placeholder="Search business, phone, category..."
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

          <!-- Deal Priority Filter -->
          <div>
            <select name="priority" onchange="document.getElementById('kanbanFilterForm').submit()"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
              <option value="">⚡ All Priorities</option>
              <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🔥 Urgent Priority</option>
              <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>⚡ High Priority</option>
              <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>⭐ Medium Priority</option>
              <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>💤 Low Priority</option>
            </select>
          </div>
        </div>

        <!-- Bottom Controls: Assignee & Actions -->
        <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100 flex-wrap">
          <div class="flex items-center gap-2.5 flex-wrap">
            <!-- Solar Executive Assignee Filter -->
            <select name="developer_id" onchange="document.getElementById('kanbanFilterForm').submit()"
              class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
              <option value="">👤 All Solar Executives</option>
              @foreach($developers as $dev)
                <option value="{{ $dev->id }}" {{ request('developer_id') == $dev->id ? 'selected' : '' }}>👤 {{ $dev->name }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-2">
            @if(request()->anyFilled(['search', 'city', 'area', 'industry', 'priority', 'developer_id']))
              <a href="/admin/leads/kanban"
                class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition flex items-center gap-1">
                <i class="fa-solid fa-xmark text-[10px]"></i> Clear Filters
              </a>
            @endif
            <button type="submit"
              class="px-5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
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
          <div
            class="w-80 shrink-0 bg-slate-100/70 border border-slate-200/80 rounded-2xl p-3 flex flex-col max-h-[calc(100vh-250px)]">

            <!-- Column Header -->
            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200/60">
              <div>
                <span class="font-extrabold text-xs text-slate-800">{{ $stageName }}</span>
                <span
                  class="text-[10px] font-bold text-slate-400 block mt-0.5">₹{{ number_format($totalBudgetByStage[$stageKey] ?? 0, 0) }}</span>
              </div>
              <span
                class="column-count-badge h-5 px-2 rounded-full bg-white text-slate-700 font-extrabold text-[10px] flex items-center justify-center border border-slate-200 shadow-2xs">
                {{ $stageCounts[$stageKey] ?? count($leadsByStage[$stageKey]) }}
              </span>
            </div>

            <!-- Cards Drop Zone -->
            <div class="kanban-column flex-1 overflow-y-auto space-y-2.5 min-h-[150px] p-0.5" data-stage="{{ $stageKey }}">
              <div class="kanban-cards-wrapper space-y-2.5">
                @forelse($leadsByStage[$stageKey] as $lead)
                  @include('admin.leads.partials.kanban-card', ['lead' => $lead])
                @empty
                  <div
                    class="empty-state-placeholder h-24 flex items-center justify-center text-center text-[11px] text-slate-400 italic">
                    Drop leads here
                  </div>
                @endforelse
              </div>

              @php
                $stgTotal = $stageCounts[$stageKey] ?? count($leadsByStage[$stageKey]);
                $stgRendered = count($leadsByStage[$stageKey]);
              @endphp
              @if($stgTotal > $stgRendered)
                <div class="load-more-box pt-2 pb-1 text-center" id="load-box-{{ $stageKey }}">
                  <button type="button" onclick="loadMoreLeads('{{ $stageKey }}')"
                    class="w-full py-1.5 px-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-600 hover:text-brand-600 transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-angles-down text-[9px]"></i>
                    <span>Load More (+{{ min(35, $stgTotal - $stgRendered) }})</span>
                    <span class="text-[9px] text-slate-400 font-semibold"
                      id="rem-badge-{{ $stageKey }}">({{ $stgTotal - $stgRendered }} left)</span>
                  </button>
                </div>
              @endif
            </div>

          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Modal: Import Leads Excel/CSV -->
  <div id="modal-import-leads"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2">
          <span
            class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
            <i class="fa-solid fa-file-excel"></i>
          </span>
          <div>
            <h3 class="font-bold text-slate-800 text-sm">Import Leads from Excel/CSV</h3>
            <p class="text-[11px] text-slate-400">Upload your lead spreadsheet to batch import.</p>
          </div>
        </div>
        <button onclick="$('#modal-import-leads').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-xs">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <form action="/admin/leads/import" method="POST" enctype="multipart/form-data" class="space-y-3">
        @csrf
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Select File (.xlsx, .csv)</label>
          <input type="file" name="file" accept=".xlsx,.csv" required
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100">
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
          <button type="button" onclick="$('#modal-import-leads').addClass('hidden')"
            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
          <button type="submit"
            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
            Import Now
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Quick Create Lead -->
  <div id="modal-create-lead"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2">
          <span class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
            <i class="fa-solid fa-user-plus"></i>
          </span>
          <div>
            <h3 class="font-bold text-slate-800 text-sm">Create New Pipeline Lead</h3>
            <p class="text-[11px] text-slate-400">Add a prospective client to your sales funnel.</p>
          </div>
        </div>
        <button onclick="closeCreateLeadModal()" class="text-slate-400 hover:text-slate-600 text-xs">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <form action="/admin/leads" method="POST" class="space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-2.5">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Company / Business Name *</label>
            <input type="text" name="company_name" required placeholder="e.g. Apex Technologies"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Contact Person *</label>
            <input type="text" name="contact_person" required placeholder="e.g. John Doe"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2.5">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
            <input type="text" name="phone" placeholder="e.g. +91 98765 43210"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
            <input type="email" name="email" placeholder="e.g. john@apex.com"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2.5">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Industry / Category</label>
            <input type="text" name="industry" placeholder="e.g. Solar, Real Estate, Dental"
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
            <select name="priority"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
              <option value="urgent">Urgent (Hot)</option>
              <option value="high">High</option>
              <option value="medium" selected>Medium</option>
              <option value="low">Low</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
            <select name="status"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
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
          <label class="block text-xs font-bold text-slate-700 mb-1">Solar Rooftop Requirements & Notes</label>
          <textarea name="requirement" rows="3"
            placeholder="Describe solar system requirements, rooftop type, shadow conditions..."
            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500"></textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
          <button type="button" onclick="closeCreateLeadModal()"
            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
          <button type="submit"
            class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
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
      const columns = document.querySelectorAll('.kanban-column');

      function updateColumnCounters() {
        columns.forEach(col => {
          const cardsWrapper = col.querySelector('.kanban-cards-wrapper') || col;
          const count = cardsWrapper.querySelectorAll('.kanban-card').length;
          const badge = col.closest('.shrink-0')?.querySelector('.column-count-badge');
          if (badge) badge.textContent = count;
        });
      }

      // Event Delegation for Drag Start
      document.addEventListener('dragstart', (e) => {
        const card = e.target.closest('.kanban-card');
        if (!card) return;

        e.dataTransfer.setData('text/plain', card.dataset.leadId);
        e.dataTransfer.effectAllowed = 'move';
        document.body.classList.add('is-kanban-dragging');

        // Paint drag ghost before dimming card
        requestAnimationFrame(() => {
          card.classList.add('opacity-40', 'scale-95');
        });
      });

      // Event Delegation for Drag End
      document.addEventListener('dragend', (e) => {
        const card = e.target.closest('.kanban-card');
        if (card) {
          card.classList.remove('opacity-40', 'scale-95');
        }
        document.body.classList.remove('is-kanban-dragging');
        document.querySelectorAll('.kanban-column').forEach(c => c.classList.remove('drag-hover'));
      });

      // Setup column drag targets
      columns.forEach(col => {
        col.addEventListener('dragover', (e) => {
          e.preventDefault();
          e.dataTransfer.dropEffect = 'move';
        });

        col.addEventListener('dragenter', (e) => {
          e.preventDefault();
          col.classList.add('drag-hover');
        });

        col.addEventListener('dragleave', (e) => {
          if (!col.contains(e.relatedTarget)) {
            col.classList.remove('drag-hover');
          }
        });

        col.addEventListener('drop', (e) => {
          e.preventDefault();
          col.classList.remove('drag-hover');

          const leadId = e.dataTransfer.getData('text/plain');
          const newStage = col.dataset.stage;
          const card = document.querySelector(`[data-lead-id="${leadId}"]`);

          if (!card || !col) return;

          const cardsWrapper = col.querySelector('.kanban-cards-wrapper') || col;
          const prevWrapper = card.parentElement;
          const emptyState = cardsWrapper.querySelector('.empty-state-placeholder');
          if (emptyState) emptyState.remove();

          // Immediate optimistic update
          cardsWrapper.prepend(card);
          updateColumnCounters();

          // Lightweight saving feedback overlay
          const overlay = document.createElement('div');
          overlay.className = 'card-loading-overlay absolute inset-0 bg-white/80 rounded-xl flex items-center justify-center gap-1.5 text-xs font-bold text-brand-600 z-30 animate-pulse';
          overlay.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-brand-600 text-xs"></i><span class="text-[10px] font-bold">Saving...</span>';
          card.classList.add('relative', 'ring-2', 'ring-brand-500/40');
          card.appendChild(overlay);

          const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
          const url = '/admin/leads/' + leadId + '/stage';

          fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ status: newStage, stage: newStage })
          })
            .then(r => r.json())
            .then(data => {
              overlay.remove();
              card.classList.remove('ring-2', 'ring-brand-500/40');

              if (data.success) {
                card.classList.add('ring-2', 'ring-emerald-500');
                setTimeout(() => card.classList.remove('ring-2', 'ring-emerald-500'), 600);

                const stageLabel = col.closest('.shrink-0')?.querySelector('.font-extrabold')?.textContent?.trim() || newStage;
                const companyTitle = card.querySelector('a')?.textContent?.trim() || 'Lead';
                if (typeof window.showToast === 'function') {
                  window.showToast('success', `${companyTitle} moved to "${stageLabel}"`);
                }
              } else {
                if (prevWrapper) prevWrapper.appendChild(card);
                updateColumnCounters();
                if (typeof window.showToast === 'function') {
                  window.showToast('error', data.message || 'Failed to update stage');
                }
              }
            })
            .catch(err => {
              overlay.remove();
              card.classList.remove('ring-2', 'ring-brand-500/40');
              if (prevWrapper) prevWrapper.appendChild(card);
              updateColumnCounters();
              if (typeof window.showToast === 'function') {
                window.showToast('error', 'Network error moving lead');
              }
            });
        });
      });
    });

    // High-speed AJAX pagination per column
    const stageOffsets = {};

    window.loadMoreLeads = function (stage) {
      const col = document.querySelector(`.kanban-column[data-stage="${stage}"]`);
      if (!col) return;
      const cardsWrapper = col.querySelector('.kanban-cards-wrapper') || col;
      const currentCount = cardsWrapper.querySelectorAll('.kanban-card').length;
      const offset = stageOffsets[stage] || currentCount;
      const loadBox = document.getElementById(`load-box-${stage}`);
      const btn = loadBox ? loadBox.querySelector('button') : null;

      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i><span>Loading...</span>';
      }

      const form = document.getElementById('kanbanFilterForm');
      const params = new URLSearchParams(form ? new FormData(form) : []);
      params.set('stage', stage);
      params.set('offset', offset);
      params.set('limit', 35);

      fetch(`/admin/leads/kanban?${params.toString()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(r => r.json())
        .then(data => {
          if (data.success && data.html) {
            cardsWrapper.insertAdjacentHTML('beforeend', data.html);
            stageOffsets[stage] = offset + data.loaded;
            if (loadBox) {
              if (data.has_more) {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-angles-down text-[9px]"></i><span>Load More (+${Math.min(35, data.remaining)})</span><span class="text-[9px] text-slate-400 font-semibold">(${data.remaining} left)</span>`;
              } else {
                loadBox.remove();
              }
            }
          } else {
            if (loadBox) loadBox.remove();
          }
        })
        .catch(() => {
          if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-rotate-right text-xs"></i><span>Retry Loading</span>';
          }
        });
    };
  </script>
@endsection
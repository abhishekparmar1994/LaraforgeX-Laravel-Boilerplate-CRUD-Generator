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

    <!-- Header (Mirrored identically with Leads Table/Card View) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <div class="flex items-center gap-2.5">
          <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-solar-panel text-amber-500"></i> Solar Energy & Rooftop Leads
          </h2>
          @php
            $totalCount = array_sum($stageCounts ?? array_map('count', $leadsByStage));
            $totalVal = array_sum($totalBudgetByStage);
          @endphp
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
            {{ number_format($totalCount) }} Solar Leads
          </span>
        </div>
        <p class="text-xs text-slate-500 font-medium mt-0.5">
          Showing {{ number_format($totalCount) }} commercial, industrial & residential solar inquiries across pipeline
          stages
        </p>
      </div>
      <div class="flex items-center gap-2 flex-wrap">
        <!-- Triple View Switcher: Card | Table | Kanban -->
        <div class="inline-flex rounded-xl bg-slate-100 p-0.5 border border-slate-200">
          <a href="/admin/leads?view=card"
            class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer text-slate-600 hover:text-slate-900 flex items-center">
            <i class="fa-solid fa-grip mr-1"></i> Card
          </a>
          <a href="/admin/leads"
            class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer text-slate-600 hover:text-slate-900 flex items-center">
            <i class="fa-solid fa-table-list mr-1"></i> Table
          </a>
          <button type="button"
            class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-default bg-white text-emerald-700 shadow-2xs flex items-center">
            <i class="fa-solid fa-table-columns mr-1"></i> Kanban
          </button>
        </div>

        <button type="button" onclick="openImportModal()"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/20 transition cursor-pointer">
          <i class="fa-solid fa-file-excel"></i> Import Solar Leads
        </button>
        <button type="button" onclick="openCreateLeadModal()"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm shadow-amber-500/20 transition cursor-pointer">
          <i class="fa-solid fa-plus"></i> Add Solar Lead
        </button>
      </div>
    </div>

    <!-- Filter & Search Control Center (Identical Layout to Table/Card View) -->
    <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">

      <!-- Filter Form -->
      <form method="GET" action="/admin/leads/kanban" id="kanbanFilterForm" class="space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5">
          <!-- Search Bar -->
          <div class="relative sm:col-span-2 md:col-span-3 lg:col-span-3">
            <i
              class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
            <input type="text" name="search" id="kanban-filter-search" value="{{ request('search') }}"
              placeholder="Search customer, company, phone, locality, address..."
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-8 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
            @if(request('search'))
              <a href="/admin/leads/kanban"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs cursor-pointer">
                <i class="fa-solid fa-circle-xmark"></i>
              </a>
            @endif
          </div>

          <!-- City Filter -->
          <select name="city" id="kanban-filter-city" onchange="handleKanbanCityChange(this.value)"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">🏙️ All Cities</option>
            @foreach($cities as $c)
              <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
          </select>

          <!-- Area / Locality Filter (Dependent on City) -->
          <select name="area" id="kanban-filter-area" onchange="document.getElementById('kanbanFilterForm').submit()"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">📍 All Areas ({{ count($areas) }})</option>
            @foreach($areas as $ar)
              <option value="{{ $ar }}" {{ request('area') === $ar ? 'selected' : '' }}>{{ $ar }}</option>
            @endforeach
          </select>

          <!-- Deal Priority Filter -->
          <select name="priority" id="kanban-filter-priority"
            onchange="document.getElementById('kanbanFilterForm').submit()"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
            <option value="">🔥 All Priorities</option>
            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>⚡ Urgent / High kW</option>
            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Commercial C&I</option>
            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Residential Rooftop</option>
            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Standard Inquiry</option>
          </select>
        </div>

        <!-- Bottom Controls: Pipeline Stage & Assignee & Actions -->
        <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100 flex-wrap">
          <div class="flex items-center gap-2.5 flex-wrap">
            <!-- Pipeline Stage Filter -->
            <select name="status" id="kanban-filter-status"
              onchange="document.getElementById('kanbanFilterForm').submit()"
              class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
              <option value="">⚡ All Pipeline Stages</option>
              <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>1. New Solar Lead</option>
              <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>2. Site Survey Scheduled
              </option>
              <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>3. Feasibility Approved
              </option>
              <option value="proposal_sent" {{ request('status') === 'proposal_sent' ? 'selected' : '' }}>4. Sizing &
                Quotation Sent</option>
              <option value="negotiation" {{ request('status') === 'negotiation' ? 'selected' : '' }}>5. Subsidy /
                Net-Metering</option>
              <option value="won" {{ request('status') === 'won' ? 'selected' : '' }}>6. Commissioned & Handed Over</option>
              <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>7. Lost / Drop</option>
            </select>

            <!-- Solar Executive Assignee Filter -->
            <select name="developer_id" id="kanban-filter-developer"
              onchange="document.getElementById('kanbanFilterForm').submit()"
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
            @if(request()->anyFilled(['search', 'city', 'area', 'status', 'priority', 'developer_id']))
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
  @include('admin.leads.partials.import-modal')

  <!-- Modal: Create Solar Lead Multi-Step Wizard -->
  @include('admin.leads.partials.create-modal')

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

    const KANBAN_CITY_AREA_MAP = @json($cityAreaMap ?? []);
    const KANBAN_ALL_AREAS = @json($areas ?? []);

    window.handleKanbanCityChange = function (selectedCity) {
      const areaSelect = document.getElementById('kanban-filter-area');
      if (areaSelect) {
        let areasList = [];
        if (selectedCity && KANBAN_CITY_AREA_MAP[selectedCity]) {
          areasList = KANBAN_CITY_AREA_MAP[selectedCity];
        } else if (!selectedCity) {
          areasList = KANBAN_ALL_AREAS;
        }

        areaSelect.innerHTML = `<option value="">📍 All Areas (${areasList.length})</option>`;
        areasList.forEach(ar => {
          const opt = document.createElement('option');
          opt.value = ar;
          opt.textContent = ar;
          areaSelect.appendChild(opt);
        });
      }

      document.getElementById('kanbanFilterForm').submit();
    };
  </script>
@endsection
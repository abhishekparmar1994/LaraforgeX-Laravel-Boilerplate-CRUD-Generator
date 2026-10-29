<script>
/**
 * Leads AJAX Server-Side DataTable Engine
 */
(function ($) {
  'use strict';

  let leadsState = { page: 1, perPage: 10, sortBy: 'created_at', sortDir: 'desc', search: '', city: '', area: '', industry: '', hasWebsite: '', status: '', priority: '', developerId: '', isLoading: false };

  /**
   * Purpose: Fetch leads from server via AJAX and update table UI.
   * Return: Promise<void>
   * Side Effects: DOM update, AJAX network call.
   */
  async function fetchLeads() {
    if (leadsState.isLoading) return;
    leadsState.isLoading = true;
    $('#leads-loading-bar').removeClass('hidden');

    try {
      const params = {
        format: 'json',
        page: leadsState.page,
        per_page: leadsState.perPage,
        sort_by: leadsState.sortBy,
        sort_dir: leadsState.sortDir
      };

      if (leadsState.search) params.search = leadsState.search;
      if (leadsState.city) params.city = leadsState.city;
      if (leadsState.area) params.area = leadsState.area;
      if (leadsState.industry) params.industry = leadsState.industry;
      if (leadsState.hasWebsite) params.has_website = leadsState.hasWebsite;
      if (leadsState.status) params.status = leadsState.status;
      if (leadsState.priority) params.priority = leadsState.priority;
      if (leadsState.developerId) params.developer_id = leadsState.developerId;

      const res = await axios.get('/admin/leads', { baseURL: '', params });
      if (res.data?.success) {
        renderLeadsTable(res.data.data, res.data.pagination);
      }
    } catch (err) {
      console.error('Leads fetch error:', err);
      if (window.showToast) window.showToast('error', 'Failed to load leads.');
    } finally {
      leadsState.isLoading = false;
      $('#leads-loading-bar').addClass('hidden');
    }
  }

  /**
   * Purpose: Render table body and pagination controls from API payload.
   * Parameters: leads (Array), meta (Object)
   */
  function renderLeadsTable(leads, meta) {
    const $tbody = $('#leads-table-body').empty();
    $('#leads-total-badge').text(Number(meta.total).toLocaleString() + ' Total Leads');
    $('#leads-summary-text, #leads-pagination-info').html(
      `Showing <span class="font-bold text-slate-800">${meta.from || 0}</span> to <span class="font-bold text-slate-800">${meta.to || 0}</span> of <span class="font-bold text-slate-800">${Number(meta.total).toLocaleString()}</span> entries`
    );

    if (!leads || !leads.length) {
      $tbody.html(`<tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">
        <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
        <p class="font-bold text-sm">No solar leads found</p>
      </td></tr>`);
      $('#leads-pagination-links').empty();
      return;
    }

    const rows = leads.map((lead, idx) => buildLeadRowHtml(lead, (meta.current_page - 1) * meta.per_page + idx + 1)).join('');
    $tbody.html(rows);
    if (typeof renderCardsGrid === 'function') renderCardsGrid(leads);
    renderPaginationButtons(meta.current_page, meta.last_page);
  }

  /**
   * Purpose: Generate row HTML for a single lead record.
   * Parameters: lead (Object), index (number)
   * Return: string (HTML)
   */
  function buildLeadRowHtml(lead, index) {
    const rawPhone = String(lead.phone || '').replace(/\D/g, '');
    const waPhone = rawPhone.length === 10 ? '91' + rawPhone : rawPhone;

    const phoneHtml = lead.phone && lead.phone !== 'N/A'
      ? `<div class="flex items-center gap-1.5 font-bold text-slate-900"><a href="tel:${lead.phone}" class="hover:text-brand-600">${lead.phone}</a>${waPhone ? `<a href="https://wa.me/${waPhone}" target="_blank" rel="noopener" class="text-emerald-500 hover:text-emerald-600 transition" title="Chat on WhatsApp"><i class="fa-brands fa-whatsapp text-sm"></i></a>` : ''}</div>`
      : `<span class="text-slate-300 font-semibold italic">N/A</span>`;

    return `<tr class="hover:bg-slate-50/80 transition">
      <td class="px-3.5 py-3.5 text-center font-bold text-slate-400">${index}</td>
      <td class="px-4 py-3.5"><a href="/admin/leads/${lead.id}" class="font-bold text-slate-900 hover:text-brand-600 block text-xs">${escapeHtml(lead.company_name)}</a><span class="text-[11px] text-slate-400 block mt-0.5">${escapeHtml(lead.contact_person || 'Owner')} ${lead.email && lead.email !== 'N/A' ? '• ' + escapeHtml(lead.email) : ''}</span></td>
      <td class="px-4 py-3.5 whitespace-nowrap">${phoneHtml}</td>
      <td class="px-4 py-3.5 text-slate-600">${escapeHtml(lead.area_locality || lead.location || 'Vadodara')}</td>
      <td class="px-4 py-3.5 text-right whitespace-nowrap"><a href="/admin/leads/${lead.id}" class="p-1.5 text-slate-400 hover:text-brand-600 rounded-lg hover:bg-slate-100 transition inline-block" title="View 360 Profile"><i class="fa-solid fa-arrow-right text-xs"></i></a></td>
    </tr>`;
  }

  /**
   * Purpose: Render pagination buttons with previous, next, and active states.
   * Parameters: current (number), last (number)
   */
  function renderPaginationButtons(current, last) {
    const $wrap = $('#leads-pagination-links').empty();
    if (last <= 1) return;

    const prevDisabled = current <= 1 ? 'opacity-40 pointer-events-none' : '';
    $wrap.append(`<button type="button" data-page="${current - 1}" class="dt-page-btn px-2.5 py-1 text-xs font-bold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition ${prevDisabled}">‹ Prev</button>`);

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let p = start; p <= end; p++) {
      const active = p === current ? 'bg-brand-600 text-white border-brand-600 font-extrabold' : 'bg-white text-slate-700 hover:bg-slate-50 border-slate-200';
      $wrap.append(`<button type="button" data-page="${p}" class="dt-page-btn px-3 py-1 text-xs rounded-lg border transition ${active}">${p}</button>`);
    }

    const nextDisabled = current >= last ? 'opacity-40 pointer-events-none' : '';
    $wrap.append(`<button type="button" data-page="${current + 1}" class="dt-page-btn px-2.5 py-1 text-xs font-bold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition ${nextDisabled}">Next ›</button>`);
  }

  /**
   * Helper: Escape strings for safe HTML attribute and text injection.
   */
  function escapeHtml(str) {
    return String(str || '').replace(/[&<>"']/g, m => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' })[m]);
  }

  const SORT_SVGS = {
    none: '<svg class="w-2.5 h-2.5 text-slate-300" viewBox="0 0 320 512" fill="currentColor"><path d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8L32 224c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8l256 0c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/></svg>',
    asc: '<svg class="w-2.5 h-2.5 text-brand-600" viewBox="0 0 320 512" fill="currentColor"><path d="M182.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8l256 0c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>',
    desc: '<svg class="w-2.5 h-2.5 text-brand-600" viewBox="0 0 320 512" fill="currentColor"><path d="M182.6 470.6c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8l256 0c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128z"/></svg>'
  };

  /**
   * Purpose: Sort click handler exposed to global window.
   * Parameters: column (string)
   * Return: void
   * Side Effects: Updates leadsState sort state, updates header icons, and triggers fetchLeads.
   */
  window.handleLeadsSort = function (column) {
    leadsState.sortDir = (leadsState.sortBy === column && leadsState.sortDir === 'asc') ? 'desc' : 'asc';
    leadsState.sortBy = column;
    leadsState.page = 1;

    $('.dt-sort-wrap').html(SORT_SVGS.none);
    const activeSvg = leadsState.sortDir === 'asc' ? SORT_SVGS.asc : SORT_SVGS.desc;
    $('#sort-wrap-' + column).html(activeSvg);

    fetchLeads();
  };

  // Event bindings
  let searchDebounce;

  /**
   * Purpose: Sync search inputs, update state, and trigger debounced fetch.
   * Parameters: val (string)
   */
  function handleSearchInput(query) {
    const val = (query || '').trim();
    leadsState.search = val;
    leadsState.page = 1;
    $('#filter-search, #dt-quick-search').val(val);
    $('#btn-clear-search, #dt-quick-clear').toggleClass('hidden', !val);

    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => {
      fetchLeads();
    }, 280);
  }

  $('#filter-search, #dt-quick-search').on('input', function () {
    handleSearchInput($(this).val());
  });

  $('#filter-search, #dt-quick-search').on('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      clearTimeout(searchDebounce);
      fetchLeads();
    }
  });

  $('#btn-clear-search, #dt-quick-clear').on('click', function () {
    handleSearchInput('');
    fetchLeads();
  });

  /**
   * Purpose: Sync per-page selectors and reload leads table.
   * Parameters: val (number|string)
   */
  function handlePerPageChange(val) {
    leadsState.perPage = parseInt(val, 10) || 10;
    leadsState.page = 1;
    $('#filter-per-page, #dt-page-size').val(leadsState.perPage);
    fetchLeads();
  }

  $('#filter-per-page, #dt-page-size').on('change', function () {
    handlePerPageChange($(this).val());
  });

  $('#filter-city, #filter-area, #filter-industry, #filter-status, #filter-priority, #filter-developer').on('change', function () {
    leadsState.city = $('#filter-city').val();
    leadsState.area = $('#filter-area').val();
    leadsState.industry = $('#filter-industry').val();
    leadsState.status = $('#filter-status').val();
    leadsState.priority = $('#filter-priority').val();
    leadsState.developerId = $('#filter-developer').val();
    leadsState.page = 1;
    fetchLeads();
  });

  $(document).on('click', '.dt-page-btn', function () {
    const targetPage = parseInt($(this).data('page'), 10);
    if (targetPage && targetPage !== leadsState.page) {
      leadsState.page = targetPage;
      fetchLeads();
    }
  });

  $('#btn-clear-filters').on('click', function () {
    $('#filter-search, #dt-quick-search').val('');
    $('#btn-clear-search, #dt-quick-clear').addClass('hidden');
    $('#filter-city, #filter-area, #filter-industry, #filter-status, #filter-priority, #filter-developer').val('');
    $('#filter-per-page, #dt-page-size').val('10');
    leadsState = { page: 1, perPage: 10, sortBy: 'created_at', sortDir: 'desc', search: '', city: '', area: '', industry: '', hasWebsite: '', status: '', priority: '', developerId: '', isLoading: false };
    $('.dt-sort-wrap').html(SORT_SVGS.none);
    fetchLeads();
  });

  // Modal & View Mode helpers
  window.switchLeadsView = function(mode) {
    if (mode === 'card') {
      $('#leads-cards-view').removeClass('hidden');
      $('#leads-table-container').addClass('hidden');
      $('#btn-view-card').removeClass('text-slate-600 hover:text-slate-900').addClass('bg-white text-emerald-700 shadow-2xs');
      $('#btn-view-table').removeClass('bg-white text-emerald-700 shadow-2xs').addClass('text-slate-600 hover:text-slate-900');
    } else {
      $('#leads-cards-view').addClass('hidden');
      $('#leads-table-container').removeClass('hidden');
      $('#btn-view-table').removeClass('text-slate-600 hover:text-slate-900').addClass('bg-white text-emerald-700 shadow-2xs');
      $('#btn-view-card').removeClass('bg-white text-emerald-700 shadow-2xs').addClass('text-slate-600 hover:text-slate-900');
    }
  };

  window.openImportModal = () => $('#modal-import-leads').removeClass('hidden');
  window.closeImportModal = () => $('#modal-import-leads').addClass('hidden');
  window.openCreateLeadModal = () => $('#modal-create-lead').removeClass('hidden');
  window.closeCreateLeadModal = () => $('#modal-create-lead').addClass('hidden');

  // Pre-populate initial pagination buttons
  const initialCurrent = {{ $leads->currentPage() }};
  const initialLast = {{ $leads->lastPage() }};
  renderPaginationButtons(initialCurrent, initialLast);
})(jQuery);
</script>

@include('admin.leads.partials.card-scripts')


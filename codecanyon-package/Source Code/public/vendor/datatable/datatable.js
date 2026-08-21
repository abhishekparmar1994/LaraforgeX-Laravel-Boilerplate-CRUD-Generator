/**
 * datatable.js — AdminTable (LaraforgeX Universal DataTable)
 * Version  : 2.0.0
 * ──────────────────────────────────────────────────────────────
 * Fully responsive, zero-dependency client-side data table.
 * Works in any portal (Admin, Teacher, Student) or plain HTML.
 *
 * RESPONSIVE COLUMN SYSTEM
 * ────────────────────────
 * Each column definition accepts an optional `responsive` key:
 *
 *   responsive: 'always'  → shown on all screen sizes  (default)
 *   responsive: 'sm'      → hidden below 640 px
 *   responsive: 'md'      → hidden below 768 px
 *   responsive: 'lg'      → hidden below 1024 px
 *   responsive: 'xl'      → hidden below 1280 px
 *
 * The component auto-injects a scoped <style> block using nth-child
 * selectors so any row renderer <td> order is respected automatically.
 * No Tailwind responsive classes are needed in the rowFn.
 *
 * USAGE
 * ─────
 *   const table = new AdminTable({
 *     container : '#my-table',
 *     columns   : [
 *       { key: 'name',       label: 'Name',       sortable: true                   },
 *       { key: 'email',      label: 'Email',       sortable: true,  responsive:'md' },
 *       { key: 'status',     label: 'Status',      sortable: true,  responsive:'sm' },
 *       { key: 'last_login', label: 'Last Login',  sortable: true,  responsive:'lg' },
 *       { key: 'actions',    label: '',            sortable: false, class:'text-right' },
 *     ],
 *     fetch   : async () => { const r = await axios.get('/api/v1/resource'); return r.data.data; },
 *     row     : (item) => `<tr>...</tr>`,
 *     perPage : 10,
 *   });
 *   table.load();    // Initial data load
 *   table.reload();  // Re-fetch after mutations
 *
 * PORTALS
 * ───────
 *   Admin  (Laravel Blade) : <script src="{{ asset('vendor/datatable/datatable.js') }}">
 *   Student / Teacher      : <script src="/vendor/datatable/datatable.js">
 *   Nuxt 4                 : useHead({ script:[{ src:'/vendor/datatable/datatable.js' }] })
 */

/* global window, document */

(function (global) {
  'use strict';

  /* Unique ID counter for scoped CSS */
  var _instanceCount = 0;

  /* Responsive breakpoints map (px) */
  var BP = { sm: 640, md: 768, lg: 1024, xl: 1280 };

  /* ────────────────────────────────────────────────────────────── */
  /*  CONSTRUCTOR                                                   */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * AdminTable constructor.
   * @param {object} cfg
   * @param {string}   cfg.container  CSS selector of mount element
   * @param {Array}    cfg.columns    Column definition array
   * @param {Function} cfg.fetch      Async fn returning plain array
   * @param {Function} cfg.row        Row renderer fn: (item) => '<tr>…</tr>'
   * @param {number}  [cfg.perPage=10] Default rows per page
   */
  function AdminTable(cfg) {
    _instanceCount++;
    this._uid     = 'dt-' + _instanceCount;
    this.el       = document.querySelector(cfg.container);
    this.columns  = cfg.columns  || [];
    this.fetchFn  = cfg.fetch;
    this.rowFn    = cfg.row;
    this.perPage  = cfg.perPage  || 10;

    /* Internal state */
    this.data     = [];
    this.filtered = [];
    this.page     = 1;
    this.sortKey  = null;
    this.sortDir  = 'asc';
    this.query    = '';

    if (!this.el) {
      console.error('[AdminTable] Mount point not found:', cfg.container);
      return;
    }

    this._injectStyles();
    this._build();
  }

  /* ────────────────────────────────────────────────────────────── */
  /*  RESPONSIVE CSS INJECTION                                      */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Inject a scoped <style> element that hides low-priority columns
   * at appropriate breakpoints using CSS nth-child selectors.
   * This approach is row-renderer-agnostic — the caller's <td> order
   * just needs to match the columns array order.
   */
  AdminTable.prototype._injectStyles = function () {
    var uid  = this._uid;
    var cols = this.columns;
    var rules = '';

    cols.forEach(function (col, idx) {
      var bp = BP[col.responsive];
      if (!bp) return; /* 'always' or undefined → always visible */

      /* Media query: hide th + td at column position (1-based) below bp */
      rules +=
        '@media (max-width: ' + (bp - 1) + 'px) {' +
          '.' + uid + ' thead tr th:nth-child(' + (idx + 1) + '),' +
          '.' + uid + ' tbody tr td:nth-child(' + (idx + 1) + ') {' +
            'display: none;' +
          '}' +
        '}';
    });

    /* Shared base styles for all AdminTable instances */
    rules +=
      /* Scroll hint gradient on narrow viewports */
      '.' + uid + '-wrap { position: relative; }' +
      '.' + uid + '-wrap::after {' +
        'content: "";' +
        'position: absolute; top: 0; right: 0; bottom: 0;' +
        'width: 24px;' +
        'background: linear-gradient(to right, transparent, rgba(255,255,255,0.9));' +
        'pointer-events: none;' +
        'border-radius: 0 1rem 1rem 0;' +
        'opacity: 0;' +
        'transition: opacity 0.2s;' +
      '}' +
      '.' + uid + '-scrollable::after { opacity: 1; }' +
      /* Compact cell padding on mobile */
      '@media (max-width: 639px) {' +
        '.' + uid + ' thead th { padding: 10px 12px; font-size: 10px; }' +
        '.' + uid + ' tbody td { padding: 10px 12px; font-size: 12px; }' +
      '}';

    var styleEl    = document.createElement('style');
    styleEl.id     = uid + '-styles';
    styleEl.textContent = rules;
    document.head.appendChild(styleEl);
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  DOM SCAFFOLD                                                  */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Build the complete HTML scaffold once and bind all events.
   */
  AdminTable.prototype._build = function () {
    var uid     = this._uid;
    var colSpan = this.columns.length;

    this.el.innerHTML =
      /* ── Controls row (Show N / Search) ── */
      '<div class="flex flex-col xs:flex-row xs:items-center justify-between gap-3 mb-4">' +
        '<div class="flex items-center gap-2">' +
          '<label class="text-xs font-semibold text-slate-500 whitespace-nowrap">Show</label>' +
          '<select class="dt-per-page text-xs border border-slate-200 rounded-lg px-2.5 py-1.5 bg-white text-slate-700 focus:outline-none focus:border-brand-500 transition cursor-pointer">' +
            '<option value="10">10</option>' +
            '<option value="25">25</option>' +
            '<option value="50">50</option>' +
          '</select>' +
          '<span class="text-xs font-semibold text-slate-500 whitespace-nowrap">entries</span>' +
        '</div>' +
        '<div class="relative w-full xs:w-auto">' +
          '<i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[11px] pointer-events-none"></i>' +
          '<input type="search" class="dt-search w-full xs:w-52 sm:w-64 pl-8 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brand-500 transition" placeholder="Search\u2026">' +
        '</div>' +
      '</div>' +

      /* ── Table wrapper with scroll hint ── */
      '<div class="' + uid + '-wrap bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">' +
        '<div class="' + uid + '-scroll overflow-x-auto -webkit-overflow-scrolling-touch">' +
          '<table class="' + uid + ' w-full text-left border-collapse text-sm" style="min-width:480px">' +
            '<thead>' +
              '<tr class="bg-slate-50 border-b border-slate-200">' +
                this._buildHeaders() +
              '</tr>' +
            '</thead>' +
            '<tbody class="dt-tbody divide-y divide-slate-100 text-slate-700">' +
            '</tbody>' +
          '</table>' +
        '</div>' +
      '</div>' +

      /* ── Footer row (info / pagination) ── */
      '<div class="flex flex-col xs:flex-row xs:items-center justify-between gap-3 mt-4">' +
        '<span class="dt-info text-xs text-slate-500 font-medium order-2 xs:order-1"></span>' +
        '<div class="dt-pagination flex items-center gap-1 flex-wrap order-1 xs:order-2"></div>' +
      '</div>';

    this._showLoading();
    this._bindEvents();
    this._bindScrollHint();
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  HEADER BUILDER                                                */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Generate <th> HTML for each column.
   * @returns {string}
   */
  AdminTable.prototype._buildHeaders = function () {
    return this.columns.map(function (col) {
      var sortable = col.sortable !== false;
      var icon     = sortable
        ? '<i class="fa-solid fa-sort dt-sort-icon ml-1.5 text-[10px] text-slate-300" data-key="' + col.key + '"></i>'
        : '';
      var cursor   = sortable
        ? 'cursor-pointer select-none hover:bg-slate-100 hover:text-slate-600 active:bg-slate-200 transition'
        : '';

      return (
        '<th class="px-5 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap ' +
          cursor + ' ' + (col.class || '') + '" ' +
          (sortable ? 'data-sort="' + col.key + '"' : '') + '>' +
          col.label + icon +
        '</th>'
      );
    }).join('');
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  SCROLL HINT                                                   */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Show/hide the right-edge scroll hint gradient based on whether
   * the scroll container is currently at its rightmost position.
   */
  AdminTable.prototype._bindScrollHint = function () {
    var uid       = this._uid;
    var scrollEl  = this.el.querySelector('.' + uid + '-scroll');
    var wrapEl    = this.el.querySelector('.' + uid + '-wrap');
    if (!scrollEl || !wrapEl) return;

    function update() {
      var atEnd = scrollEl.scrollLeft + scrollEl.clientWidth >= scrollEl.scrollWidth - 4;
      if (atEnd) {
        wrapEl.classList.remove(uid + '-scrollable');
      } else {
        wrapEl.classList.add(uid + '-scrollable');
      }
    }

    scrollEl.addEventListener('scroll', update, { passive: true });
    /* Re-check on window resize */
    window.addEventListener('resize', update, { passive: true });
    /* Initial check after next paint */
    setTimeout(update, 100);
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  EVENT BINDINGS                                                */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Bind per-page selector, search input, and sort header events.
   */
  AdminTable.prototype._bindEvents = function () {
    var self = this;

    /* Per-page selector */
    this.el.querySelector('.dt-per-page').addEventListener('change', function (e) {
      self.perPage = parseInt(e.target.value, 10);
      self.page    = 1;
      self._render();
    });

    /* Search — debounced 200 ms */
    var _timer;
    this.el.querySelector('.dt-search').addEventListener('input', function (e) {
      clearTimeout(_timer);
      _timer = setTimeout(function () {
        self.query = e.target.value.toLowerCase().trim();
        self.page  = 1;
        self._applyFilter();
        self._render();
      }, 200);
    });

    /* Sort header clicks — delegated on <thead> */
    this.el.querySelector('thead').addEventListener('click', function (e) {
      var th = e.target.closest('[data-sort]');
      if (!th) return;
      var key      = th.getAttribute('data-sort');
      self.sortDir = (self.sortKey === key && self.sortDir === 'asc') ? 'desc' : 'asc';
      self.sortKey = key;
      self.page    = 1;
      self._applySort();
      self._render();
      self._refreshSortIcons();
    });
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  PUBLIC API                                                    */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Fetch data and render the table.
   * Shows a loading row while awaiting the fetch promise.
   * @returns {Promise<void>}
   */
  AdminTable.prototype.load = function () {
    var self = this;
    self._showLoading();

    return Promise.resolve()
      .then(function () { return self.fetchFn(); })
      .then(function (data) {
        self._rawData = Array.isArray(data) ? data.slice() : [];
        self.data     = Array.isArray(data) ? data : [];
        self._applyFilter();
        self._render();
        /* Re-trigger scroll hint after data renders */
        var scrollEl = self.el.querySelector('.' + self._uid + '-scroll');
        if (scrollEl) scrollEl.dispatchEvent(new Event('scroll'));
      })
      .catch(function (err) {
        self._showError();
        if (typeof window.handleAjaxError === 'function') {
          window.handleAjaxError(err);
        } else {
          console.error('[AdminTable] fetch error:', err);
        }
      });
  };

  /**
   * Re-fetch from source and re-render.
   * Call after any CRUD mutation (create / update / delete).
   * @returns {Promise<void>}
   */
  AdminTable.prototype.reload = function () {
    return this.load();
  };

  /**
   * Override the displayed rows with an externally filtered subset.
   * Called by filter bars — does NOT re-fetch from the server.
   * Pass null to restore the full original dataset.
   * @param {Array|null} rows - Pre-filtered row array, or null to reset
   */
  AdminTable.prototype.setData = function (rows) {
    this.data     = Array.isArray(rows) ? rows : (this._rawData || []);
    this.page     = 1;
    this.query    = '';
    var searchEl  = this.el.querySelector('.dt-search');
    if (searchEl) searchEl.value = '';
    this._applyFilter();
    this._render();
  };

  /**
   * Return the raw (unfiltered) dataset cached on last load.
   * @returns {Array}
   */
  AdminTable.prototype.getRawData = function () {
    return this._rawData || [];
  };


  /**
   * Destroy the table — remove injected styles and clear the mount point.
   * Useful in SPA teardown (e.g., Vue/Nuxt beforeUnmount).
   */
  AdminTable.prototype.destroy = function () {
    var styleEl = document.getElementById(this._uid + '-styles');
    if (styleEl) styleEl.remove();
    this.el.innerHTML = '';
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  DATA OPERATIONS                                               */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Filter raw data against the current search query.
   * JSON.stringify ensures nested fields (e.g., roles array) are searched.
   */
  AdminTable.prototype._applyFilter = function () {
    var self = this;
    self.filtered = self.query
      ? self.data.filter(function (row) {
          return JSON.stringify(row).toLowerCase().indexOf(self.query) !== -1;
        })
      : self.data.slice();

    if (self.sortKey) self._applySort();
  };

  /**
   * Sort the filtered array by sortKey / sortDir.
   * String-based locale-aware comparison (handles numbers & dates as strings).
   */
  AdminTable.prototype._applySort = function () {
    var self = this;
    self.filtered.sort(function (a, b) {
      var av = String(a[self.sortKey] != null ? a[self.sortKey] : '').toLowerCase();
      var bv = String(b[self.sortKey] != null ? b[self.sortKey] : '').toLowerCase();
      if (av < bv) return self.sortDir === 'asc' ? -1 : 1;
      if (av > bv) return self.sortDir === 'asc' ?  1 : -1;
      return 0;
    });
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  RENDER                                                        */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Slice the filtered set to the current page and render:
   *   - tbody rows
   *   - info label (showing X–Y of Z records)
   *   - pagination controls
   */
  AdminTable.prototype._render = function () {
    var self    = this;
    var total   = self.filtered.length;
    var pages   = Math.max(1, Math.ceil(total / self.perPage));
    self.page   = Math.min(self.page, pages);
    var start   = (self.page - 1) * self.perPage;
    var slice   = self.filtered.slice(start, start + self.perPage);
    var end     = Math.min(start + self.perPage, total);
    var colSpan = self.columns.length;

    /* Rows */
    var tbody = self.el.querySelector('.dt-tbody');
    tbody.innerHTML = slice.length === 0
      ? '<tr><td colspan="' + colSpan + '" class="text-center py-12 text-slate-400 italic text-sm">No records found.</td></tr>'
      : slice.map(function (row) { return self.rowFn(row); }).join('');

    /* Info label */
    self.el.querySelector('.dt-info').textContent = total === 0
      ? 'No records'
      : 'Showing ' + (start + 1) + '\u2013' + end + ' of ' + total + ' record' + (total !== 1 ? 's' : '');

    /* Pagination */
    self._renderPagination(pages);
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  PAGINATION                                                    */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Build smart pagination with ellipsis and prev/next arrows.
   * Touch-friendly button sizing (min 32px tap target).
   * @param {number} pages - Total page count
   */
  AdminTable.prototype._renderPagination = function (pages) {
    var self = this;
    var el   = self.el.querySelector('.dt-pagination');
    if (pages <= 1) { el.innerHTML = ''; return; }

    var BASE     = 'min-w-[32px] h-8 px-2.5 rounded-lg text-xs font-semibold transition border inline-flex items-center justify-center';
    var DISABLED = BASE + ' border-slate-200 text-slate-300 cursor-not-allowed bg-white';
    var ACTIVE   = BASE + ' border-brand-500 bg-brand-600 text-white shadow-sm';
    var IDLE     = BASE + ' border-slate-200 bg-white text-slate-600 hover:bg-brand-50 hover:border-brand-300 active:bg-brand-100';

    function mkBtn(label, p, active, disabled) {
      var cls = disabled ? DISABLED : (active ? ACTIVE : IDLE);
      return '<button class="' + cls + '" data-page="' + p + '"' + (disabled ? ' disabled' : '') + '>' + label + '</button>';
    }

    var RANGE = 2;
    var from  = Math.max(1, self.page - RANGE);
    var to    = Math.min(pages, self.page + RANGE);
    var html  = mkBtn('\u2039', self.page - 1, false, self.page === 1);

    if (from > 1) {
      html += mkBtn('1', 1, false, false);
      if (from > 2) html += '<span class="px-1 text-slate-300 text-sm">\u2026</span>';
    }
    for (var p = from; p <= to; p++) {
      html += mkBtn(p, p, p === self.page, false);
    }
    if (to < pages) {
      if (to < pages - 1) html += '<span class="px-1 text-slate-300 text-sm">\u2026</span>';
      html += mkBtn(pages, pages, false, false);
    }
    html += mkBtn('\u203a', self.page + 1, false, self.page === pages);

    el.innerHTML = html;
    el.querySelectorAll('button[data-page]:not([disabled])').forEach(function (btn) {
      btn.addEventListener('click', function () {
        self.page = parseInt(btn.getAttribute('data-page'), 10);
        self._render();
        /* Scroll table into view on mobile after page change */
        self.el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      });
    });
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  SORT ICON STATE                                               */
  /* ────────────────────────────────────────────────────────────── */

  /**
   * Update all sort icons to reflect the current active sort state.
   * Active column: directional arrow in brand colour.
   * All others:    neutral fa-sort in slate.
   */
  AdminTable.prototype._refreshSortIcons = function () {
    var self = this;
    this.el.querySelectorAll('.dt-sort-icon').forEach(function (icon) {
      if (icon.getAttribute('data-key') === self.sortKey) {
        icon.className = 'fa-solid ' +
          (self.sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') +
          ' dt-sort-icon ml-1.5 text-[10px] text-brand-500';
      } else {
        icon.className = 'fa-solid fa-sort dt-sort-icon ml-1.5 text-[10px] text-slate-300';
      }
    });
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  STATE HELPERS                                                 */
  /* ────────────────────────────────────────────────────────────── */

  /** Render a skeleton layout in the tbody while data is loading. */
  AdminTable.prototype._showLoading = function () {
    var tbody = this.el.querySelector('.dt-tbody');
    if (!tbody) return;

    var colSpan = this.columns.length;
    var html = '';

    for (var r = 0; r < 5; r++) {
      html += '<tr class="border-b border-slate-100">';
      for (var c = 0; c < colSpan; c++) {
        var widthClass = 'w-3/4';
        if (c === 0) widthClass = 'w-1/2';
        else if (c === colSpan - 1) widthClass = 'w-1/4 ml-auto';
        else if (c % 2 === 0) widthClass = 'w-2/3';
        else widthClass = 'w-5/6';

        var content = '';
        if (c === 0 && (this.columns[c].key === 'avatar' || this.columns[c].key === 'preview')) {
          content = '<div class="h-9 w-9 bg-slate-200 rounded-lg animate-pulse"></div>';
        } else {
          content = '<div class="h-4 bg-slate-200 rounded ' + widthClass + ' animate-pulse"></div>';
        }

        html += '<td class="px-5 py-4">' + content + '</td>';
      }
      html += '</tr>';
    }

    tbody.innerHTML = html;
  };

  /** Render a friendly error row in the tbody on fetch failure. */
  AdminTable.prototype._showError = function () {
    var tbody = this.el.querySelector('.dt-tbody');
    if (!tbody) return;
    tbody.innerHTML =
      '<tr><td colspan="' + this.columns.length + '" class="text-center py-10 text-rose-500 text-sm font-semibold">' +
        '<i class="fa-solid fa-circle-exclamation mr-2"></i>Failed to load data. Please try again.' +
      '</td></tr>';
  };

  /* ────────────────────────────────────────────────────────────── */
  /*  EXPORT                                                        */
  /* ────────────────────────────────────────────────────────────── */

  global.AdminTable = AdminTable;

}(typeof window !== 'undefined' ? window : this));

@extends('admin.layouts.app')

@section('title', "LaraforgeX — Manage Table {$table}")

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition" data-i18n="dashboard">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/database-manager" class="hover:text-brand-600 transition" data-i18n="database_manager">Database
      Studio</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Manage {{ $table }}</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-6 font-sans w-full relative">

    <!-- Header Hero Banner -->
    <div
      class="theme-hero-banner bg-gradient-to-r from-brand-900 via-brand-700 to-indigo-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
      <div
        class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none">
      </div>
      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-brand-200 text-xs font-bold uppercase tracking-widest mb-1">
            <i class="fa-solid fa-table text-amber-300"></i> Table Schema & Data Manager
          </div>
          <h1 class="text-2xl font-extrabold tracking-tight font-mono">Table: {{ $table }}</h1>
          <p class="text-sm text-brand-100 mt-1 max-w-2xl">
            Inspect column types, configure indexes, manage fields in bulk, view foreign key constraints, and browse live
            records.
          </p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
          <a href="/admin/database-manager"
            class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/20 inline-flex items-center gap-2 no-underline">
            <i class="fa-solid fa-arrow-left text-xs"></i> All Tables
          </a>
          <button type="button" id="btn-truncate-table"
            class="px-3.5 py-2.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-200 font-bold text-xs transition border border-amber-400/30 inline-flex items-center gap-1.5 cursor-pointer">
            <i class="fa-solid fa-eraser text-xs"></i> Truncate
          </button>
          <button type="button" id="btn-drop-table"
            class="px-3.5 py-2.5 rounded-xl bg-rose-600/30 hover:bg-rose-600/40 text-rose-200 font-bold text-xs transition border border-rose-400/40 inline-flex items-center gap-1.5 cursor-pointer">
            <i class="fa-solid fa-trash-can text-xs"></i> Drop Table
          </button>
        </div>
      </div>
    </div>

    <!-- Sticky Bulk Action Bar for Fields / Columns (Clean White Theme) -->
    <div id="column-bulk-bar"
      class="hidden sticky top-4 z-30 bg-white border border-slate-200 rounded-2xl p-4 shadow-xl text-slate-800 flex items-center justify-between gap-4 transition-all">
      <div class="flex items-center gap-3">
        <div
          class="h-8 w-8 rounded-xl bg-brand-50 border border-brand-200 flex items-center justify-center text-brand-600">
          <i class="fa-solid fa-square-check text-sm"></i>
        </div>
        <span class="text-xs font-bold text-slate-700">
          <span id="bulk-cols-selected-count" class="font-extrabold text-brand-600 text-sm">0</span> fields selected
        </span>
      </div>

      <div class="flex items-center gap-2">
        <button type="button" id="btn-bulk-add-index"
          class="px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs border border-amber-200 transition cursor-pointer inline-flex items-center gap-1.5">
          <i class="fa-solid fa-key text-xs"></i> Add Index on Fields
        </button>

        <button type="button" id="btn-bulk-drop-cols"
          class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs border border-rose-200 transition cursor-pointer inline-flex items-center gap-1.5">
          <i class="fa-solid fa-trash-can text-xs"></i> Drop Selected Fields
        </button>

        <button type="button" id="btn-cancel-col-selection"
          class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs border border-slate-200 transition cursor-pointer">
          Cancel
        </button>
      </div>
    </div>

    <!-- Main Studio Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">

      <!-- Navigation Tabs -->
      <div class="flex items-center gap-2 border-b border-slate-200 text-xs font-bold text-slate-500">
        <button type="button"
          class="tab-btn px-5 py-3 border-b-2 border-brand-500 text-brand-600 flex items-center gap-2 font-bold cursor-pointer"
          data-tab="columns" id="tab-btn-columns">
          <i class="fa-solid fa-columns"></i> Columns / Fields (<span id="count-columns">0</span>)
        </button>
        <button type="button"
          class="tab-btn px-5 py-3 border-b-2 border-transparent hover:text-slate-800 flex items-center gap-2 cursor-pointer"
          data-tab="indexes" id="tab-btn-indexes">
          <i class="fa-solid fa-key"></i> Indexes (<span id="count-indexes">0</span>)
        </button>
        <button type="button"
          class="tab-btn px-5 py-3 border-b-2 border-transparent hover:text-slate-800 flex items-center gap-2 cursor-pointer"
          data-tab="fks" id="tab-btn-fks">
          <i class="fa-solid fa-link"></i> Foreign Keys (<span id="count-fks">0</span>)
        </button>
        <button type="button"
          class="tab-btn px-5 py-3 border-b-2 border-transparent hover:text-slate-800 flex items-center gap-2 cursor-pointer"
          data-tab="data" id="tab-btn-data">
          <i class="fa-solid fa-table-list"></i> Browse Data
        </button>
        <button type="button"
          class="tab-btn px-5 py-3 border-b-2 border-transparent hover:text-slate-800 flex items-center gap-2 cursor-pointer"
          data-tab="sql" id="tab-btn-sql">
          <i class="fa-solid fa-code"></i> DDL & SQL Console
        </button>
      </div>

      <!-- Tab 1: Columns / Fields -->
      <div id="tab-content-columns" class="tab-content space-y-4">
        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr
                class="bg-slate-50 border-b border-slate-200 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                <th class="p-3.5 w-10 text-center">
                  <input type="checkbox" id="cb-select-all-columns"
                    class="h-4 w-4 rounded border-slate-300 text-brand-600 cursor-pointer">
                </th>
                <th class="p-3.5">Field Name</th>
                <th class="p-3.5">Data Type</th>
                <th class="p-3.5 text-center">Nullable</th>
                <th class="p-3.5">Key</th>
                <th class="p-3.5">Default Value</th>
                <th class="p-3.5">Extra / Attributes</th>
                <th class="p-3.5">Comment</th>
                <th class="p-3.5 text-right">Action</th>
              </tr>
            </thead>
            <tbody id="tbody-columns" class="divide-y divide-slate-100 font-mono text-xs text-slate-700">
              <tr>
                <td colspan="9" class="p-6 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin"></i>
                  Loading columns…</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tab 2: Indexes -->
      <div id="tab-content-indexes" class="tab-content hidden space-y-4">
        <div class="flex items-center justify-between">
          <p class="text-xs text-slate-500 font-medium">Index and unique constraints defined on `{{ $table }}`.</p>
          <button type="button" id="btn-open-add-index"
            class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i> Add New Index
          </button>
        </div>

        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr
                class="bg-slate-50 border-b border-slate-200 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                <th class="p-3.5">Index Name</th>
                <th class="p-3.5">Type</th>
                <th class="p-3.5">Target Columns</th>
                <th class="p-3.5 text-right">Cardinality</th>
                <th class="p-3.5 text-right">Action</th>
              </tr>
            </thead>
            <tbody id="tbody-indexes" class="divide-y divide-slate-100 font-mono text-xs text-slate-700">
              <tr>
                <td colspan="5" class="p-6 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin"></i>
                  Loading indexes…</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tab 3: Foreign Keys -->
      <div id="tab-content-fks" class="tab-content hidden space-y-4">
        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr
                class="bg-slate-50 border-b border-slate-200 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                <th class="p-3.5">Constraint Name</th>
                <th class="p-3.5">Local Column</th>
                <th class="p-3.5">Foreign Table</th>
                <th class="p-3.5">Foreign Column</th>
                <th class="p-3.5">On Delete</th>
                <th class="p-3.5">On Update</th>
              </tr>
            </thead>
            <tbody id="tbody-fks" class="divide-y divide-slate-100 font-mono text-xs text-slate-700">
              <tr>
                <td colspan="6" class="p-6 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin"></i>
                  Loading foreign keys…</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tab 4: Browse Data -->
      <div id="tab-content-data" class="tab-content hidden space-y-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="relative w-full sm:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="data-search-input"
              class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500"
              placeholder="Search table rows…">
          </div>
          <span id="data-pagination-info" class="text-xs font-semibold text-slate-500">Loading data…</span>
        </div>

        <div class="border border-slate-200 rounded-xl overflow-x-auto shadow-xs max-h-[500px]">
          <table class="w-full text-left border-collapse text-xs font-mono" id="data-grid-table">
            <!-- Dynamically populated via JS -->
          </table>
        </div>

        <div class="flex items-center justify-between text-xs font-bold text-slate-600">
          <button type="button" id="btn-data-prev"
            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 transition cursor-pointer">
            <i class="fa-solid fa-chevron-left text-[10px]"></i> Previous
          </button>
          <span id="data-current-page-text">Page 1</span>
          <button type="button" id="btn-data-next"
            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 transition cursor-pointer">
            Next <i class="fa-solid fa-chevron-right text-[10px]"></i>
          </button>
        </div>
      </div>

      <!-- Tab 5: DDL & SQL Console -->
      <div id="tab-content-sql" class="tab-content hidden space-y-6">
        <!-- Section A: Interactive SQL Console -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-4">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
              <i class="fa-solid fa-terminal text-brand-600"></i> Interactive Query Console (`{{ $table }}`)
            </h4>
            <span class="text-[11px] text-slate-400 font-mono">Press <b class="text-slate-700">Ctrl + Enter</b> to
              execute</span>
          </div>

          <div class="relative">
            <textarea id="page-sql-editor" rows="4"
              class="w-full bg-slate-900 text-emerald-400 font-mono text-xs p-4 rounded-xl leading-relaxed focus:outline-none focus:ring-2 focus:ring-brand-500 shadow-inner resize-y"
              placeholder="SELECT * FROM {{ $table }} LIMIT 25;"></textarea>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs">
              <button type="button" id="btn-snippet-select-all"
                class="px-2.5 py-1 rounded bg-white hover:bg-slate-100 text-slate-700 font-mono border border-slate-200 text-[11px] cursor-pointer">
                SELECT ALL
              </button>
              <button type="button" id="btn-snippet-explain"
                class="px-2.5 py-1 rounded bg-white hover:bg-slate-100 text-slate-700 font-mono border border-slate-200 text-[11px] cursor-pointer">
                EXPLAIN
              </button>
            </div>
            <button type="button" id="btn-page-sql-exec"
              class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-extrabold text-xs transition shadow-md inline-flex items-center gap-2 cursor-pointer">
              <i class="fa-solid fa-play text-xs"></i> Execute Query
            </button>
          </div>

          <div id="page-sql-output-container"
            class="hidden border border-slate-200 rounded-xl overflow-hidden max-h-72 overflow-auto text-xs font-mono bg-white">
          </div>
        </div>

        <!-- Section B: Raw Create Table DDL SQL -->
        <div class="space-y-2">
          <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-500"></i> Raw CREATE TABLE DDL Definition
          </h4>
          <div class="relative">
            <textarea id="sql-textarea" readonly rows="8"
              class="w-full bg-slate-900 text-emerald-400 font-mono text-xs p-5 rounded-2xl leading-relaxed focus:outline-none select-all resize-none shadow-inner"></textarea>
            <button type="button" id="btn-copy-ddl"
              class="absolute right-4 top-4 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition cursor-pointer">
              <i class="fa-regular fa-copy"></i> Copy DDL SQL
            </button>
          </div>
        </div>
      </div>

    </div>

  </div>

  <!-- Add Index Modal -->
  <div id="modal-add-index" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" id="btn-close-ai-bg"></div>
      <form id="form-add-index"
        class="relative bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 font-sans">
        <div class="flex items-center gap-3">
          <div
            class="h-10 w-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
            <i class="fa-solid fa-key"></i>
          </div>
          <div>
            <h3 class="font-bold text-slate-900 text-base">Add Table Index</h3>
            <p class="text-xs text-slate-400">Create a key or unique constraint on `{{ $table }}`</p>
          </div>
        </div>

        <div class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Index Name *</label>
            <input type="text" id="ai-index-name" required pattern="[a-zA-Z0-9_]+"
              class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500"
              placeholder="e.g. idx_title">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Index Type *</label>
            <select id="ai-index-type"
              class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800">
              <option value="INDEX" selected>INDEX (Standard Key)</option>
              <option value="UNIQUE">UNIQUE (Unique Constraint)</option>
              <option value="FULLTEXT">FULLTEXT (Text Search)</option>
              <option value="PRIMARY">PRIMARY KEY</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Target Columns *</label>
            <div id="ai-columns-checkboxes"
              class="space-y-1.5 max-h-40 overflow-y-auto bg-slate-50 p-3 rounded-lg border border-slate-200">
              <!-- Inserted via JS -->
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-3">
          <button type="button" id="btn-cancel-ai"
            class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
            Cancel
          </button>
          <button type="submit"
            class="px-5 py-2 rounded-lg bg-brand-600 hover:bg-brand-500 text-white font-extrabold text-xs transition shadow-sm shadow-brand-600/20">
            Add Index
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document.ready ? $(document) : $(window)).ready(function () {
      const _tableName = "{{ $table }}";
      let _tableDetails = null;
      let _currentPage = 1;

      // Setup CSRF header for jQuery AJAX
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      loadTableSchema();

      // Check URL params for initial tab
      const params = new URLSearchParams(window.location.search);
      const initialTab = params.get('tab') || 'columns';
      switchTab(initialTab);

      // Event Bindings
      $('.tab-btn').on('click', function () {
        const tab = $(this).data('tab');
        switchTab(tab);
      });

      $('#btn-truncate-table').on('click', function () { confirmTruncateTable(_tableName); });
      $('#btn-drop-table').on('click', function () { confirmDropTable(_tableName); });

      $('#cb-select-all-columns').on('change', function () {
        $('.col-cb').prop('checked', $(this).is(':checked'));
        updateColumnSelectionUI();
      });

      $(document).on('change', '.col-cb', function () {
        updateColumnSelectionUI();
      });

      $('#btn-cancel-col-selection').on('click', clearColumnSelection);

      $('#btn-bulk-add-index').on('click', function () {
        const checked = $('.col-cb:checked').map(function () { return $(this).val(); }).get();
        openAddIndexModal(checked);
      });

      $('#btn-bulk-drop-cols').on('click', executeBulkDropColumns);

      $(document).on('click', '.btn-drop-single-col', function () {
        const col = $(this).data('col');
        confirmSingleDropColumn(col);
      });

      $(document).on('click', '.btn-drop-index', function () {
        const idx = $(this).data('index');
        confirmDropIndex(idx);
      });

      $('#btn-open-add-index').on('click', function () { openAddIndexModal(); });
      $('#btn-close-ai-bg, #btn-cancel-ai').on('click', closeAddIndexModal);

      $('#form-add-index').on('submit', function (e) {
        e.preventDefault();
        submitAddIndex();
      });

      $('#data-search-input').on('keyup', function () {
        loadDataRows(1);
      });

      $('#btn-data-prev').on('click', function () { changeDataPage(-1); });
      $('#btn-data-next').on('click', function () { changeDataPage(1); });

      $('#btn-snippet-select-all').on('click', function () {
        $('#page-sql-editor').val(`SELECT * FROM ${_tableName} LIMIT 25;`);
      });

      $('#btn-snippet-explain').on('click', function () {
        $('#page-sql-editor').val(`EXPLAIN SELECT * FROM ${_tableName};`);
      });

      $('#btn-page-sql-exec').on('click', executePageSqlQuery);

      $('#btn-copy-ddl').on('click', function () {
        navigator.clipboard.writeText($('#sql-textarea').val());
        window.showToast('success', 'SQL copied to clipboard!');
      });

      function loadTableSchema() {
        $.ajax({
          url: `/api/v1/database-manager/${_tableName}`,
          type: 'GET',
          success: function (res) {
            if (res.success) {
              _tableDetails = res.data;

              renderColumns(_tableDetails.columns || []);
              renderIndexes(_tableDetails.indexes || []);
              renderFks(_tableDetails.foreign_keys || []);
              $('#sql-textarea').val(_tableDetails.create_sql || '');
              if (!$('#page-sql-editor').val()) {
                $('#page-sql-editor').val(`SELECT * FROM ${_tableName} LIMIT 25;`);
              }

              $('#count-columns').text((_tableDetails.columns || []).length);
              $('#count-indexes').text((_tableDetails.indexes || []).length);
              $('#count-fks').text((_tableDetails.foreign_keys || []).length);
            }
          },
          error: function (xhr) {
            window.handleAjaxError(xhr, 'Failed to inspect table details.');
          }
        });
      }

      function switchTab(tab) {
        $('.tab-btn').each(function () {
          const t = $(this).data('tab');
          if (t === tab) {
            $(this).attr('class', 'tab-btn px-5 py-3 border-b-2 border-brand-500 text-brand-600 flex items-center gap-2 font-bold cursor-pointer');
            $(`#tab-content-${t}`).removeClass('hidden');
          } else {
            $(this).attr('class', 'tab-btn px-5 py-3 border-b-2 border-transparent hover:text-slate-800 flex items-center gap-2 cursor-pointer');
            $(`#tab-content-${t}`).addClass('hidden');
          }
        });

        if (tab === 'data') {
          loadDataRows(1);
        }
      }

      function renderColumns(cols) {
        const $tbody = $('#tbody-columns');
        $('#cb-select-all-columns').prop('checked', false);
        updateColumnSelectionUI();

        let html = '';
        $.each(cols, function (i, c) {
          html += `
            <tr class="hover:bg-slate-50 transition">
              <td class="p-3.5 text-center">
                <input type="checkbox" value="${c.name}" class="col-cb h-4 w-4 rounded border-slate-300 text-brand-600 cursor-pointer">
              </td>
              <td class="p-3.5 font-bold text-slate-900 font-mono">${c.name}</td>
              <td class="p-3.5 text-slate-600">${c.type}</td>
              <td class="p-3.5 text-center">${c.nullable ? '<span class="text-amber-600 font-bold">YES</span>' : '<span class="text-slate-400">NO</span>'}</td>
              <td class="p-3.5 font-bold ${c.key === 'PRI' ? 'text-rose-600' : 'text-slate-500'}">${c.key || '-'}</td>
              <td class="p-3.5 text-slate-500">${c.default !== null ? c.default : '<span class="text-slate-300 italic">NULL</span>'}</td>
              <td class="p-3.5 text-brand-600 font-semibold">${c.extra || '-'}</td>
              <td class="p-3.5 text-slate-400 italic font-sans">${c.comment || '-'}</td>
              <td class="p-3.5 text-right">
                <button data-col="${c.name}" class="btn-drop-single-col px-2.5 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs transition border border-rose-200 cursor-pointer">
                  <i class="fa-solid fa-trash-can text-[10px]"></i> Drop
                </button>
              </td>
            </tr>
          `;
        });
        $tbody.html(html);
      }

      function updateColumnSelectionUI() {
        const checked = $('.col-cb:checked').map(function () { return $(this).val(); }).get();
        const $bulkBar = $('#column-bulk-bar');

        if (checked.length > 0) {
          $('#bulk-cols-selected-count').text(checked.length);
          $bulkBar.removeClass('hidden');
        } else {
          $bulkBar.addClass('hidden');
        }
      }

      function clearColumnSelection() {
        $('#cb-select-all-columns').prop('checked', false);
        $('.col-cb').prop('checked', false);
        updateColumnSelectionUI();
      }

      function executeBulkDropColumns() {
        const checked = $('.col-cb:checked').map(function () { return $(this).val(); }).get();
        if (checked.length === 0) return;

        Swal.fire({
          title: `Drop ${checked.length} Selected Column(s)?`,
          html: `Are you sure you want to drop column(s) from table <b>${_tableName}</b>?<br><br><span class="font-mono text-xs text-rose-600 font-bold">${checked.join(', ')}</span>`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#be123c',
          confirmButtonText: `Yes, Drop ${checked.length} Column(s)`,
        }).then(function (result) {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/database-manager/${_tableName}/drop-columns`,
              type: 'POST',
              contentType: 'application/json',
              data: JSON.stringify({ columns: checked }),
              success: function (res) {
                if (res.success) {
                  window.showToast('success', res.message);
                  clearColumnSelection();
                  loadTableSchema();
                }
              },
              error: function (xhr) {
                window.handleAjaxError(xhr, 'Failed to drop columns.');
              }
            });
          }
        });
      }

      function confirmSingleDropColumn(columnName) {
        Swal.fire({
          title: `Drop Column '${columnName}'?`,
          text: `Column '${columnName}' and its stored data will be permanently removed from '${_tableName}'!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#be123c',
          confirmButtonText: 'Yes, Drop Column',
        }).then(function (result) {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/database-manager/${_tableName}/drop-columns`,
              type: 'POST',
              contentType: 'application/json',
              data: JSON.stringify({ columns: [columnName] }),
              success: function (res) {
                if (res.success) {
                  window.showToast('success', res.message);
                  loadTableSchema();
                }
              },
              error: function (xhr) {
                window.handleAjaxError(xhr, 'Failed to drop column.');
              }
            });
          }
        });
      }

      function renderIndexes(indexes) {
        const $tbody = $('#tbody-indexes');
        let html = '';
        $.each(indexes, function (i, idx) {
          const colsStr = idx.columns.join(', ');
          html += `
            <tr class="hover:bg-slate-50 transition">
              <td class="p-3.5 font-bold text-slate-900 font-mono">${idx.name}</td>
              <td class="p-3.5"><span class="px-2.5 py-1 rounded-md text-[10px] font-bold ${idx.type === 'PRIMARY' ? 'bg-rose-50 text-rose-600 border border-rose-200' : (idx.unique ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-slate-100 text-slate-600')}">${idx.type}</span></td>
              <td class="p-3.5 font-bold text-slate-700 font-mono">${colsStr}</td>
              <td class="p-3.5 text-right font-mono text-slate-500">${idx.cardinality}</td>
              <td class="p-3.5 text-right">
                <button data-index="${idx.name}" class="btn-drop-index px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs transition border border-rose-200 cursor-pointer">
                  Drop Index
                </button>
              </td>
            </tr>
          `;
        });
        $tbody.html(html);
      }

      function renderFks(fks) {
        const $tbody = $('#tbody-fks');
        if (fks.length === 0) {
          $tbody.html(`<tr><td colspan="6" class="p-6 text-center text-slate-400 italic">No foreign key constraints defined on table \`${_tableName}\`.</td></tr>`);
          return;
        }
        let html = '';
        $.each(fks, function (i, fk) {
          html += `
            <tr class="hover:bg-slate-50 transition">
              <td class="p-3.5 font-bold text-slate-900 font-mono">${fk.constraint_name}</td>
              <td class="p-3.5 text-brand-600 font-bold font-mono">${fk.column}</td>
              <td class="p-3.5 text-slate-700 font-mono">${fk.foreign_table}</td>
              <td class="p-3.5 text-emerald-600 font-bold font-mono">${fk.foreign_column}</td>
              <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-bold text-slate-600">${fk.on_delete}</span></td>
              <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-bold text-slate-600">${fk.on_update}</span></td>
            </tr>
          `;
        });
        $tbody.html(html);
      }

      function loadDataRows(page = 1) {
        _currentPage = page;
        const $grid = $('#data-grid-table');
        const search = $('#data-search-input').val().trim();

        $grid.html(`<tr><td class="p-6 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin"></i> Loading records…</td></tr>`);

        $.ajax({
          url: `/api/v1/database-manager/${_tableName}/data`,
          type: 'GET',
          data: { page: page, per_page: 15, search: search },
          success: function (res) {
            if (res.success) {
              const d = res.data;
              $('#data-pagination-info').text(`Total ${d.total} records (Page ${d.current_page} of ${d.last_page})`);
              $('#data-current-page-text').text(`Page ${d.current_page} of ${d.last_page}`);

              const rows = d.rows || [];
              if (rows.length === 0) {
                $grid.html(`<tr><td class="p-6 text-center text-slate-400 italic">No records found.</td></tr>`);
                return;
              }

              const cols = Object.keys(rows[0]);
              let headerHtml = `<thead class="bg-slate-100 border-b border-slate-200 text-[10px] uppercase font-bold text-slate-500"><tr>`;
              $.each(cols, function (i, c) { headerHtml += `<th class="p-3">${c}</th>`; });
              headerHtml += `</tr></thead>`;

              let bodyHtml = `<tbody class="divide-y divide-slate-100 text-xs">`;
              $.each(rows, function (i, r) {
                bodyHtml += `<tr class="hover:bg-slate-50 transition">`;
                $.each(cols, function (j, c) {
                  const val = r[c];
                  bodyHtml += `<td class="p-3 max-w-xs truncate" title="${val}">${val !== null ? val : '<span class="text-slate-300 font-sans italic">NULL</span>'}</td>`;
                });
                bodyHtml += `</tr>`;
              });
              bodyHtml += `</tbody>`;

              $grid.html(headerHtml + bodyHtml);
            }
          },
          error: function (xhr) {
            window.handleAjaxError(xhr, 'Failed to fetch table rows.');
          }
        });
      }

      function changeDataPage(delta) {
        const newPage = _currentPage + delta;
        if (newPage >= 1) {
          loadDataRows(newPage);
        }
      }

      function openAddIndexModal(preselectedCols = []) {
        if (!_tableDetails) return;
        $('#form-add-index')[0].reset();
        $('#ai-index-name').val(`idx_${_tableName}_` + Math.floor(Math.random() * 100));

        const $container = $('#ai-columns-checkboxes');
        let html = '';
        $.each(_tableDetails.columns || [], function (i, c) {
          const isChecked = $.inArray(c.name, preselectedCols) !== -1 ? 'checked' : '';
          html += `
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
              <input type="checkbox" name="ai_col" value="${c.name}" ${isChecked} class="rounded border-slate-300 text-brand-600">
              <span class="font-mono">${c.name}</span> <span class="text-[10px] text-slate-400">(${c.type})</span>
            </label>
          `;
        });
        $container.html(html);

        $('#modal-add-index').removeClass('hidden');
      }

      function closeAddIndexModal() {
        $('#modal-add-index').addClass('hidden');
      }

      function submitAddIndex() {
        const idxName = $('#ai-index-name').val().trim();
        const idxType = $('#ai-index-type').val();
        const checked = $('input[name="ai_col"]:checked').map(function () { return $(this).val(); }).get();

        if (checked.length === 0) {
          window.showToast('warning', 'Please select at least one column for the index.');
          return;
        }

        $.ajax({
          url: `/api/v1/database-manager/${_tableName}/indexes`,
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({
            index_name: idxName,
            index_type: idxType,
            columns: checked,
          }),
          success: function (res) {
            if (res.success) {
              window.showToast('success', res.message);
              closeAddIndexModal();
              loadTableSchema();
              switchTab('indexes');
            }
          },
          error: function (xhr) {
            window.handleAjaxError(xhr, 'Failed to add index.');
          }
        });
      }

      function confirmDropIndex(indexName) {
        Swal.fire({
          title: `Drop Index '${indexName}'?`,
          text: `Are you sure you want to drop index '${indexName}' from table '${_tableName}'?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#e11d48',
          confirmButtonText: 'Yes, Drop Index',
        }).then(function (result) {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/database-manager/${_tableName}/indexes/${indexName}`,
              type: 'DELETE',
              success: function (res) {
                if (res.success) {
                  window.showToast('success', res.message);
                  loadTableSchema();
                  switchTab('indexes');
                }
              },
              error: function (xhr) {
                window.handleAjaxError(xhr, 'Failed to drop index.');
              }
            });
          }
        });
      }

      function executePageSqlQuery() {
        const sql = $('#page-sql-editor').val().trim();
        if (!sql) {
          window.showToast('warning', 'Please enter a SQL query.');
          return;
        }

        const $output = $('#page-sql-output-container');
        const $btn = $('#btn-page-sql-exec');

        $btn.prop('disabled', true).html(`<i class="fa-solid fa-circle-notch fa-spin"></i> Executing…`);
        $output.removeClass('hidden').html(`<div class="p-6 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin"></i> Running SQL query…</div>`);

        $.ajax({
          url: '/api/v1/database-manager/execute-sql',
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({ sql: sql }),
          success: function (res) {
            $btn.prop('disabled', false).html(`<i class="fa-solid fa-play text-xs"></i> Execute Query`);

            if (res.success) {
              const d = res.data;
              if (d.type === 'READ') {
                const rows = d.rows || [];
                if (rows.length === 0) {
                  $output.html(`<div class="p-4 text-center text-slate-400 italic font-sans">Query executed in ${d.execution_ms} ms. 0 rows returned.</div>`);
                  return;
                }

                const cols = d.columns || Object.keys(rows[0]);
                let headerHtml = `<thead class="bg-slate-100 border-b border-slate-200 text-[10px] uppercase font-bold text-slate-500"><tr>`;
                $.each(cols, function (i, c) { headerHtml += `<th class="p-2.5">${c}</th>`; });
                headerHtml += `</tr></thead>`;

                let bodyHtml = `<tbody class="divide-y divide-slate-100 text-[11px]">`;
                $.each(rows, function (i, r) {
                  bodyHtml += `<tr class="hover:bg-slate-50 transition">`;
                  $.each(cols, function (j, c) {
                    const val = r[c];
                    bodyHtml += `<td class="p-2.5 max-w-xs truncate" title="${val}">${val !== null ? val : '<span class="text-slate-300 font-sans italic">NULL</span>'}</td>`;
                  });
                  bodyHtml += `</tr>`;
                });
                bodyHtml += `</tbody>`;

                $output.html(`<table class="w-full text-left border-collapse">${headerHtml}${bodyHtml}</table>`);
              } else {
                $output.html(`
                  <div class="p-4 bg-emerald-50 text-emerald-800 text-xs font-sans font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>${d.message}</span>
                  </div>
                `);
              }
            }
          },
          error: function (xhr) {
            $btn.prop('disabled', false).html(`<i class="fa-solid fa-play text-xs"></i> Execute Query`);
            const errMsg = xhr.responseJSON?.message || 'Database query error.';
            $output.html(`
              <div class="p-4 bg-rose-50 text-rose-700 text-xs font-mono leading-relaxed">
                <i class="fa-solid fa-circle-exclamation mr-1 text-rose-600"></i> ${errMsg}
              </div>
            `);
          }
        });
      }

      function confirmTruncateTable(tableName) {
        Swal.fire({
          title: `Truncate Table '${tableName}'?`,
          html: `
            <div class="text-left space-y-2.5 font-sans text-xs text-slate-600">
              <p class="font-semibold text-slate-800 text-sm">Are you sure you want to erase all records in table <b class="font-mono text-amber-600">${tableName}</b>?</p>
              <div class="p-2.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs">
                <i class="fa-solid fa-triangle-exclamation mr-1 text-amber-600"></i>
                <b>Warning:</b> All rows will be permanently deleted and auto-increment sequences reset.
              </div>
              <p class="text-[11px] font-bold text-slate-500 pt-1">Please type <span class="font-mono text-slate-900 bg-slate-100 px-1.5 py-0.5 rounded">${tableName}</span> below to confirm:</p>
            </div>
          `,
          input: 'text',
          inputPlaceholder: `Type '${tableName}' to confirm`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d97706',
          confirmButtonText: '<i class="fa-solid fa-eraser"></i> Truncate Table',
          cancelButtonText: 'Cancel',
          inputValidator: (value) => {
            if (value !== tableName) {
              return 'Table name does not match!';
            }
          }
        }).then(function (result) {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/database-manager/${tableName}/truncate`,
              type: 'POST',
              success: function (res) {
                if (res.success) {
                  window.showToast('success', res.message);
                  loadTableSchema();
                }
              },
              error: function (xhr) {
                window.handleAjaxError(xhr, 'Failed to truncate table.');
              }
            });
          }
        });
      }

      function confirmDropTable(tableName) {
        Swal.fire({
          title: `DROP TABLE '${tableName}'?`,
          html: `
            <div class="text-left space-y-2.5 font-sans text-xs text-slate-600">
              <p class="font-semibold text-slate-800 text-sm">You are about to permanently delete table <b class="font-mono text-rose-600">${tableName}</b>!</p>
              <div class="p-2.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs">
                <i class="fa-solid fa-radiation mr-1 text-rose-600"></i>
                <b>Critical Warning:</b> The table structure, all columns, indexes, and records will be deleted forever.
              </div>
              <p class="text-[11px] font-bold text-slate-500 pt-1">Please type <span class="font-mono text-slate-900 bg-slate-100 px-1.5 py-0.5 rounded">${tableName}</span> to confirm drop:</p>
            </div>
          `,
          input: 'text',
          inputPlaceholder: `Type '${tableName}' to confirm`,
          icon: 'error',
          showCancelButton: true,
          confirmButtonColor: '#be123c',
          confirmButtonText: '<i class="fa-solid fa-trash-can"></i> Permanently Drop Table',
          cancelButtonText: 'Cancel',
          inputValidator: (value) => {
            if (value !== tableName) {
              return 'Table name does not match!';
            }
          }
        }).then(function (result) {
          if (result.isConfirmed) {
            $.ajax({
              url: `/api/v1/database-manager/${tableName}`,
              type: 'DELETE',
              success: function (res) {
                if (res.success) {
                  window.showToast('success', res.message);
                  window.location.href = '/admin/database-manager';
                }
              },
              error: function (xhr) {
                window.handleAjaxError(xhr, 'Failed to drop table.');
              }
            });
          }
        });
      }
    });
  </script>
@endsection
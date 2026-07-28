@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Navicat Grade SQL Query Studio')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition" data-i18n="dashboard">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/database-manager" class="hover:text-brand-600 transition" data-i18n="database_manager">Database
      Studio</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Navicat SQL Studio</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-4 font-sans w-full select-none">

    <!-- Header Hero Banner -->
    <div
      class="theme-hero-banner bg-gradient-to-r from-slate-900 via-brand-900 to-indigo-900 rounded-2xl p-5 text-white shadow-xl relative overflow-hidden">
      <div
        class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none">
      </div>
      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-brand-300 text-xs font-bold uppercase tracking-widest mb-1">
            <i class="fa-solid fa-laptop-code text-amber-300"></i> Navicat & HeidiSQL Grade Desktop Studio
          </div>
          <h1 class="text-xl font-extrabold tracking-tight flex items-center gap-2">
            <i class="fa-solid fa-terminal text-emerald-400"></i> Navicat SQL Query Console
          </h1>
          <p class="text-xs text-slate-300 mt-0.5">
            Full-featured SQL Query Editor with connection selectors, query formatter, row selector, and CSV/Excel
            exporter.
          </p>
        </div>
        <div class="shrink-0 flex items-center gap-2">
          <a href="/admin/database-manager"
            class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/20 inline-flex items-center gap-2 no-underline">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Database Studio
          </a>
        </div>
      </div>
    </div>

    <!-- Navicat Top IDE Toolbar -->
    <div
      class="bg-white border border-slate-200/90 rounded-2xl p-3 shadow-sm flex flex-wrap items-center justify-between gap-3 text-xs font-sans relative">

      <!-- Connection & Database Selectors -->
      <div class="flex items-center gap-2.5 flex-wrap">
        <div
          class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono font-bold text-xs">
          <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <i class="fa-solid fa-server text-slate-400 text-[11px]"></i>
          <span>localhost_3306</span>
        </div>

        <div
          class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 font-mono font-bold text-xs">
          <i class="fa-solid fa-database text-brand-600 text-[11px]"></i>
          <span id="active-db-name-label">laraforgex</span>
        </div>
      </div>

      <!-- Action Buttons Bar (Navicat Style) -->
      <div class="flex items-center gap-2 flex-wrap">

        <!-- Run Query -->
        <button type="button" id="btn-navicat-run"
          class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer border-0">
          <i class="fa-solid fa-play text-xs text-emerald-200"></i> Run Query
        </button>

        <!-- Stop Execution -->
        <button type="button" id="btn-navicat-stop"
          class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition border border-slate-200 inline-flex items-center gap-1.5 cursor-pointer">
          <i class="fa-solid fa-square text-rose-500 text-[10px]"></i> Stop
        </button>

        <div class="h-4 w-px bg-slate-200 mx-1"></div>

        <!-- Explain -->
        <button type="button" id="btn-navicat-explain"
          class="px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition cursor-pointer inline-flex items-center gap-1.5">
          <i class="fa-solid fa-sitemap text-indigo-500 text-xs"></i> Explain
        </button>

        <!-- Beautify SQL -->
        <button type="button" id="btn-navicat-beautify"
          class="px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition cursor-pointer inline-flex items-center gap-1.5">
          <i class="fa-solid fa-wand-magic-sparkles text-amber-500 text-xs"></i> Beautify SQL
        </button>

        <!-- Export Result Dropdown Button (Navicat Style) -->
        <div class="relative inline-block text-left" id="export-dropdown-wrapper">
          <button type="button" id="btn-toggle-export-menu"
            class="px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-300 transition cursor-pointer inline-flex items-center gap-1.5">
            <i class="fa-solid fa-file-export text-emerald-600 text-xs"></i> Export Result <i
              class="fa-solid fa-chevron-down text-[10px] text-emerald-500"></i>
          </button>

          <!-- Dropdown Menu -->
          <div id="export-menu-dropdown"
            class="hidden absolute right-0 mt-2 w-52 rounded-xl bg-white border border-slate-200 shadow-xl z-50 font-sans text-xs divide-y divide-slate-100 overflow-hidden">
            <div
              class="p-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 border-b border-slate-100">
              Export Format Options
            </div>
            <div class="py-1">
              <button type="button"
                class="btn-do-export w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 font-semibold transition cursor-pointer"
                data-format="csv">
                <i class="fa-solid fa-file-csv text-emerald-600 text-base"></i>
                <div>
                  <span class="block text-xs">Export to CSV</span>
                  <span class="text-[10px] text-slate-400 font-normal">Comma Separated (.csv)</span>
                </div>
              </button>
              <button type="button"
                class="btn-do-export w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 font-semibold transition cursor-pointer"
                data-format="excel">
                <i class="fa-solid fa-file-excel text-emerald-700 text-base"></i>
                <div>
                  <span class="block text-xs">Export to Excel</span>
                  <span class="text-[10px] text-slate-400 font-normal">Excel Workbook (.csv)</span>
                </div>
              </button>
            </div>
            <div class="py-1">
              <button type="button"
                class="btn-do-export w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 font-semibold transition cursor-pointer"
                data-format="json">
                <i class="fa-solid fa-code text-amber-600 text-base"></i>
                <div>
                  <span class="block text-xs">Export to JSON</span>
                  <span class="text-[10px] text-slate-400 font-normal">Raw JSON dataset (.json)</span>
                </div>
              </button>
            </div>
          </div>
        </div>

        <!-- Save SQL -->
        <button type="button" id="btn-navicat-save"
          class="px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition cursor-pointer inline-flex items-center gap-1.5">
          <i class="fa-regular fa-floppy-disk text-slate-500 text-xs"></i> Save .SQL
        </button>

        <!-- Clear -->
        <button type="button" id="btn-navicat-clear"
          class="px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition cursor-pointer inline-flex items-center gap-1.5">
          <i class="fa-solid fa-eraser text-slate-400 text-xs"></i> Clear
        </button>

      </div>
    </div>

    <!-- Main Navicat 2-Column Workspace -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-start">

      <!-- LEFT COLUMN: Main Code Editor & Results Drawer (3 cols) -->
      <div class="lg:col-span-3 space-y-4">

        <!-- Editor Panel with Gutter Line Numbers -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg relative">
          <!-- Editor Title Header -->
          <div
            class="bg-slate-950 px-4 py-2 border-b border-slate-800 flex items-center justify-between text-[11px] font-mono text-slate-400">
            <span class="flex items-center gap-2">
              <i class="fa-solid fa-file-code text-brand-400"></i> Query_1.sql
            </span>
            <span class="text-slate-500">Press <b class="text-slate-300">Ctrl + Enter</b> to execute query</span>
          </div>

          <!-- Code Area with Line Numbers -->
          <div class="flex min-h-[220px]">
            <!-- Line Numbers Gutter -->
            <div id="editor-gutter"
              class="w-10 bg-slate-950/70 border-r border-slate-800 py-4 text-center font-mono text-[11px] text-slate-600 select-none leading-relaxed">
              1
            </div>
            <!-- Textarea Editor -->
            <textarea id="sql-console-editor" rows="9" spellcheck="false"
              class="w-full bg-slate-900 text-emerald-300 font-mono text-xs p-4 leading-relaxed focus:outline-none focus:ring-0 resize-y border-0"
              placeholder="SELECT * FROM users LIMIT 25;"></textarea>
          </div>
        </div>

        <!-- Output / Results Panel -->
        <div id="sql-console-output" class="bg-white border border-slate-200/90 rounded-2xl p-5 space-y-4 shadow-sm">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-3">
              <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-table-list text-brand-600"></i> Query Execution Output
              </h3>
              <span id="results-selected-count-badge"
                class="hidden px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-100 text-sky-700 border border-sky-200">
                0 rows selected
              </span>
            </div>
            <div class="flex items-center gap-2">
              <span id="sql-exec-badge"
                class="px-2.5 py-1 rounded-md text-[11px] font-bold font-mono bg-slate-100 text-slate-600 border border-slate-200">
                Ready
              </span>
            </div>
          </div>

          <div id="sql-console-result-container"
            class="border border-slate-200 rounded-xl overflow-hidden overflow-x-auto shadow-xs text-xs font-mono bg-white min-h-[120px]">
            <div class="p-8 text-center text-slate-400 font-sans italic">
              Click <b class="text-emerald-600">"▶ Run Query"</b> or press <b class="text-slate-700 font-mono">Ctrl +
                Enter</b> to view SQL execution results.
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: Code Snippets & Labels Library Sidebar (1 col) -->
      <div class="bg-white border border-slate-200/90 rounded-2xl p-4 space-y-4 shadow-sm">

        <!-- Sidebar Header -->
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
          <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
            <i class="fa-solid fa-tags text-brand-500"></i> Code Snippets Library
          </h3>
        </div>

        <!-- Filter Select & Search Box -->
        <div class="space-y-2">
          <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="snippet-search-input"
              class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 transition"
              placeholder="Search snippets…">
          </div>

          <select id="snippet-category-select"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none">
            <option value="ALL">All Labels & Categories</option>
            <option value="DML">DML (Data Queries)</option>
            <option value="DDL">DDL (Schema Definition)</option>
            <option value="FLOW">Flow Control</option>
            <option value="SYSTEM">System & Maintenance</option>
          </select>
        </div>

        <!-- Snippets List Container -->
        <div id="snippets-list-container" class="space-y-2 max-h-[480px] overflow-y-auto pr-1">
          <!-- Dynamically Rendered via JS -->
        </div>

      </div>

    </div>

    <!-- Bottom Navicat Status Bar -->
    <div
      class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-slate-400 text-[11px] font-mono flex flex-col sm:flex-row items-center justify-between gap-2 shadow-inner">
      <div class="flex items-center gap-4">
        <span>Status: <b class="text-emerald-400" id="navicat-status-text">Connected</b></span>
        <span>|</span>
        <span>Query time: <b class="text-amber-300" id="navicat-query-time">0.000s</b></span>
        <span>|</span>
        <span class="text-sky-400 font-bold" id="navicat-selected-rows-text">0 Rows Selected</span>
      </div>
      <div class="flex items-center gap-4 text-slate-500">
        <span>UTF-8 Multilingual</span>
        <span>|</span>
        <span>MySQL InnoDB</span>
      </div>
    </div>

  </div>
@endsection

@section('scripts')
  <script>
    $(document.ready ? $(document) : $(window)).ready(function () {
      let _activeQueryResult = null; // Stores last active query dataset

      // Setup CSRF header for jQuery AJAX
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // Snippets Library Master Dataset (Navicat Style)
      const _snippetsList = [
        {
          id: 'select',
          name: 'SELECT Syntax',
          category: 'DML',
          desc: 'Retrieve rows selected from one or more tables',
          sql: "SELECT * FROM users WHERE status = 'active' ORDER BY id DESC LIMIT 25;"
        },
        {
          id: 'insert',
          name: 'INSERT Syntax',
          category: 'DML',
          desc: 'Insert new rows into an existing table',
          sql: "INSERT INTO users (name, email, created_at) VALUES ('John Doe', 'john@example.com', NOW());"
        },
        {
          id: 'update',
          name: 'UPDATE Syntax',
          category: 'DML',
          desc: 'Updates columns of existing rows in the named table',
          sql: "UPDATE users SET status = 'active' WHERE id = 1;"
        },
        {
          id: 'delete',
          name: 'DELETE Syntax',
          category: 'DML',
          desc: 'Delete rows from specified table',
          sql: "DELETE FROM users WHERE status = 'inactive';"
        },
        {
          id: 'join',
          name: 'JOIN Query',
          category: 'DML',
          desc: 'Inner / Left Join query across multiple tables',
          sql: "SELECT u.id, u.name, o.total FROM users u\nINNER JOIN orders o ON u.id = o.user_id\nLIMIT 25;"
        },
        {
          id: 'case',
          name: 'CASE',
          category: 'FLOW',
          desc: 'Create a conditional construct',
          sql: "SELECT id, name,\n  CASE\n    WHEN status = 'active' THEN 'Enabled'\n    ELSE 'Disabled'\n  END AS status_label\nFROM users;"
        },
        {
          id: 'if_else',
          name: 'IF...ELSE...',
          category: 'FLOW',
          desc: 'Create an IF...ELSE... statement construct',
          sql: "IF (1 = 1) THEN\n  SELECT 'Condition Passed';\nEND IF;"
        },
        {
          id: 'comments',
          name: 'COMMENTS',
          category: 'FLOW',
          desc: 'Create SQL query comment',
          sql: "/* Custom SQL Query block */\n-- Single line comment\nSELECT 1;"
        },
        {
          id: 'create_table',
          name: 'CREATE TABLE',
          category: 'DDL',
          desc: 'Create a new database table schema',
          sql: "CREATE TABLE `sample_table` (\n  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n  `name` VARCHAR(255) NOT NULL,\n  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        },
        {
          id: 'alter_table',
          name: 'ALTER TABLE',
          category: 'DDL',
          desc: 'Modify table columns or add index',
          sql: "ALTER TABLE `users` ADD COLUMN `phone` VARCHAR(50) NULL AFTER `email`;"
        },
        {
          id: 'show_tables',
          name: 'SHOW TABLES',
          category: 'SYSTEM',
          desc: 'List all tables in active database',
          sql: "SHOW TABLES;"
        },
        {
          id: 'processlist',
          name: 'PROCESSLIST',
          category: 'SYSTEM',
          desc: 'Show running process list & active queries',
          sql: "SHOW PROCESSLIST;"
        },
        {
          id: 'table_status',
          name: 'TABLE STATUS',
          category: 'SYSTEM',
          desc: 'Inspect row counts and table size status',
          sql: "SHOW TABLE STATUS;"
        },
        {
          id: 'optimize_table',
          name: 'OPTIMIZE TABLE',
          category: 'SYSTEM',
          desc: 'Defragment storage and reclaim unused space',
          sql: "OPTIMIZE TABLE `users`;"
        }
      ];

      // Initial Render
      renderSnippetsSidebar(_snippetsList);
      updateEditorGutter();

      if (!$('#sql-console-editor').val()) {
        $('#sql-console-editor').val("SELECT * FROM users LIMIT 25;");
        updateEditorGutter();
      }

      // Line Numbers Gutter Sync
      $('#sql-console-editor').on('input keyup change scroll', updateEditorGutter);

      // Event Bindings
      $('#btn-navicat-run').on('click', function () { executeNavicatQuery(false); });
      $('#btn-navicat-explain').on('click', function () { executeNavicatQuery(true); });
      $('#btn-navicat-stop').on('click', stopNavicatExecution);
      $('#btn-navicat-beautify').on('click', beautifySql);
      $('#btn-navicat-save').on('click', downloadSqlFile);
      $('#btn-navicat-clear').on('click', clearNavicatEditor);

      // Export Dropdown Toggle
      $('#btn-toggle-export-menu').on('click', function (e) {
        e.stopPropagation();
        $('#export-menu-dropdown').toggleClass('hidden');
      });

      $(document).on('click', function () {
        $('#export-menu-dropdown').addClass('hidden');
      });

      $('.btn-do-export').on('click', function () {
        const format = $(this).data('format');
        exportResultData(format);
        $('#export-menu-dropdown').addClass('hidden');
      });

      // Ctrl + Enter shortcut
      $('#sql-console-editor').on('keydown', function (e) {
        if (e.ctrlKey && e.key === 'Enter') {
          e.preventDefault();
          executeNavicatQuery(false);
        }
      });

      // Snippets Search & Filter
      $('#snippet-search-input').on('keyup', filterSnippets);
      $('#snippet-category-select').on('change', filterSnippets);

      // Snippet Card Click Delegate
      $(document).on('click', '.snippet-card-item', function () {
        const sql = $(this).data('sql');
        insertSqlAtCursor(sql);
      });

      // Select All Result Rows Header Checkbox
      $(document).on('change', '#cb-select-all-results', function () {
        const isChecked = $(this).is(':checked');
        $('.result-row-cb').prop('checked', isChecked);
        updateRowSelectionHighlighting();
      });

      // Individual Result Row Checkbox Delegate
      $(document).on('change', '.result-row-cb', function (e) {
        e.stopPropagation();
        updateRowSelectionHighlighting();
      });

      // Click anywhere on row toggles selection (Navicat Style)
      $(document).on('click', '.result-table-row', function (e) {
        if ($(e.target).is('input[type="checkbox"]')) return;
        const $cb = $(this).find('.result-row-cb');
        $cb.prop('checked', !$cb.is(':checked'));
        updateRowSelectionHighlighting();
      });

      function updateEditorGutter() {
        const text = $('#sql-console-editor').val() || '';
        const lines = text.split('\n').length;
        let gutterHtml = '';
        for (let i = 1; i <= Math.max(lines, 8); i++) {
          gutterHtml += `${i}<br>`;
        }
        $('#editor-gutter').html(gutterHtml);
      }

      function renderSnippetsSidebar(list) {
        const $container = $('#snippets-list-container');
        if (list.length === 0) {
          $container.html('<p class="text-xs text-slate-400 italic p-3 text-center">No snippets found.</p>');
          return;
        }

        let html = '';
        $.each(list, function (i, item) {
          const catColor = item.category === 'DML' ? 'bg-blue-50 text-blue-600 border-blue-200' :
            (item.category === 'DDL' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' :
              (item.category === 'FLOW' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'));

          html += `
            <div data-sql="${encodeURIComponent(item.sql)}" class="snippet-card-item p-3 bg-slate-50 hover:bg-slate-100/90 border border-slate-200/80 hover:border-brand-300 rounded-xl transition cursor-pointer space-y-1 group">
              <div class="flex items-center justify-between">
                <span class="font-bold text-slate-800 text-xs font-mono group-hover:text-brand-600">${item.name}</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase font-mono border ${catColor}">${item.category}</span>
              </div>
              <p class="text-[11px] text-slate-400 leading-tight">${item.desc}</p>
            </div>
          `;
        });

        $container.html(html);
      }

      function filterSnippets() {
        const q = $('#snippet-search-input').val().toLowerCase().trim();
        const cat = $('#snippet-category-select').val();

        const filtered = $.grep(_snippetsList, function (item) {
          const matchQuery = !q || item.name.toLowerCase().includes(q) || item.desc.toLowerCase().includes(q) || item.category.toLowerCase().includes(q);
          const matchCat = cat === 'ALL' || item.category === cat;
          return matchQuery && matchCat;
        });

        renderSnippetsSidebar(filtered);
      }

      function insertSqlAtCursor(sqlText) {
        const decodedSql = decodeURIComponent(sqlText);
        const editor = $('#sql-console-editor')[0];
        const startPos = editor.selectionStart;
        const endPos = editor.selectionEnd;

        const currentValue = editor.value;
        editor.value = currentValue.substring(0, startPos) + decodedSql + currentValue.substring(endPos, currentValue.length);

        updateEditorGutter();
        editor.focus();
        window.showToast('info', 'Snippet inserted into query editor.');
      }

      function beautifySql() {
        let sql = $('#sql-console-editor').val();
        if (!sql.trim()) return;

        const keywords = ['SELECT', 'FROM', 'WHERE', 'AND', 'OR', 'ORDER BY', 'GROUP BY', 'LIMIT', 'JOIN', 'LEFT JOIN', 'INNER JOIN', 'RIGHT JOIN', 'ON', 'INSERT INTO', 'VALUES', 'UPDATE', 'SET', 'DELETE FROM', 'CREATE TABLE', 'ALTER TABLE', 'PRIMARY KEY', 'NOT NULL', 'DEFAULT', 'ENGINE'];

        $.each(keywords, function (i, kw) {
          const regex = new RegExp('\\b' + kw + '\\b', 'gi');
          sql = sql.replace(regex, '\n' + kw);
        });

        sql = sql.replace(/^\n+/, '');
        $('#sql-console-editor').val(sql);
        updateEditorGutter();
        window.showToast('success', 'SQL query formatted cleanly.');
      }

      function clearNavicatEditor() {
        $('#sql-console-editor').val('');
        updateEditorGutter();
        _activeQueryResult = null;
        updateRowSelectionHighlighting();
        $('#sql-console-result-container').html('<div class="p-8 text-center text-slate-400 font-sans italic">Editor cleared.</div>');
      }

      function stopNavicatExecution() {
        $('#navicat-status-text').text('Stopped').attr('class', 'text-rose-400');
        window.showToast('warning', 'Execution stopped.');
      }

      function downloadSqlFile() {
        const sql = $('#sql-console-editor').val();
        if (!sql.trim()) {
          window.showToast('warning', 'Editor is empty.');
          return;
        }
        const blob = new Blob([sql], { type: 'text/plain' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = `query_${Date.now()}.sql`;
        a.click();
      }

      /**
       * Update Row Selection Highlighting (Navicat Blue Style)
       */
      function updateRowSelectionHighlighting() {
        const checkedRows = $('.result-row-cb:checked');
        const count = checkedRows.length;

        // Update bottom status bar text
        $('#navicat-selected-rows-text').text(`${count} Row${count === 1 ? '' : 's'} Selected`);

        const $badge = $('#results-selected-count-badge');
        if (count > 0) {
          $badge.text(`${count} row${count === 1 ? '' : 's'} selected`).removeClass('hidden');
        } else {
          $badge.addClass('hidden');
        }

        // Highlight selected rows in Navicat Blue (`bg-sky-600 text-white`)
        $('.result-table-row').each(function () {
          const $tr = $(this);
          const $cb = $tr.find('.result-row-cb');
          if ($cb.is(':checked')) {
            $tr.attr('class', 'result-table-row bg-sky-600 text-white font-medium transition cursor-pointer select-none');
            $tr.find('td').addClass('text-white');
          } else {
            $tr.attr('class', 'result-table-row hover:bg-slate-50 text-slate-700 transition cursor-pointer select-none');
            $tr.find('td').removeClass('text-white');
          }
        });
      }

      /**
       * Export Result Dataset (CSV / Excel / JSON)
       */
      function exportResultData(format) {
        if (!_activeQueryResult || !_activeQueryResult.rows || _activeQueryResult.rows.length === 0) {
          window.showToast('warning', 'No query execution results available to export.');
          return;
        }

        // Check if user selected specific rows, otherwise export all rows
        const checkedIndices = $('.result-row-cb:checked').map(function () {
          return parseInt($(this).val());
        }).get();

        let targetRows = [];
        if (checkedIndices.length > 0) {
          $.each(checkedIndices, function (i, idx) {
            if (_activeQueryResult.rows[idx]) {
              targetRows.push(_activeQueryResult.rows[idx]);
            }
          });
        } else {
          targetRows = _activeQueryResult.rows;
        }

        if (targetRows.length === 0) {
          window.showToast('warning', 'No rows selected for export.');
          return;
        }

        const cols = _activeQueryResult.columns || Object.keys(targetRows[0]);
        const filename = `export_${Date.now()}`;

        if (format === 'json') {
          const jsonStr = JSON.stringify(targetRows, null, 2);
          downloadFile(jsonStr, `${filename}.json`, 'application/json');
          window.showToast('success', `Exported ${targetRows.length} row(s) to JSON successfully.`);
        } else {
          // CSV or Excel format
          let csvContent = '';

          // Header Row
          const formattedCols = $.map(cols, function (c) { return `"${c.replace(/"/g, '""')}"`; });
          csvContent += formattedCols.join(',') + '\r\n';

          // Data Rows
          $.each(targetRows, function (i, r) {
            const rowVals = $.map(cols, function (c) {
              let val = r[c];
              if (val === null || val === undefined) val = '';
              else val = String(val).replace(/"/g, '""');
              return `"${val}"`;
            });
            csvContent += rowVals.join(',') + '\r\n';
          });

          const mime = format === 'excel' ? 'application/vnd.ms-excel' : 'text/csv;charset=utf-8;';
          const ext = '.csv';
          downloadFile(csvContent, filename + ext, mime);

          window.showToast('success', `Exported ${targetRows.length} row(s) to ${format.toUpperCase()} successfully.`);
        }
      }

      function downloadFile(content, fileName, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }

      /**
       * Execute SQL Query via jQuery AJAX
       */
      function executeNavicatQuery(isExplain = false) {
        let sql = $('#sql-console-editor').val().trim();
        if (!sql) {
          window.showToast('warning', 'Please enter a SQL statement.');
          return;
        }

        if (isExplain && !sql.toUpperCase().startsWith('EXPLAIN')) {
          sql = 'EXPLAIN ' + sql;
        }

        const $resultBox = $('#sql-console-result-container');
        const $badge = $('#sql-exec-badge');
        const $btn = $('#btn-navicat-run');

        $btn.prop('disabled', true).html(`<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> Running…`);
        $('#navicat-status-text').text('Executing…').attr('class', 'text-amber-300');
        $resultBox.html(`<div class="p-8 text-center text-slate-400 font-sans"><i class="fa-solid fa-circle-notch fa-spin text-lg mb-2"></i><p class="font-semibold">Executing statement against MySQL database…</p></div>`);

        $.ajax({
          url: '/api/v1/database-manager/execute-sql',
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({ sql: sql }),
          success: function (res) {
            $btn.prop('disabled', false).html(`<i class="fa-solid fa-play text-xs text-emerald-200"></i> Run Query`);

            if (res.success) {
              const d = res.data;
              _activeQueryResult = d; // Store active dataset for selection & export
              const execSec = (d.execution_ms / 1000).toFixed(3);
              $('#navicat-query-time').text(`${execSec}s`);
              $('#navicat-status-text').text('OK').attr('class', 'text-emerald-400');
              $badge.attr('class', 'px-2.5 py-1 rounded-md text-[11px] font-bold font-mono bg-emerald-50 text-emerald-700 border border-emerald-200').text(d.message);

              if (d.type === 'READ') {
                const rows = d.rows || [];
                if (rows.length === 0) {
                  $resultBox.html(`<div class="p-8 text-center text-slate-400 italic font-sans">Query time: ${execSec}s. 0 rows returned.</div>`);
                  updateRowSelectionHighlighting();
                  return;
                }

                const cols = d.columns || Object.keys(rows[0]);

                // Header with Master Checkbox
                let headerHtml = `<thead class="bg-slate-100 border-b border-slate-200 text-[10px] uppercase font-bold text-slate-500"><tr>`;
                headerHtml += `<th class="p-2.5 w-10 text-center select-none"><input type="checkbox" id="cb-select-all-results" class="h-4 w-4 rounded border-slate-300 text-brand-600 cursor-pointer"></th>`;
                $.each(cols, function (i, c) { headerHtml += `<th class="p-2.5">${c}</th>`; });
                headerHtml += `</tr></thead>`;

                // Body with Selectable Rows
                let bodyHtml = `<tbody class="divide-y divide-slate-100 text-xs font-mono">`;
                $.each(rows, function (i, r) {
                  bodyHtml += `<tr class="result-table-row hover:bg-slate-50 transition cursor-pointer select-none" data-row-idx="${i}">`;
                  bodyHtml += `<td class="p-2.5 text-center"><input type="checkbox" value="${i}" class="result-row-cb h-4 w-4 rounded border-slate-300 text-brand-600 cursor-pointer"></td>`;
                  $.each(cols, function (j, c) {
                    const val = r[c];
                    bodyHtml += `<td class="p-2.5 max-w-xs truncate" title="${val}">${val !== null ? val : '<span class="text-slate-300 font-sans italic">NULL</span>'}</td>`;
                  });
                  bodyHtml += `</tr>`;
                });
                bodyHtml += `</tbody>`;

                $resultBox.html(`<table class="w-full text-left border-collapse">${headerHtml}${bodyHtml}</table>`);
                updateRowSelectionHighlighting();
              } else {
                _activeQueryResult = null;
                updateRowSelectionHighlighting();
                $resultBox.html(`
                  <div class="p-5 bg-emerald-50/70 text-emerald-800 text-xs font-sans font-medium flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
                    <span class="font-bold text-sm">${d.message}</span>
                  </div>
                `);
              }
            }
          },
          error: function (xhr) {
            $btn.prop('disabled', false).html(`<i class="fa-solid fa-play text-xs text-emerald-200"></i> Run Query`);
            _activeQueryResult = null;
            updateRowSelectionHighlighting();
            $('#navicat-status-text').text('Error').attr('class', 'text-rose-400');
            $badge.attr('class', 'px-2.5 py-1 rounded-md text-[11px] font-bold font-mono bg-rose-50 text-rose-700 border border-rose-200').text('SQL Error');

            const errMsg = xhr.responseJSON?.message || 'Database query error.';
            $resultBox.html(`
              <div class="p-5 bg-rose-50 text-rose-700 text-xs font-mono leading-relaxed flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-xl mt-0.5"></i>
                <div>
                  <b class="font-bold text-sm block mb-1 font-sans">SQL Execution Error</b>
                  <span>${errMsg}</span>
                </div>
              </div>
            `);
          }
        });
      }
    });
  </script>
@endsection
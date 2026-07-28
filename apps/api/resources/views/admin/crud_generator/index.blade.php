@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Advanced CRUD Generator')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition" data-i18n="dashboard">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700" data-i18n="crud_generator">CRUD Generator</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-6 font-sans">

    <!-- Header Header Banner -->
    <div
      class="theme-hero-banner bg-gradient-to-r from-brand-900 via-brand-700 to-indigo-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
      <div
        class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none">
      </div>
      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-brand-200 text-xs font-bold uppercase tracking-widest mb-1">
            <i class="fa-solid fa-wand-magic-sparkles text-amber-300"></i> Code Automation Engine
          </div>
          <h1 class="text-2xl font-extrabold tracking-tight">Advanced Laravel CRUD Generator</h1>
          <p class="text-sm text-brand-100 mt-1 max-w-2xl">
            Auto-inspect database schemas and generate production-ready DDD Lite modules. Generated files are saved inside
            the <code class="bg-brand-950/50 px-2 py-0.5 rounded text-amber-300 font-mono">codegenerator/</code>
            directory.
          </p>
        </div>
        <div class="flex items-center gap-3">
          <a href="#wizard-container"
            class="px-4 py-2.5 rounded-xl bg-white text-brand-900 font-bold text-xs shadow-lg hover:bg-brand-50 transition">
            <i class="fa-solid fa-rocket mr-1"></i> Start Generator
          </a>
        </div>
      </div>
    </div>

    <!-- Wizard Steps Progress Bar -->
    <div id="wizard-container" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
      <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-xs font-bold">
        <button type="button" onclick="goToStep(1)" id="step-btn-1"
          class="step-btn py-3 px-2 rounded-xl bg-brand-50 text-brand-600 border border-brand-200 transition">
          <span class="block text-[10px] text-slate-400 font-normal uppercase">Step 1</span>
          1. Select Table
        </button>
        <button type="button" onclick="goToStep(2)" id="step-btn-2"
          class="step-btn py-3 px-2 rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 transition">
          <span class="block text-[10px] text-slate-400 font-normal uppercase">Step 2</span>
          2. Field Customizer
        </button>
        <button type="button" onclick="goToStep(3)" id="step-btn-3"
          class="step-btn py-3 px-2 rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 transition">
          <span class="block text-[10px] text-slate-400 font-normal uppercase">Step 3</span>
          3. Relationships
        </button>
        <button type="button" onclick="goToStep(4)" id="step-btn-4"
          class="step-btn py-3 px-2 rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 transition">
          <span class="block text-[10px] text-slate-400 font-normal uppercase">Step 4</span>
          4. Options
        </button>
        <button type="button" onclick="goToStep(5)" id="step-btn-5"
          class="step-btn py-3 px-2 rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 transition">
          <span class="block text-[10px] text-slate-400 font-normal uppercase">Step 5</span>
          5. Code Preview
        </button>
      </div>
    </div>

    <!-- Wizard Content Container -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">

      <!-- ── STEP 1: Connection, Database & Table Selection ────────────────── -->
      <div id="step-content-1" class="wizard-step-content space-y-6">
        <div>
          <h3 class="text-base font-bold text-slate-900">Database & Table Selection</h3>
          <p class="text-xs text-slate-500 mt-0.5">Select a database connection & database first, then pick a table to
            inspect its fields.</p>
        </div>

        <!-- Phase 1: Database Connection & Database Name -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-4 bg-slate-50 border border-slate-200 rounded-xl">
          <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center gap-1.5">
              <span
                class="h-5 w-5 rounded-full bg-brand-600 text-white text-[10px] flex items-center justify-center font-bold">1</span>
              Database Connection
            </label>
            <select id="select-connection"
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:border-brand-500 focus:outline-none transition">
              <!-- Dynamically populated -->
            </select>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center gap-1.5">
              <span
                class="h-5 w-5 rounded-full bg-brand-600 text-white text-[10px] flex items-center justify-center font-bold">2</span>
              Select Database
            </label>
            <select id="select-database"
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:border-brand-500 focus:outline-none transition">
              <option value="">-- Loading Databases... --</option>
            </select>
          </div>
        </div>

        <!-- Phase 2: Select Table (Appears after Database selected) -->
        <div id="section-tables" class="space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-xl">
          <label class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center gap-1.5">
            <span
              class="h-5 w-5 rounded-full bg-brand-600 text-white text-[10px] flex items-center justify-center font-bold">3</span>
            Select Target Table
          </label>
          <select id="select-table"
            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:border-brand-500 focus:outline-none transition">
            <option value="">-- Choose Table --</option>
          </select>
        </div>

        <!-- Phase 3: Module / Model Name Configuration (Appears after Table selected) -->
        <div id="section-model-config" class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 hidden">
          <div class="space-y-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Module Name (StudlyCase)</label>
            <input type="text" id="input-module-name" placeholder="e.g. Product"
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-800 focus:border-brand-500 focus:outline-none transition">
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Model Name</label>
            <input type="text" id="input-model-name" placeholder="e.g. Product"
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-800 focus:border-brand-500 focus:outline-none transition">
          </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100">
          <button type="button" id="btn-fetch-schema" disabled
            class="px-6 py-3 rounded-xl bg-slate-200 text-slate-400 font-bold text-xs cursor-not-allowed transition inline-flex items-center gap-2">
            Inspect Schema & Edit Fields <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>

      <!-- ── STEP 2: Field Customizer Matrix ────────────────────── -->
      <div id="step-content-2" class="wizard-step-content space-y-6 hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div>
            <h3 class="text-base font-bold text-slate-900">Field Configuration Matrix</h3>
            <p class="text-xs text-slate-500 mt-0.5">Customize control types, validation rules, visibility, and behaviors
              for every column.</p>
          </div>
          <span class="text-xs font-bold bg-brand-50 text-brand-600 px-3 py-1 rounded-full border border-brand-100"
            id="badge-column-count">0 Columns</span>
        </div>

        <div class="overflow-x-auto border border-slate-200 rounded-xl">
          <table class="w-full text-left text-xs font-sans">
            <thead
              class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[10px] font-bold">
              <tr>
                <th class="px-3 py-3">Column</th>
                <th class="px-3 py-3">Label</th>
                <th class="px-3 py-3">Input Control Type</th>
                <th class="px-3 py-3">Validation Rules</th>
                <th class="px-3 py-3 text-center">Visibilities</th>
                <th class="px-3 py-3 text-center">Flags</th>
              </tr>
            </thead>
            <tbody id="tbody-fields" class="divide-y divide-slate-100">
              <!-- Rows injected dynamically -->
            </tbody>
          </table>
        </div>

        <div class="flex justify-between pt-4 border-t border-slate-100">
          <button type="button" onclick="goToStep(1)"
            class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition">Back</button>
          <button type="button" onclick="goToStep(3)"
            class="px-6 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-500 transition shadow-md shadow-brand-600/20">Proceed
            to Relationships <i class="fa-solid fa-arrow-right ml-1"></i></button>
        </div>
      </div>

      <!-- ── STEP 3: Relationship Builder ───────────────────────── -->
      <div id="step-content-3" class="wizard-step-content space-y-6 hidden">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="text-base font-bold text-slate-900">Laravel Relationship Builder</h3>
            <p class="text-xs text-slate-500 mt-0.5">Map relationships (BelongsTo, HasMany, BelongsToMany, Polymorphic,
              etc.).</p>
          </div>
          <button type="button" id="btn-add-relation"
            class="px-3.5 py-2 rounded-xl bg-brand-50 text-brand-600 border border-brand-200 font-bold text-xs hover:bg-brand-100 transition">
            <i class="fa-solid fa-plus mr-1"></i> Add Relation
          </button>
        </div>

        <div id="relations-container" class="space-y-3">
          <!-- Relation cards injected here -->
        </div>

        <div class="flex justify-between pt-4 border-t border-slate-100">
          <button type="button" onclick="goToStep(2)"
            class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition">Back</button>
          <button type="button" onclick="proceedFromStep3()"
            class="px-6 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-500 transition shadow-md shadow-brand-600/20">Proceed
            to Options <i class="fa-solid fa-arrow-right ml-1"></i></button>
        </div>
      </div>

      <!-- ── STEP 4: Module Options & Page Features ─────────────── -->
      <div id="step-content-4" class="wizard-step-content space-y-6 hidden">
        <div>
          <h3 class="text-base font-bold text-slate-900">Module Architecture Options</h3>
          <p class="text-xs text-slate-500 mt-0.5">Toggle optional classes and files to include in the generated output.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          <label
            class="flex items-start gap-3 p-4 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition">
            <input type="checkbox" id="opt-seeder" checked
              class="mt-1 rounded border-slate-300 text-brand-600 focus:ring-0">
            <div>
              <span class="block text-sm font-bold text-slate-900">Database Seeder</span>
              <span class="block text-xs text-slate-400">Generate fake record seeder.</span>
            </div>
          </label>

          <label
            class="flex items-start gap-3 p-4 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition">
            <input type="checkbox" id="opt-factory" checked
              class="mt-1 rounded border-slate-300 text-brand-600 focus:ring-0">
            <div>
              <span class="block text-sm font-bold text-slate-900">Model Factory</span>
              <span class="block text-xs text-slate-400">Generate Eloquent factory definitions.</span>
            </div>
          </label>

          <label
            class="flex items-start gap-3 p-4 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition">
            <input type="checkbox" id="opt-observer" checked
              class="mt-1 rounded border-slate-300 text-brand-600 focus:ring-0">
            <div>
              <span class="block text-sm font-bold text-slate-900">Model Observer</span>
              <span class="block text-xs text-slate-400">Lifecycle observer hooks.</span>
            </div>
          </label>

          <label
            class="flex items-start gap-3 p-4 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition">
            <input type="checkbox" id="opt-notification"
              class="mt-1 rounded border-slate-300 text-brand-600 focus:ring-0">
            <div>
              <span class="block text-sm font-bold text-slate-900">Notification Class</span>
              <span class="block text-xs text-slate-400">Email/Database notification.</span>
            </div>
          </label>

          <label
            class="flex items-start gap-3 p-4 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition">
            <input type="checkbox" id="opt-tests" checked
              class="mt-1 rounded border-slate-300 text-brand-600 focus:ring-0">
            <div>
              <span class="block text-sm font-bold text-slate-900">Unit & Feature Tests</span>
              <span class="block text-xs text-slate-400">Automated PHPUnit test suites.</span>
            </div>
          </label>
        </div>

        <div class="flex justify-between pt-4 border-t border-slate-100">
          <button type="button" onclick="goToStep(3)"
            class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition">Back</button>
          <button type="button" id="btn-generate-preview"
            class="px-6 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-500 transition shadow-md shadow-brand-600/20">
            Generate Code Preview <i class="fa-solid fa-code ml-1"></i>
          </button>
        </div>
      </div>

      <!-- ── STEP 5: Code Preview & Output Execution ─────────────── -->
      <div id="step-content-5" class="wizard-step-content space-y-6 hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div>
            <h3 class="text-base font-bold text-slate-900">Code Preview & Target Directory</h3>
            <p class="text-xs text-slate-500 mt-0.5">
              Target Output Folder: <code id="lbl-output-folder"
                class="bg-slate-100 px-2 py-0.5 rounded text-brand-700 font-mono font-bold">codegenerator/Product/</code>
            </p>
          </div>

          <div class="flex gap-2">
            <button type="button" id="btn-execute-generation"
              class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition shadow-lg shadow-emerald-600/20 inline-flex items-center gap-2">
              <i class="fa-solid fa-file-export"></i> Generate Files to codegenerator/
            </button>
            <a id="btn-download-zip" href="#"
              class="px-4 py-2.5 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 font-bold text-xs transition hidden inline-flex items-center gap-2">
              <i class="fa-solid fa-file-zipper"></i> Download ZIP
            </a>
          </div>
        </div>

        <!-- Tabstrip of generated files -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div
            class="md:col-span-1 border border-slate-200 rounded-xl p-2.5 max-h-[480px] overflow-y-auto space-y-2 bg-slate-50"
            id="preview-file-list">
            <!-- List of file buttons injected dynamically -->
          </div>
          <div
            class="md:col-span-3 border border-slate-800 rounded-xl overflow-hidden bg-slate-950 flex flex-col shadow-lg">
            <div
              class="bg-slate-900 border-b border-slate-800 px-4 py-2.5 flex items-center justify-between font-mono text-xs text-slate-300">
              <div class="flex items-center gap-2 truncate pr-2">
                <i class="fa-solid fa-file-code text-brand-400"></i>
                <span class="text-slate-400 text-[11px]">Full Path:</span>
                <span id="lbl-current-file-path"
                  class="font-bold text-slate-100 truncate selection:bg-brand-500 selection:text-white">codegenerator/</span>
              </div>
              <button type="button" onclick="copyCodeContent()"
                class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-[11px] font-sans font-semibold transition flex items-center gap-1">
                <i class="fa-solid fa-copy"></i> Copy Code
              </button>
            </div>
            <div class="p-4 font-mono text-xs text-slate-200 overflow-x-auto max-h-[420px]">
              <pre id="preview-code-viewer">// Select a file to inspect generated source code...</pre>
            </div>
          </div>
        </div>

        <div class="flex justify-start pt-4 border-t border-slate-100">
          <button type="button" onclick="goToStep(4)"
            class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition">Back
            to Options</button>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('scripts')
  <script>
    let currentSchema = null;
    let generatedFilesContent = {};

    const CONTROL_TYPES = [
      'text', 'textarea', 'rich_text', 'password', 'email', 'number', 'decimal', 'phone', 'url',
      'date', 'time', 'datetime', 'month', 'week', 'color_picker', 'range_slider', 'checkbox',
      'switch_toggle', 'radio_button', 'single_select', 'multi_select', 'autocomplete', 'tags_input',
      'file_upload', 'multiple_file_upload', 'image_upload', 'multiple_image_upload', 'hidden_field',
      'json_editor', 'code_editor', 'icon_picker', 'address', 'google_map_location', 'slug', 'uuid',
      'currency', 'rating', 'boolean', 'custom_component'
    ];

    const RELATION_TYPES = [
      'hasOne', 'hasMany', 'belongsTo', 'belongsToMany', 'hasOneThrough', 'hasManyThrough',
      'morphOne', 'morphMany', 'morphTo', 'morphToMany', 'morphedByMany'
    ];

    $(document).ready(function () {
      loadConnections();

      $('#select-connection').change(function () {
        loadDatabases($(this).val());
      });

      $('#select-database').change(function () {
        const conn = $('#select-connection').val();
        const db = $(this).val();
        loadTables(conn, db);
      });

      $('#select-table').change(function () {
        const table = $(this).val();
        if (table) {
          const studly = table.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join('');
          const singular = studly.endsWith('s') ? studly.slice(0, -1) : studly;
          $('#input-module-name').val(singular);
          $('#input-model-name').val(singular);
          $('#section-model-config').removeClass('hidden');
          $('#btn-fetch-schema').prop('disabled', false)
            .removeClass('bg-slate-200 text-slate-400 cursor-not-allowed')
            .addClass('bg-brand-600 hover:bg-brand-500 text-white shadow-md shadow-brand-600/20');
        } else {
          $('#section-model-config').addClass('hidden');
          $('#btn-fetch-schema').prop('disabled', true)
            .removeClass('bg-brand-600 hover:bg-brand-500 text-white shadow-md shadow-brand-600/20')
            .addClass('bg-slate-200 text-slate-400 cursor-not-allowed');
        }
      });

      $('#btn-fetch-schema').click(async function () {
        const conn = $('#select-connection').val();
        const db = $('#select-database').val();
        const table = $('#select-table').val();
        if (!table) {
          showToast('error', 'Please select a database table.');
          return;
        }

        try {
          const res = await axios.get(`/crud-generator/schema?connection=${conn}&database=${db}&table=${table}`);
          currentSchema = res.data.data;
          renderFieldMatrix(currentSchema.columns);
          renderRelations(currentSchema.suggested_relations);
          goToStep(2);
        } catch (e) {
          handleAjaxError(e);
        }
      });

      $('#btn-add-relation').click(function () {
        addRelationRow();
      });

      $('#btn-generate-preview').click(async function () {
        const payload = collectPayload();
        try {
          const res = await axios.post('/crud-generator/preview', payload);
          generatedFilesContent = res.data.data.files_content;
          renderPreviewFileList(res.data.data.generated_files);
          $('#lbl-output-folder').text(`codegenerator/${payload.module_name}/`);
          goToStep(5);
        } catch (e) {
          handleAjaxError(e);
        }
      });

      $('#btn-execute-generation').click(async function () {
        const payload = collectPayload();
        try {
          const res = await axios.post('/crud-generator/generate', payload);
          showToast('success', res.data.message);
          $('#btn-download-zip').attr('href', res.data.data.download_url).removeClass('hidden');
        } catch (e) {
          handleAjaxError(e);
        }
      });
    });

    function loadConnections() {
      axios.get('/crud-generator/connections').then(res => {
        const options = res.data.data.map(c => `<option value="${c}">${c}</option>`).join('');
        $('#select-connection').html(options);
        loadDatabases(res.data.data[0]);
      });
    }

    function loadDatabases(conn) {
      axios.get(`/crud-generator/databases?connection=${conn}`).then(res => {
        const dbs = res.data.data;
        const options = dbs.map(d => {
          const dbName = typeof d === 'object' ? d.name : d;
          const count = typeof d === 'object' ? d.tables : 0;
          const label = `${dbName} (${count} ${count === 1 ? 'table' : 'tables'})`;
          return `<option value="${dbName}">${label}</option>`;
        }).join('');
        $('#select-database').html(options);
        if (dbs.length > 0) {
          const firstDb = typeof dbs[0] === 'object' ? dbs[0].name : dbs[0];
          loadTables(conn, firstDb);
        }
      });
    }

    function loadTables(conn, db) {
      axios.get(`/crud-generator/tables?connection=${conn}&database=${db || ''}`).then(res => {
        window.allDbTables = res.data.data || [];
        const options = '<option value="">-- Choose Table --</option>' + window.allDbTables.map(t => `<option value="${t.name}">${t.name} (${t.rows} rows)</option>`).join('');
        $('#select-table').html(options);
        $('#section-model-config').addClass('hidden');
        $('#btn-fetch-schema').prop('disabled', true)
          .removeClass('bg-brand-600 hover:bg-brand-500 text-white shadow-md shadow-brand-600/20')
          .addClass('bg-slate-200 text-slate-400 cursor-not-allowed');
      });
    }

    function goToStep(step) {
      $('.wizard-step-content').addClass('hidden');
      $(`#step-content-${step}`).removeClass('hidden');
      $('.step-btn').removeClass('bg-brand-50 text-brand-600 border border-brand-200').addClass('bg-slate-50 text-slate-500');
      $(`#step-btn-${step}`).removeClass('bg-slate-50 text-slate-500').addClass('bg-brand-50 text-brand-600 border border-brand-200');
    }

    const COMMON_RULES = ['required', 'nullable', 'string', 'numeric', 'integer', 'boolean', 'email', 'url', 'date', 'image', 'file', 'unique', 'max:255'];

    function renderFieldMatrix(columns) {
      $('#badge-column-count').text(`${columns.length} Columns`);
      const html = columns.map((col, idx) => {
        const controlOptions = CONTROL_TYPES.map(ct => `<option value="${ct}" ${ct === col.control_type ? 'selected' : ''}>${ct.replace(/_/g, ' ')}</option>`).join('');
        const rulesList = col.validation_rules ? col.validation_rules.split('|').map(r => r.trim()) : [];

        const ruleBadges = COMMON_RULES.map(r => {
          const active = rulesList.includes(r);
          const cls = active ? 'bg-brand-600 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-500 hover:bg-slate-200';
          return `<button type="button" onclick="toggleRuleBadge(this, '${r}')" class="rule-badge px-1.5 py-0.5 rounded text-[9px] font-sans transition ${cls}">${r}</button>`;
        }).join('');

        return `
          <tr class="hover:bg-slate-50/70 transition" data-col="${col.name}">
            <td class="px-3 py-3 font-semibold text-slate-900">
              <div>${col.name}</div>
              <div class="text-[10px] text-slate-400 font-mono">${col.type_name}</div>
            </td>
            <td class="px-3 py-3">
              <input type="text" class="field-label w-28 border border-slate-200 rounded px-2 py-1 text-xs" value="${col.label}">
            </td>
            <td class="px-3 py-3">
              <select class="field-control border border-slate-200 rounded px-2 py-1 text-xs bg-white capitalize">
                ${controlOptions}
              </select>
            </td>
            <td class="px-3 py-3">
              <div class="space-y-1">
                <input type="text" class="field-rules w-44 border border-slate-200 rounded px-2 py-1 text-xs font-mono bg-white" value="${col.validation_rules}">
                <div class="flex flex-wrap gap-1 max-w-[210px]">${ruleBadges}</div>
              </div>
            </td>
            <td class="px-3 py-3 text-center space-x-1">
              <label class="inline-flex items-center text-[10px] font-bold text-slate-500" title="Show in List">
                <input type="checkbox" class="field-vis-list rounded text-brand-600 focus:ring-0" ${col.show_in_list ? 'checked' : ''}> L
              </label>
              <label class="inline-flex items-center text-[10px] font-bold text-slate-500" title="Show in Create">
                <input type="checkbox" class="field-vis-create rounded text-brand-600 focus:ring-0" ${col.show_in_create ? 'checked' : ''}> C
              </label>
              <label class="inline-flex items-center text-[10px] font-bold text-slate-500" title="Show in Edit">
                <input type="checkbox" class="field-vis-edit rounded text-brand-600 focus:ring-0" ${col.show_in_edit ? 'checked' : ''}> E
              </label>
            </td>
            <td class="px-3 py-3 text-center space-x-1">
              <label class="inline-flex items-center text-[10px] font-bold text-slate-500" title="Searchable">
                <input type="checkbox" class="field-flag-searchable rounded text-brand-600 focus:ring-0" ${col.searchable ? 'checked' : ''}> S
              </label>
              <label class="inline-flex items-center text-[10px] font-bold text-slate-500" title="Sortable">
                <input type="checkbox" class="field-flag-sortable rounded text-brand-600 focus:ring-0" ${col.sortable ? 'checked' : ''}> O
              </label>
            </td>
          </tr>
        `;
      }).join('');
      $('#tbody-fields').html(html);
    }

    function toggleRuleBadge(btn, rule) {
      const $row = $(btn).closest('tr');
      const $rulesInput = $row.find('.field-rules');
      let currentRules = $rulesInput.val().split('|').map(r => r.trim()).filter(Boolean);

      if (currentRules.includes(rule)) {
        currentRules = currentRules.filter(r => r !== rule);
        $(btn).removeClass('bg-brand-600 text-white font-bold shadow-xs').addClass('bg-slate-100 text-slate-500 hover:bg-slate-200');
      } else {
        // If required/nullable are mutually exclusive
        if (rule === 'required') currentRules = currentRules.filter(r => r !== 'nullable');
        if (rule === 'nullable') currentRules = currentRules.filter(r => r !== 'required');
        currentRules.push(rule);
        $(btn).removeClass('bg-slate-100 text-slate-500 hover:bg-slate-200').addClass('bg-brand-600 text-white font-bold shadow-xs');
      }

      $rulesInput.val(currentRules.join('|'));
    }

    function renderRelations(relations) {
      $('#relations-container').empty();
      if (relations && relations.length > 0) {
        relations.forEach(rel => addRelationRow(rel));
      } else {
        addRelationRow();
      }
    }

    function addRelationRow(rel = {}) {
      const relOptions = RELATION_TYPES.map(rt => `<option value="${rt}" ${rt === (rel.type || 'belongsTo') ? 'selected' : ''}>${rt}</option>`).join('');
      const colOptions = currentSchema && currentSchema.columns ? currentSchema.columns.map(c => `<option value="${c.name}" ${c.name === (rel.foreign_key || '') ? 'selected' : ''}>${c.name} (${c.type_name})</option>`).join('') : '';

      const targetTableOptions = window.allDbTables
        ? '<option value="">-- Select Target Table --</option>' + window.allDbTables.map(t => `<option value="${t.name}" ${t.name === (rel.target_table || '') ? 'selected' : ''}>${t.name}</option>`).join('')
        : '<option value="">-- Select Target Table --</option>';

      const cardId = 'rel-card-' + Math.random().toString(36).substring(2, 9);

      const card = `
        <div id="${cardId}" class="relation-card p-4 border border-slate-200 rounded-xl bg-slate-50 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 items-end" data-owner-key="${rel.owner_key || 'id'}">
          <div>
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Selected Table Field</label>
            <select class="rel-fk w-full border border-slate-200 rounded-lg px-2 py-2 text-xs bg-white font-semibold text-slate-800" onchange="onRelationFkChange(this)">
              <option value="">-- Select Column --</option>
              ${colOptions}
            </select>
          </div>
          <div>
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Relationship Type</label>
            <select class="rel-type w-full border border-slate-200 rounded-lg px-2 py-2 text-xs bg-white font-semibold text-slate-800">${relOptions}</select>
          </div>
          <div>
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Relation Name</label>
            <input type="text" class="rel-name w-full border border-slate-200 rounded-lg px-2.5 py-2 text-xs font-semibold text-slate-800" value="${rel.name || ''}" placeholder="e.g. category">
          </div>
          <div>
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Target Table</label>
            <select class="rel-target-table w-full border border-slate-200 rounded-lg px-2 py-2 text-xs bg-white font-semibold text-slate-800" onchange="onTargetTableChange(this)">
              ${targetTableOptions}
            </select>
            <input type="hidden" class="rel-model" value="${rel.related_model || ''}">
          </div>
          <div>
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Target Table Field</label>
            <select class="rel-owner-key w-full border border-slate-200 rounded-lg px-2 py-2 text-xs bg-white font-semibold text-slate-800">
              <option value="id">id</option>
            </select>
          </div>
          <div class="text-right">
            <button type="button" onclick="$(this).closest('.relation-card').remove()" class="w-full px-3 py-2 rounded-lg bg-rose-50 border border-rose-100 text-rose-600 font-bold text-xs hover:bg-rose-100 transition">
              <i class="fa-solid fa-trash-can mr-1"></i> Delete
            </button>
          </div>
        </div>
      `;
      $('#relations-container').append(card);

      if (rel.target_table) {
        $(`#${cardId} .rel-target-table`).trigger('change');
      }
    }

    function onRelationFkChange(selectEl) {
      const fk = $(selectEl).val();
      if (!fk) return;
      const card = $(selectEl).closest('.relation-card');
      const relNameInput = card.find('.rel-name');
      const targetTableSelect = card.find('.rel-target-table');

      let baseName = fk.replace(/_id$/, '');
      const pluralName = baseName + 's';

      if (!relNameInput.val()) {
        relNameInput.val(baseName);
      }

      // Try auto-selecting target table if matches
      if (!targetTableSelect.val() && window.allDbTables) {
        const match = window.allDbTables.find(t => t.name === pluralName || t.name === baseName);
        if (match) {
          targetTableSelect.val(match.name).trigger('change');
        }
      }
    }

    async function onTargetTableChange(selectEl) {
      const targetTable = $(selectEl).val();
      const card = $(selectEl).closest('.relation-card');
      const targetKeySelect = card.find('.rel-owner-key');
      const relModelInput = card.find('.rel-model');

      if (!targetTable) {
        targetKeySelect.html('<option value="id">id</option>');
        card.removeData('target-columns');
        return;
      }

      // Derive StudlyName for model
      const studly = targetTable.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join('');
      const singular = studly.endsWith('s') ? studly.slice(0, -1) : studly;
      relModelInput.val(`App\\Domains\\${singular}\\Models\\${singular}`);

      // Fetch columns for targetTable to populate target field dropdown
      const conn = $('#select-connection').val();
      const db = $('#select-database').val();
      try {
        const res = await axios.get(`/crud-generator/schema?connection=${conn}&database=${db}&table=${targetTable}`);
        const cols = res.data.data.columns || [];
        card.data('target-columns', cols);
        const currentOwnerKey = card.data('owner-key') || 'id';
        const options = cols.map(c => `<option value="${c.name}" ${c.name === currentOwnerKey ? 'selected' : ''}>${c.name} (${c.type_name})</option>`).join('');
        targetKeySelect.html(options || '<option value="id">id</option>');
      } catch (e) {
        targetKeySelect.html('<option value="id">id</option>');
      }
    }

    function proceedFromStep3() {
      if (validateRelationships()) {
        goToStep(4);
      }
    }

    function validateRelationships() {
      const cards = $('.relation-card');
      if (cards.length === 0) return true;

      let isValid = true;
      let errorMessage = '';
      const relationNames = [];

      cards.each(function (idx) {
        const num = idx + 1;
        const type = $(this).find('.rel-type').val();
        const fkName = $(this).find('.rel-fk').val();
        const relName = $(this).find('.rel-name').val();
        const targetTable = $(this).find('.rel-target-table').val();
        const targetKey = $(this).find('.rel-owner-key').val();

        if (type === 'morphTo') {
          if (!relName) {
            isValid = false;
            errorMessage = `Relation #${num}: Relation Name is required for morphTo.`;
            return false;
          }
          return true;
        }

        if (!fkName) {
          isValid = false;
          errorMessage = `Relation #${num}: Please select a field from the current table.`;
          return false;
        }

        if (!relName) {
          isValid = false;
          errorMessage = `Relation #${num}: Please provide a Relation Name.`;
          return false;
        }

        if (relationNames.includes(relName)) {
          isValid = false;
          errorMessage = `Relation #${num}: Duplicate relation name '${relName}' found. Each relation method name must be unique.`;
          return false;
        }
        relationNames.push(relName);

        if (!targetTable) {
          isValid = false;
          errorMessage = `Relation #${num} ('${relName}'): Please select a Target Table.`;
          return false;
        }

        if (!targetKey) {
          isValid = false;
          errorMessage = `Relation #${num} ('${relName}'): Please select a Target Table Field.`;
          return false;
        }

        // Type compatibility validation check
        const sourceCol = currentSchema && currentSchema.columns ? currentSchema.columns.find(c => c.name === fkName) : null;
        const targetCols = $(this).data('target-columns') || [];
        const targetCol = targetCols.find(c => c.name === targetKey);

        if (sourceCol && targetCol) {
          const sourceType = normalizeTypeCategory(sourceCol.type_name);
          const targetType = normalizeTypeCategory(targetCol.type_name);

          if (sourceType !== targetType) {
            isValid = false;
            errorMessage = `Relation #${num} ('${relName}'): Incompatible Data Types!\nCannot link '${fkName} (${sourceCol.type_name})' with '${targetKey} (${targetCol.type_name})' on target table '${targetTable}'.\nForeign key fields must share matching data type categories (e.g. Integer to Integer, or String/UUID to String).`;
            return false;
          }
        }
      });

      if (!isValid) {
        Swal.fire({
          icon: 'warning',
          title: 'Invalid Relationship Mapping',
          text: errorMessage,
          confirmButtonColor: '#2b47ff'
        });
      }

      return isValid;
    }

    function normalizeTypeCategory(typeName) {
      const t = (typeName || '').toLowerCase();
      if (['integer', 'bigint', 'smallint', 'tinyint', 'int', 'mediumint', 'numeric'].some(k => t.includes(k))) {
        return 'numeric';
      }
      if (['varchar', 'char', 'string', 'uuid', 'text', 'mediumtext', 'longtext'].some(k => t.includes(k))) {
        return 'string';
      }
      if (['date', 'datetime', 'timestamp', 'time', 'year'].some(k => t.includes(k))) {
        return 'datetime';
      }
      if (['decimal', 'float', 'double', 'real'].some(k => t.includes(k))) {
        return 'decimal';
      }
      if (['boolean', 'bool'].some(k => t.includes(k))) {
        return 'boolean';
      }
      return t;
    }

    function collectPayload() {
      const columns = [];
      $('#tbody-fields tr').each(function () {
        columns.push({
          name: $(this).data('col'),
          label: $(this).find('.field-label').val(),
          control_type: $(this).find('.field-control').val(),
          validation_rules: $(this).find('.field-rules').val(),
          show_in_list: $(this).find('.field-vis-list').is(':checked'),
          show_in_create: $(this).find('.field-vis-create').is(':checked'),
          show_in_edit: $(this).find('.field-vis-edit').is(':checked'),
          searchable: $(this).find('.field-flag-searchable').is(':checked'),
          sortable: $(this).find('.field-flag-sortable').is(':checked'),
        });
      });

      const relationships = [];
      $('.relation-card').each(function () {
        relationships.push({
          type: $(this).find('.rel-type').val(),
          name: $(this).find('.rel-name').val(),
          target_table: $(this).find('.rel-target-table').val(),
          related_model: $(this).find('.rel-model').val(),
          foreign_key: $(this).find('.rel-fk').val(),
          owner_key: $(this).find('.rel-owner-key').val() || 'id',
        });
      });

      return {
        module_name: $('#input-module-name').val(),
        model_name: $('#input-model-name').val(),
        table_name: $('#select-table').val(),
        columns,
        relationships,
        options: {
          include_seeder: $('#opt-seeder').is(':checked'),
          include_factory: $('#opt-factory').is(':checked'),
          include_observer: $('#opt-observer').is(':checked'),
          include_notification: $('#opt-notification').is(':checked'),
          include_tests: $('#opt-tests').is(':checked'),
        }
      };
    }

    function renderPreviewFileList(files) {
      const moduleName = $('#input-module-name').val() || 'Module';
      const html = files.map((file, idx) => {
        const parts = file.split('/');
        const fileName = parts.pop();
        const dirPath = parts.join('/') + '/';
        const fullPath = `codegenerator/${moduleName}/${file}`;
        const ext = fileName.includes('.') ? fileName.split('.').pop().toUpperCase() : 'FILE';

        return `
          <button type="button" 
            title="Full Path: ${fullPath}"
            onclick="selectPreviewFile('${file}', this)" 
            class="preview-file-item group w-full text-left p-2.5 rounded-xl border transition flex flex-col gap-1 ${idx === 0 ? 'bg-brand-50 border-brand-200 text-brand-700 shadow-xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300'}">
            <div class="flex items-center justify-between w-full gap-1">
              <span class="font-bold text-xs font-mono truncate text-slate-900">${fileName}</span>
              <span class="text-[9px] px-1.5 py-0.5 rounded font-sans font-bold uppercase bg-slate-100 text-slate-500 shrink-0">${ext}</span>
            </div>
            <div class="text-[10px] text-slate-400 font-mono truncate w-full group-hover:text-slate-600">
              ${dirPath}
            </div>
            <div class="text-[9px] text-brand-600 font-mono truncate w-full hidden group-hover:block border-t border-slate-100 pt-1 font-semibold">
              ${fullPath}
            </div>
          </button>
        `;
      }).join('');

      $('#preview-file-list').html(html);
      if (files.length > 0) {
        selectPreviewFile(files[0], $('#preview-file-list button').first()[0]);
      }
    }

    function selectPreviewFile(filePath, el) {
      const moduleName = $('#input-module-name').val() || 'Module';
      const fullPath = `codegenerator/${moduleName}/${filePath}`;

      $('#lbl-current-file-path').text(fullPath);
      $('.preview-file-item').removeClass('bg-brand-50 border-brand-200 text-brand-700 shadow-xs').addClass('bg-white border-slate-200 text-slate-700');

      if (el) {
        $(el).removeClass('bg-white border-slate-200 text-slate-700').addClass('bg-brand-50 border-brand-200 text-brand-700 shadow-xs');
      }

      const content = generatedFilesContent[filePath] || '// Empty content';
      $('#preview-code-viewer').text(content);
    }

    function copyCodeContent() {
      const code = $('#preview-code-viewer').text();
      navigator.clipboard.writeText(code);
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Code copied to clipboard!',
        showConfirmButton: false,
        timer: 1500
      });
    }
  </script>
@endsection
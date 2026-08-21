@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Config Settings')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Platform Configurations</span>
</nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Platform Configurations</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Decrypted settings store with AES-256 mutators protection.</p>
    </div>
  </div>

  <!-- Dedicated Toast Notification Customizer Card -->
  <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
          <i class="fa-solid fa-bell text-base"></i>
        </div>
        <div>
          <h3 class="text-sm font-bold text-slate-900">Toast Notification Customizer</h3>
          <p class="text-xs text-slate-500 font-medium">Select toaster design theme, color palette, and screen alignment stored in Database.</p>
        </div>
      </div>
      <span class="px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600">Appearance UI</span>
    </div>

    <form id="form-toast-settings" class="space-y-5">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2.5">Toast Design & Color Aesthetic</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
          <!-- Obsidian Theme Radio -->
          <label class="toast-theme-option relative border-2 border-brand-500 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-slate-900 text-white shadow-sm">
            <input type="radio" name="toast_theme_input" value="obsidian" class="sr-only" checked>
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Deep Obsidian Glass</span>
              <i class="fa-solid fa-circle-check text-brand-400 theme-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-slate-400 leading-snug">Linear/Stripe obsidian glass with neon glowing left border edge.</p>
          </label>

          <!-- White Glass Theme Radio -->
          <label class="toast-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-slate-50 hover:bg-white text-slate-900">
            <input type="radio" name="toast_theme_input" value="white_glass" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">White Frosted Glass</span>
              <i class="fa-solid fa-circle-check text-slate-300 theme-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-slate-500 leading-snug">Apple/Vercel style frosted white glass card with dark typography.</p>
          </label>

          <!-- Solid Vibrant Theme Radio -->
          <label class="toast-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-gradient-to-r from-emerald-600 to-teal-700 text-white">
            <input type="radio" name="toast_theme_input" value="solid_vibrant" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Solid Vibrant Gradient</span>
              <i class="fa-solid fa-circle-check text-white/50 theme-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-white/80 leading-snug">Rich full-color gradient backgrounds per alert status.</p>
          </label>

          <!-- Minimal Dark Theme Radio -->
          <label class="toast-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-zinc-800 text-white">
            <input type="radio" name="toast_theme_input" value="minimal_dark" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Minimal Matte Dark</span>
              <i class="fa-solid fa-circle-check text-zinc-500 theme-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-zinc-400 leading-snug">Compact dark charcoal matte card with high contrast text.</p>
          </label>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-1">
          <label for="toast-position-select" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Screen Position</label>
          <select id="toast-position-select" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
            <option value="top-right">Top Right (Default)</option>
            <option value="top-left">Top Left</option>
            <option value="top-center">Top Center</option>
            <option value="bottom-right">Bottom Right</option>
            <option value="bottom-left">Bottom Left</option>
          </select>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Test Toasters Live</label>
          <div class="flex items-center gap-2 pt-0.5">
            <button type="button" onclick="showToast('success', 'Operation completed successfully!', 'Success Test')" class="px-3 py-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition border border-emerald-200 flex-1">
              <i class="fa-solid fa-check mr-1"></i> Success
            </button>
            <button type="button" onclick="showToast('error', 'Critical validation issue detected!', 'Error Test')" class="px-3 py-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition border border-rose-200 flex-1">
              <i class="fa-solid fa-xmark mr-1"></i> Error
            </button>
            <button type="button" onclick="showToast('warning', 'Please review settings configuration.', 'Warning Test')" class="px-3 py-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold transition border border-amber-200 flex-1">
              <i class="fa-solid fa-triangle-exclamation mr-1"></i> Warning
            </button>
          </div>
        </div>
      </div>

      <div class="flex justify-end pt-1">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5 border-0 cursor-pointer">
          <i class="fa-solid fa-floppy-disk"></i> Save Toast Preferences to Database
        </button>
      </div>
    </form>
  </div>

  <!-- Dedicated Sidebar Navigation Theme Customizer Card -->
  <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600">
          <i class="fa-solid fa-bars-staggered text-base"></i>
        </div>
        <div>
          <h3 class="text-sm font-bold text-slate-900">Sidebar Navigation Theme</h3>
          <p class="text-xs text-slate-500 font-medium">Select dynamic admin sidebar aesthetic, background color, and active link highlights saved in Database.</p>
        </div>
      </div>
      <span class="px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600">Appearance UI</span>
    </div>

    <form id="form-sidebar-settings" class="space-y-5">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2.5">Select Sidebar Theme Aesthetic</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
          <!-- Clean Light Theme Radio -->
          <label class="sidebar-theme-option relative border-2 border-brand-500 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-white text-slate-900 shadow-sm">
            <input type="radio" name="sidebar_theme_input" value="clean_light" class="sr-only" checked>
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Clean Modern Light</span>
              <i class="fa-solid fa-circle-check text-brand-500 sidebar-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-slate-500 leading-snug">Crisp pure white background with soft slate borders & indigo accents.</p>
          </label>

          <!-- Obsidian Midnight Theme Radio -->
          <label class="sidebar-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-slate-900 text-white">
            <input type="radio" name="sidebar_theme_input" value="obsidian_dark" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Obsidian Midnight</span>
              <i class="fa-solid fa-circle-check text-slate-400 sidebar-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-slate-400 leading-snug">Dark midnight charcoal background with glowing brand accents.</p>
          </label>

          <!-- Royal Indigo Glass Theme Radio -->
          <label class="sidebar-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-gradient-to-b from-indigo-950 to-slate-900 text-white">
            <input type="radio" name="sidebar_theme_input" value="royal_glass" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Royal Indigo Glass</span>
              <i class="fa-solid fa-circle-check text-indigo-400 sidebar-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-indigo-200/80 leading-snug">Deep indigo glass gradient background with pastel neon badges.</p>
          </label>

          <!-- Nordic Emerald Theme Radio -->
          <label class="sidebar-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-emerald-950 text-white">
            <input type="radio" name="sidebar_theme_input" value="nordic_emerald" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Nordic Emerald</span>
              <i class="fa-solid fa-circle-check text-emerald-400 sidebar-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-emerald-200/80 leading-snug">Rich dark forest emerald slate card with neon green highlights.</p>
          </label>

          <!-- Sunset Crimson Theme Radio -->
          <label class="sidebar-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-gradient-to-b from-rose-950 to-zinc-900 text-white">
            <input type="radio" name="sidebar_theme_input" value="sunset_crimson" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Sunset Crimson</span>
              <i class="fa-solid fa-circle-check text-rose-400 sidebar-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-rose-200/80 leading-snug">Deep crimson velvet gradient background with glowing rose badges.</p>
          </label>

          <!-- Cyber Neon Theme Radio -->
          <label class="sidebar-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-zinc-950 text-white">
            <input type="radio" name="sidebar_theme_input" value="cyber_neon" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Cyberpunk Neon</span>
              <i class="fa-solid fa-circle-check text-cyan-400 sidebar-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-cyan-200/80 leading-snug">Matte pitch black with high-voltage cyan neon border highlights.</p>
          </label>

          <!-- Amber Gold Theme Radio -->
          <label class="sidebar-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-gradient-to-b from-stone-900 to-zinc-950 text-white">
            <input type="radio" name="sidebar_theme_input" value="amber_gold" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Luxury Amber Gold</span>
              <i class="fa-solid fa-circle-check text-amber-400 sidebar-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-amber-200/80 leading-snug">Rich dark charcoal card with metallic gold active link badges.</p>
          </label>

          <!-- Minimal Titanium Slate Theme Radio -->
          <label class="sidebar-theme-option relative border border-slate-200 rounded-xl p-3.5 cursor-pointer transition flex flex-col justify-between bg-slate-800 text-white">
            <input type="radio" name="sidebar_theme_input" value="minimal_slate" class="sr-only">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold">Titanium Slate</span>
              <i class="fa-solid fa-circle-check text-slate-300 sidebar-check-icon text-sm"></i>
            </div>
            <p class="text-[11px] text-slate-300/80 leading-snug">Compact matte titanium slate card with clean white active pills.</p>
          </label>
        </div>
      </div>

      <div class="flex justify-end pt-1">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5 border-0 cursor-pointer">
          <i class="fa-solid fa-floppy-disk"></i> Save Sidebar Theme to Database
        </button>
      </div>
    </form>
  </div>

  <!-- Dedicated Google reCAPTCHA v2 Configuration Card -->
  <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600">
          <i class="fa-solid fa-shield-halved text-base"></i>
        </div>
        <div>
          <h3 class="text-sm font-bold text-slate-900">Google reCAPTCHA v2 Security</h3>
          <p class="text-xs text-slate-500 font-medium">Protect Login, Password Reset, and Auth pages against automated bot attacks.</p>
        </div>
      </div>
      <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="recaptcha-enable-switch" class="sr-only peer">
        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-300 after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600 transition"></div>
        <span class="ml-2.5 text-xs font-bold text-slate-700" id="recaptcha-status-label">Disabled</span>
      </label>
    </div>

    <form id="form-recaptcha-settings" class="space-y-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-1">
          <label for="recaptcha-site-key" class="text-xs font-semibold uppercase tracking-wider text-slate-500">reCAPTCHA v2 Site Key (Public)</label>
          <input id="recaptcha-site-key" type="text" placeholder="Enter Site Key (e.g. 6Le...)"
                 class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 font-mono focus:outline-none focus:border-brand-500 transition">
        </div>
        <div class="space-y-1">
          <label for="recaptcha-secret-key" class="text-xs font-semibold uppercase tracking-wider text-slate-500">reCAPTCHA v2 Secret Key (Private)</label>
          <input id="recaptcha-secret-key" type="password" placeholder="Enter Secret Key"
                 class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 font-mono focus:outline-none focus:border-brand-500 transition">
        </div>
      </div>

      <div class="flex justify-end pt-2">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm shadow-brand-600/20 inline-flex items-center gap-1.5">
          <i class="fa-solid fa-floppy-disk"></i> Save reCAPTCHA Config
        </button>
      </div>
    </form>
  </div>

  <!-- Reusable Responsive DataTable -->
  <div id="settings-datatable"></div>
</div>

<!-- ============================================== -->
<!-- SETTING EDIT MODAL                             -->
<!-- ============================================== -->
<div id="modal-setting" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <form id="form-setting" class="relative bg-white border border-slate-200 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 font-sans">
      <input type="hidden" id="setting-edit-id">
      <h3 class="font-bold text-lg text-slate-900">Edit Platform Configuration</h3>
      
      <div class="space-y-4">
        <div class="space-y-1">
          <label for="setting-key" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Config Key</label>
          <input id="setting-key" type="text" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 select-all focus:outline-none">
        </div>
        <div class="space-y-1">
          <label for="setting-value" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Config Value</label>
          <input id="setting-value" type="text" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
        </div>
        <div class="space-y-1">
          <label for="setting-group" class="text-xs font-semibold uppercase tracking-wider text-slate-500">System Group</label>
          <input id="setting-group" type="text" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
        </div>
        <div class="flex items-center justify-between text-sm py-2">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">AES-256 Storage Encryption</label>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" id="setting-encrypted" class="sr-only peer">
            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-300 after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 transition"></div>
          </label>
        </div>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="button" class="close-modal w-1/2 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">Cancel</button>
        <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition">Update Parameter</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let settingsList = [];

    // ── Row Renderer ───────────────────────────────────────────
    function settingRow(set) {
        const securityType = set.is_encrypted 
            ? '<span class="px-2 py-0.5 rounded bg-emerald-50 border border-emerald-100 text-xs font-bold text-emerald-600"><i class="fa-solid fa-lock text-[10px] mr-1 text-emerald-500"></i> Encrypted</span>'
            : '<span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs font-bold text-slate-500">Plaintext</span>';

        return `
          <tr class="hover:bg-slate-50/60 transition">
            <td class="px-5 py-4 font-mono text-xs font-bold text-slate-900">${set.key}</td>
            <td class="px-5 py-4 font-mono text-xs max-w-sm overflow-hidden truncate">
              <div class="flex items-center gap-2">
                <span id="sett-value-${set.id}" class="text-slate-500 truncate">${set.is_encrypted ? '••••••••••••••••' : set.value}</span>
                ${set.is_encrypted ? `<button onclick="toggleSettingView('${set.id}', '${set.value}')" class="text-slate-400 hover:text-slate-900 transition" title="Toggle visibility"><i class="fa-regular fa-eye text-xs"></i></button>` : ''}
              </div>
            </td>
            <td class="px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-widest">${set.group}</td>
            <td class="px-5 py-4">${securityType}</td>
            <td class="px-5 py-4 text-right">
              <button onclick="editSetting('${set.id}')" class="px-2.5 py-1.5 rounded bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-xs transition">Edit</button>
            </td>
          </tr>
        `;
    }

    // ── Init AdminTable ────────────────────────────────────────
    const settingsTable = new AdminTable({
        container: '#settings-datatable',
        columns: [
            { key: 'key',          label: 'Configuration Key', sortable: true },
            { key: 'value',        label: 'Decryptable Value', sortable: true,  responsive: 'sm' },
            { key: 'group',        label: 'System Group',      sortable: true,  responsive: 'md' },
            { key: 'is_encrypted', label: 'Security Type',     sortable: true,  responsive: 'lg' },
            { key: 'actions',      label: 'Actions',           sortable: false, class: 'text-right' }
        ],
        fetch: async () => {
            const response = await axios.get('/settings');
            settingsList = response.data.data;
            return settingsList;
        },
        row: settingRow
    });

    settingsTable.load();

    // ── Action Handlers ────────────────────────────────────────
    window.toggleSettingView = function(id, decryptedValue) {
        const target = $(`#sett-value-${id}`);
        if (target.text() === '••••••••••••••••') {
            target.text(decryptedValue).removeClass('text-slate-500').addClass('text-emerald-600 font-semibold');
        } else {
            target.text('••••••••••••••••').removeClass('text-emerald-600 font-semibold').addClass('text-slate-500');
        }
    }

    window.editSetting = function(id) {
        const setting = settingsList.find(s => s.id === id);
        if (!setting) return;

        $('#setting-edit-id').val(setting.id);
        $('#setting-key').val(setting.key);
        $('#setting-value').val(setting.value);
        $('#setting-group').val(setting.group);
        $('#setting-encrypted').prop('checked', !!setting.is_encrypted);
        
        $('#modal-setting').removeClass('hidden');
    }

    // ── Load reCAPTCHA Config ──────────────────────────────────
    async function loadReCaptchaConfig() {
        try {
            const res = await axios.get('/settings?group=security');
            const secSettings = res.data.data || [];
            
            const enabledSet = secSettings.find(s => s.key === 'recaptcha_enabled');
            const siteKeySet = secSettings.find(s => s.key === 'recaptcha_site_key');
            const secretKeySet = secSettings.find(s => s.key === 'recaptcha_secret_key');

            const isEnabled = enabledSet ? (enabledSet.value === '1' || enabledSet.value === true || enabledSet.value === 'true') : false;
            $('#recaptcha-enable-switch').prop('checked', isEnabled);
            $('#recaptcha-status-label').text(isEnabled ? 'Enabled' : 'Disabled').toggleClass('text-emerald-600', isEnabled).toggleClass('text-slate-700', !isEnabled);

            if (siteKeySet) $('#recaptcha-site-key').val(siteKeySet.value || '');
            if (secretKeySet && secretKeySet.value) $('#recaptcha-secret-key').val(secretKeySet.value);
        } catch (e) {
            console.error('Failed to load reCAPTCHA settings', e);
        }
    }

    // ── Load & Sync Toast Config from Database Settings Table ─────────
    async function loadToastConfig() {
        try {
            const res = await axios.get('/settings?group=appearance');
            const appSettings = res.data.data || [];
            
            const themeSet = appSettings.find(s => s.key === 'toast_theme');
            const posSet = appSettings.find(s => s.key === 'toast_position');

            const savedTheme = themeSet ? themeSet.value : (window.currentToastTheme || 'obsidian');
            const savedPos = posSet ? posSet.value : (window.currentToastPosition || 'top-right');

            window.currentToastTheme = savedTheme;
            window.currentToastPosition = savedPos;

            // Select active radio button
            $(`input[name="toast_theme_input"][value="${savedTheme}"]`).prop('checked', true);
            updateThemeCardBorders(savedTheme);

            // Select active position dropdown
            $('#toast-position-select').val(savedPos);
        } catch (e) {
            console.error('Failed to load toast settings from database', e);
        }
    }

    function updateThemeCardBorders(selectedTheme) {
        $('.toast-theme-option').removeClass('border-2 border-brand-500 ring-2 ring-brand-500 shadow-md').addClass('border border-slate-200');
        const $activeCard = $(`input[name="toast_theme_input"][value="${selectedTheme}"]`).closest('.toast-theme-option');
        $activeCard.addClass('border-2 border-brand-500 ring-2 ring-brand-500 shadow-md').removeClass('border-slate-200');
    }

    $(document).on('change click', '.toast-theme-option, input[name="toast_theme_input"]', function() {
        setTimeout(function() {
            const val = $('input[name="toast_theme_input"]:checked').val();
            if (val) {
                updateThemeCardBorders(val);
                window.currentToastTheme = val;
            }
        }, 10);
    });

    $(document).on('change', '#toast-position-select', function() {
        const val = $(this).val();
        window.currentToastPosition = val;
    });

    $('#form-toast-settings').submit(async function(e) {
        e.preventDefault();
        const selectedTheme = $('input[name="toast_theme_input"]:checked').val() || 'obsidian';
        const selectedPos = $('#toast-position-select').val() || 'top-right';

        const payload = {
            settings: [
                {
                    key: 'toast_theme',
                    value: selectedTheme,
                    group: 'appearance',
                    is_encrypted: false
                },
                {
                    key: 'toast_position',
                    value: selectedPos,
                    group: 'appearance',
                    is_encrypted: false
                }
            ]
        };

        try {
            await axios.post('/settings', payload);
            window.currentToastTheme = selectedTheme;
            window.currentToastPosition = selectedPos;
            localStorage.setItem('laraforgex_toast_theme', selectedTheme);
            localStorage.setItem('laraforgex_toast_position', selectedPos);
            showToast('success', `Toast preferences saved to settings table & cache! (Theme: ${selectedTheme}, Position: ${selectedPos})`);
            settingsTable.reload();
        } catch (err) {
            handleAjaxError(err);
        }
    });

    // ── Load & Sync Sidebar Theme Config ──────────────────────────────
    async function loadSidebarConfig() {
        try {
            const cachedSbTheme = localStorage.getItem('laraforgex_sidebar_theme');
            if (cachedSbTheme) {
                $(`input[name="sidebar_theme_input"][value="${cachedSbTheme}"]`).prop('checked', true);
                updateSidebarThemeCardBorders(cachedSbTheme);
                applySidebarThemeLive(cachedSbTheme);
            }

            const res = await axios.get('/settings');
            const appSettings = res.data.data || [];
            const sbThemeSet = appSettings.find(s => s.key === 'sidebar_theme');
            const savedSbTheme = (sbThemeSet && sbThemeSet.value) ? sbThemeSet.value : 'clean_light';

            localStorage.setItem('laraforgex_sidebar_theme', savedSbTheme);
            $(`input[name="sidebar_theme_input"][value="${savedSbTheme}"]`).prop('checked', true);
            updateSidebarThemeCardBorders(savedSbTheme);
            applySidebarThemeLive(savedSbTheme);
        } catch (e) {
            console.error('Failed to load sidebar theme settings', e);
        }
    }

    function updateSidebarThemeCardBorders(selectedTheme) {
        $('.sidebar-theme-option').removeClass('border-2 border-brand-500 ring-2 ring-brand-500 shadow-md').addClass('border border-slate-200');
        const $activeCard = $(`input[name="sidebar_theme_input"][value="${selectedTheme}"]`).closest('.sidebar-theme-option');
        $activeCard.addClass('border-2 border-brand-500 ring-2 ring-brand-500 shadow-md').removeClass('border-slate-200');
    }

    function applySidebarThemeLive(themeKey) {
        const $sidebar = $('#admin-sidebar');
        if ($sidebar.length) {
            $sidebar.attr('class', function(i, c) {
                return (c || '').replace(/\bsidebar-theme-\S+/g, '');
            }).addClass(`sidebar-theme-${themeKey}`);
        }
    }

    $(document).on('change click', '.sidebar-theme-option, input[name="sidebar_theme_input"]', function() {
        setTimeout(function() {
            const val = $('input[name="sidebar_theme_input"]:checked').val();
            if (val) {
                updateSidebarThemeCardBorders(val);
                applySidebarThemeLive(val);
            }
        }, 10);
    });

    $('#form-sidebar-settings').submit(async function(e) {
        e.preventDefault();
        const selectedSbTheme = $('input[name="sidebar_theme_input"]:checked').val() || 'clean_light';

        const payload = {
            settings: [
                {
                    key: 'sidebar_theme',
                    value: selectedSbTheme,
                    group: 'appearance',
                    is_encrypted: false
                }
            ]
        };

        try {
            await axios.post('/settings', payload);
            localStorage.setItem('laraforgex_sidebar_theme', selectedSbTheme);
            applySidebarThemeLive(selectedSbTheme);
            showToast('success', `Sidebar theme '${selectedSbTheme}' saved to database table & cache!`);
            settingsTable.reload();
            if (typeof window.loadGlobalAppSettingsFromDB === 'function') {
                window.loadGlobalAppSettingsFromDB();
            }
        } catch (err) {
            handleAjaxError(err);
        }
    });

    loadToastConfig();
    loadSidebarConfig();
    loadReCaptchaConfig();

    $('#recaptcha-enable-switch').change(function() {
        const checked = $(this).is(':checked');
        const siteKey = $('#recaptcha-site-key').val().trim();
        const secretKey = $('#recaptcha-secret-key').val().trim();

        if (checked && (!siteKey || !secretKey)) {
            $(this).prop('checked', false);
            $('#recaptcha-status-label').text('Disabled').removeClass('text-emerald-600').addClass('text-slate-700');
            showToast('error', 'Cannot enable reCAPTCHA v2 without valid Site Key and Secret Key. Please fill in both keys first.');
            return;
        }

        $('#recaptcha-status-label').text(checked ? 'Enabled' : 'Disabled').toggleClass('text-emerald-600', checked).toggleClass('text-slate-700', !checked);
    });

    $('#form-recaptcha-settings').submit(async function(e) {
        e.preventDefault();
        const isEnabled = $('#recaptcha-enable-switch').is(':checked');
        const siteKey = $('#recaptcha-site-key').val().trim();
        const secretKey = $('#recaptcha-secret-key').val().trim();

        if (!siteKey || !secretKey) {
            if (isEnabled) {
                $('#recaptcha-enable-switch').prop('checked', false);
                $('#recaptcha-status-label').text('Disabled').removeClass('text-emerald-600').addClass('text-slate-700');
            }
            showToast('error', 'Please enter both reCAPTCHA v2 Site Key and Secret Key before saving.');
            return;
        }

        const payload = {
            settings: [
                {
                    key: 'recaptcha_enabled',
                    value: isEnabled ? '1' : '0',
                    group: 'security',
                    is_encrypted: false
                },
                {
                    key: 'recaptcha_site_key',
                    value: siteKey,
                    group: 'security',
                    is_encrypted: false
                },
                {
                    key: 'recaptcha_secret_key',
                    value: secretKey,
                    group: 'security',
                    is_encrypted: true
                }
            ]
        };

        try {
            await axios.post('/settings', payload);
            showToast('success', 'Google reCAPTCHA v2 configuration saved successfully!');
            settingsTable.reload();
        } catch (err) {
            handleAjaxError(err);
        }
    });

    $('#form-setting').submit(async function(e) {
        e.preventDefault();
        const id = $('#setting-edit-id').val();
        const key = $('#setting-key').val();
        const value = $('#setting-value').val();
        const payload = {
            key: key,
            value: value,
            group: $('#setting-group').val(),
            is_encrypted: $('#setting-encrypted').is(':checked')
        };

        try {
            await axios.put(`/settings/${id}`, payload);
            if (key === 'app_name') localStorage.setItem('laraforgex_app_name', value);
            if (key === 'app_logo') localStorage.setItem('laraforgex_app_logo', value);
            if (key === 'sidebar_theme') localStorage.setItem('laraforgex_sidebar_theme', value);
            if (key === 'toast_theme') localStorage.setItem('laraforgex_toast_theme', value);
            if (key === 'toast_position') localStorage.setItem('laraforgex_toast_position', value);

            showToast('success', 'Configuration updated successfully.');
            $('#modal-setting').addClass('hidden');
            settingsTable.reload();
            if (typeof window.loadGlobalAppSettingsFromDB === 'function') {
                window.loadGlobalAppSettingsFromDB();
            }
        } catch (e) {
            handleAjaxError(e);
        }
    });
});
</script>
@endsection

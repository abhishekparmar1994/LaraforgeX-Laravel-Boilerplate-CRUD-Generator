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
        const payload = {
            key: $('#setting-key').val(),
            value: $('#setting-value').val(),
            group: $('#setting-group').val(),
            is_encrypted: $('#setting-encrypted').is(':checked')
        };

        try {
            await axios.put(`/settings/${id}`, payload);
            showToast('success', 'Configuration updated successfully.');
            $('#modal-setting').addClass('hidden');
            settingsTable.reload();
        } catch (e) {
            handleAjaxError(e);
        }
    });
});
</script>
@endsection

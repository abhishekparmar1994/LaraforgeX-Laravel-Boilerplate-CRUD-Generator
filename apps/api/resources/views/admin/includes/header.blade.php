<header class="flex items-center justify-between pb-4 border-b border-slate-200 font-sans">

  <!-- Left: breadcrumbs + mobile hamburger toggle -->
  <div class="flex items-center gap-3">
    <!-- Sidebar Toggle -->
    <button type="button"
      class="h-9 w-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:border-slate-300 transition shadow-sm cursor-pointer"
      onclick="toggleSidebar()"
      title="Toggle Sidebar">
      <i class="fa-solid fa-bars text-sm"></i>
    </button>

    @yield('breadcrumbs')
  </div>

  <!-- Right: notification + user dropdown -->
  <div class="flex items-center gap-3">

    <!-- Theme Customizer Dropdown -->
    <div class="relative" id="theme-dropdown-wrapper">
      <button id="theme-dropdown-toggle"
        class="h-9 w-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:border-slate-300 transition shadow-sm cursor-pointer"
        onclick="toggleThemeDropdown()"
        title="Theme Customizer">
        <i class="fa-solid fa-palette text-sm text-brand-600"></i>
      </button>

      <div id="theme-dropdown-panel"
        class="hidden absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden text-xs p-3 space-y-3">
        <div>
          <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Accent Color Palette</p>
          <div class="grid grid-cols-5 gap-1.5">
            <button onclick="setAccentTheme('#2b47ff')" class="h-6 w-6 rounded-full bg-[#2b47ff] border-2 border-white shadow cursor-pointer" title="Royal Blue"></button>
            <button onclick="setAccentTheme('#10b981')" class="h-6 w-6 rounded-full bg-[#10b981] border-2 border-white shadow cursor-pointer" title="Emerald"></button>
            <button onclick="setAccentTheme('#8b5cf6')" class="h-6 w-6 rounded-full bg-[#8b5cf6] border-2 border-white shadow cursor-pointer" title="Deep Violet"></button>
            <button onclick="setAccentTheme('#f59e0b')" class="h-6 w-6 rounded-full bg-[#f59e0b] border-2 border-white shadow cursor-pointer" title="Sunset Amber"></button>
            <button onclick="setAccentTheme('#f43f5e')" class="h-6 w-6 rounded-full bg-[#f43f5e] border-2 border-white shadow cursor-pointer" title="Crimson Rose"></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Language Switcher Dropdown -->
    <div class="relative" id="lang-dropdown-wrapper">
      <button id="lang-dropdown-toggle"
        class="h-9 px-2.5 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:border-slate-300 transition shadow-sm cursor-pointer text-xs font-bold gap-1.5"
        onclick="toggleLangDropdown()"
        title="Language Selector">
        <span id="current-lang-flag">🇺🇸</span>
        <span id="current-lang-code" class="uppercase font-mono">EN</span>
        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
      </button>

      <div id="lang-dropdown-panel"
        class="hidden absolute right-0 mt-2 w-44 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden text-xs">
        <div class="py-1">
          <button onclick="setLanguage('en', '🇺🇸', 'EN', 'ltr')" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 flex items-center justify-between font-semibold text-slate-700">
            <span>🇺🇸 English</span>
            <span class="text-[10px] text-slate-400">LTR</span>
          </button>
          <button onclick="setLanguage('es', '🇪🇸', 'ES', 'ltr')" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 flex items-center justify-between font-semibold text-slate-700">
            <span>🇪🇸 Español</span>
            <span class="text-[10px] text-slate-400">LTR</span>
          </button>
          <button onclick="setLanguage('fr', '🇫🇷', 'FR', 'ltr')" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 flex items-center justify-between font-semibold text-slate-700">
            <span>🇫🇷 Français</span>
            <span class="text-[10px] text-slate-400">LTR</span>
          </button>
          <button onclick="setLanguage('de', '🇩🇪', 'DE', 'ltr')" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 flex items-center justify-between font-semibold text-slate-700">
            <span>🇩🇪 Deutsch</span>
            <span class="text-[10px] text-slate-400">LTR</span>
          </button>
          <button onclick="setLanguage('ar', '🇸🇦', 'AR', 'rtl')" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 flex items-center justify-between font-semibold text-slate-700">
            <span>🇸🇦 العربية</span>
            <span class="text-[10px] text-brand-600 font-bold">RTL</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Notification Bell & Dropdown Hub -->
    <div class="relative" id="notif-dropdown-wrapper">
      <button id="notif-dropdown-toggle"
        class="relative h-9 w-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:border-slate-300 transition shadow-sm cursor-pointer"
        onclick="toggleNotifDropdown()"
        title="Notifications">
        <i class="fa-regular fa-bell text-sm"></i>
        <span id="notif-badge-dot" class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-rose-500 border-2 border-white"></span>
      </button>

      <div id="notif-dropdown-panel"
        class="hidden absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden text-xs font-sans">
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
          <span class="font-extrabold text-slate-900">Notifications</span>
          <button onclick="markAllNotificationsRead()" class="text-[10px] font-bold text-brand-600 hover:underline border-0 bg-transparent cursor-pointer">Mark all as read</button>
        </div>
        <div id="notif-list-container" class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
          <div class="p-4 text-center text-slate-400 text-xs">Loading notifications...</div>
        </div>
      </div>
    </div>

    <!-- User Dropdown -->
    <div class="relative" id="user-dropdown-wrapper">
      <button id="user-dropdown-toggle"
        class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-slate-300 transition shadow-sm cursor-pointer select-none"
        onclick="toggleUserDropdown()">
        <div id="header-avatar"
          class="h-8 w-8 rounded-lg bg-gradient-to-tr from-brand-500 to-violet-500 flex items-center justify-center text-white font-bold uppercase text-sm shrink-0">
          U
        </div>
        <div class="text-left hidden sm:block">
          <p class="text-xs font-bold text-slate-900 leading-tight" id="header-user-name">Loading…</p>
          <p class="text-[10px] font-semibold leading-tight" id="header-2fa-badge">—</p>
        </div>
        <i id="header-chevron"
          class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-0.5 transition-transform duration-200"></i>
      </button>

      <!-- Dropdown Panel -->
      <div id="user-dropdown-panel"
        class="hidden absolute right-0 mt-2 w-60 bg-white border border-slate-200 rounded-xl shadow-xl shadow-slate-900/10 z-50 overflow-hidden">

        <!-- User info block -->
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
          <p class="text-xs font-bold text-slate-900 truncate" id="dropdown-user-name">User Name</p>
          <p class="text-[11px] text-slate-400 truncate" id="dropdown-user-email">user@email.com</p>
        </div>

        <!-- Menu Items -->
        <div class="py-1.5">
          <a href="/admin/profile"
            class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
            <i class="fa-regular fa-circle-user w-4 text-center text-slate-400"></i>
            My Profile
          </a>

          <!-- 2FA Toggle — label and action change based on current state -->
          <button type="button" id="btn-header-2fa"
            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
            <i class="fa-solid fa-shield-halved w-4 text-center text-slate-400"></i>
            <span id="btn-header-2fa-label">Two-Factor Auth</span>
          </button>

          <button type="button" onclick="openChangePasswordModal()"
            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
            <i class="fa-solid fa-key w-4 text-center text-slate-400"></i>
            Change Password
          </button>
        </div>

        <!-- Logout -->
        <div class="border-t border-slate-100 py-1.5">
          <button type="button" id="header-logout-btn"
            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
            <i class="fa-solid fa-right-from-bracket w-4 text-center text-rose-500"></i>
            Sign Out
          </button>
        </div>
      </div>
    </div>
  </div>
</header>



<!-- ============================================================ -->
<!-- 2FA ENABLE MODAL (QR + verify code)                         -->
<!-- ============================================================ -->
<div id="modal-2fa-enable" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="close2FAModal()"></div>
    <div
      class="relative bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 font-sans">

      <!-- Step 1: QR + secret + verify code -->
      <div id="2fa-step-scan" class="space-y-4">
        <div class="flex items-center gap-3">
          <div
            class="h-10 w-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <div>
            <h3 class="font-bold text-slate-900 text-base">Enable Two-Factor Auth</h3>
            <p class="text-xs text-slate-400">Scan the QR code with your authenticator app</p>
          </div>
        </div>

        <!-- QR canvas -->
        <div class="flex flex-col items-center gap-2 p-4 bg-slate-50 border border-slate-200 rounded-xl">
          <canvas id="2fa-qr-canvas" class="p-2 bg-white rounded-lg shadow-sm border border-slate-100"></canvas>
          <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Scan with Google
            Authenticator</span>
        </div>

        <!-- Manual secret key -->
        <div>
          <p class="text-xs text-slate-500 font-medium mb-1.5">Or enter this key manually:</p>
          <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5">
            <span id="2fa-secret-key" class="font-mono text-sm font-bold text-emerald-600 tracking-wider">——</span>
            <button type="button"
              onclick="navigator.clipboard.writeText(document.getElementById('2fa-secret-key').textContent); showToast('success', 'Secret key copied!')"
              class="text-slate-400 hover:text-slate-700 transition ml-3">
              <i class="fa-regular fa-copy text-sm"></i>
            </button>
          </div>
        </div>

        <!-- 6-digit code input -->
        <div class="space-y-1 border-t border-slate-100 pt-3">
          <label for="2fa-setup-code" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Enter
            6-digit code from app</label>
          <input id="2fa-setup-code" type="text" maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
            class="w-full text-center tracking-[0.5em] text-xl font-mono bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-slate-900 focus:outline-none focus:border-brand-500 placeholder-slate-300"
            placeholder="000000">
        </div>

        <div class="flex gap-3">
          <button type="button" onclick="close2FAModal()"
            class="w-1/2 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">
            Cancel
          </button>
          <button type="button" id="btn-verify-2fa"
            class="w-1/2 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition">
            Confirm & Enable
          </button>
        </div>
      </div>

      <!-- Step 2: Recovery codes -->
      <div id="2fa-step-recovery" class="hidden space-y-4">
        <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-2.5">
          <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5"></i>
          <div>
            <p class="text-xs font-bold text-emerald-700">2FA is now active on your account!</p>
            <p class="text-xs text-emerald-600 mt-0.5">Save these recovery codes in a secure location. Each is
              single-use.</p>
          </div>
        </div>
        <textarea id="2fa-recovery-codes-text" readonly rows="6"
          class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-xs font-mono text-slate-700 focus:outline-none select-all leading-relaxed resize-none"></textarea>
        <button type="button" id="btn-finish-2fa"
          class="w-full py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition">
          Done — I've saved my codes
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================ -->
<!-- 2FA DISABLE MODAL (password confirmation)                   -->
<!-- ============================================================ -->
<div id="modal-2fa-disable" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="close2FADisableModal()"></div>
    <div
      class="relative bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 font-sans">
      <div class="flex items-center gap-3">
        <div
          class="h-10 w-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
          <i class="fa-solid fa-shield-slash"></i>
        </div>
        <div>
          <h3 class="font-bold text-slate-900 text-base">Disable Two-Factor Auth</h3>
          <p class="text-xs text-slate-400 mt-0.5">Confirm your password to remove 2FA protection</p>
        </div>
      </div>

      <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2.5">
        <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm mt-0.5"></i>
        <p class="text-xs text-amber-700 font-semibold">Disabling 2FA reduces your account security. Your account will
          rely on password-only authentication.</p>
      </div>

      <div class="space-y-1">
        <label for="2fa-disable-password" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Current
          Password</label>
        <input id="2fa-disable-password" type="password" required autocomplete="current-password"
          class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-rose-400 transition">
      </div>

      <div class="flex gap-3">
        <button type="button" onclick="close2FADisableModal()"
          class="w-1/2 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">
          Cancel
        </button>
        <button type="button" id="btn-confirm-disable-2fa"
          class="w-1/2 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition">
          Disable 2FA
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================ -->
<!-- CHANGE PASSWORD MODAL                                        -->
<!-- ============================================================ -->
<div id="modal-change-password" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeChangePasswordModal()"></div>
    <form id="form-change-password"
      class="relative bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 font-sans">
      <div class="flex items-center gap-3">
        <div
          class="h-10 w-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600">
          <i class="fa-solid fa-key"></i>
        </div>
        <div>
          <h3 class="font-bold text-slate-900 text-base">Change Password</h3>
          <p class="text-xs text-slate-400 mt-0.5">Update your account security credentials</p>
        </div>
      </div>
      <div class="space-y-4">
        <div class="space-y-1">
          <label for="cp-current" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Current
            Password</label>
          <input id="cp-current" type="password" required autocomplete="current-password"
            class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
        </div>
        <div class="space-y-1">
          <label for="cp-new" class="text-xs font-semibold uppercase tracking-wider text-slate-500">New Password</label>
          <input id="cp-new" type="password" required autocomplete="new-password" minlength="8"
            class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
        </div>
        <div class="space-y-1">
          <label for="cp-confirm" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Confirm New
            Password</label>
          <input id="cp-confirm" type="password" required autocomplete="new-password" minlength="8"
            class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
        </div>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="button" onclick="closeChangePasswordModal()"
          class="w-1/2 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">
          Cancel
        </button>
        <button type="submit"
          class="w-1/2 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm shadow-brand-600/20">
          Update Password
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ============================================================ -->
<!-- HEADER JAVASCRIPT                                            -->
<!-- ============================================================ -->
<script>
  // ── State ──────────────────────────────────────────────────────
  let _2faEnabled = false;

  /**
   * Boot header user info from localStorage session.
   * Populates avatar initial, name, 2FA badge, and dropdown fields.
   */
  (function initHeaderUser() {
    const session = localStorage.getItem('laraforgex_user');
    if (!session) return;

    const user = JSON.parse(session);
    _2faEnabled = !!user.two_factor_enabled;

    const initial = (user.name || 'U').charAt(0).toUpperCase();
    document.getElementById('header-avatar').textContent = initial;
    document.getElementById('header-user-name').textContent = user.name || 'Administrator';
    document.getElementById('dropdown-user-name').textContent = user.name || 'Administrator';
    document.getElementById('dropdown-user-email').textContent = user.email || '';

    refresh2FADropdownState();
  })();

  /**
   * Updates the 2FA badge in the dropdown button and the dropdown label
   * to reflect whether 2FA is currently enabled or disabled.
   */
  function refresh2FADropdownState() {
    const badge = document.getElementById('header-2fa-badge');
    const label = document.getElementById('btn-header-2fa-label');

    if (_2faEnabled) {
      badge.innerHTML = '<span class="inline-flex items-center gap-1 text-emerald-600 font-bold"><i class="fa-solid fa-shield-halved text-[9px]"></i> 2FA On</span>';
      label.textContent = 'Disable 2FA';
    } else {
      badge.innerHTML = '<span class="text-slate-400 font-semibold">2FA Off</span>';
      label.textContent = 'Enable 2FA';
    }
  }

  // ── Dropdown toggle ──────────────────────────────────────────────
  function toggleUserDropdown() {
    const panel = document.getElementById('user-dropdown-panel');
    const chevron = document.getElementById('header-chevron');
    const isHidden = panel.classList.contains('hidden');
    panel.classList.toggle('hidden', !isHidden);
    chevron.classList.toggle('rotate-180', isHidden);
  }

  document.addEventListener('click', function (e) {
    const wrapper = document.getElementById('user-dropdown-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
      document.getElementById('user-dropdown-panel').classList.add('hidden');
      document.getElementById('header-chevron').classList.remove('rotate-180');
    }
  });

  // ── 2FA Enable modal helpers ─────────────────────────────────────
  function open2FAEnableModal() {
    document.getElementById('2fa-step-scan').classList.remove('hidden');
    document.getElementById('2fa-step-recovery').classList.add('hidden');
    document.getElementById('2fa-setup-code').value = '';
    document.getElementById('modal-2fa-enable').classList.remove('hidden');
    document.getElementById('user-dropdown-panel').classList.add('hidden');
    document.getElementById('header-chevron').classList.remove('rotate-180');
  }

  function close2FAModal() {
    document.getElementById('modal-2fa-enable').classList.add('hidden');
    document.getElementById('2fa-setup-code').value = '';
  }

  // ── 2FA Disable modal helpers ────────────────────────────────────
  function open2FADisableModal() {
    document.getElementById('2fa-disable-password').value = '';
    document.getElementById('modal-2fa-disable').classList.remove('hidden');
    document.getElementById('user-dropdown-panel').classList.add('hidden');
    document.getElementById('header-chevron').classList.remove('rotate-180');
  }

  function close2FADisableModal() {
    document.getElementById('modal-2fa-disable').classList.add('hidden');
    document.getElementById('2fa-disable-password').value = '';
  }

  // ── Change Password modal helpers ────────────────────────────────
  window.openChangePasswordModal = function () {
    document.getElementById('modal-change-password').classList.remove('hidden');
    document.getElementById('user-dropdown-panel').classList.add('hidden');
    document.getElementById('header-chevron').classList.remove('rotate-180');
  };

  window.closeChangePasswordModal = function () {
    document.getElementById('modal-change-password').classList.add('hidden');
    document.getElementById('form-change-password').reset();
  };

  // ── Wire up all handlers after DOM ready ─────────────────────────
  document.addEventListener('DOMContentLoaded', function () {

    // Header logout
    document.getElementById('header-logout-btn').addEventListener('click', async function () {
      try { await axios.post('/auth/logout'); } catch (_) { }
      localStorage.removeItem('laraforgex_auth_token');
      localStorage.removeItem('laraforgex_user');
      window.location.href = '/admin/login';
    });

    // 2FA toggle button — routes to enable or disable based on current state
    document.getElementById('btn-header-2fa').addEventListener('click', async function () {
      if (_2faEnabled) {
        open2FADisableModal();
      } else {
        // Fetch QR code + secret from API
        try {
          const response = await axios.post('/auth/2fa/enable');
          if (response.data.success) {
            const data = response.data.data;
            document.getElementById('2fa-secret-key').textContent = data.secret;

            new QRious({
              element: document.getElementById('2fa-qr-canvas'),
              value: data.qr_code_url,
              size: 180
            });

            open2FAEnableModal();
          }
        } catch (e) {
          window.handleAjaxError(e);
        }
      }
    });

    // Verify TOTP code and complete 2FA enable
    document.getElementById('btn-verify-2fa').addEventListener('click', async function () {
      const code = document.getElementById('2fa-setup-code').value.trim();
      if (!code || code.length !== 6) {
        window.showToast('warning', 'Please enter the 6-digit code from your authenticator app.');
        return;
      }

      try {
        const response = await axios.post('/auth/2fa/verify', { code });
        if (response.data.success) {
          const codes = response.data.data.recovery_codes;
          document.getElementById('2fa-recovery-codes-text').value = codes.join('\n');

          // Update local session state
          const session = JSON.parse(localStorage.getItem('laraforgex_user') || '{}');
          session.two_factor_enabled = true;
          localStorage.setItem('laraforgex_user', JSON.stringify(session));
          _2faEnabled = true;
          refresh2FADropdownState();

          document.getElementById('2fa-step-scan').classList.add('hidden');
          document.getElementById('2fa-step-recovery').classList.remove('hidden');
          window.showToast('success', '2FA enabled! Save your recovery codes.');
        }
      } catch (e) {
        window.handleAjaxError(e, '2FA verification failed. Check your code and try again.');
      }
    });

    // Finish 2FA setup (dismiss recovery codes screen)
    document.getElementById('btn-finish-2fa').addEventListener('click', function () {
      close2FAModal();
    });

    // Confirm 2FA disable
    document.getElementById('btn-confirm-disable-2fa').addEventListener('click', async function () {
      const password = document.getElementById('2fa-disable-password').value;
      if (!password) {
        window.showToast('warning', 'Please enter your current password to confirm.');
        return;
      }

      try {
        await axios.post('/auth/2fa/disable', { password });

        // Update local session state
        const session = JSON.parse(localStorage.getItem('laraforgex_user') || '{}');
        session.two_factor_enabled = false;
        localStorage.setItem('laraforgex_user', JSON.stringify(session));
        _2faEnabled = false;
        refresh2FADropdownState();

        close2FADisableModal();
        window.showToast('success', 'Two-factor authentication has been disabled.');
      } catch (e) {
        window.handleAjaxError(e, 'Failed to disable 2FA. Check your password and try again.');
      }
    });

    // Change password form
    document.getElementById('form-change-password').addEventListener('submit', async function (e) {
      e.preventDefault();
      const current = document.getElementById('cp-current').value;
      const newPwd = document.getElementById('cp-new').value;
      const confirm = document.getElementById('cp-confirm').value;

      if (newPwd !== confirm) {
        window.showToast('warning', 'New passwords do not match.');
        return;
      }

      try {
        await axios.put('/auth/change-password', {
          current_password: current,
          password: newPwd,
          password_confirmation: confirm
        });
        window.showToast('success', 'Password updated! You will be signed out now.');
        window.closeChangePasswordModal();

        setTimeout(async () => {
          try { await axios.post('/auth/logout'); } catch (_) { }
          localStorage.removeItem('laraforgex_auth_token');
          localStorage.removeItem('laraforgex_user');
          window.location.href = '/admin/login';
        }, 1800);
      } catch (err) {
        window.handleAjaxError(err, 'Failed to update password.');
      }
    });
  });

  function toggleThemeDropdown() {
    $('#theme-dropdown-panel').toggleClass('hidden');
  }

  function applyAccentColor(color) {
    if (!color) return;
    let styleEl = document.getElementById('dynamic-accent-style');
    if (!styleEl) {
      styleEl = document.createElement('style');
      styleEl.id = 'dynamic-accent-style';
      document.head.appendChild(styleEl);
    }
    styleEl.textContent = `
      .bg-brand-600, .bg-brand-500 { background-color: ${color} !important; }
      .text-brand-600, .text-brand-500 { color: ${color} !important; }
      .border-brand-500, .border-brand-600 { border-color: ${color} !important; }
      .bg-brand-50 { background-color: ${color}18 !important; }
    `;
  }

  function setAccentTheme(color) {
    localStorage.setItem('laraforgex_theme_color', color);
    applyAccentColor(color);
    $('#theme-dropdown-panel').addClass('hidden');
    if (typeof showToast === 'function') {
      showToast('success', 'Theme accent color updated!');
    }
  }

  function toggleLangDropdown() {
    $('#lang-dropdown-panel').toggleClass('hidden');
  }

  function setLanguage(code, flag, label, dir) {
    localStorage.setItem('laraforgex_lang_code', code);
    localStorage.setItem('laraforgex_lang_flag', flag);
    localStorage.setItem('laraforgex_lang_label', label);
    localStorage.setItem('laraforgex_lang_dir', dir);

    $('#current-lang-flag').text(flag);
    $('#current-lang-code').text(label);
    $('html').attr('dir', dir);
    $('#lang-dropdown-panel').addClass('hidden');

    if (typeof window.translatePage === 'function') {
      window.translatePage(code);
    }

    if (typeof showToast === 'function') {
      showToast('success', `Language set to ${label} (${dir.toUpperCase()})`);
    }
  }

  $(document).ready(function() {
    const savedCode = localStorage.getItem('laraforgex_lang_code');
    const savedFlag = localStorage.getItem('laraforgex_lang_flag');
    const savedLabel = localStorage.getItem('laraforgex_lang_label');
    const savedDir = localStorage.getItem('laraforgex_lang_dir');

    if (savedFlag && savedLabel && savedDir) {
      $('#current-lang-flag').text(savedFlag);
      $('#current-lang-code').text(savedLabel);
      $('html').attr('dir', savedDir);
      if (typeof window.translatePage === 'function' && savedCode) {
        window.translatePage(savedCode);
      }
    }
  });

  $(document).on('click', function(e) {
    if (!$(e.target).closest('#lang-dropdown-wrapper').length) {
      $('#lang-dropdown-panel').addClass('hidden');
    }
  });
  function toggleNotifDropdown() {
    const isHidden = $('#notif-dropdown-panel').hasClass('hidden');
    $('#notif-dropdown-panel').toggleClass('hidden');
    if (isHidden) {
      loadNotifications();
    }
  }

  async function loadNotifications() {
    try {
      const res = await axios.get('/notifications');
      const items = res.data.data || [];
      const unreadCount = res.data.unread_count || 0;

      if (unreadCount > 0) {
        $('#notif-badge-dot').removeClass('hidden');
      } else {
        $('#notif-badge-dot').addClass('hidden');
      }

      if (items.length === 0) {
        $('#notif-list-container').html('<div class="p-4 text-center text-slate-400 text-xs">No new notifications</div>');
        return;
      }

      let html = '';
      items.forEach(item => {
        html += `
          <div class="p-3 hover:bg-slate-50 transition flex items-start gap-3 ${item.read ? 'opacity-60' : ''}">
            <div class="h-7 w-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid ${item.icon}"></i>
            </div>
            <div class="min-w-0 flex-1">
              <p class="font-bold text-slate-900 leading-tight">${item.title}</p>
              <p class="text-slate-500 text-[11px] leading-snug truncate mt-0.5">${item.message}</p>
              <span class="text-[9px] text-slate-400 font-medium block mt-1">${item.time_ago}</span>
            </div>
          </div>
        `;
      });

      $('#notif-list-container').html(html);
    } catch (e) {
      $('#notif-list-container').html('<div class="p-4 text-center text-rose-500 text-xs">Failed to load notifications</div>');
    }
  }

  async function markAllNotificationsRead() {
    try {
      await axios.post('/notifications/mark-read');
      $('#notif-badge-dot').addClass('hidden');
      loadNotifications();
      if (typeof showToast === 'function') {
        showToast('success', 'Notifications marked as read');
      }
    } catch (e) { }
  }

  $(document).ready(function() {
    loadNotifications();
  });

  $(document).on('click', function(e) {
    if (!$(e.target).closest('#notif-dropdown-wrapper').length) {
      $('#notif-dropdown-panel').addClass('hidden');
    }
  });
</script>
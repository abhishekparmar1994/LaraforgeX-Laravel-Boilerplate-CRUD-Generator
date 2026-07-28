<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LaraforgeX Core Admin')</title>

  <!-- Tailwind CDN (offline vendor) -->
  <script src="{{ asset('vendor/tailwind/tailwind.js') }}"></script>
  <!-- Universal i18n Translations -->
  <script src="{{ asset('vendor/i18n/translations.js') }}"></script>
  <!-- ApexCharts CDN -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    (function () {
      var savedColor = localStorage.getItem('laraforgex_theme_color');
      if (savedColor) {
        var styleEl = document.createElement('style');
        styleEl.id = 'dynamic-accent-style';
        var gradientMap = {
          '#2b47ff': 'linear-gradient(135deg, #1b2eff 0%, #2b47ff 50%, #4338ca 100%)',
          '#10b981': 'linear-gradient(135deg, #047857 0%, #10b981 50%, #0f766e 100%)',
          '#8b5cf6': 'linear-gradient(135deg, #5b21b6 0%, #8b5cf6 50%, #6d28d9 100%)',
          '#f59e0b': 'linear-gradient(135deg, #b45309 0%, #f59e0b 50%, #c2410c 100%)',
          '#f43f5e': 'linear-gradient(135deg, #be123c 0%, #f43f5e 50%, #9f1239 100%)',
        };
        var gradient = gradientMap[savedColor.toLowerCase()] || `linear-gradient(135deg, ${savedColor} 0%, ${savedColor} 100%)`;

        styleEl.textContent = `
          .bg-brand-600, .bg-brand-500, .bg-brand-400 { background-color: ${savedColor} !important; }
          .text-brand-600, .text-brand-500, .text-brand-400 { color: ${savedColor} !important; }
          .border-brand-500, .border-brand-600, .border-l-brand-500, .border-l-brand-400 { border-color: ${savedColor} !important; }
          .bg-brand-50 { background-color: ${savedColor}18 !important; }
          .border-brand-100 { border-color: ${savedColor}33 !important; }

          .theme-hero-banner,
          .bg-gradient-to-r.from-brand-900,
          .bg-gradient-to-r.from-brand-600,
          .bg-gradient-to-r.from-brand-500 {
            background: ${gradient} !important;
          }
        `;
        document.head.appendChild(styleEl);
      }
    })();
  </script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#ebf0ff', 100: '#d6e0ff', 200: '#b3c7ff',
              300: '#85a3ff', 400: '#5275ff', 500: '#2b47ff',
              600: '#1b2eff', 700: '#111fff', 800: '#0007e0', 900: '#0004a8',
            }
          },
          screens: { xs: '475px' }
        }
      }
    }
  </script>
  <style>
    /* ── Toast Multi-Theme & Screen Position Engine ─────────────── */
    #laraforgex-toast-container {
      position: fixed;
      z-index: 999999;
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      max-width: 420px;
      width: calc(100vw - 2.5rem);
      pointer-events: none;
      transition: all 0.3s ease;
    }

    /* Screen Positions with !important overrides for instant live testing */
    #laraforgex-toast-container.pos-top-right,
    #laraforgex-toast-container {
      top: 1.25rem;
      right: 1.25rem;
      left: auto;
      bottom: auto;
      transform: none;
    }

    #laraforgex-toast-container.pos-top-left {
      top: 1.25rem !important;
      left: 1.25rem !important;
      right: auto !important;
      bottom: auto !important;
      transform: none !important;
    }

    #laraforgex-toast-container.pos-top-center {
      top: 1.25rem !important;
      left: 50% !important;
      right: auto !important;
      bottom: auto !important;
      transform: translateX(-50%) !important;
    }

    #laraforgex-toast-container.pos-bottom-right {
      bottom: 1.25rem !important;
      right: 1.25rem !important;
      top: auto !important;
      left: auto !important;
      flex-direction: column-reverse !important;
      transform: none !important;
    }

    #laraforgex-toast-container.pos-bottom-left {
      bottom: 1.25rem !important;
      left: 1.25rem !important;
      top: auto !important;
      right: auto !important;
      flex-direction: column-reverse !important;
      transform: none !important;
    }

    /* Base Toast Card */
    .lf-toast-card {
      pointer-events: auto;
      position: relative;
      border-radius: 1.125rem;
      padding: 0.95rem 1.125rem;
      font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      display: flex;
      align-items: flex-start;
      gap: 0.875rem;
      overflow: hidden;
      animation: obsidianToastIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .lf-toast-card:hover {
      transform: translateY(-2px);
    }

    .lf-toast-card.hide {
      animation: obsidianToastOut 0.3s cubic-bezier(0.7, 0, 0.84, 0) forwards;
    }

    @keyframes obsidianToastIn {
      from {
        opacity: 0;
        transform: translateY(-20px) scale(0.92);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes obsidianToastOut {
      from {
        opacity: 1;
        transform: translateY(0) scale(1);
      }

      to {
        opacity: 0;
        transform: translateY(-16px) scale(0.92);
      }
    }

    /* ── THEME 1: obsidian (Deep Obsidian Glass) ────────────────── */
    .theme-obsidian.lf-toast-card {
      background: rgba(10, 10, 14, 0.92);
      backdrop-filter: blur(24px) saturate(210%);
      -webkit-backdrop-filter: blur(24px) saturate(210%);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-left-width: 4px;
      color: #ffffff;
    }

    .theme-obsidian.lf-toast-success {
      border-left-color: #10b981;
      box-shadow: -4px 0 20px -2px rgba(16, 185, 129, 0.4), 0 20px 30px -5px rgba(0, 0, 0, 0.75);
    }

    .theme-obsidian.lf-toast-error {
      border-left-color: #f43f5e;
      box-shadow: -4px 0 20px -2px rgba(244, 63, 94, 0.4), 0 20px 30px -5px rgba(0, 0, 0, 0.75);
    }

    .theme-obsidian.lf-toast-warning {
      border-left-color: #f59e0b;
      box-shadow: -4px 0 20px -2px rgba(245, 158, 11, 0.4), 0 20px 30px -5px rgba(0, 0, 0, 0.75);
    }

    .theme-obsidian.lf-toast-info {
      border-left-color: #38bdf8;
      box-shadow: -4px 0 20px -2px rgba(56, 189, 248, 0.4), 0 20px 30px -5px rgba(0, 0, 0, 0.75);
    }

    .theme-obsidian .lf-toast-title {
      color: #ffffff;
      font-weight: 700;
    }

    .theme-obsidian .lf-toast-message {
      color: #cbd5e1;
    }

    .theme-obsidian .lf-toast-close {
      color: #64748b;
    }

    .theme-obsidian .lf-toast-close:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.1);
    }

    /* ── THEME 2: white_glass (White Frosted Glass) ─────────────── */
    .theme-white_glass.lf-toast-card {
      background: rgba(255, 255, 255, 0.94);
      backdrop-filter: blur(24px) saturate(200%);
      -webkit-backdrop-filter: blur(24px) saturate(200%);
      border: 1px solid rgba(226, 232, 240, 0.9);
      border-left-width: 4px;
      color: #0f172a;
      box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.12), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    }

    .theme-white_glass.lf-toast-success {
      border-left-color: #10b981;
    }

    .theme-white_glass.lf-toast-error {
      border-left-color: #f43f5e;
    }

    .theme-white_glass.lf-toast-warning {
      border-left-color: #f59e0b;
    }

    .theme-white_glass.lf-toast-info {
      border-left-color: #38bdf8;
    }

    .theme-white_glass .lf-toast-title {
      color: #0f172a;
      font-weight: 700;
    }

    .theme-white_glass .lf-toast-message {
      color: #475569;
    }

    .theme-white_glass .lf-toast-close {
      color: #94a3b8;
    }

    .theme-white_glass .lf-toast-close:hover {
      color: #0f172a;
      background: rgba(0, 0, 0, 0.05);
    }

    /* ── THEME 3: solid_vibrant (Solid Gradient Pill) ───────────── */
    .theme-solid_vibrant.lf-toast-card {
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #ffffff;
      box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.3);
    }

    .theme-solid_vibrant.lf-toast-success {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }

    .theme-solid_vibrant.lf-toast-error {
      background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%);
    }

    .theme-solid_vibrant.lf-toast-warning {
      background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%);
    }

    .theme-solid_vibrant.lf-toast-info {
      background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
    }

    .theme-solid_vibrant .lf-toast-title {
      color: #ffffff;
      font-weight: 800;
    }

    .theme-solid_vibrant .lf-toast-message {
      color: rgba(255, 255, 255, 0.9);
    }

    .theme-solid_vibrant .lf-toast-icon-wrap {
      background: rgba(255, 255, 255, 0.2);
      color: #ffffff;
      border-color: rgba(255, 255, 255, 0.3);
    }

    .theme-solid_vibrant .lf-toast-close {
      color: rgba(255, 255, 255, 0.7);
    }

    .theme-solid_vibrant .lf-toast-close:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.2);
    }

    /* ── THEME 4: minimal_dark (Minimal Matte Dark) ─────────────── */
    .theme-minimal_dark.lf-toast-card {
      background: #18181b;
      border: 1px solid #27272a;
      color: #ffffff;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
    }

    .theme-minimal_dark.lf-toast-success {
      border-left: 3px solid #10b981;
    }

    .theme-minimal_dark.lf-toast-error {
      border-left: 3px solid #f43f5e;
    }

    .theme-minimal_dark.lf-toast-warning {
      border-left: 3px solid #f59e0b;
    }

    .theme-minimal_dark.lf-toast-info {
      border-left: 3px solid #38bdf8;
    }

    .theme-minimal_dark .lf-toast-title {
      color: #f4f4f5;
      font-weight: 700;
    }

    .theme-minimal_dark .lf-toast-message {
      color: #a1a1aa;
    }

    .theme-minimal_dark .lf-toast-close {
      color: #71717a;
    }

    .theme-minimal_dark .lf-toast-close:hover {
      color: #ffffff;
    }

    /* Shared Icon Badge Styles */
    .lf-toast-icon-wrap {
      flex-shrink: 0;
      width: 2.125rem;
      height: 2.125rem;
      border-radius: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
    }

    .theme-obsidian .lf-toast-success .lf-toast-icon-wrap,
    .theme-white_glass .lf-toast-success .lf-toast-icon-wrap,
    .theme-minimal_dark .lf-toast-success .lf-toast-icon-wrap {
      background: rgba(16, 185, 129, 0.15);
      color: #34d399;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .theme-obsidian .lf-toast-error .lf-toast-icon-wrap,
    .theme-white_glass .lf-toast-error .lf-toast-icon-wrap,
    .theme-minimal_dark .lf-toast-error .lf-toast-icon-wrap {
      background: rgba(244, 63, 94, 0.15);
      color: #fb7185;
      border: 1px solid rgba(244, 63, 94, 0.3);
    }

    .theme-obsidian .lf-toast-warning .lf-toast-icon-wrap,
    .theme-white_glass .lf-toast-warning .lf-toast-icon-wrap,
    .theme-minimal_dark .lf-toast-warning .lf-toast-icon-wrap {
      background: rgba(245, 158, 11, 0.15);
      color: #fbbf24;
      border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .theme-obsidian .lf-toast-info .lf-toast-icon-wrap,
    .theme-white_glass .lf-toast-info .lf-toast-icon-wrap,
    .theme-minimal_dark .lf-toast-info .lf-toast-icon-wrap {
      background: rgba(56, 189, 248, 0.15);
      color: #38bdf8;
      border: 1px solid rgba(56, 189, 248, 0.3);
    }

    .lf-toast-content {
      flex: 1;
      min-width: 0;
      padding-top: 0.1rem;
    }

    .lf-toast-header {
      display: flex;
      align-items: center;
    }

    .lf-toast-title {
      font-size: 0.875rem;
      line-height: 1.2rem;
      letter-spacing: -0.01em;
    }

    .lf-toast-message {
      font-size: 0.8125rem;
      line-height: 1.2rem;
      margin-top: 0.2rem;
      word-break: break-word;
    }

    .lf-toast-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-top: 0.625rem;
    }

    .lf-toast-action-btn {
      font-size: 0.8125rem;
      font-weight: 600;
      background: transparent;
      border: none;
      padding: 0;
      cursor: pointer;
    }

    .lf-toast-action-btn:hover {
      text-decoration: underline;
    }

    .lf-toast-close {
      background: transparent;
      border: none;
      width: 1.75rem;
      height: 1.75rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      cursor: pointer;
      line-height: 1;
      transition: all 0.15s ease;
      flex-shrink: 0;
      margin-top: -0.1rem;
      margin-right: -0.25rem;
    }

    .lf-toast-progress-track {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: rgba(255, 255, 255, 0.08);
      overflow: hidden;
    }

    .lf-toast-progress-bar {
      height: 100%;
      width: 100%;
      transform-origin: left;
      animation: obsidianToastProgress 4.5s linear forwards;
    }

    .lf-toast-success .lf-toast-progress-bar {
      background: linear-gradient(90deg, #10b981, #059669);
    }

    .lf-toast-error .lf-toast-progress-bar {
      background: linear-gradient(90deg, #f43f5e, #be123c);
    }

    .lf-toast-warning .lf-toast-progress-bar {
      background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    .lf-toast-info .lf-toast-progress-bar {
      background: linear-gradient(90deg, #38bdf8, #0284c7);
    }

    /* Sidebar slide transition */
    #admin-sidebar {
      transition: transform 0.25s ease, width 0.25s ease;
    }

    /* Smooth overlay fade */
    #sidebar-overlay {
      transition: opacity 0.25s ease;
    }

    /* Sidebar Popovers: Hidden by default */
    .sidebar-popover {
      display: none !important;
    }

    /* Caret pointer for dark tooltips pointing left to icon */
    .sidebar-tooltip-caret::before {
      content: '';
      position: absolute;
      right: 100%;
      top: 50%;
      transform: translateY(-50%);
      border-width: 6px;
      border-style: solid;
      border-color: transparent rgba(15, 23, 42, 0.95) transparent transparent;
    }

    @keyframes popoverFadeIn {
      0% {
        opacity: 0;
        transform: translateY(-50%) translateX(-8px) scale(0.95);
      }

      100% {
        opacity: 1;
        transform: translateY(-50%) translateX(0) scale(1);
      }
    }

    /* Collapsed sidebar styling on desktop */
    @media (min-width: 1024px) {
      #admin-sidebar.sidebar-collapsed {
        width: 4.5rem !important;
        overflow: visible !important;
        /* Collapsed width */
      }

      #admin-sidebar.sidebar-collapsed>div {
        overflow: visible !important;
      }

      #admin-sidebar.sidebar-collapsed .px-4 {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
      }

      /* Hide text, headers, submenus and chevrons in collapsed mode */
      #admin-sidebar.sidebar-collapsed nav p {
        display: none !important;
      }

      /* Hide only direct spans of links/buttons, preserving popover spans */
      #admin-sidebar.sidebar-collapsed nav .sidebar-link>span,
      #admin-sidebar.sidebar-collapsed nav button>div>span,
      #admin-sidebar.sidebar-collapsed nav button>span,
      #admin-sidebar.sidebar-collapsed .logo-title-span {
        display: none !important;
      }

      #admin-sidebar.sidebar-collapsed div.min-w-0 {
        display: none !important;
      }

      #admin-sidebar.sidebar-collapsed .fa-chevron-right {
        display: none !important;
      }

      #admin-sidebar.sidebar-collapsed .submenu-wrapper {
        display: none !important;
      }

      #admin-sidebar.sidebar-collapsed #user-avatar-placeholder+div {
        display: none !important;
      }

      /* Center navigation items and icons */
      #admin-sidebar.sidebar-collapsed .sidebar-link,
      #admin-sidebar.sidebar-collapsed nav button[type="button"] {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }

      #admin-sidebar.sidebar-collapsed #logout-btn {
        padding-left: 0 !important;
        padding-right: 0 !important;
        justify-content: center !important;
        font-size: 0 !important;
      }

      #admin-sidebar.sidebar-collapsed #logout-btn i {
        font-size: 0.875rem !important;
        /* keep icon visible */
      }

      #admin-sidebar.sidebar-collapsed .sidebar-group:hover .sidebar-popover {
        display: flex !important;
        animation: popoverFadeIn 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-700 antialiased selection:bg-brand-500 selection:text-white">



  <!-- ── Sidebar mobile overlay ───────────────────────────────── -->
  <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-30 hidden lg:hidden" onclick="closeSidebar()"></div>

  <!-- ── Root layout grid ─────────────────────────────────────── -->
  <div class="min-h-screen flex bg-slate-50 text-slate-800 font-sans">

    @include('admin.includes.sidebar')

    <!-- Main content column -->
    <main class="flex-1 min-w-0 flex flex-col overflow-x-hidden">

      <!-- Topbar (user dropdown, hamburger) -->
      <div class="px-4 sm:px-5 lg:px-6 pt-5 mb-6">
        @include('admin.includes.header')
      </div>

      <!-- Page content -->
      <div class="flex-1 px-4 sm:px-5 lg:px-6 pb-8">
        @yield('content')
      </div>

      <!-- Footer -->
      <div class="px-4 sm:px-5 lg:px-6">
        @include('admin.includes.footer')
      </div>
    </main>
  </div>

  <!-- ── Vendor JS ─────────────────────────────────────────────── -->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/axios/axios.min.js') }}"></script>
  <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('vendor/dropzone/dropzone.min.js') }}"></script>
  <script src="{{ asset('vendor/fontawesome/all.min.js') }}"></script>
  <script src="{{ asset('vendor/qrious/qrious.min.js') }}"></script>

  <!-- ── Global JS (Pure jQuery Engine) ────────────────────────── -->
  <script>
    // ── Axios CSRF & Auth Interceptors ───────────────────────────
    axios.defaults.baseURL = '/api/v1';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    const _csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (_csrfToken) axios.defaults.headers.common['X-CSRF-TOKEN'] = _csrfToken;

    axios.interceptors.request.use(cfg => {
      const t = localStorage.getItem('laraforgex_auth_token');
      if (t) cfg.headers.Authorization = `Bearer ${t}`;
      return cfg;
    }, err => Promise.reject(err));

    axios.interceptors.response.use(res => res, err => {
      if (err.response && err.response.status === 401) {
        localStorage.removeItem('laraforgex_auth_token');
        localStorage.removeItem('laraforgex_user');
        if (window.location.pathname !== '/admin/login') {
          window.location.href = '/admin/login';
        }
      }
      return Promise.reject(err);
    });

    // ── Global Dynamic Settings Engine (App Name, Logo, Toast Theme) ────
    window.currentToastTheme = 'obsidian';
    window.currentToastPosition = 'top-right';

    window.loadGlobalAppSettingsFromDB = function () {
      axios.get('/settings').then(res => {
        const appSettings = res.data.data || [];
        
        // 1. Toast settings
        const themeSet = appSettings.find(s => s.key === 'toast_theme');
        const posSet = appSettings.find(s => s.key === 'toast_position');

        if (themeSet && themeSet.value) window.currentToastTheme = themeSet.value;
        if (posSet && posSet.value) window.currentToastPosition = posSet.value;

        // 2. App Name & Logo Branding Settings
        const appNameSet = appSettings.find(s => s.key === 'app_name');
        const appLogoSet = appSettings.find(s => s.key === 'app_logo');

        if (appNameSet && appNameSet.value) {
          const appName = appNameSet.value;
          $('#sidebar-app-name').text(appName);
          $('#sidebar-logo-text').text(appName.charAt(0).toUpperCase());
        }

        if (appLogoSet && appLogoSet.value && appLogoSet.value.trim() !== '') {
          $('#sidebar-logo-img').attr('src', appLogoSet.value).removeClass('hidden');
          $('#sidebar-logo-text').addClass('hidden');
        } else {
          $('#sidebar-logo-img').addClass('hidden');
          $('#sidebar-logo-text').removeClass('hidden');
        }
      }).catch(() => {});
    };
    window.loadGlobalAppSettingsFromDB();

    window.showToast = function (type, message, customTitle, options = {}) {
      const activeTheme = options.theme || window.currentToastTheme || $('input[name="toast_theme_input"]:checked').val() || 'obsidian';
      const activePos = options.position || window.currentToastPosition || $('#toast-position-select').val() || 'top-right';

      let $container = $('#laraforgex-toast-container');
      if ($container.length === 0) {
        $container = $('<div id="laraforgex-toast-container"></div>').appendTo('body');
      }

      $container.attr('class', `pos-${activePos}`);

      const iconMap = {
        success: 'fa-solid fa-circle-check',
        info: 'fa-solid fa-circle-info',
        warning: 'fa-solid fa-triangle-exclamation',
        error: 'fa-solid fa-circle-xmark',
      };

      const titleMap = {
        success: 'Success',
        info: 'Information',
        warning: 'Warning',
        error: 'Error',
      };

      const toastType = iconMap[type] ? type : 'info';
      const iconClass = iconMap[toastType];
      const titleText = customTitle || titleMap[toastType];

      let actionsHtml = '';
      if (options.actions && $.isArray(options.actions)) {
        actionsHtml = '<div class="lf-toast-actions">';
        $.each(options.actions, function (idx, act) {
          actionsHtml += `<button type="button" class="lf-toast-action-btn" data-act-idx="${idx}">${act.label}</button>`;
        });
        actionsHtml += '</div>';
      }

      const $toastEl = $(`
        <div class="lf-toast-card theme-${activeTheme} lf-toast-${toastType}">
          <div class="lf-toast-icon-wrap">
            <i class="${iconClass}"></i>
          </div>
          <div class="lf-toast-content">
            <div class="lf-toast-header">
              <span class="lf-toast-title">${titleText}</span>
            </div>
            <div class="lf-toast-message">${message}</div>
            ${actionsHtml}
          </div>
          <button type="button" class="lf-toast-close" aria-label="Close">&times;</button>
          <div class="lf-toast-progress-track">
            <div class="lf-toast-progress-bar"></div>
          </div>
        </div>
      `);

      $container.append($toastEl);

      $toastEl.find('.lf-toast-close').on('click', function () {
        removeToast($toastEl);
      });

      if (options.actions && $.isArray(options.actions)) {
        $toastEl.find('.lf-toast-action-btn').on('click', function (e) {
          const idx = parseInt($(this).attr('data-act-idx'));
          if (options.actions[idx] && typeof options.actions[idx].onClick === 'function') {
            options.actions[idx].onClick(e);
          }
          removeToast($toastEl);
        });
      }

      const duration = options.duration || 4500;
      let timer = setTimeout(function () { removeToast($toastEl); }, duration);

      $toastEl.on('mouseenter', function () { clearTimeout(timer); });
      $toastEl.on('mouseleave', function () {
        timer = setTimeout(function () { removeToast($toastEl); }, 2000);
      });

      function removeToast($el) {
        if ($el.hasClass('hide')) return;
        $el.addClass('hide');
        setTimeout(function () {
          $el.remove();
        }, 300);
      }
    };

    window.handleAjaxError = function (error, defaultMsg = 'An unexpected error occurred.') {
      console.error(error);
      const msg = error.response?.data?.message ?? defaultMsg;
      window.showToast('error', msg);
    };

    // ── Mobile Sidebar jQuery Helpers ─────────────────────────────
    window.openSidebar = function () {
      $('#admin-sidebar').removeClass('-translate-x-full');
      $('#sidebar-overlay').removeClass('hidden');
    };

    window.closeSidebar = function () {
      $('#admin-sidebar').addClass('-translate-x-full');
      $('#sidebar-overlay').addClass('hidden');
    };

    window.toggleSidebar = function () {
      if ($(window).width() < 1024) {
        if ($('#admin-sidebar').hasClass('-translate-x-full')) {
          window.openSidebar();
        } else {
          window.closeSidebar();
        }
      } else {
        const $sidebar = $('#admin-sidebar');
        $sidebar.toggleClass('sidebar-collapsed');
        const isCollapsed = $sidebar.hasClass('sidebar-collapsed');
        localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
      }
    };

    // ── Session Guard + Global Bindings ───────────────────────────
    $(document).ready(function () {
      const authToken = localStorage.getItem('laraforgex_auth_token');
      const userSession = localStorage.getItem('laraforgex_user');

      if (!authToken || !userSession) {
        if (window.location.pathname !== '/admin/login') {
          window.location.href = '/admin/login';
        }
        return;
      }

      // Global .close-modal helper
      $(document).on('click', '.close-modal', function () {
        $(this).closest('.fixed').addClass('hidden');
      });

      // Apply initial desktop sidebar minimized state if persisted
      const isSidebarCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
      if (isSidebarCollapsed && $(window).width() >= 1024) {
        $('#admin-sidebar').addClass('sidebar-collapsed');
      }

      // Back to Top Scroll Listener via jQuery
      $(window).on('scroll', function () {
        const $btn = $('#btn-back-to-top');
        if ($btn.length === 0) return;
        if ($(window).scrollTop() > 300) {
          $btn.removeClass('opacity-0 pointer-events-none translate-y-4');
        } else {
          $btn.addClass('opacity-0 pointer-events-none translate-y-4');
        }
      });

      // Back to Top Click Handler
      $('#btn-back-to-top').on('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    });
  </script>

  <!-- ── Back to Top Floating Action Button ─────────────────────── -->
  <button id="btn-back-to-top"
    class="fixed bottom-6 right-6 z-40 h-10 w-10 rounded-full bg-brand-600 hover:bg-brand-500 text-white flex items-center justify-center shadow-lg transition-all duration-300 opacity-0 pointer-events-none translate-y-4 cursor-pointer border-0"
    title="Back to Top">
    <i class="fa-solid fa-arrow-up text-sm"></i>
  </button>

  <!-- Universal i18n Translations -->
  <script src="{{ asset('vendor/i18n/translations.js') }}"></script>

  {{-- Universal DataTable — also consumable by student/teacher portals via the same public URL --}}
  <script src="{{ asset('vendor/datatable/datatable.js') }}"></script>

  @yield('scripts')
</body>

</html>
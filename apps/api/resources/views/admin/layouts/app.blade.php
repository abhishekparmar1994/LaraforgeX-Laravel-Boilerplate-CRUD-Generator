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

      // Early localStorage reader (First load from localStorage)
      var savedSbTheme = localStorage.getItem('laraforgex_sidebar_theme');
      var savedAppName = localStorage.getItem('laraforgex_app_name');
      var savedAppLogo = localStorage.getItem('laraforgex_app_logo');

      document.addEventListener('DOMContentLoaded', function() {
        if (savedSbTheme) {
          var sb = document.getElementById('admin-sidebar');
          if (sb) {
            sb.className = (sb.className || '').replace(/\bsidebar-theme-\S+/g, '') + ' sidebar-theme-' + savedSbTheme;
          }
        }
        if (savedAppName) {
          var nameEl = document.getElementById('sidebar-app-name');
          var textEl = document.getElementById('sidebar-logo-text');
          if (nameEl) nameEl.textContent = savedAppName;
          if (textEl) textEl.textContent = savedAppName.charAt(0).toUpperCase();
        }
        if (savedAppLogo) {
          var imgEl = document.getElementById('sidebar-logo-img');
          var textEl2 = document.getElementById('sidebar-logo-text');
          if (imgEl && savedAppLogo.trim() !== '') {
            imgEl.src = savedAppLogo;
            imgEl.classList.remove('hidden');
            if (textEl2) textEl2.classList.add('hidden');
          }
        }
      });
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

    /* ── SIDEBAR THEME ENGINE ───────────────────────────────────── */
    /* 1. Theme: clean_light (Default) */
    #admin-sidebar.sidebar-theme-clean_light {
      background-color: #ffffff;
      border-color: #e2e8f0;
      color: #0f172a;
    }

    /* 2. Theme: obsidian_dark */
    #admin-sidebar.sidebar-theme-obsidian_dark {
      background-color: #0b0f19;
      border-color: #1e293b;
      color: #ffffff;
    }
    #admin-sidebar.sidebar-theme-obsidian_dark #sidebar-app-name { color: #ffffff !important; }
    #admin-sidebar.sidebar-theme-obsidian_dark p.text-\[10px\] { color: #64748b !important; }
    #admin-sidebar.sidebar-theme-obsidian_dark .sidebar-link { color: #cbd5e1; }
    #admin-sidebar.sidebar-theme-obsidian_dark .sidebar-link:hover { background-color: rgba(30, 41, 59, 0.7); color: #ffffff; }
    #admin-sidebar.sidebar-theme-obsidian_dark .sidebar-link.bg-brand-50 {
      background-color: rgba(79, 70, 229, 0.2) !important;
      color: #818cf8 !important;
      border-left-color: #6366f1 !important;
    }
    #admin-sidebar.sidebar-theme-obsidian_dark .sidebar-link i { color: #818cf8; }
    #admin-sidebar.sidebar-theme-obsidian_dark .sidebar-user-card { background-color: #111827; border-color: #1f2937; color: #ffffff; }

    /* 3. Theme: royal_glass */
    #admin-sidebar.sidebar-theme-royal_glass {
      background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%);
      border-color: rgba(99, 102, 241, 0.25);
      color: #ffffff;
    }
    #admin-sidebar.sidebar-theme-royal_glass #sidebar-app-name { color: #ffffff !important; }
    #admin-sidebar.sidebar-theme-royal_glass p.text-\[10px\] { color: #818cf8 !important; }
    #admin-sidebar.sidebar-theme-royal_glass .sidebar-link { color: #c7d2fe; }
    #admin-sidebar.sidebar-theme-royal_glass .sidebar-link:hover { background-color: rgba(255, 255, 255, 0.1); color: #ffffff; }
    #admin-sidebar.sidebar-theme-royal_glass .sidebar-link.bg-brand-50 {
      background-color: rgba(99, 102, 241, 0.3) !important;
      color: #a5b4fc !important;
      border-left-color: #818cf8 !important;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }
    #admin-sidebar.sidebar-theme-royal_glass .sidebar-link i { color: #a5b4fc; }
    #admin-sidebar.sidebar-theme-royal_glass .sidebar-user-card { background-color: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.12); color: #ffffff; }

    /* 4. Theme: nordic_emerald */
    #admin-sidebar.sidebar-theme-nordic_emerald {
      background-color: #064e3b;
      border-color: #065f46;
      color: #ffffff;
    }
    #admin-sidebar.sidebar-theme-nordic_emerald #sidebar-app-name { color: #ffffff !important; }
    #admin-sidebar.sidebar-theme-nordic_emerald p.text-\[10px\] { color: #34d399 !important; }
    #admin-sidebar.sidebar-theme-nordic_emerald .sidebar-link { color: #a7f3d0; }
    #admin-sidebar.sidebar-theme-nordic_emerald .sidebar-link:hover { background-color: rgba(255, 255, 255, 0.1); color: #ffffff; }
    #admin-sidebar.sidebar-theme-nordic_emerald .sidebar-link.bg-brand-50 {
      background-color: rgba(16, 185, 129, 0.25) !important;
      color: #6ee7b7 !important;
      border-left-color: #10b981 !important;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    #admin-sidebar.sidebar-theme-nordic_emerald .sidebar-link i { color: #6ee7b7; }
    #admin-sidebar.sidebar-theme-nordic_emerald .sidebar-user-card { background-color: rgba(0, 0, 0, 0.25); border-color: rgba(16, 185, 129, 0.3); color: #ecfdf5; }

    /* 5. Theme: sunset_crimson */
    #admin-sidebar.sidebar-theme-sunset_crimson {
      background: linear-gradient(180deg, #4c0519 0%, #111827 100%);
      border-color: rgba(244, 63, 94, 0.25);
      color: #ffffff;
    }
    #admin-sidebar.sidebar-theme-sunset_crimson #sidebar-app-name { color: #ffffff !important; }
    #admin-sidebar.sidebar-theme-sunset_crimson p.text-\[10px\] { color: #fb7185 !important; }
    #admin-sidebar.sidebar-theme-sunset_crimson .sidebar-link { color: #fecdd3; }
    #admin-sidebar.sidebar-theme-sunset_crimson .sidebar-link:hover { background-color: rgba(255, 255, 255, 0.1); color: #ffffff; }
    #admin-sidebar.sidebar-theme-sunset_crimson .sidebar-link.bg-brand-50 {
      background-color: rgba(244, 63, 94, 0.25) !important;
      color: #fda4af !important;
      border-left-color: #f43f5e !important;
      box-shadow: 0 4px 12px rgba(244, 63, 94, 0.25);
    }
    #admin-sidebar.sidebar-theme-sunset_crimson .sidebar-link i { color: #fda4af; }
    #admin-sidebar.sidebar-theme-sunset_crimson .sidebar-user-card { background-color: rgba(0, 0, 0, 0.3); border-color: rgba(244, 63, 94, 0.3); color: #ffe4e6; }

    /* 6. Theme: cyber_neon */
    #admin-sidebar.sidebar-theme-cyber_neon {
      background-color: #050508;
      border-color: rgba(56, 189, 248, 0.25);
      color: #ffffff;
    }
    #admin-sidebar.sidebar-theme-cyber_neon #sidebar-app-name { color: #00f0ff !important; }
    #admin-sidebar.sidebar-theme-cyber_neon p.text-\[10px\] { color: #38bdf8 !important; }
    #admin-sidebar.sidebar-theme-cyber_neon .sidebar-link { color: #e2e8f0; }
    #admin-sidebar.sidebar-theme-cyber_neon .sidebar-link:hover { background-color: rgba(56, 189, 248, 0.12); color: #ffffff; }
    #admin-sidebar.sidebar-theme-cyber_neon .sidebar-link.bg-brand-50 {
      background-color: rgba(56, 189, 248, 0.2) !important;
      color: #38bdf8 !important;
      border-left-color: #00f0ff !important;
      box-shadow: 0 0 15px rgba(0, 240, 255, 0.3);
    }
    #admin-sidebar.sidebar-theme-cyber_neon .sidebar-link i { color: #38bdf8; }
    #admin-sidebar.sidebar-theme-cyber_neon .sidebar-user-card { background-color: rgba(15, 23, 42, 0.8); border-color: rgba(56, 189, 248, 0.3); color: #f0f9ff; }

    /* 7. Theme: amber_gold */
    #admin-sidebar.sidebar-theme-amber_gold {
      background: linear-gradient(180deg, #1c1917 0%, #09090b 100%);
      border-color: rgba(245, 158, 11, 0.25);
      color: #ffffff;
    }
    #admin-sidebar.sidebar-theme-amber_gold #sidebar-app-name { color: #fef08a !important; }
    #admin-sidebar.sidebar-theme-amber_gold p.text-\[10px\] { color: #fbbf24 !important; }
    #admin-sidebar.sidebar-theme-amber_gold .sidebar-link { color: #e7e5e4; }
    #admin-sidebar.sidebar-theme-amber_gold .sidebar-link:hover { background-color: rgba(245, 158, 11, 0.12); color: #ffffff; }
    #admin-sidebar.sidebar-theme-amber_gold .sidebar-link.bg-brand-50 {
      background-color: rgba(245, 158, 11, 0.2) !important;
      color: #fde047 !important;
      border-left-color: #eab308 !important;
      box-shadow: 0 4px 12px rgba(234, 179, 8, 0.2);
    }
    #admin-sidebar.sidebar-theme-amber_gold .sidebar-link i { color: #fde047; }
    #admin-sidebar.sidebar-theme-amber_gold .sidebar-user-card { background-color: rgba(44, 36, 22, 0.6); border-color: rgba(245, 158, 11, 0.3); color: #fefce8; }

    /* 8. Theme: minimal_slate */
    #admin-sidebar.sidebar-theme-minimal_slate {
      background-color: #1e293b;
      border-color: #334155;
      color: #ffffff;
    }
    #admin-sidebar.sidebar-theme-minimal_slate #sidebar-app-name { color: #f8fafc !important; }
    #admin-sidebar.sidebar-theme-minimal_slate p.text-\[10px\] { color: #94a3b8 !important; }
    #admin-sidebar.sidebar-theme-minimal_slate .sidebar-link { color: #cbd5e1; }
    #admin-sidebar.sidebar-theme-minimal_slate .sidebar-link:hover { background-color: #0f172a; color: #ffffff; }
    #admin-sidebar.sidebar-theme-minimal_slate .sidebar-link.bg-brand-50 {
      background-color: rgba(255, 255, 255, 0.12) !important;
      color: #ffffff !important;
      border-left-color: #38bdf8 !important;
    }
    #admin-sidebar.sidebar-theme-minimal_slate .sidebar-link i { color: #38bdf8; }
    #admin-sidebar.sidebar-theme-minimal_slate .sidebar-user-card { background-color: #0f172a; border-color: #334155; color: #f8fafc; }

    /* ── SweetAlert2 Modal Clean Overrides (No Scrollbars) ── */
    .swal2-popup {
      border-radius: 1.25rem !important;
      padding: 1.5rem 1.75rem !important;
      overflow: visible !important;
      max-height: none !important;
    }

    .swal2-html-container {
      overflow: visible !important;
      max-height: none !important;
      margin: 0.75rem 0 1rem 0 !important;
      padding: 0 !important;
    }

    .swal2-input {
      margin: 0.75rem auto 0 auto !important;
      box-shadow: none !important;
      border-color: #cbd5e1 !important;
      border-radius: 0.75rem !important;
      height: 2.75rem !important;
      font-size: 0.875rem !important;
    }

    .swal2-input:focus {
      border-color: #6366f1 !important;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
    }

    .swal2-actions {
      margin-top: 1.25rem !important;
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

    // ── Global Dynamic Settings Engine (App Name, Logo, Toast, Sidebar) ────
    window.currentToastTheme = localStorage.getItem('laraforgex_toast_theme') || 'obsidian';
    window.currentToastPosition = localStorage.getItem('laraforgex_toast_position') || 'top-right';

    window.loadGlobalAppSettingsFromDB = function () {
      // 1. Immediately apply cached local storage if available (Zero-FOUC)
      const cachedSbTheme = localStorage.getItem('laraforgex_sidebar_theme');
      const cachedToastTheme = localStorage.getItem('laraforgex_toast_theme');
      const cachedToastPos = localStorage.getItem('laraforgex_toast_position');
      const cachedAppName = localStorage.getItem('laraforgex_app_name');
      const cachedAppLogo = localStorage.getItem('laraforgex_app_logo');

      if (cachedToastTheme) window.currentToastTheme = cachedToastTheme;
      if (cachedToastPos) window.currentToastPosition = cachedToastPos;

      const $sidebar = $('#admin-sidebar');
      if ($sidebar.length && cachedSbTheme) {
        $sidebar.attr('class', function(i, c) {
          return (c || '').replace(/\bsidebar-theme-\S+/g, '');
        }).addClass(`sidebar-theme-${cachedSbTheme}`);
      }

      if (cachedAppName) {
        $('#sidebar-app-name').text(cachedAppName);
        $('#sidebar-logo-text').text(cachedAppName.charAt(0).toUpperCase());
      }
      if (cachedAppLogo && cachedAppLogo.trim() !== '') {
        $('#sidebar-logo-img').attr('src', cachedAppLogo).removeClass('hidden');
        $('#sidebar-logo-text').addClass('hidden');
      }

      // 2. Fetch fresh settings from DB table & sync with localStorage + UI
      axios.get('/settings').then(res => {
        const appSettings = res.data.data || [];
        
        const themeSet = appSettings.find(s => s.key === 'toast_theme');
        const posSet = appSettings.find(s => s.key === 'toast_position');
        const appNameSet = appSettings.find(s => s.key === 'app_name');
        const appLogoSet = appSettings.find(s => s.key === 'app_logo');
        const sbThemeSet = appSettings.find(s => s.key === 'sidebar_theme');

        if (themeSet && themeSet.value) {
          window.currentToastTheme = themeSet.value;
          localStorage.setItem('laraforgex_toast_theme', themeSet.value);
        }
        if (posSet && posSet.value) {
          window.currentToastPosition = posSet.value;
          localStorage.setItem('laraforgex_toast_position', posSet.value);
        }

        if (appNameSet && appNameSet.value) {
          const appName = appNameSet.value;
          localStorage.setItem('laraforgex_app_name', appName);
          $('#sidebar-app-name').text(appName);
          $('#sidebar-logo-text').text(appName.charAt(0).toUpperCase());
        }

        if (appLogoSet && appLogoSet.value !== undefined) {
          localStorage.setItem('laraforgex_app_logo', appLogoSet.value || '');
          if (appLogoSet.value && appLogoSet.value.trim() !== '') {
            $('#sidebar-logo-img').attr('src', appLogoSet.value).removeClass('hidden');
            $('#sidebar-logo-text').addClass('hidden');
          } else {
            $('#sidebar-logo-img').addClass('hidden');
            $('#sidebar-logo-text').removeClass('hidden');
          }
        }

        if (sbThemeSet && sbThemeSet.value) {
          localStorage.setItem('laraforgex_sidebar_theme', sbThemeSet.value);
          if ($sidebar.length) {
            $sidebar.attr('class', function(i, c) {
              return (c || '').replace(/\bsidebar-theme-\S+/g, '');
            }).addClass(`sidebar-theme-${sbThemeSet.value}`);
          }
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
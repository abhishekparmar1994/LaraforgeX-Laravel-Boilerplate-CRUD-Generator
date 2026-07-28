<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LaraforgeX Admin Panel')</title>

  <!-- jQuery Vendor Script -->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

  <!-- Local Offline Tailwind JS (Play CDN script loaded locally) -->
  <script src="{{ asset('vendor/tailwind/tailwind.js') }}"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
          colors: {
            brand: {
              50: '#f5f7ff',
              100: '#ebf0ff',
              200: '#d6e0ff',
              300: '#b3c7ff',
              400: '#85a3ff',
              500: '#5275ff',
              600: '#2b47ff',
              700: '#1b2eff',
              800: '#111fff',
              900: '#0007e0',
            }
          }
        }
      }
    };
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

    #laraforgex-toast-container.pos-top-right,
    #laraforgex-toast-container { top: 1.25rem; right: 1.25rem; left: auto; bottom: auto; transform: none; }
    #laraforgex-toast-container.pos-top-left { top: 1.25rem!important; left: 1.25rem!important; right: auto!important; bottom: auto!important; transform: none!important; }
    #laraforgex-toast-container.pos-top-center { top: 1.25rem!important; left: 50%!important; right: auto!important; bottom: auto!important; transform: translateX(-50%)!important; }
    #laraforgex-toast-container.pos-bottom-right { bottom: 1.25rem!important; right: 1.25rem!important; top: auto!important; left: auto!important; flex-direction: column-reverse!important; transform: none!important; }
    #laraforgex-toast-container.pos-bottom-left { bottom: 1.25rem!important; left: 1.25rem!important; top: auto!important; right: auto!important; flex-direction: column-reverse!important; transform: none!important; }

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

    .lf-toast-card:hover { transform: translateY(-2px); }
    .lf-toast-card.hide { animation: obsidianToastOut 0.3s cubic-bezier(0.7, 0, 0.84, 0) forwards; }

    @keyframes obsidianToastIn {
      from { opacity: 0; transform: translateY(-20px) scale(0.92); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes obsidianToastOut {
      from { opacity: 1; transform: translateY(0) scale(1); }
      to { opacity: 0; transform: translateY(-16px) scale(0.92); }
    }

    .theme-obsidian.lf-toast-card {
      background: rgba(10, 10, 14, 0.92);
      backdrop-filter: blur(24px) saturate(210%);
      -webkit-backdrop-filter: blur(24px) saturate(210%);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-left-width: 4px;
      color: #ffffff;
    }
    .theme-obsidian.lf-toast-success { border-left-color: #10b981; box-shadow: -4px 0 20px -2px rgba(16, 185, 129, 0.4), 0 20px 30px -5px rgba(0, 0, 0, 0.75); }
    .theme-obsidian.lf-toast-error { border-left-color: #f43f5e; box-shadow: -4px 0 20px -2px rgba(244, 63, 94, 0.4), 0 20px 30px -5px rgba(0, 0, 0, 0.75); }
    .theme-obsidian.lf-toast-warning { border-left-color: #f59e0b; box-shadow: -4px 0 20px -2px rgba(245, 158, 11, 0.4), 0 20px 30px -5px rgba(0, 0, 0, 0.75); }
    .theme-obsidian.lf-toast-info { border-left-color: #38bdf8; box-shadow: -4px 0 20px -2px rgba(56, 189, 248, 0.4), 0 20px 30px -5px rgba(0, 0, 0, 0.75); }

    .theme-obsidian.lf-toast-title { color: #ffffff; font-weight: 700; }
    .theme-obsidian.lf-toast-message { color: #cbd5e1; }
    .theme-obsidian.lf-toast-close { color: #64748b; }
    .theme-obsidian.lf-toast-close:hover { color: #ffffff; background: rgba(255, 255, 255, 0.1); }

    .theme-white_glass.lf-toast-card {
      background: rgba(255, 255, 255, 0.94);
      backdrop-filter: blur(24px) saturate(200%);
      -webkit-backdrop-filter: blur(24px) saturate(200%);
      border: 1px solid rgba(226, 232, 240, 0.9);
      border-left-width: 4px;
      color: #0f172a;
      box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.12), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    }
    .theme-white_glass.lf-toast-success { border-left-color: #10b981; }
    .theme-white_glass.lf-toast-error { border-left-color: #f43f5e; }
    .theme-white_glass.lf-toast-warning { border-left-color: #f59e0b; }
    .theme-white_glass.lf-toast-info { border-left-color: #38bdf8; }

    .theme-white_glass.lf-toast-title { color: #0f172a; font-weight: 700; }
    .theme-white_glass.lf-toast-message { color: #475569; }
    .theme-white_glass.lf-toast-close { color: #94a3b8; }
    .theme-white_glass.lf-toast-close:hover { color: #0f172a; background: rgba(0, 0, 0, 0.05); }

    .theme-solid_vibrant.lf-toast-card {
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #ffffff;
      box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.3);
    }
    .theme-solid_vibrant.lf-toast-success { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .theme-solid_vibrant.lf-toast-error { background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); }
    .theme-solid_vibrant.lf-toast-warning { background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%); }
    .theme-solid_vibrant.lf-toast-info { background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%); }

    .theme-solid_vibrant.lf-toast-title { color: #ffffff; font-weight: 800; }
    .theme-solid_vibrant.lf-toast-message { color: rgba(255, 255, 255, 0.9); }
    .theme-solid_vibrant.lf-toast-icon-wrap { background: rgba(255, 255, 255, 0.2); color: #ffffff; border-color: rgba(255, 255, 255, 0.3); }
    .theme-solid_vibrant.lf-toast-close { color: rgba(255, 255, 255, 0.7); }
    .theme-solid_vibrant.lf-toast-close:hover { color: #ffffff; background: rgba(255, 255, 255, 0.2); }

    .theme-minimal_dark.lf-toast-card {
      background: #18181b;
      border: 1px solid #27272a;
      color: #ffffff;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
    }
    .theme-minimal_dark.lf-toast-success { border-left: 3px solid #10b981; }
    .theme-minimal_dark.lf-toast-error { border-left: 3px solid #f43f5e; }
    .theme-minimal_dark.lf-toast-warning { border-left: 3px solid #f59e0b; }
    .theme-minimal_dark.lf-toast-info { border-left: 3px solid #38bdf8; }

    .theme-minimal_dark.lf-toast-title { color: #f4f4f5; font-weight: 700; }
    .theme-minimal_dark.lf-toast-message { color: #a1a1aa; }
    .theme-minimal_dark.lf-toast-close { color: #71717a; }
    .theme-minimal_dark.lf-toast-close:hover { color: #ffffff; }

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

    .theme-obsidian.lf-toast-success.lf-toast-icon-wrap,
    .theme-white_glass.lf-toast-success.lf-toast-icon-wrap,
    .theme-minimal_dark.lf-toast-success.lf-toast-icon-wrap { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }

    .theme-obsidian.lf-toast-error.lf-toast-icon-wrap,
    .theme-white_glass.lf-toast-error.lf-toast-icon-wrap,
    .theme-minimal_dark.lf-toast-error.lf-toast-icon-wrap { background: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3); }

    .theme-obsidian.lf-toast-warning.lf-toast-icon-wrap,
    .theme-white_glass.lf-toast-warning.lf-toast-icon-wrap,
    .theme-minimal_dark.lf-toast-warning.lf-toast-icon-wrap { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }

    .theme-obsidian.lf-toast-info.lf-toast-icon-wrap,
    .theme-white_glass.lf-toast-info.lf-toast-icon-wrap,
    .theme-minimal_dark.lf-toast-info.lf-toast-icon-wrap { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }

    .lf-toast-content { flex: 1; min-width: 0; padding-top: 0.1rem; }
    .lf-toast-header { display: flex; align-items: center; }
    .lf-toast-title { font-size: 0.875rem; line-height: 1.2rem; letter-spacing: -0.01em; }
    .lf-toast-message { font-size: 0.8125rem; line-height: 1.2rem; margin-top: 0.2rem; word-break: break-word; }

    .lf-toast-actions { display: flex; align-items: center; gap: 1rem; margin-top: 0.625rem; }
    .lf-toast-action-btn { font-size: 0.8125rem; font-weight: 600; background: transparent; border: none; padding: 0; cursor: pointer; }
    .lf-toast-action-btn:hover { text-decoration: underline; }

    .lf-toast-close {
      background: transparent; border: none; width: 1.75rem; height: 1.75rem; border-radius: 0.5rem;
      display: flex; align-items: center; justify-content: center; font-size: 1rem; cursor: pointer;
      line-height: 1; transition: all 0.15s ease; flex-shrink: 0; margin-top: -0.1rem; margin-right: -0.25rem;
    }

    .lf-toast-progress-track { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: rgba(255, 255, 255, 0.08); overflow: hidden; }
    .lf-toast-progress-bar { height: 100%; width: 100%; transform-origin: left; animation: obsidianToastProgress 4.5s linear forwards; }
    .lf-toast-success.lf-toast-progress-bar { background: linear-gradient(90deg, #10b981, #059669); }
    .lf-toast-error.lf-toast-progress-bar { background: linear-gradient(90deg, #f43f5e, #be123c); }
    .lf-toast-warning.lf-toast-progress-bar { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .lf-toast-info.lf-toast-progress-bar { background: linear-gradient(90deg, #38bdf8, #0284c7); }
  </style>
</head>

<body class="bg-slate-50 text-slate-700 antialiased selection:bg-brand-500 selection:text-white">



        @yield('content')

        <!-- Local Offline JavaScript Assets -->
  <script src="{{ asset('vendor/axios/axios.min.js') }}"></script>

  <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('vendor/fontawesome/all.min.js') }}"></script>

  <!-- Axios Interceptors & Authentication Guards -->
  <script>
    // Axios defaults
      axios.defaults.baseURL = '/api/v1';
      axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

      // Inject CSRF Token automatically
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
      if (csrfToken) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
      }


      // Auth Request Interceptor
      axios.interceptors.request.use(function (config) {
      const authToken = localStorage.getItem('laraforgex_auth_token');
      if (authToken) {
        config.headers.Authorization = `Bearer ${authToken}`;
      }
      return config;
    }, function (error) {
      return Promise.reject(error);
    });

      // Auth Response Interceptor
      axios.interceptors.response.use(function (response) {
      return response;
    }, function (error) {
      if (error.response && error.response.status === 401) {
        localStorage.clear();
        sessionStorage.clear();
        if (window.location.pathname !== '/admin/login') {
          window.location.href = '/admin/login';
        }

      }
      return Promise.reject(error);
    });

      // ── Database-Backed Multi-Theme Toast Engine ──────────────────────
      window.currentToastTheme = 'obsidian';
      window.currentToastPosition = 'top-right';

      function loadGlobalToastSettingsFromDB() {
        axios.get('/settings?group=appearance').then(res => {
          const appSettings = res.data.data || [];
          const themeSet = appSettings.find(s => s.key === 'toast_theme');
          const posSet = appSettings.find(s => s.key === 'toast_position');

          if (themeSet && themeSet.value) window.currentToastTheme = themeSet.value;
          if (posSet && posSet.value) window.currentToastPosition = posSet.value;
        }).catch(() => { });
    }
      loadGlobalToastSettingsFromDB();

      window.showToast = function (type, message, customTitle, options = {}) {
        const activeTheme = options.theme || window.currentToastTheme || 'obsidian';
        const activePos = options.position || window.currentToastPosition || 'top-right';

        let $container = $('#laraforgex-toast-container');
        if (!$container.length) {
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
        if (options.actions && Array.isArray(options.actions)) {
          actionsHtml = '<div class="lf-toast-actions">';
          options.actions.forEach((act, idx) => {
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

        $toastEl.find('.lf-toast-close').on('click', () => removeToast($toastEl));

        if (options.actions && Array.isArray(options.actions)) {
          $toastEl.find('.lf-toast-action-btn').on('click', function (e) {
            const idx = parseInt($(this).attr('data-act-idx'));
            if (options.actions[idx] && typeof options.actions[idx].onClick === 'function') {
              options.actions[idx].onClick(e);
            }
            removeToast($toastEl);
          });
        }

        const duration = options.duration || 4500;
        let timer = setTimeout(() => removeToast($toastEl), duration);

        $toastEl.on('mouseenter', () => clearTimeout(timer));
        $toastEl.on('mouseleave', () => {
          timer = setTimeout(() => removeToast($toastEl), 2000);
        });

        function removeToast($el) {
          if ($el.hasClass('hide')) return;
          $el.addClass('hide');
          setTimeout(() => {
            $el.remove();
          }, 300);
        }
      };

      window.handleAjaxError = function (error, defaultMsg = 'An unexpected error occurred.') {
        console.error(error);
        const msg = error.response && error.response.data && error.response.data.message
          ? error.response.data.message
          : defaultMsg;
        showToast('error', msg);
      };

      // Toggle password visibility helper
      window.togglePasswordVisibility = function (inputId, iconId) {
        const $input = $('#' + inputId);
        const $icon = $('#' + iconId);
        if (!$input.length || !$icon.length) return;

        if ($input.attr('type') === 'password') {
          $input.attr('type', 'text');
          $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          $input.attr('type', 'password');
          $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      };

  </script>

  @yield('scripts')
  </body>

</html>
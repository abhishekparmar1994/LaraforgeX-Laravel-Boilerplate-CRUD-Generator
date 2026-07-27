<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LaraforgeX Core Admin')</title>

  <!-- Tailwind CDN (offline vendor) -->
  <script src="{{ asset('vendor/tailwind/tailwind.js') }}"></script>
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
    /* Sidebar slide transition */
    #admin-sidebar {
      transition: transform 0.25s ease, width 0.25s ease;
    }

    /* Smooth overlay fade */
    #sidebar-overlay {
      transition: opacity 0.25s ease;
    }

    /* Collapsed sidebar styling on desktop */
    @media (min-width: 1024px) {
      #admin-sidebar.sidebar-collapsed {
        width: 4.5rem !important;
        /* Collapsed width */
      }

      #admin-sidebar.sidebar-collapsed .px-4 {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
      }

      /* Hide all text descriptions, headers, submenus and chevrons individually to prevent group parser discard */
      #admin-sidebar.sidebar-collapsed nav p {
        display: none !important;
      }

      #admin-sidebar.sidebar-collapsed span {
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

      /* Sidebar Collapsed Popovers (Desktop only) */
      @keyframes popoverFadeIn {
        from {
          opacity: 0;
        }

        to {
          opacity: 1;
        }
      }

      .sidebar-popover {
        display: none !important;
      }

      #admin-sidebar.sidebar-collapsed .sidebar-group:hover .sidebar-popover {
        display: block !important;
        animation: popoverFadeIn 0.15s ease-out forwards;
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
      <div class="px-4 sm:px-6 lg:px-8 pt-5 mb-6">
        @include('admin.includes.header')
      </div>

      <!-- Page content -->
      <div class="flex-1 px-4 sm:px-6 lg:px-8 pb-8">
        @yield('content')
      </div>

      <!-- Footer -->
      <div class="px-4 sm:px-6 lg:px-8">
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

  <!-- ── Global JS ─────────────────────────────────────────────── -->
  <script>
    // ── Axios config ──────────────────────────────────────────────
    axios.defaults.baseURL = '/api/v1';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    const _csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (_csrfToken) axios.defaults.headers.common['X-CSRF-TOKEN'] = _csrfToken.content;

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

    // ── Toast helper ──────────────────────────────────────────────
    const Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false,
      timer: 3500, timerProgressBar: true,
      didOpen: t => {
        t.addEventListener('mouseenter', Swal.stopTimer);
        t.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });
    window.showToast = (type, message) => Toast.fire({ icon: type, title: message });
    window.handleAjaxError = (error, defaultMsg = 'An unexpected error occurred.') => {
      console.error(error);
      const msg = error.response?.data?.message ?? defaultMsg;
      showToast('error', msg);
    };

    // ── Session guard + sidebar user init ─────────────────────────
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
      if (isSidebarCollapsed && window.innerWidth >= 1024) {
        $('#admin-sidebar').addClass('sidebar-collapsed');
      }
    });

    // ── Mobile sidebar helpers ────────────────────────────────────
    function openSidebar() {
      document.getElementById('admin-sidebar').classList.remove('-translate-x-full');
      document.getElementById('sidebar-overlay').classList.remove('hidden');
    }
    function closeSidebar() {
      document.getElementById('admin-sidebar').classList.add('-translate-x-full');
      document.getElementById('sidebar-overlay').classList.add('hidden');
    }

    // ── Toggle sidebar (desktop collapse or mobile toggle drawer) ──
    function toggleSidebar() {
      if (window.innerWidth < 1024) {
        const sidebar = document.getElementById('admin-sidebar');
        if (sidebar.classList.contains('-translate-x-full')) {
          openSidebar();
        } else {
          closeSidebar();
        }
      } else {
        const sidebar = document.getElementById('admin-sidebar');
        sidebar.classList.toggle('sidebar-collapsed');
        const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
      }
    }

    // ── Back to Top scroll listener ──────────────────────────────
    window.addEventListener('scroll', function () {
      const btn = document.getElementById('btn-back-to-top');
      if (!btn) return;
      if (window.scrollY > 300) {
        btn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
      } else {
        btn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
      }
    }, { passive: true });
  </script>

  <!-- ── Back to Top Floating Action Button ─────────────────────── -->
  <button id="btn-back-to-top"
    class="fixed bottom-6 right-6 z-40 h-10 w-10 rounded-full bg-brand-600 hover:bg-brand-500 text-white flex items-center justify-center shadow-lg transition-all duration-300 opacity-0 pointer-events-none translate-y-4 cursor-pointer border-0"
    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" title="Back to Top">
    <i class="fa-solid fa-arrow-up text-sm"></i>
  </button>

  <!-- Universal i18n Translations -->
  <script src="{{ asset('vendor/i18n/translations.js') }}"></script>

  {{-- Universal DataTable — also consumable by student/teacher portals via the same public URL --}}
  <script src="{{ asset('vendor/datatable/datatable.js') }}"></script>

  @yield('scripts')
</body>

</html>
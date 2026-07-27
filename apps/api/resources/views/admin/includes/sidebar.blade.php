<!-- Sidebar -->
<aside
  class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 h-screen fixed inset-y-0 left-0 lg:sticky top-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-250 ease-in-out font-sans overflow-y-auto"
  id="admin-sidebar">
  <div class="px-4 py-6 space-y-6">
    <!-- Logo -->
    <div class="flex items-center justify-between px-2">
      <div class="flex items-center gap-2.5">
        <div
          class="h-8 w-8 rounded-lg bg-gradient-to-tr from-brand-600 to-violet-500 flex items-center justify-center text-white font-display font-extrabold text-base shadow-md">
          L
        </div>
        <span class="font-display font-bold text-lg text-slate-900 tracking-tight">Laraforge<span
            class="text-brand-600">X</span></span>
      </div>
      <button type="button" class="lg:hidden h-8 w-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition" onclick="closeSidebar()" title="Close Sidebar">
        <i class="fa-solid fa-xmark text-base"></i>
      </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="space-y-0.5">

      <!-- ── OVERVIEW ──────────────────────── -->
      <p class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Overview</p>

      <!-- Dashboard Link Group -->
      <div class="relative group sidebar-group">
        <a href="/admin/dashboard"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/dashboard') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-chart-line w-4 text-center {{ request()->is('admin/dashboard') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span data-i18n="dashboard">Dashboard</span>
        </a>
        <!-- Popover tooltip -->
        <div class="sidebar-popover absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[10px] font-bold rounded-lg shadow-lg whitespace-nowrap pointer-events-none z-50" data-i18n="dashboard">
          Dashboard
        </div>
      </div>

      <!-- ── IDENTITY & ACCESS ─────────────── -->
      <p id="header-identity" class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden">Identity & Access</p>

      <!-- Users top-level Link Group -->
      <div id="menu-users" class="relative group sidebar-group hidden">
        <a href="/admin/users"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/users*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-users w-4 text-center {{ request()->is('admin/users*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span data-i18n="users">User Accounts</span>
        </a>
        <!-- Popover tooltip -->
        <div class="sidebar-popover absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[10px] font-bold rounded-lg shadow-lg whitespace-nowrap pointer-events-none z-50" data-i18n="users">
          User Accounts
        </div>
      </div>

      <!-- Roles & Permissions — Accordion parent -->
      <div id="menu-rbac" class="relative group sidebar-group hidden">
        <div class="space-y-0.5">
          <button type="button" id="submenu-rbac-toggle"
            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                          {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}"
            onclick="toggleSubmenu('submenu-rbac', 'chevron-rbac')">
            <span class="flex items-center gap-3">
              <i
                class="fa-solid fa-shield-halved w-4 text-center {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
              <span data-i18n="roles">Access Control</span>
            </span>
            <i id="chevron-rbac"
              class="fa-solid fa-chevron-right text-[10px] text-slate-400 transition-transform duration-200 {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'rotate-90' : '' }}"></i>
          </button>

          <!-- Submenu items (inline standard mode) -->
          <div id="submenu-rbac"
            class="submenu-wrapper pl-4 space-y-0.5 overflow-hidden transition-all duration-200 {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? '' : 'hidden' }}">
            <a href="/admin/roles"
              class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                      {{ request()->is('admin/roles') && !request()->is('admin/permissions') ? 'bg-brand-50 text-brand-600' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
              <i class="fa-regular fa-circle-dot w-4 text-center text-slate-300 text-[10px]"></i>
              <span data-i18n="roles">Roles Matrix</span>
            </a>
            <a href="/admin/permissions"
              class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                      {{ request()->is('admin/permissions*') ? 'bg-brand-50 text-brand-600' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
              <i class="fa-regular fa-circle-dot w-4 text-center text-slate-300 text-[10px]"></i>
              <span data-i18n="permissions">Permissions Map</span>
            </a>
          </div>
        </div>

        <!-- Floating Popover Submenu (collapsed mode hover overlay) -->
        <div class="sidebar-popover absolute left-full top-0 pl-2 z-50">
          <div class="w-48 bg-white border border-slate-200 rounded-xl shadow-xl py-1 text-left font-sans">
            <div class="px-3.5 py-2 border-b border-slate-100 bg-slate-50">
              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Access Control</span>
            </div>
            <div class="py-1 space-y-0.5">
              <a href="/admin/roles" class="block px-3.5 py-2 text-xs font-semibold {{ request()->is('admin/roles') && !request()->is('admin/permissions') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition">Roles Matrix</a>
              <a href="/admin/permissions" class="block px-3.5 py-2 text-xs font-semibold {{ request()->is('admin/permissions*') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition">Permissions Map</a>
            </div>
          </div>
        </div>
      </div>

      <!-- ── SYSTEM CORE ───────────────────── -->
      <p id="header-system" class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden">System Core</p>

      <!-- Media Manager — Accordion parent -->
      <div id="menu-media" class="relative group sidebar-group hidden">
        <div class="space-y-0.5">
          <button type="button" id="submenu-media-toggle"
            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                          {{ request()->is('admin/media*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}"
            onclick="toggleSubmenu('submenu-media', 'chevron-media')">
            <span class="flex items-center gap-3">
              <i
                class="fa-solid fa-folder-open w-4 text-center {{ request()->is('admin/media*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
              <span data-i18n="media">Media Cloud</span>
            </span>
            <i id="chevron-media"
              class="fa-solid fa-chevron-right text-[10px] text-slate-400 transition-transform duration-200 {{ request()->is('admin/media*') ? 'rotate-90' : '' }}"></i>
          </button>

          <!-- Inline Submenu items -->
          <div id="submenu-media"
            class="submenu-wrapper pl-4 space-y-0.5 overflow-hidden transition-all duration-200 {{ request()->is('admin/media*') ? '' : 'hidden' }}">
            <a href="/admin/media"
              class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                      {{ request()->is('admin/media') ? 'bg-brand-50 text-brand-600' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
              <i class="fa-regular fa-circle-dot w-4 text-center text-slate-300 text-[10px]"></i>
              <span>File Browser</span>
            </a>
          </div>
        </div>

        <!-- Floating Popover Submenu (collapsed mode hover overlay) -->
        <div class="sidebar-popover absolute left-full top-0 pl-2 z-50">
          <div class="w-48 bg-white border border-slate-200 rounded-xl shadow-xl py-1 text-left font-sans">
            <div class="px-3.5 py-2 border-b border-slate-100 bg-slate-50">
              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Media Cloud</span>
            </div>
            <div class="py-1 space-y-0.5">
              <a href="/admin/media" class="block px-3.5 py-2 text-xs font-semibold {{ request()->is('admin/media*') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition">File Browser</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Encrypted Settings Group -->
      <div id="menu-settings" class="relative group sidebar-group hidden">
        <a href="/admin/settings"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/settings*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-sliders w-4 text-center {{ request()->is('admin/settings*') ? 'text-brand-500' : 'text-amber-400' }}"></i>
          <span data-i18n="settings">Config Settings</span>
        </a>
        <!-- Popover tooltip -->
        <div class="sidebar-popover absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[10px] font-bold rounded-lg shadow-lg whitespace-nowrap pointer-events-none z-50" data-i18n="settings">
          Config Settings
        </div>
      </div>

      <!-- CRUD Generator Group -->
      <div id="menu-generator" class="relative group sidebar-group">
        <a href="/admin/crud-generator"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/crud-generator*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-wand-magic-sparkles w-4 text-center {{ request()->is('admin/crud-generator*') ? 'text-brand-500' : 'text-violet-500' }}"></i>
          <span data-i18n="crud_generator">CRUD Generator</span>
        </a>
        <!-- Popover tooltip -->
        <div class="sidebar-popover absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[10px] font-bold rounded-lg shadow-lg whitespace-nowrap pointer-events-none z-50" data-i18n="crud_generator">
          CRUD Generator
        </div>
      </div>

      <!-- Database Backups Group -->
      <div id="menu-backups" class="relative group sidebar-group">
        <a href="/admin/backups"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/backups*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-database w-4 text-center {{ request()->is('admin/backups*') ? 'text-brand-500' : 'text-emerald-500' }}"></i>
          <span>Database Backups</span>
        </a>
        <div class="sidebar-popover absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[10px] font-bold rounded-lg shadow-lg whitespace-nowrap pointer-events-none z-50">
          Database Backups
        </div>
      </div>

      <!-- Outgoing Webhooks Group -->
      <div id="menu-webhooks" class="relative group sidebar-group">
        <a href="/admin/webhooks"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/webhooks*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-satellite-dish w-4 text-center {{ request()->is('admin/webhooks*') ? 'text-brand-500' : 'text-sky-500' }}"></i>
          <span>Webhooks Engine</span>
        </a>
        <div class="sidebar-popover absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[10px] font-bold rounded-lg shadow-lg whitespace-nowrap pointer-events-none z-50">
          Webhooks Engine
        </div>
      </div>

      <!-- API Docs Group -->
      <div id="menu-docs" class="relative group sidebar-group">
        <a href="/admin/docs"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/docs*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-code w-4 text-center {{ request()->is('admin/docs*') ? 'text-brand-500' : 'text-indigo-500' }}"></i>
          <span>API Documentation</span>
        </a>
        <div class="sidebar-popover absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[10px] font-bold rounded-lg shadow-lg whitespace-nowrap pointer-events-none z-50">
          API Documentation
        </div>
      </div>

    </nav>
  </div>
</aside>

<script>
  /**
   * Toggles accordion submenu open/closed.
   * @param {string} menuId - ID of the submenu container element
   * @param {string} chevronId - ID of the chevron icon to rotate
   */
  function toggleSubmenu(menuId, chevronId) {
    const menu = document.getElementById(menuId);
    const chevron = document.getElementById(chevronId);

    if (menu.classList.contains('hidden')) {
      menu.classList.remove('hidden');
      chevron.classList.add('rotate-90');
    } else {
      menu.classList.add('hidden');
      chevron.classList.remove('rotate-90');
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const user = JSON.parse(localStorage.getItem('laraforgex_user') || '{}');
    const permissions = user.permissions || [];
    const roles = user.roles || [];
    const isAdmin = roles.includes('administrator');

    const hasPermission = (p) => permissions.includes(p) || isAdmin;

    let showIdentityHeader = false;
    let showSystemHeader = false;

    // 1. User Accounts
    if (hasPermission('users.view')) {
      const el = document.getElementById('menu-users');
      if (el) el.classList.remove('hidden');
      showIdentityHeader = true;
    }

    // 2. Access Control (roles & permissions)
    if (hasPermission('roles.view')) {
      const el = document.getElementById('menu-rbac');
      if (el) el.classList.remove('hidden');
      showIdentityHeader = true;
    }

    // 3. Media Cloud
    if (hasPermission('media.view') || hasPermission('media.upload')) {
      const el = document.getElementById('menu-media');
      if (el) el.classList.remove('hidden');
      showSystemHeader = true;
    }

    // 4. Config Settings
    if (hasPermission('settings.view')) {
      const el = document.getElementById('menu-settings');
      if (el) el.classList.remove('hidden');
      showSystemHeader = true;
    }

    // 5. CRUD Generator
    showSystemHeader = true;

    // Toggle headers
    if (showIdentityHeader) {
      const el = document.getElementById('header-identity');
      if (el) el.classList.remove('hidden');
    }
    if (showSystemHeader) {
      const el = document.getElementById('header-system');
      if (el) el.classList.remove('hidden');
    }

    // Auto-minimize sidebar on mobile when navigating links
    const sidebar = document.getElementById('admin-sidebar');
    if (!sidebar) return;

    const links = sidebar.querySelectorAll('nav a');
    links.forEach(function (el) {
      el.addEventListener('click', function () {
        if (window.innerWidth < 1024 && typeof window.closeSidebar === 'function') {
          window.closeSidebar();
        }
      });
    });
  });
</script>
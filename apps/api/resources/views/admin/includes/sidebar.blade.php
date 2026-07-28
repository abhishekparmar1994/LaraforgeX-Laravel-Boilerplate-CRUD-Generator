<!-- Sidebar -->
<aside
  class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 h-screen fixed inset-y-0 left-0 lg:sticky top-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-250 ease-in-out font-sans overflow-y-auto"
  id="admin-sidebar">
  <div class="px-4 py-6 space-y-6">
    <!-- Logo -->
    <div class="flex items-center justify-between px-2">
      <div class="flex items-center gap-2.5 min-w-0">
        <div id="sidebar-logo-container"
          class="h-8 w-8 rounded-lg bg-gradient-to-tr from-brand-600 to-violet-500 flex items-center justify-center text-white font-display font-extrabold text-base shadow-md shrink-0 overflow-hidden">
          <span id="sidebar-logo-text">L</span>
          <img id="sidebar-logo-img" class="h-8 w-8 rounded-lg object-cover hidden" src="" alt="Logo">
        </div>
        <span id="sidebar-app-name"
          class="logo-title-span font-display font-bold text-lg text-slate-900 tracking-tight truncate">Laraforge<span
            class="text-brand-600">X</span></span>
      </div>
      <button type="button"
        class="lg:hidden h-8 w-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition shrink-0"
        onclick="closeSidebar()" title="Close Sidebar">
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
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-chart-line text-brand-400 text-xs"></i>
          <span data-i18n="dashboard">Dashboard</span>
        </div>
      </div>

      <!-- ── IDENTITY & ACCESS ─────────────── -->
      <p id="header-identity"
        class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden">Identity & Access
      </p>

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
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-users text-brand-400 text-xs"></i>
          <span data-i18n="users">User Accounts</span>
        </div>
      </div>

      <!-- Roles & Permissions — Accordion parent -->
      <div id="menu-rbac" class="relative group sidebar-group hidden">
        <div class="space-y-0.5">
          <button type="button" id="submenu-rbac-toggle"
            class="sidebar-accordion-btn w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                          {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}"
            onclick="toggleSubmenu('submenu-rbac', 'chevron-rbac')">
            <div class="flex items-center gap-3">
              <i
                class="fa-solid fa-shield-halved w-4 text-center {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
              <span data-i18n="roles">Access Control</span>
            </div>
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
        <div class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 z-50">
          <div
            class="w-52 bg-slate-900/95 backdrop-blur-md border border-slate-700/60 rounded-2xl shadow-2xl p-2 text-left font-sans ring-1 ring-black/10">
            <div class="px-3 py-2 rounded-xl bg-slate-800/80 border border-slate-700/50 flex items-center gap-2 mb-1.5">
              <i class="fa-solid fa-shield-halved text-brand-400 text-xs"></i>
              <span class="text-xs font-bold text-white uppercase tracking-wider" data-i18n="roles">Access
                Control</span>
            </div>
            <div class="space-y-0.5">
              <a href="/admin/roles"
                class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->is('admin/roles') && !request()->is('admin/permissions') ? 'bg-brand-500/20 text-brand-400 font-bold border-l-2 border-brand-400' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }} transition">
                <i class="fa-regular fa-circle-dot text-[10px] text-brand-400"></i>
                <span data-i18n="roles">Roles Matrix</span>
              </a>
              <a href="/admin/permissions"
                class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->is('admin/permissions*') ? 'bg-brand-500/20 text-brand-400 font-bold border-l-2 border-brand-400' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }} transition">
                <i class="fa-regular fa-circle-dot text-[10px] text-brand-400"></i>
                <span data-i18n="permissions">Permissions Map</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- ── SYSTEM CORE ───────────────────── -->
      <p id="header-system"
        class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden">System Core</p>

      <!-- Media Manager — Accordion parent -->
      <div id="menu-media" class="relative group sidebar-group hidden">
        <div class="space-y-0.5">
          <button type="button" id="submenu-media-toggle"
            class="sidebar-accordion-btn w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                          {{ request()->is('admin/media*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}"
            onclick="toggleSubmenu('submenu-media', 'chevron-media')">
            <div class="flex items-center gap-3">
              <i
                class="fa-solid fa-folder-open w-4 text-center {{ request()->is('admin/media*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
              <span data-i18n="media">Media Cloud</span>
            </div>
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
        <div class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 z-50">
          <div
            class="w-52 bg-slate-900/95 backdrop-blur-md border border-slate-700/60 rounded-2xl shadow-2xl p-2 text-left font-sans ring-1 ring-black/10">
            <div class="px-3 py-2 rounded-xl bg-slate-800/80 border border-slate-700/50 flex items-center gap-2 mb-1.5">
              <i class="fa-solid fa-folder-open text-brand-400 text-xs"></i>
              <span class="text-xs font-bold text-white uppercase tracking-wider" data-i18n="media">Media Cloud</span>
            </div>
            <div class="space-y-0.5">
              <a href="/admin/media"
                class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold {{ request()->is('admin/media*') ? 'bg-brand-500/20 text-brand-400 font-bold border-l-2 border-brand-400' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }} transition">
                <i class="fa-regular fa-circle-dot text-[10px] text-brand-400"></i>
                <span>File Browser</span>
              </a>
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
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-sliders text-brand-400 text-xs"></i>
          <span data-i18n="settings">Config Settings</span>
        </div>
      </div>

      <!-- CRUD Generator Group -->
      <div id="menu-generator" class="relative group sidebar-group hidden">
        <a href="/admin/crud-generator"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/crud-generator*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-wand-magic-sparkles w-4 text-center {{ request()->is('admin/crud-generator*') ? 'text-brand-500' : 'text-violet-500' }}"></i>
          <span data-i18n="crud_generator">CRUD Generator</span>
        </a>
        <!-- Popover tooltip -->
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-wand-magic-sparkles text-brand-400 text-xs"></i>
          <span data-i18n="crud_generator">CRUD Generator</span>
        </div>
      </div>

      <!-- Database Backups Group -->
      <div id="menu-backups" class="relative group sidebar-group hidden">
        <a href="/admin/backups"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/backups*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-database w-4 text-center {{ request()->is('admin/backups*') ? 'text-brand-500' : 'text-emerald-500' }}"></i>
          <span data-i18n="backups">Database Backups</span>
        </a>
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-database text-brand-400 text-xs"></i>
          <span data-i18n="backups">Database Backups</span>
        </div>
      </div>

      <!-- Database Studio / Table Manager Group -->
      <div id="menu-database-manager" class="relative group sidebar-group hidden">
        <a href="/admin/database-manager"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/database-manager*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-table-cells w-4 text-center {{ request()->is('admin/database-manager*') ? 'text-brand-500' : 'text-cyan-500' }}"></i>
          <span data-i18n="database_manager">Database Studio</span>
        </a>
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-table-cells text-brand-400 text-xs"></i>
          <span data-i18n="database_manager">Database Studio</span>
        </div>
      </div>

      <!-- Outgoing Webhooks Group -->
      <div id="menu-webhooks" class="relative group sidebar-group hidden">
        <a href="/admin/webhooks"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/webhooks*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-satellite-dish w-4 text-center {{ request()->is('admin/webhooks*') ? 'text-brand-500' : 'text-sky-500' }}"></i>
          <span data-i18n="webhooks">Webhooks Engine</span>
        </a>
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-satellite-dish text-brand-400 text-xs"></i>
          <span data-i18n="webhooks">Webhooks Engine</span>
        </div>
      </div>

      <!-- API Docs Group -->
      <div id="menu-docs" class="relative group sidebar-group hidden">
        <a href="/admin/docs"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/docs*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-code w-4 text-center {{ request()->is('admin/docs*') ? 'text-brand-500' : 'text-indigo-500' }}"></i>
          <span data-i18n="api_docs">API Documentation</span>
        </a>
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-code text-brand-400 text-xs"></i>
          <span data-i18n="api_docs">API Documentation</span>
        </div>
      </div>

      <!-- Audit Trail Group -->
      <div id="menu-audit" class="relative group sidebar-group hidden">
        <a href="/admin/activity-logs"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/activity-logs*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-list-check w-4 text-center {{ request()->is('admin/activity-logs*') ? 'text-brand-500' : 'text-amber-500' }}"></i>
          <span data-i18n="audit_logs">Audit Logs</span>
        </a>
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-list-check text-brand-400 text-xs"></i>
          <span data-i18n="audit_logs">Audit Logs</span>
        </div>
      </div>

      <!-- System Health Group -->
      <div id="menu-health" class="relative group sidebar-group hidden">
        <a href="/admin/health"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150
                  {{ request()->is('admin/health*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i
            class="fa-solid fa-heart-pulse w-4 text-center {{ request()->is('admin/health*') ? 'text-brand-500' : 'text-rose-500' }}"></i>
          <span data-i18n="system_health">System Health</span>
        </a>
        <div
          class="sidebar-popover sidebar-tooltip-caret absolute left-full top-1/2 -translate-y-1/2 ml-3.5 px-3 py-2 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold rounded-xl shadow-2xl border border-slate-700/60 flex items-center gap-2.5 whitespace-nowrap pointer-events-none z-50">
          <i class="fa-solid fa-heart-pulse text-brand-400 text-xs"></i>
          <span data-i18n="system_health">System Health</span>
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

  function renderSidebarPermissions() {
    const user = JSON.parse(localStorage.getItem('laraforgex_user') || '{}');
    const permissions = user.permissions || [];
    const hasPermission = (p) => permissions.includes(p);

    const toggle = (id, allowed) => {
      const el = document.getElementById(id);
      if (el) {
        if (allowed) {
          el.classList.remove('hidden');
        } else {
          el.classList.add('hidden');
        }
      }
      return allowed;
    };

    let showIdentity = false;
    let showSystem = false;

    if (toggle('menu-users', hasPermission('users.view'))) showIdentity = true;
    if (toggle('menu-rbac', hasPermission('roles.view'))) showIdentity = true;
    if (toggle('menu-media', hasPermission('media.view') || hasPermission('media.upload'))) showSystem = true;
    if (toggle('menu-settings', hasPermission('settings.view'))) showSystem = true;
    if (toggle('menu-generator', hasPermission('crud_generator.view'))) showSystem = true;
    if (toggle('menu-backups', hasPermission('backups.view'))) showSystem = true;
    if (toggle('menu-database-manager', hasPermission('database_manager.view'))) showSystem = true;
    if (toggle('menu-webhooks', hasPermission('webhooks.view'))) showSystem = true;
    if (toggle('menu-docs', hasPermission('docs.view'))) showSystem = true;
    if (toggle('menu-audit', hasPermission('audit_logs.view'))) showSystem = true;
    if (toggle('menu-health', hasPermission('system_health.view'))) showSystem = true;

    toggle('header-identity', showIdentity);
    toggle('header-system', showSystem);
  }


  window.renderSidebarPermissions = renderSidebarPermissions;
  document.addEventListener('DOMContentLoaded', function () {
    renderSidebarPermissions();

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
<!-- Sidebar: TailAdmin Inspired Clean Modern Aesthetic -->
<style>
  #admin-sidebar {
    font-family: 'Plus Jakarta Sans', 'Satoshi', ui-sans-serif, system-ui, sans-serif !important;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  #admin-sidebar::-webkit-scrollbar {
    display: none;
  }
  #admin-sidebar-nav {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
  }
  #admin-sidebar-nav::-webkit-scrollbar {
    width: 4px;
  }
  #admin-sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
  }
  #admin-sidebar-nav::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 9999px;
  }
  #admin-sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
  }
  #admin-sidebar.sidebar-collapsed .sidebar-badge,
  #admin-sidebar.sidebar-collapsed .logo-subtext,
  #admin-sidebar.sidebar-collapsed .user-details {
    display: none !important;
  }
</style>

<aside
  id="admin-sidebar"
  class="w-68 xl:w-72 bg-white border-r border-slate-200/90 flex flex-col shrink-0 h-screen fixed inset-y-0 left-0 lg:sticky top-0 z-40 -translate-x-full lg:translate-x-0 transition-all duration-200 ease-in-out select-none overflow-hidden"
>
  <!-- Brand Logo Header (Pinned Top) -->
  <div class="h-16 px-4 xl:px-5 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
    <a href="/admin/dashboard" class="flex items-center gap-3 min-w-0 group sidebar-logo-link">
      <div id="sidebar-logo-container"
        class="h-9 w-9 rounded-xl bg-gradient-to-tr from-amber-500 via-orange-500 to-amber-600 flex items-center justify-center text-white font-bold text-base shadow-sm shadow-orange-500/20 shrink-0 group-hover:scale-105 transition-transform duration-200">
        <i class="fa-solid fa-solar-panel text-sm"></i>
      </div>
      <div class="min-w-0">
        <div class="flex items-center gap-1.5">
          <span id="sidebar-app-name" class="logo-title-span font-bold text-base text-slate-900 tracking-tight truncate block">SolarPulse</span>
          <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded-md bg-amber-100 text-amber-800 uppercase tracking-wider logo-subtext">CRM</span>
        </div>
        <span class="text-[10px] font-semibold text-slate-400 block -mt-0.5 tracking-wider uppercase truncate logo-subtext">Solar EPC & Rooftop OS</span>
      </div>
    </a>
    <button type="button"
      class="lg:hidden h-8 w-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition shrink-0 cursor-pointer"
      onclick="closeSidebar()" title="Close Sidebar">
      <i class="fa-solid fa-xmark text-sm"></i>
    </button>
  </div>

  <!-- Navigation Menu (Smooth Scrollable Center) -->
  <nav id="admin-sidebar-nav" class="flex-1 overflow-y-auto px-3.5 py-3.5 space-y-4 overscroll-contain">

    <!-- ── MAIN MENU ──────────────────────── -->
    <div>
      <p class="px-3 pb-1 text-[11px] font-semibold text-[#8a99ad] uppercase tracking-wider">Main Menu</p>
      <div class="relative group sidebar-group">
        <a href="/admin/dashboard"
          class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
            {{ request()->is('admin/dashboard') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
          <i class="fa-solid fa-chart-pie w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/dashboard') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
          <span class="sidebar-link-text truncate flex-1">Dashboard</span>
        </a>
        <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
          Dashboard
        </div>
      </div>
    </div>

    <!-- Hidden per user requests -->
    <div class="hidden">
      <a href="/admin/ai-agent">AI CRM Agent</a>
      <a href="/admin/websites">Websites</a>
      <a href="/admin/activities">Activity Stream</a>
      <a href="/admin/notes">Notes Vault</a>
    </div>

    <!-- ── SOLAR CRM PIPELINE ─────────────── -->
    <div>
      <p class="px-3 pb-1 text-[11px] font-semibold text-[#8a99ad] uppercase tracking-wider">Solar CRM</p>
      <div class="space-y-0.5">

        <!-- Team Performance -->
        <div class="relative group sidebar-group">
          <a href="/admin/team-performance"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/team-performance*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-trophy w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/team-performance*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Team Performance</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Team Performance
          </div>
        </div>

        <!-- Solar Leads -->
        <div class="relative group sidebar-group">
          <a href="/admin/leads"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/leads') && !request()->is('admin/leads/kanban') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-solar-panel w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/leads') && !request()->is('admin/leads/kanban') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Solar Leads</span>
            <span class="sidebar-badge text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-800 shrink-0">1.3k</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Solar Leads
          </div>
        </div>

        <!-- EPC Solar Pipeline -->
        <div class="relative group sidebar-group">
          <a href="/admin/leads/kanban"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/leads/kanban*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-table-columns w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/leads/kanban*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">EPC Solar Pipeline</span>
            <span class="sidebar-badge text-[9px] font-black uppercase px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-500 group-hover:bg-amber-100 group-hover:text-amber-800 transition shrink-0">PIPE</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            EPC Solar Pipeline
          </div>
        </div>

        <!-- Follow-ups -->
        <div class="relative group sidebar-group">
          <a href="/admin/followups"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/followups*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-phone-volume w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/followups*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Follow-ups</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Follow-ups
          </div>
        </div>

        <!-- Contacts -->
        <div class="relative group sidebar-group">
          <a href="/admin/contacts"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/contacts*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-address-book w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/contacts*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Contacts</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Contacts
          </div>
        </div>

        <!-- Clients -->
        <div class="relative group sidebar-group">
          <a href="/admin/clients"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/clients*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-user-tie w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/clients*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Clients</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Clients
          </div>
        </div>

        <!-- Companies -->
        <div class="relative group sidebar-group">
          <a href="/admin/companies"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/companies*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-building w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/companies*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Companies</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Companies
          </div>
        </div>

      </div>
    </div>

    <!-- ── OPERATIONS ─────────────────────── -->
    <div>
      <p class="px-3 pb-1 text-[11px] font-semibold text-[#8a99ad] uppercase tracking-wider">Operations</p>
      <div class="space-y-0.5">

        <!-- Projects -->
        <div class="relative group sidebar-group">
          <a href="/admin/projects"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/projects*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-diagram-project w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/projects*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Projects</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Projects
          </div>
        </div>

        <!-- Solar Materials & BOM -->
        <div class="relative group sidebar-group">
          <a href="/admin/materials"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/materials*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-boxes-stacked w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/materials*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Solar Materials & BOM</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Solar Materials & BOM
          </div>
        </div>

        <!-- Tasks List -->
        <div class="relative group sidebar-group">
          <a href="/admin/tasks"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/tasks') && !request()->is('admin/tasks/kanban') && !request()->is('admin/tasks/todo') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-list-check w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/tasks') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Tasks List</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Tasks List
          </div>
        </div>

        <!-- Task Kanban -->
        <div class="relative group sidebar-group">
          <a href="/admin/tasks/kanban"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/tasks/kanban*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-table-cells-large w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/tasks/kanban*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Task Kanban</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Task Kanban
          </div>
        </div>

        <!-- My Todo List -->
        <div class="relative group sidebar-group">
          <a href="/admin/tasks/todo"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/tasks/todo*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-circle-check w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/tasks/todo*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">My Todo List</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            My Todo List
          </div>
        </div>

        <!-- Operations Calendar -->
        <div class="relative group sidebar-group">
          <a href="/admin/calendar"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/calendar*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-calendar-days w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/calendar*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Operations Calendar</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Operations Calendar
          </div>
        </div>

      </div>
    </div>

    <!-- ── FINANCES ───────────────────────── -->
    <div>
      <p class="px-3 pb-1 text-[11px] font-semibold text-[#8a99ad] uppercase tracking-wider">Finances</p>
      <div class="space-y-0.5">

        <div class="relative group sidebar-group">
          <a href="/admin/payments"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/payments*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/payments*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Invoices & Payments</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Invoices & Payments
          </div>
        </div>

        <div class="relative group sidebar-group">
          <a href="/admin/reports"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/reports*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-chart-line w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/reports*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Business Reports</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Business Reports
          </div>
        </div>

      </div>
    </div>

    <!-- ── ADMINISTRATION ─────────────────── -->
    <div>
      <p class="px-3 pb-1 text-[11px] font-semibold text-[#8a99ad] uppercase tracking-wider">Administration</p>
      <div class="space-y-0.5">

        <div class="relative group sidebar-group">
          <a href="/admin/team"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/team*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-users-gear w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/team*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Executive Team</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Executive Team
          </div>
        </div>

        <div class="relative group sidebar-group">
          <a href="/admin/users"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/users*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-user-shield w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/users*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">User Accounts</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            User Accounts
          </div>
        </div>

        <div class="relative group sidebar-group">
          <a href="/admin/settings"
            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
              {{ request()->is('admin/settings*') ? 'bg-amber-500/10 text-amber-800 font-semibold border border-amber-500/20 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
            <i class="fa-solid fa-sliders w-5 text-center text-[15px] shrink-0 {{ request()->is('admin/settings*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
            <span class="sidebar-link-text truncate flex-1">Settings</span>
          </a>
          <div class="sidebar-popover sidebar-tooltip-caret hidden absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2.5 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-xl whitespace-nowrap z-50 pointer-events-none">
            Settings
          </div>
        </div>

      </div>
    </div>

  </nav>

  <!-- Pinned Administrator Profile Footer (Pinned Bottom) -->
  <div class="sidebar-user-card shrink-0 p-3 xl:p-3.5 border-t border-slate-100 bg-slate-50/80">
    <div class="flex items-center justify-between gap-2.5">
      <div class="flex items-center gap-2.5 min-w-0 user-info-wrapper">
        <div id="user-avatar-placeholder" class="relative shrink-0">
          <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center font-bold text-xs shadow-xs">
            {{ substr(auth()->user()->name ?? 'Admin', 0, 2) }}
          </div>
          <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
        </div>
        <div class="min-w-0 user-details">
          <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
          <p class="text-[10px] font-semibold text-amber-700 truncate">Solar EPC Executive</p>
        </div>
      </div>
      <a href="/admin/login" id="logout-btn" title="Logout"
        class="h-8 w-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition shrink-0 cursor-pointer">
        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
      </a>
    </div>
  </div>
</aside>

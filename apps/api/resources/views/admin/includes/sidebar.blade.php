<!-- Sidebar -->
<aside
  class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 h-screen fixed inset-y-0 left-0 lg:sticky top-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-250 ease-in-out font-sans overflow-y-auto"
  id="admin-sidebar">
  <div class="px-4 py-5 space-y-5">
    <!-- Logo -->
    <div class="flex items-center justify-between px-2">
      <a href="/admin/dashboard" class="flex items-center gap-2.5 min-w-0 group">
        <div id="sidebar-logo-container"
          class="h-9 w-9 rounded-xl bg-gradient-to-tr from-brand-600 via-indigo-600 to-violet-600 flex items-center justify-center text-white font-display font-extrabold text-base shadow-md shrink-0 overflow-hidden group-hover:scale-105 transition-transform">
          <i class="fa-solid fa-layer-group text-sm"></i>
        </div>
        <div class="min-w-0">
          <span id="sidebar-app-name"
            class="logo-title-span font-display font-bold text-base text-slate-900 tracking-tight truncate block">WebForge<span
              class="text-brand-600"> CRM</span></span>
          <span class="text-[10px] font-semibold text-slate-400 block -mt-1 tracking-wider uppercase">Agency OS</span>
        </div>
      </a>
      <button type="button"
        class="lg:hidden h-8 w-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition shrink-0"
        onclick="closeSidebar()" title="Close Sidebar">
        <i class="fa-solid fa-xmark text-base"></i>
      </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="space-y-0.5">

      <!-- ── DASHBOARD ──────────────────────── -->
      <p class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dashboard</p>
      <div class="relative group sidebar-group">
        <a href="/admin/dashboard"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/dashboard') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->is('admin/dashboard') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Dashboard</span>
        </a>
      </div>

      <!-- ── CRM MODULES ────────────────────── -->
      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">CRM & Pipeline</p>

      <!-- Leads -->
      <div class="relative group sidebar-group">
        <a href="/admin/leads"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/leads') && !request()->is('admin/leads/kanban') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-filter w-4 text-center {{ request()->is('admin/leads') && !request()->is('admin/leads/kanban') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Leads</span>
        </a>
      </div>

      <!-- Lead Pipeline Kanban -->
      <div class="relative group sidebar-group">
        <a href="/admin/leads/kanban"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/leads/kanban*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-table-columns w-4 text-center {{ request()->is('admin/leads/kanban*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Sales Pipeline</span>
        </a>
      </div>

      <!-- Contacts -->
      <div class="relative group sidebar-group">
        <a href="/admin/contacts"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/contacts*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-address-book w-4 text-center {{ request()->is('admin/contacts*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Contacts</span>
        </a>
      </div>

      <!-- Clients -->
      <div class="relative group sidebar-group">
        <a href="/admin/clients"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/clients*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-user-tie w-4 text-center {{ request()->is('admin/clients*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Clients</span>
        </a>
      </div>

      <!-- Companies -->
      <div class="relative group sidebar-group">
        <a href="/admin/companies"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/companies*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-building w-4 text-center {{ request()->is('admin/companies*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Companies</span>
        </a>
      </div>

      <!-- ── PROJECTS & SITES ───────────────── -->
      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Websites & Projects</p>

      <!-- Websites -->
      <div class="relative group sidebar-group">
        <a href="/admin/websites"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/websites*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-globe w-4 text-center {{ request()->is('admin/websites*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Websites</span>
        </a>
      </div>

      <!-- Projects -->
      <div class="relative group sidebar-group">
        <a href="/admin/projects"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/projects*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-diagram-project w-4 text-center {{ request()->is('admin/projects*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Projects</span>
        </a>
      </div>

      <!-- Tasks Group -->
      <div class="space-y-0.5">
        <a href="/admin/tasks"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/tasks') && !request()->is('admin/tasks/kanban') && !request()->is('admin/tasks/todo') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-list-check w-4 text-center {{ request()->is('admin/tasks') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Tasks List</span>
        </a>
        <a href="/admin/tasks/kanban"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/tasks/kanban*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-table-cells-large w-4 text-center {{ request()->is('admin/tasks/kanban*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Task Kanban</span>
        </a>
        <a href="/admin/tasks/todo"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/tasks/todo*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-circle-check w-4 text-center {{ request()->is('admin/tasks/todo*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>My Todo List</span>
        </a>
      </div>

      <!-- Calendar -->
      <div class="relative group sidebar-group">
        <a href="/admin/calendar"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/calendar*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-calendar-days w-4 text-center {{ request()->is('admin/calendar*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Agency Calendar</span>
        </a>
      </div>

      <!-- ── COMMUNICATION ──────────────────── -->
      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Communication</p>

      <div class="relative group sidebar-group">
        <a href="/admin/followups"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/followups*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-phone-volume w-4 text-center {{ request()->is('admin/followups*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Follow-ups</span>
        </a>
      </div>

      <div class="relative group sidebar-group">
        <a href="/admin/activities"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/activities*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-clock-rotate-left w-4 text-center {{ request()->is('admin/activities*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Activity Stream</span>
        </a>
      </div>

      <div class="relative group sidebar-group">
        <a href="/admin/notes"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/notes*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-note-sticky w-4 text-center {{ request()->is('admin/notes*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Notes Vault</span>
        </a>
      </div>

      <!-- ── FINANCE & REPORTS ──────────────── -->
      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Finance & Analytics</p>

      <div class="relative group sidebar-group">
        <a href="/admin/payments"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/payments*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-file-invoice-dollar w-4 text-center {{ request()->is('admin/payments*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Invoices & Payments</span>
        </a>
      </div>

      <div class="relative group sidebar-group">
        <a href="/admin/reports"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/reports*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-chart-line w-4 text-center {{ request()->is('admin/reports*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Business Reports</span>
        </a>
      </div>

      <!-- ── TEAM & SYSTEM ──────────────────── -->
      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Team & System</p>

      <div class="relative group sidebar-group">
        <a href="/admin/team"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/team*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-users-gear w-4 text-center {{ request()->is('admin/team*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>4-Developer Team</span>
        </a>
      </div>

      <div class="relative group sidebar-group">
        <a href="/admin/users"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/users*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-user-shield w-4 text-center {{ request()->is('admin/users*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>User Accounts</span>
        </a>
      </div>

      <div class="relative group sidebar-group">
        <a href="/admin/settings"
          class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ request()->is('admin/settings*') ? 'bg-brand-50 text-brand-600 border-l-2 border-brand-500 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
          <i class="fa-solid fa-sliders w-4 text-center {{ request()->is('admin/settings*') ? 'text-brand-500' : 'text-slate-400' }}"></i>
          <span>Settings</span>
        </a>
      </div>

    </nav>
  </div>

  <!-- Bottom Agency Profile Badge -->
  <div class="p-4 border-t border-slate-200 bg-slate-50/50">
    <div class="flex items-center gap-3">
      <div class="h-9 w-9 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
        {{ substr(auth()->user()->name ?? 'Rohan', 0, 2) }}
      </div>
      <div class="min-w-0 flex-1">
        <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name ?? 'Rohan' }}</p>
        <p class="text-[10px] font-medium text-slate-400 truncate">Lead Dev / Admin</p>
      </div>
      <a href="/admin/login" title="Logout / Switch User" class="text-slate-400 hover:text-rose-600 transition text-sm">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </a>
    </div>
  </div>
</aside>
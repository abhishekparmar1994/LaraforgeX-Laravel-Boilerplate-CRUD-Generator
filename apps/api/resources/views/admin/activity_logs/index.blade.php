@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Activity Audit Logs')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition" data-i18n="dashboard">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Audit Trail</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-6 font-sans">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-700 to-indigo-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
      <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-brand-200 text-xs font-bold uppercase tracking-widest mb-1">
            <i class="fa-solid fa-list-check text-amber-300"></i> Security Audit Trail
          </div>
          <h1 class="text-2xl font-extrabold tracking-tight">System Activity Logs</h1>
          <p class="text-sm text-brand-100 mt-1 max-w-2xl">
            Real-time audit logging of user authentication events, CRUD operations, and setting modifications.
          </p>
        </div>
        <button type="button" onclick="clearLogs()"
          class="px-4 py-2.5 rounded-xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 font-extrabold text-xs transition border border-rose-400/30 inline-flex items-center gap-2 shrink-0 cursor-pointer">
          <i class="fa-solid fa-trash-can"></i> Clear Audit Trail
        </button>
      </div>
    </div>

    <!-- Logs Table Container -->
    <div id="logs-datatable"></div>

  </div>
@endsection

@section('scripts')
<script>
  let logsTable;

  $(document).ready(function() {
      logsTable = new AdminTable({
          container: '#logs-datatable',
          columns: [
              { key: 'action', label: 'Action Event', sortable: true },
              { key: 'user', label: 'User / Identity', sortable: true },
              { key: 'description', label: 'Description', sortable: true, responsive: 'sm' },
              { key: 'ip_address', label: 'IP Address', sortable: true, responsive: 'md' },
              { key: 'created_at', label: 'Timestamp', sortable: true, responsive: 'sm' }
          ],
          fetch: async () => {
              const res = await axios.get('/activity-logs');
              return res.data.data;
          },
          row: (item) => `
              <tr class="hover:bg-slate-50/80 transition">
                  <td class="px-4 py-3 text-xs">
                      <span class="px-2.5 py-1 rounded-md bg-brand-50 text-brand-600 font-mono font-bold text-[10px]">${item.action}</span>
                  </td>
                  <td class="px-4 py-3 text-xs font-bold text-slate-900">${item.user}</td>
                  <td class="px-4 py-3 text-xs text-slate-600">${item.description}</td>
                  <td class="px-4 py-3 text-xs font-mono text-slate-500">${item.ip_address}</td>
                  <td class="px-4 py-3 text-xs text-slate-500">${item.created_at}</td>
              </tr>
          `
      });

      logsTable.load();
  });

  async function clearLogs() {
      if (confirm('Clear all audit logs?')) {
          try {
              const res = await axios.delete('/activity-logs');
              if (res.data.success) {
                  showToast('success', res.data.message);
                  logsTable.reload();
              }
          } catch (err) {
              handleAjaxError(err, 'Failed to clear logs.');
          }
      }
  }
</script>
@endsection

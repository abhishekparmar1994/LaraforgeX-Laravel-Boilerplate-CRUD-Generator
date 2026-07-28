@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Database Backups')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition" data-i18n="dashboard">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Database Backups</span>
  </nav>
@endsection

@section('content')
  <div class="space-y-6 font-sans">

    <!-- Header Banner -->
    <div class="theme-hero-banner bg-gradient-to-r from-brand-900 via-brand-700 to-indigo-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
      <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-brand-200 text-xs font-bold uppercase tracking-widest mb-1">
            <i class="fa-solid fa-database text-amber-300"></i> System Storage & Backups
          </div>
          <h1 class="text-2xl font-extrabold tracking-tight">Database Backup & SQL Export Manager</h1>
          <p class="text-sm text-brand-100 mt-1 max-w-2xl">
            Generate 1-click MySQL database SQL dump backups, download dump archives, or restore system state.
          </p>
        </div>
        <button type="button" onclick="generateBackup()" id="btn-generate-backup"
          class="px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-extrabold text-xs transition shadow-lg shadow-emerald-900/30 inline-flex items-center gap-2 shrink-0 cursor-pointer border-0">
          <i class="fa-solid fa-cloud-arrow-down text-sm"></i> Generate Backup Now
        </button>
      </div>
    </div>

    <!-- ── Filter Bar ──────────────────────────────────────────────── -->
    <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
        <!-- Date From -->
        <div class="space-y-1">
          <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Date From</label>
          <input type="date" id="filter-backup-from"
                 class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 transition">
        </div>
        <!-- Date To -->
        <div class="space-y-1">
          <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Date To</label>
          <input type="date" id="filter-backup-to"
                 class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 transition">
        </div>
        <!-- Reset -->
        <div class="flex items-end">
          <button id="btn-reset-backup-filters"
                  class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">
            <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Backups Table Container -->
    <div id="backups-datatable"></div>

  </div>
@endsection

@section('scripts')
<script>
  let backupTable;

  $(document).ready(function() {
      backupTable = new AdminTable({
          container: '#backups-datatable',
          columns: [
              { key: 'filename', label: 'Backup File Name', sortable: true },
              { key: 'size_human', label: 'File Size', sortable: true, responsive: 'sm' },
              { key: 'created_at', label: 'Created At', sortable: true, responsive: 'sm' },
              { key: 'actions', label: 'Actions', sortable: false, class: 'text-right' }
          ],
          fetch: async () => {
              const params = {};
              const from = $('#filter-backup-from').val();
              const to   = $('#filter-backup-to').val();
              if (from) params.from = from;
              if (to)   params.to   = to;
              const res = await axios.get('/backups', { params });
              return res.data.data;
          },
          row: (item) => `
              <tr class="hover:bg-slate-50/80 transition">
                  <td class="px-4 py-3 text-xs font-mono font-bold text-slate-900 flex items-center gap-2">
                      <i class="fa-solid fa-file-code text-brand-500 text-sm"></i>
                      <span>${item.filename}</span>
                  </td>
                  <td class="px-4 py-3 text-xs font-mono text-slate-600">${item.size_human}</td>
                  <td class="px-4 py-3 text-xs text-slate-500">${item.created_at}</td>
                  <td class="px-4 py-3 text-xs text-right space-x-1">
                      <a href="${item.download_url}" target="_blank" download class="px-3 py-1.5 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-600 font-bold transition inline-flex items-center gap-1">
                          <i class="fa-solid fa-download"></i> Download
                      </a>
                      <button onclick="deleteBackup('${item.filename}')" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold transition inline-flex items-center gap-1 border-0 cursor-pointer">
                          <i class="fa-solid fa-trash"></i> Delete
                      </button>
                  </td>
              </tr>
          `
      });

      backupTable.load();

      // ── Server-Side Filter Logic ──────────────────────────────────
      $('#filter-backup-from, #filter-backup-to').on('change', () => backupTable.reload());

      $('#btn-reset-backup-filters').on('click', () => {
        $('#filter-backup-from').val('');
        $('#filter-backup-to').val('');
        backupTable.reload();
      });
  });

  async function generateBackup() {
      $('#btn-generate-backup').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Generating Dump...');

      try {
          const res = await axios.post('/backups/generate');
          if (res.data.success) {
              showToast('success', res.data.message);
              backupTable.reload();
          }
      } catch (err) {
          handleAjaxError(err, 'Failed to generate database backup.');
      } finally {
          $('#btn-generate-backup').prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-down text-sm"></i> Generate Backup Now');
      }
  }

  async function deleteBackup(filename) {
      const confirm = await Swal.fire({
          title: 'Delete Backup?',
          text: `Are you sure you want to permanently delete '${filename}'?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#e11d48',
          confirmButtonText: 'Yes, Delete'
      });

      if (confirm.isConfirmed) {
          try {
              const res = await axios.delete(`/backups/${encodeURIComponent(filename)}`);
              if (res.data.success) {
                  showToast('success', res.data.message);
                  backupTable.reload();
              }
          } catch (err) {
              handleAjaxError(err, 'Failed to delete backup file.');
          }
      }
  }
</script>
@endsection

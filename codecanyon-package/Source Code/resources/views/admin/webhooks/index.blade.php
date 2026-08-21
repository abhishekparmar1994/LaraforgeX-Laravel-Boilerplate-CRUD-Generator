@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Outgoing Webhooks')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition" data-i18n="dashboard">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Webhooks Engine</span>
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
            <i class="fa-solid fa-satellite-dish text-amber-300"></i> Automation & Events
          </div>
          <h1 class="text-2xl font-extrabold tracking-tight">Outgoing Webhooks Engine</h1>
          <p class="text-sm text-brand-100 mt-1 max-w-2xl">
            Dispatch HTTP POST webhooks on system events (`user.registered`, `crud.created`, `setting.updated`) to external endpoints.
          </p>
        </div>
        <button type="button" onclick="openCreateWebhookModal()"
          class="px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-extrabold text-xs transition shadow-lg shadow-emerald-900/30 inline-flex items-center gap-2 shrink-0 cursor-pointer border-0">
          <i class="fa-solid fa-plus text-sm"></i> Add Webhook Endpoint
        </button>
      </div>
    </div>

    <!-- ── Filter Bar ──────────────────────────────────────────────── -->
    <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
        <!-- Name Search -->
        <div class="space-y-1">
          <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Webhook Name</label>
          <input id="filter-wh-name" type="text" placeholder="Search webhook name..."
                 class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brand-500 transition">
        </div>
        <!-- Event Type -->
        <div class="space-y-1">
          <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Trigger Event</label>
          <select id="filter-wh-event"
                  class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 transition">
            <option value="">All Events</option>
            <option value="user.registered">user.registered</option>
            <option value="crud.created">crud.created</option>
            <option value="backup.generated">backup.generated</option>
            <option value="setting.updated">setting.updated</option>
          </select>
        </div>
        <!-- Reset -->
        <div class="flex items-end">
          <button id="btn-reset-wh-filters"
                  class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">
            <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Webhooks Table -->
    <div id="webhooks-datatable"></div>

  </div>

  <!-- Create Webhook Modal -->
  <div id="modal-webhook" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl border border-slate-200">
      <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
        <h3 class="font-extrabold text-slate-900 text-base">New Webhook Endpoint</h3>
        <button onclick="closeWebhookModal()" class="text-slate-400 hover:text-slate-600 border-0 bg-transparent cursor-pointer"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>
      <form id="form-webhook" class="space-y-4">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Webhook Label Name</label>
          <input type="text" id="wh-name" required placeholder="e.g. Zapier Lead Trigger" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-500 outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Target Endpoint URL</label>
          <input type="url" id="wh-url" required placeholder="https://example.com/api/webhook" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-500 outline-none font-mono text-xs">
        </div>
        <div>
          <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Trigger Event</label>
          <select id="wh-event" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-500 outline-none bg-white">
            <option value="user.registered">user.registered (User Created)</option>
            <option value="crud.created">crud.created (Module Generated)</option>
            <option value="backup.generated">backup.generated (SQL Export Completed)</option>
          </select>
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" onclick="closeWebhookModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs border-0 cursor-pointer">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs border-0 cursor-pointer">Save Webhook</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  let webhookTable;

  $(document).ready(function() {
      webhookTable = new AdminTable({
          container: '#webhooks-datatable',
          columns: [
              { key: 'name', label: 'Webhook Name', sortable: true },
              { key: 'url', label: 'Target URL', sortable: true, responsive: 'sm' },
              { key: 'event', label: 'Trigger Event', sortable: true, responsive: 'sm' },
              { key: 'actions', label: 'Actions', sortable: false, class: 'text-right' }
          ],
          fetch: async () => {
              const params = {};
              const name  = $('#filter-wh-name').val().trim();
              const event = $('#filter-wh-event').val();
              if (name)  params.name  = name;
              if (event) params.event = event;
              const res = await axios.get('/webhooks', { params });
              return res.data.data;
          },
          row: (item) => `
              <tr class="hover:bg-slate-50/80 transition">
                  <td class="px-4 py-3 text-xs font-bold text-slate-900">${item.name}</td>
                  <td class="px-4 py-3 text-xs font-mono text-slate-600 truncate max-w-xs">${item.url}</td>
                  <td class="px-4 py-3 text-xs">
                      <span class="px-2 py-0.5 rounded-md bg-brand-50 text-brand-600 font-mono font-bold text-[10px]">${item.event}</span>
                  </td>
                  <td class="px-4 py-3 text-xs text-right space-x-1">
                      <button onclick="pingWebhook('${item.url}')" class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 font-bold transition inline-flex items-center gap-1 border-0 cursor-pointer">
                          <i class="fa-solid fa-paper-plane"></i> Test Ping
                      </button>
                      <button onclick="deleteWebhook('${item.id}')" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold transition inline-flex items-center gap-1 border-0 cursor-pointer">
                          <i class="fa-solid fa-trash"></i> Delete
                      </button>
                  </td>
              </tr>
          `
      });

      webhookTable.load();

      // ── Server-Side Filter Logic ──────────────────────────────────
      let _whFilterTimer;
      $('#filter-wh-name').on('input', () => {
        clearTimeout(_whFilterTimer);
        _whFilterTimer = setTimeout(() => webhookTable.reload(), 400);
      });
      $('#filter-wh-event').on('change', () => webhookTable.reload());

      $('#btn-reset-wh-filters').on('click', () => {
        $('#filter-wh-name').val('');
        $('#filter-wh-event').val('');
        webhookTable.reload();
      });

      $('#form-webhook').on('submit', async function(e) {
          e.preventDefault();
          try {
              const res = await axios.post('/webhooks', {
                  name: $('#wh-name').val(),
                  url: $('#wh-url').val(),
                  event: $('#wh-event').val()
              });
              if (res.data.success) {
                  showToast('success', res.data.message);
                  closeWebhookModal();
                  webhookTable.reload();
              }
          } catch (err) {
              handleAjaxError(err, 'Failed to save webhook.');
          }
      });
  });

  function openCreateWebhookModal() { $('#modal-webhook').removeClass('hidden'); }
  function closeWebhookModal() { $('#modal-webhook').addClass('hidden'); }

  async function pingWebhook(url) {
      showToast('info', 'Sending ping test payload...');
      try {
          const res = await axios.post('/webhooks/test', { url });
          if (res.data.success) {
              showToast('success', res.data.message);
          }
      } catch (err) {
          handleAjaxError(err, 'Webhook test ping failed.');
      }
  }

  async function deleteWebhook(id) {
      if (confirm('Delete this webhook endpoint?')) {
          try {
              const res = await axios.delete(`/webhooks/${id}`);
              if (res.data.success) {
                  showToast('success', res.data.message);
                  webhookTable.reload();
              }
          } catch (err) {
              handleAjaxError(err, 'Failed to delete webhook.');
          }
      }
  }
</script>
@endsection

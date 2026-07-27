@extends('admin.layouts.app')

@section('title', 'LaraforgeX — System Health')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition" data-i18n="dashboard">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">System Health & SMTP</span>
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
            <i class="fa-solid fa-heart-pulse text-emerald-400"></i> Server Diagnostics & Mail
          </div>
          <h1 class="text-2xl font-extrabold tracking-tight">System Health & SMTP Mail Tester</h1>
          <p class="text-sm text-brand-100 mt-1 max-w-2xl">
            Monitor real-time PHP memory, disk space, database connection status, and test SMTP mail settings.
          </p>
        </div>
      </div>
    </div>

    <!-- Health Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">PHP Version</p>
        <h3 class="text-xl font-extrabold text-slate-900 mt-1" id="val-php">—</h3>
      </div>
      <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Memory Limit / Usage</p>
        <h3 class="text-xl font-extrabold text-slate-900 mt-1" id="val-memory">—</h3>
      </div>
      <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Free Disk Space</p>
        <h3 class="text-xl font-extrabold text-slate-900 mt-1" id="val-disk">—</h3>
      </div>
      <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Database Connection</p>
        <h3 class="text-xl font-extrabold text-emerald-600 mt-1" id="val-db">Online</h3>
      </div>
    </div>

    <!-- SMTP Mail Tester Card -->
    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-4">
      <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">SMTP Email Connection Tester</h3>
      <p class="text-xs text-slate-500">Send an instant test email payload to verify your SMTP mail configuration.</p>
      
      <form id="form-test-mail" class="flex flex-col sm:flex-row gap-3">
        <input type="email" id="test-email" required placeholder="Enter recipient email (e.g. test@example.com)" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-500 outline-none">
        <button type="submit" id="btn-send-mail" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs transition border-0 cursor-pointer shrink-0">
          <i class="fa-solid fa-paper-plane mr-1"></i> Send Test Mail
        </button>
      </form>
    </div>

  </div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
      // Load health metrics
      axios.get('/health/metrics').then(res => {
          const d = res.data.data;
          $('#val-php').text(d.php_version);
          $('#val-memory').text(`${d.memory_usage_human} / ${d.memory_limit}`);
          $('#val-disk').text(`${d.disk_free_human} / ${d.disk_total_human}`);
          $('#val-db').text(d.database_status);
      });

      $('#form-test-mail').on('submit', async function(e) {
          e.preventDefault();
          $('#btn-send-mail').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Sending...');

          try {
              const res = await axios.post('/health/test-mail', { email: $('#test-email').val() });
              if (res.data.success) {
                  showToast('success', res.data.message);
              }
          } catch (err) {
              handleAjaxError(err, 'SMTP Test Email failed.');
          } finally {
              $('#btn-send-mail').prop('disabled', false).html('<i class="fa-solid fa-paper-plane mr-1"></i> Send Test Mail');
          }
      });
  });
</script>
@endsection

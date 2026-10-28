{{-- Modal: Import Leads Excel/CSV --}}
<div id="modal-import-leads" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-file-excel"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Import Solar Leads from Excel / CSV</h3>
          <p class="text-[11px] text-slate-400 font-medium">Bulk import rooftop solar inquiries & commercial properties</p>
        </div>
      </div>
      <button type="button" onclick="closeImportModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form action="/admin/leads/import" method="POST" enctype="multipart/form-data" class="space-y-3.5">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Select Excel (.xlsx) or CSV File</label>
        <input type="file" name="file" accept=".xlsx,.csv,.txt" required
          class="w-full text-xs font-semibold text-slate-700 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer bg-slate-50 border border-slate-200 rounded-2xl p-2">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Import Tag / User</label>
        <input type="text" name="imported_by" value="{{ auth()->user()?->name ?? 'Excel Import' }}"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>

      <div class="p-3 bg-slate-50 border border-slate-200/70 rounded-2xl space-y-1">
        <div class="flex items-center gap-1.5 text-slate-700 font-bold text-xs">
          <i class="fa-solid fa-shield-halved text-emerald-600"></i> Smart Duplicate Protection
        </div>
        <p class="text-[11px] text-slate-500 leading-relaxed">
          Leads with existing phone numbers or matching company names in the same location are automatically skipped.
        </p>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          <i class="fa-solid fa-upload mr-1"></i> Start Import
        </button>
      </div>
    </form>
  </div>
</div>


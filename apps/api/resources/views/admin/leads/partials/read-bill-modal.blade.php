{{-- Modal: Read Bill [AI] — Instant DISCOM Electricity Bill OCR & Sizing --}}
<div id="modal-read-bill-ai" class="fixed inset-0 z-60 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2.5">
        <span class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-amber-500/20">
          <i class="fa-solid fa-bolt"></i>
        </span>
        <div>
          <div class="flex items-center gap-1.5">
            <h3 class="font-extrabold text-sm text-slate-900">Read Electricity Bill</h3>
            <span class="bg-amber-100 text-amber-800 text-[10px] font-black px-1.5 py-0.5 rounded-md">AI POWERED</span>
          </div>
          <p class="text-[11px] text-slate-400 font-medium">Extract consumer info, sanctioned load, DISCOM & bill amount</p>
        </div>
      </div>
      <button type="button" onclick="closeReadBillModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs cursor-pointer">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Upload Box -->
    <div class="space-y-3">
      <div id="bill-dropzone" class="border-2 border-dashed border-amber-300 hover:border-amber-500 bg-amber-50/30 rounded-2xl p-6 text-center transition cursor-pointer" onclick="document.getElementById('bill-file-input').click()">
        <input type="file" id="bill-file-input" accept=".pdf,.png,.jpg,.jpeg" class="hidden" onchange="handleBillFileSelected(this)">
        <i class="fa-solid fa-file-invoice text-amber-500 text-3xl mb-2 block"></i>
        <p class="text-xs font-bold text-slate-800">Drop Indian Electricity Bill here (PDF / Image)</p>
        <p class="text-[11px] text-slate-400 mt-0.5">Supports MGVCL, DGVCL, PGVCL, UGVCL, Torrent Power & MSEDCL</p>
      </div>

      <!-- Quick Sample Buttons -->
      <div class="space-y-1.5">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Or Load Instant Demo Bill Sample:</p>
        <div class="grid grid-cols-2 gap-2">
          <button type="button" onclick="loadSampleBill('mgvcl')" class="p-2.5 bg-slate-50 hover:bg-amber-50/80 border border-slate-200 hover:border-amber-300 rounded-xl text-left transition cursor-pointer">
            <span class="font-bold text-xs text-slate-800 block">⚡ MGVCL 4.5 kW Residential</span>
            <span class="text-[10px] text-slate-400 block">Vadodara • Bill ₹4,850/mo</span>
          </button>
          <button type="button" onclick="loadSampleBill('torrent')" class="p-2.5 bg-slate-50 hover:bg-amber-50/80 border border-slate-200 hover:border-amber-300 rounded-xl text-left transition cursor-pointer">
            <span class="font-bold text-xs text-slate-800 block">⚡ Torrent Power 15 kW C&I</span>
            <span class="text-[10px] text-slate-400 block">Surat / Ahmd • Bill ₹28,500/mo</span>
          </button>
        </div>
      </div>

      <!-- Scanning animation / Preview -->
      <div id="bill-scanning-state" class="hidden p-4 bg-slate-900 text-white rounded-2xl space-y-2 text-xs">
        <div class="flex items-center gap-2 text-amber-400 font-bold">
          <i class="fa-solid fa-circle-notch fa-spin"></i>
          <span id="bill-scan-status-text">Scanning DISCOM barcode & OCR text...</span>
        </div>
        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
          <div id="bill-scan-progress" class="bg-gradient-to-r from-amber-400 to-emerald-400 h-full w-1/4 transition-all duration-300"></div>
        </div>
        <p id="bill-extracted-summary" class="text-[11px] text-slate-300 font-mono"></p>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
      <button type="button" onclick="closeReadBillModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition cursor-pointer">Cancel</button>
      <button type="button" id="btn-apply-bill-ai" onclick="applyBillAiExtraction()" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
        <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> Extract & Auto-Fill Form
      </button>
    </div>
  </div>
</div>

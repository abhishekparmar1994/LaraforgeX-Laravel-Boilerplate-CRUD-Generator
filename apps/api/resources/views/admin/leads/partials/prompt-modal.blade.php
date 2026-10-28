{{-- Modal: Solar Feasibility & Sizing Pitch --}}
<div id="modal-view-prompt" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-solar-panel"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900" id="promptModalTitle">Solar Feasibility & Sizing Pitch</h3>
          <p class="text-[11px] text-slate-400 font-medium">Ready-to-use rooftop sizing, kW capacity recommendation & quotation pitch</p>
        </div>
      </div>
      <button type="button" onclick="closePromptModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <div class="relative">
      <textarea id="promptModalText" rows="10" readonly class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-mono text-slate-800 focus:outline-none select-all"></textarea>
    </div>

    <div class="flex items-center justify-between pt-2">
      <span class="text-[11px] font-bold text-slate-400">Copy technical brief for quotation, WhatsApp, or customer proposal</span>
      <div class="flex items-center gap-2">
        <button type="button" onclick="copyPromptToClipboard()" id="btnCopyPrompt" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          <i class="fa-regular fa-copy mr-1"></i> Copy Solar Pitch
        </button>
        <button type="button" onclick="closePromptModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">
          Close
        </button>
      </div>
    </div>
  </div>
</div>


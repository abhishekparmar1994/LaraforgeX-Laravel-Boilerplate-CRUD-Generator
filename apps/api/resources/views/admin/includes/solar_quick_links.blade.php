{{-- Indian Solar Schemes Quick Links Toolbar --}}
<div
  class="mb-4 bg-white/90 border border-slate-200/80 rounded-2xl px-4 py-2.5 shadow-2xs flex items-center justify-between gap-3 flex-wrap">
  <div class="flex items-center gap-2 flex-wrap min-w-0">
    <div class="flex items-center gap-1.5 shrink-0 pr-2 border-r border-slate-200">
      <i class="fa-solid fa-solar-panel text-amber-500 text-xs"></i>
      <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Portals</span>
    </div>

    <div class="flex items-center gap-1.5 flex-wrap">
      <a href="https://pmsuryaghar.gov.in/consumerLogin" target="_blank" rel="noopener"
        class="inline-flex items-center gap-2 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-emerald-50/70 text-slate-700 hover:text-emerald-800 border border-slate-200 hover:border-emerald-300 transition shadow-2xs group">
        <span
          class="w-5 h-5 rounded-lg bg-emerald-100 text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center text-[10px] transition shrink-0">
          <i class="fa-solid fa-house-chimney"></i>
        </span>
        <span>PM Surya Ghar Consumer</span>
        <i
          class="fa-solid fa-arrow-up-right-from-square text-[8px] text-slate-400 group-hover:text-emerald-600 transition"></i>
      </a>

      <a href="https://pmsuryaghar.gov.in/vendorLogin" target="_blank" rel="noopener"
        class="inline-flex items-center gap-2 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-amber-50/70 text-slate-700 hover:text-amber-800 border border-slate-200 hover:border-amber-300 transition shadow-2xs group">
        <span
          class="w-5 h-5 rounded-lg bg-amber-100 text-amber-700 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center text-[10px] transition shrink-0">
          <i class="fa-solid fa-store"></i>
        </span>
        <span>Vendor Login</span>
        <i
          class="fa-solid fa-arrow-up-right-from-square text-[8px] text-slate-400 group-hover:text-amber-600 transition"></i>
      </a>

      <a href="https://solarrooftop.gov.in/dcr_portal" target="_blank" rel="noopener"
        class="inline-flex items-center gap-2 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-blue-50/70 text-slate-700 hover:text-blue-800 border border-slate-200 hover:border-blue-300 transition shadow-2xs group">
        <span
          class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center text-[10px] transition shrink-0">
          <i class="fa-solid fa-stamp"></i>
        </span>
        <span>DCR Portal</span>
        <i
          class="fa-solid fa-arrow-up-right-from-square text-[8px] text-slate-400 group-hover:text-blue-600 transition"></i>
      </a>

      <a href="https://www.jansamarth.in/home" target="_blank" rel="noopener"
        class="inline-flex items-center gap-2 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-purple-50/70 text-slate-700 hover:text-purple-800 border border-slate-200 hover:border-purple-300 transition shadow-2xs group">
        <span
          class="w-5 h-5 rounded-lg bg-purple-100 text-purple-700 group-hover:bg-purple-600 group-hover:text-white flex items-center justify-center text-[10px] transition shrink-0">
          <i class="fa-solid fa-building-columns"></i>
        </span>
        <span>Jan Samarth Loan</span>
        <i
          class="fa-solid fa-arrow-up-right-from-square text-[8px] text-slate-400 group-hover:text-purple-600 transition"></i>
      </a>

      <a href="https://pmsuryaghar.gov.in/" target="_blank" rel="noopener"
        class="inline-flex items-center gap-2 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-rose-50/70 text-slate-700 hover:text-rose-800 border border-slate-200 hover:border-rose-300 transition shadow-2xs group">
        <span
          class="w-5 h-5 rounded-lg bg-rose-100 text-rose-700 group-hover:bg-rose-600 group-hover:text-white flex items-center justify-center text-[10px] transition shrink-0">
          <i class="fa-solid fa-headset"></i>
        </span>
        <span>Surya Ghar Help</span>
      </a>
    </div>
  </div>

  <div class="flex items-center gap-1 shrink-0 ml-auto">
    <button type="button" onclick="location.reload()" title="Refresh Page"
      class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-xs transition cursor-pointer">
      <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
    </button>
    <button type="button" onclick="toggleFullscreen()" title="Toggle Fullscreen"
      class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-xs transition cursor-pointer">
      <i class="fa-solid fa-expand text-[11px]"></i>
    </button>
  </div>
</div>

<script>
  function toggleFullscreen() {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen().catch(() => { });
    } else {
      document.exitFullscreen().catch(() => { });
    }
  }
</script>
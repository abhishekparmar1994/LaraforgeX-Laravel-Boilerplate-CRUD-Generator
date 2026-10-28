{{-- Indian Solar Schemes Quick Links Toolbar --}}
<div class="mb-4 bg-white/90 border border-slate-200/80 rounded-2xl px-4 py-2.5 shadow-2xs flex items-center justify-between gap-3 flex-wrap">
  <div class="flex items-center gap-2 flex-wrap min-w-0">
    <div class="flex items-center gap-1.5 shrink-0 pr-2 border-r border-slate-200">
      <i class="fa-solid fa-solar-panel text-emerald-600 text-xs"></i>
      <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Quick Links</span>
    </div>

    <div class="flex items-center gap-1.5 flex-wrap">
      <a href="https://pmsuryaghar.gov.in/consumerLogin" target="_blank" rel="noopener"
        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200 hover:border-emerald-200 transition shadow-2xs">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
        <span>Pm Surya Ghar Consumer Login</span>
        <i class="fa-solid fa-arrow-up-right-from-square text-[8px] text-slate-400"></i>
      </a>

      <a href="https://pmsuryaghar.gov.in/vendorLogin" target="_blank" rel="noopener"
        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-amber-50 text-slate-700 hover:text-amber-700 border border-slate-200 hover:border-amber-200 transition shadow-2xs">
        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
        <span>Pm Surya Ghar Vendor Login</span>
        <i class="fa-solid fa-arrow-up-right-from-square text-[8px] text-slate-400"></i>
      </a>

      <a href="https://solarrooftop.gov.in/dcr_portal" target="_blank" rel="noopener"
        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 border border-slate-200 hover:border-blue-200 transition shadow-2xs">
        <span>DCR Portal</span>
        <i class="fa-solid fa-arrow-up-right-from-square text-[8px] text-slate-400"></i>
      </a>

      <a href="https://www.jansamarth.in/home" target="_blank" rel="noopener"
        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-purple-50 text-slate-700 hover:text-purple-700 border border-slate-200 hover:border-purple-200 transition shadow-2xs">
        <span>Jan Samarth Portal</span>
        <i class="fa-solid fa-arrow-up-right-from-square text-[8px] text-slate-400"></i>
      </a>

      <a href="https://pmsuryaghar.gov.in/" target="_blank" rel="noopener"
        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-50 hover:bg-rose-50 text-slate-700 hover:text-rose-700 border border-slate-200 hover:border-rose-200 transition shadow-2xs">
        <span>PM Surya Ghar Help</span>
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
      document.documentElement.requestFullscreen().catch(() => {});
    } else {
      document.exitFullscreen().catch(() => {});
    }
  }
</script>

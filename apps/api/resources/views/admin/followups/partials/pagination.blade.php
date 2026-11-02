{{-- ── PERSISTENT PAGINATION & PAGE CONTROLS FOOTER ───────────── --}}
<div class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xs">
  <div class="flex items-center gap-2.5 text-xs text-slate-500 font-semibold">
    <span>
      Showing <span class="font-bold text-slate-800">{{ $followups->firstItem() ?? 0 }}</span>
      to <span class="font-bold text-slate-800">{{ $followups->lastItem() ?? 0 }}</span>
      of <span class="font-bold text-slate-800">{{ number_format($followups->total()) }}</span> follow-ups
    </span>
    <span class="text-slate-300">|</span>
    <span class="text-[11px] text-slate-400">Page {{ $followups->currentPage() }} of {{ max(1, $followups->lastPage()) }}</span>
  </div>

  <div class="flex items-center gap-1.5">
    {{-- Prev Button --}}
    @if($followups->onFirstPage())
      <span class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed">‹ Prev</span>
    @else
      <a href="{{ $followups->previousPageUrl() }}" class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition">‹ Prev</a>
    @endif

    {{-- Page Numbers --}}
    @php
      $start = max(1, $followups->currentPage() - 2);
      $end = min($followups->lastPage(), $followups->currentPage() + 2);
    @endphp

    @if($start > 1)
      <a href="{{ $followups->url(1) }}" class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition">1</a>
      @if($start > 2) <span class="px-1 text-slate-300">…</span> @endif
    @endif

    @for($p = $start; $p <= $end; $p++)
      @if($p == $followups->currentPage())
        <span class="px-3 py-1.5 text-xs font-black rounded-xl bg-amber-500 text-white border border-amber-500 shadow-xs">{{ $p }}</span>
      @else
        <a href="{{ $followups->url($p) }}" class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition">{{ $p }}</a>
      @endif
    @endfor

    @if($end < $followups->lastPage())
      @if($end < $followups->lastPage() - 1) <span class="px-1 text-slate-300">…</span> @endif
      <a href="{{ $followups->url($followups->lastPage()) }}" class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition">{{ $followups->lastPage() }}</a>
    @endif

    {{-- Next Button --}}
    @if($followups->hasMorePages())
      <a href="{{ $followups->nextPageUrl() }}" class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition">Next ›</a>
    @else
      <span class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed">Next ›</span>
    @endif
  </div>
</div>


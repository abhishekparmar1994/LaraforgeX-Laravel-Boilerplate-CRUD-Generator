{{-- Asynchronous Shared Pagination Footer for Card & Table Views --}}
<div id="leads-pagination-footer" class="px-4 py-3.5 border border-slate-200 bg-white rounded-2xl shadow-2xs flex items-center justify-between flex-wrap gap-3">
  <p id="leads-pagination-info" class="text-xs font-semibold text-slate-500">
    Showing <span class="font-bold text-slate-800">{{ $leads->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800">{{ $leads->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ number_format($leads->total()) }}</span> entries
  </p>
  <div id="leads-pagination-links" class="flex items-center gap-1"></div>
</div>


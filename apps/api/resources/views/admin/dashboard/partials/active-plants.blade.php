{{-- ── 5. ACTIVE SOLAR PLANT INSTALLATION TRACKER ──────────────── --}}
<div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-2.5">
      <div
        class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shadow-2xs">
        <i class="fa-solid fa-solar-panel"></i>
      </div>
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Active EPC Solar Plant Execution</h3>
        <p class="text-xs text-slate-400 font-medium">On-site structural fabrication, inverter synchronization, and CEIG
          approvals</p>
      </div>
    </div>
    <a href="/admin/projects"
      class="text-xs font-bold text-brand-600 hover:text-brand-500 flex items-center gap-1 transition">
      <span>View All (6)</span>
      <i class="fa-solid fa-arrow-right text-[10px]"></i>
    </a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
    @foreach($active_plants as $plant)
      @php
        $prog = (int) ($plant['progress'] ?? 0);
        $progColor = $prog >= 80 ? 'bg-emerald-500' : ($prog >= 50 ? 'bg-amber-500' : 'bg-brand-500');
        $budget = (float) ($plant['budget'] ?? 0);
      @endphp
      <div
        class="p-3.5 rounded-xl border border-slate-100 hover:border-amber-300 bg-slate-50/40 hover:bg-white transition shadow-2xs group flex flex-col justify-between">
        <div>
          <div class="flex items-start justify-between gap-2 mb-1.5">
            <a href="/admin/projects/{{ $plant['id'] }}"
              class="font-extrabold text-xs text-slate-800 group-hover:text-brand-600 transition truncate block"
              title="{{ $plant['name'] }}">
              {{ $plant['name'] }}
            </a>
            <span
              class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 shrink-0 shadow-2xs">
              {{ $prog }}%
            </span>
          </div>

          <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden my-2">
            <div class="{{ $progColor }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $prog }}%">
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-100 text-slate-500">
          <span class="font-bold text-slate-700">₹{{ number_format($budget / 100000, 1) }}L <span
              class="font-normal text-slate-400">budget</span></span>
          <span class="text-[10px] text-slate-400 flex items-center gap-1">
            <i class="fa-regular fa-clock text-[9px]"></i>
            {{ !empty($plant['deadline']) ? \Carbon\Carbon::parse($plant['deadline'])->format('M d') : 'Active' }}
          </span>
        </div>
      </div>
    @endforeach
  </div>
</div>
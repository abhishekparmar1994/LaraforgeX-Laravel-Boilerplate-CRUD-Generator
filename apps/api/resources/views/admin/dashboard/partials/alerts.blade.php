{{-- ── 1. ATTENTION REQUIRED ALERT BAR ────────────────────────── --}}
@if(!empty($attention_alerts) && count($attention_alerts) > 0)
  <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 shadow-xs">
    <div class="flex items-center justify-between gap-3 mb-2.5">
      <div class="flex items-center gap-2 text-amber-900 font-extrabold text-xs uppercase tracking-wider">
        <i class="fa-solid fa-bell-concierge text-amber-600 animate-bounce"></i>
        <span>Attention Required Today</span>
      </div>
      <span class="text-[10px] font-bold bg-amber-200/60 text-amber-800 px-2.5 py-0.5 rounded-full">
        {{ count($attention_alerts) }} Priority Items
      </span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
      @foreach($attention_alerts as $alert)
        <a href="{{ $alert['link'] }}"
          class="flex items-center gap-2.5 p-2.5 bg-white/90 hover:bg-white border border-amber-200/60 rounded-xl transition shadow-2xs group">
          <div class="h-7 w-7 rounded-lg {{ $alert['type'] === 'danger' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} flex items-center justify-center text-xs shrink-0 group-hover:scale-110 transition-transform">
            <i class="{{ $alert['icon'] }}"></i>
          </div>
          <p class="text-xs font-bold text-slate-800 leading-snug group-hover:text-brand-600 transition truncate flex-1">
            {{ $alert['title'] }}
          </p>
          <i class="fa-solid fa-arrow-right text-[10px] text-slate-400 group-hover:text-brand-600 transition shrink-0"></i>
        </a>
      @endforeach
    </div>
  </div>
@endif


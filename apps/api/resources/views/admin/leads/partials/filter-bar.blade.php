{{-- Filter & Search Control Center --}}
<div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
  <div class="space-y-3">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5">
      <div class="relative sm:col-span-2 md:col-span-3 lg:col-span-3">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
        <input type="text" id="filter-search" placeholder="Search customer, company, phone, locality, address..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-8 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <button type="button" id="btn-clear-search" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs cursor-pointer" title="Clear search">
          <i class="fa-solid fa-circle-xmark"></i>
        </button>
      </div>

      <select id="filter-city" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
        <option value="">🏙️ All Cities</option>
        @foreach($cities as $c)
          <option value="{{ $c }}">{{ $c }}</option>
        @endforeach
      </select>

      <select id="filter-area" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
        <option value="">📍 All Areas ({{ count($areas) }})</option>
        @foreach($areas as $ar)
          <option value="{{ $ar }}">{{ $ar }}</option>
        @endforeach
      </select>

      <select id="filter-industry" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 cursor-pointer">
        <option value="">🏢 All Sectors ({{ count($industries) }})</option>
        @foreach($industries as $ind)
          <option value="{{ $ind }}">{{ $ind }}</option>
        @endforeach
      </select>
    </div>

    <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100 flex-wrap">
      <div class="flex items-center gap-2.5 flex-wrap">
        <select id="filter-status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
          <option value="">⚡ All Pipeline Stages</option>
          <option value="new">1. New Solar Lead</option>
          <option value="contacted">2. Site Survey Scheduled</option>
          <option value="qualified">3. Feasibility Approved</option>
          <option value="proposal_sent">4. Sizing & Quotation Sent</option>
          <option value="negotiation">5. Subsidy / Net-Metering</option>
          <option value="won">6. Commissioned & Handed Over</option>
          <option value="lost">7. Lost / Drop</option>
        </select>

        <select id="filter-priority" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
          <option value="">🔥 All Priorities</option>
          <option value="urgent">⚡ Urgent / High kW</option>
          <option value="high">Commercial C&I</option>
          <option value="medium">Residential Rooftop</option>
          <option value="low">Standard Inquiry</option>
        </select>

        <select id="filter-developer" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
          <option value="">👤 All Solar Executives</option>
          @foreach($developers as $dev)
            <option value="{{ $dev->id }}">👤 {{ $dev->name }}</option>
          @endforeach
        </select>

        <div class="flex items-center gap-1.5 pl-2 border-l border-slate-200">
          <span class="text-[11px] font-bold text-slate-400">Show</span>
          <select id="filter-per-page" class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-bold text-slate-700 focus:outline-none cursor-pointer">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="text-[11px] font-bold text-slate-400">rows</span>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button type="button" id="btn-clear-filters" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer">
          <i class="fa-solid fa-xmark text-[10px]"></i> Clear Filters
        </button>
      </div>
    </div>
  </div>
</div>


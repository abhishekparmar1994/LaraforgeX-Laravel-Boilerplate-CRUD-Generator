{{-- ── FOLLOW-UPS SEARCH & ADVANCED FILTERS BAR ───────────────── --}}
<div class="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-xs">
  <form method="GET" action="/admin/followups" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5">
    @if(request('filter'))
      <input type="hidden" name="filter" value="{{ request('filter') }}">
    @endif
    @if(request('status'))
      <input type="hidden" name="status" value="{{ request('status') }}">
    @endif

    {{-- Search Field --}}
    <div class="lg:col-span-5 relative">
      <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
      <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search customer, company, notes, contact..."
        class="w-full pl-9 pr-3 py-2 text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-amber-500 focus:outline-hidden transition">
    </div>

    {{-- Consultation Type Filter --}}
    <div class="lg:col-span-2">
      <select name="type" onchange="this.form.submit()"
        class="w-full px-3 py-2 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-700">
        <option value="">All Types ({{ $counts['all'] ?? 0 }})</option>
        <option value="phone_call" {{ request('type') === 'phone_call' ? 'selected' : '' }}>Call Consultation</option>
        <option value="site_survey" {{ request('type') === 'site_survey' ? 'selected' : '' }}>Site Survey / Audit</option>
        <option value="whatsapp" {{ request('type') === 'whatsapp' ? 'selected' : '' }}>WhatsApp Chat</option>
        <option value="meeting" {{ request('type') === 'meeting' ? 'selected' : '' }}>In-Person Meeting</option>
        <option value="proposal" {{ request('type') === 'proposal' ? 'selected' : '' }}>Proposal Review</option>
      </select>
    </div>

    {{-- Solar Executive Filter --}}
    <div class="lg:col-span-3">
      <select name="developer_id" onchange="this.form.submit()"
        class="w-full px-3 py-2 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-700">
        <option value="">All Solar Executives</option>
        @foreach($developers as $dev)
          <option value="{{ $dev->id }}" {{ request('developer_id') == $dev->id ? 'selected' : '' }}>
            {{ $dev->name }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- Per-Page Dropdown & Clear Filters --}}
    <div class="lg:col-span-2 flex items-center gap-1.5">
      <select name="per_page" onchange="this.form.submit()"
        class="w-full px-2.5 py-2 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-700">
        <option value="6" {{ request('per_page', 9) == 6 ? 'selected' : '' }}>6 / page</option>
        <option value="9" {{ request('per_page', 9) == 9 ? 'selected' : '' }}>9 / page</option>
        <option value="18" {{ request('per_page', 9) == 18 ? 'selected' : '' }}>18 / page</option>
        <option value="27" {{ request('per_page', 9) == 27 ? 'selected' : '' }}>27 / page</option>
        <option value="45" {{ request('per_page', 9) == 45 ? 'selected' : '' }}>45 / page</option>
      </select>
      @if(request('search') || request('type') || request('developer_id') || request('per_page'))
        <a href="/admin/followups{{ request('filter') ? '?filter=' . request('filter') : '' }}"
          class="px-2.5 py-2 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition shrink-0"
          title="Reset Filters">
          <i class="fa-solid fa-xmark"></i>
        </a>
      @endif
    </div>
  </form>
</div>
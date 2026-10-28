{{-- AJAX DataTable Card Component --}}
<style>
  .dt-sort-wrap { display: inline-flex; align-items: center; justify-content: center; width: 12px; height: 12px; margin-left: 4px; vertical-align: middle; flex-shrink: 0; }
  .dt-sort-wrap svg, .dt-sort-wrap i { width: 10px !important; height: 10px !important; max-width: 10px !important; max-height: 10px !important; font-size: 10px !important; line-height: 1 !important; display: inline-block !important; }
</style>

<div id="leads-table-container" class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs relative">
  <div id="leads-loading-bar" class="hidden absolute top-0 left-0 right-0 h-1 bg-brand-600 animate-pulse z-20"></div>

  {{-- DataTable Control Bar (Commented out — unified in top filter bar)
  <div class="px-4 py-3 bg-slate-50/70 border-b border-slate-200 flex items-center justify-between flex-wrap gap-2.5">
    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-600">
      <span class="text-slate-400 text-[11px] uppercase tracking-wider">Show</span>
      <select id="dt-page-size" class="bg-white border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-bold text-slate-800 shadow-2xs focus:outline-none focus:border-brand-500 cursor-pointer">
        <option value="10" selected>10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <span class="text-slate-400 text-[11px] uppercase tracking-wider">entries</span>
    </div>

    <div class="relative w-full sm:w-64">
      <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
      <input type="text" id="dt-quick-search" placeholder="Search leads table..."
        class="w-full bg-white border border-slate-200 rounded-xl pl-8 pr-7 py-1.5 text-xs font-semibold text-slate-800 placeholder-slate-400 shadow-2xs focus:outline-none focus:border-brand-500 transition">
      <button type="button" id="dt-quick-clear" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs cursor-pointer" title="Clear">
        <i class="fa-solid fa-circle-xmark"></i>
      </button>
    </div>
  </div>
  --}}

  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
        <tr>
          <th class="px-3.5 py-3.5 text-center w-12">#</th>
          <th class="px-4 py-3.5 cursor-pointer select-none hover:text-slate-800 transition" onclick="handleLeadsSort('company_name')">
            <span class="inline-flex items-center">
              Customer Name
              <span id="sort-wrap-company_name" class="dt-sort-wrap">
                <svg class="w-2.5 h-2.5 text-slate-300" viewBox="0 0 320 512" fill="currentColor"><path d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8L32 224c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8l256 0c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/></svg>
              </span>
            </span>
          </th>
          <th class="px-4 py-3.5 cursor-pointer select-none hover:text-slate-800 transition" onclick="handleLeadsSort('phone')">
            <span class="inline-flex items-center">
              Contact Phone
              <span id="sort-wrap-phone" class="dt-sort-wrap">
                <svg class="w-2.5 h-2.5 text-slate-300" viewBox="0 0 320 512" fill="currentColor"><path d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8L32 224c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8l256 0c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/></svg>
              </span>
            </span>
          </th>
          <th class="px-4 py-3.5 cursor-pointer select-none hover:text-slate-800 transition" onclick="handleLeadsSort('area_locality')">
            <span class="inline-flex items-center">
              Area / Locality
              <span id="sort-wrap-area_locality" class="dt-sort-wrap">
                <svg class="w-2.5 h-2.5 text-slate-300" viewBox="0 0 320 512" fill="currentColor"><path d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8L32 224c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8l256 0c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/></svg>
              </span>
            </span>
          </th>
          <th class="px-4 py-3.5 text-center cursor-pointer select-none hover:text-slate-800 transition" onclick="handleLeadsSort('rating')">
            <span class="inline-flex items-center justify-center">
              Rating
              <span id="sort-wrap-rating" class="dt-sort-wrap">
                <svg class="w-2.5 h-2.5 text-slate-300" viewBox="0 0 320 512" fill="currentColor"><path d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8L32 224c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8l256 0c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/></svg>
              </span>
            </span>
          </th>
          <th class="px-3 py-3.5 text-center">Maps</th>
          <th class="px-4 py-3.5 text-right">Actions</th>
        </tr>
      </thead>
      <tbody id="leads-table-body" class="divide-y divide-slate-100 font-medium text-slate-700">
        @forelse($leads as $index => $lead)
          @php
            $rawPhone = preg_replace('/\D/', '', (string)($lead->phone ?? ''));
            $waPhone = strlen($rawPhone) === 10 ? '91' . $rawPhone : $rawPhone;
          @endphp
          <tr class="hover:bg-slate-50/80 transition">
            <td class="px-3.5 py-3.5 text-center font-bold text-slate-400">
              {{ ($leads->currentPage() - 1) * $leads->perPage() + $index + 1 }}
            </td>
            <td class="px-4 py-3.5">
              <a href="/admin/leads/{{ $lead->id }}" class="font-bold text-slate-900 hover:text-brand-600 block text-xs">
                {{ $lead->company_name }}
              </a>
              <span class="text-[11px] text-slate-400 block mt-0.5">
                {{ $lead->contact_person ?? 'Owner' }} @if($lead->email && $lead->email !== 'N/A') • {{ $lead->email }} @endif
              </span>
            </td>
            <td class="px-4 py-3.5 whitespace-nowrap">
              @if($lead->phone && $lead->phone !== 'N/A')
                <div class="flex items-center gap-1.5 font-bold text-slate-900">
                  <a href="tel:{{ $lead->phone }}" class="hover:text-brand-600">{{ $lead->phone }}</a>
                  @if($waPhone)
                    <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener" class="text-emerald-500 hover:text-emerald-600 transition" title="Chat on WhatsApp">
                      <i class="fa-brands fa-whatsapp text-sm"></i>
                    </a>
                  @endif
                </div>
              @else
                <span class="text-slate-300 font-semibold italic">N/A</span>
              @endif
            </td>
            <td class="px-4 py-3.5 text-slate-600"><span>{{ $lead->area_locality ?? $lead->location ?? 'Vadodara' }}</span></td>
            <td class="px-4 py-3.5 text-center whitespace-nowrap">
              @if($lead->rating && $lead->rating !== 'N/A')
                <span class="inline-flex items-center gap-1 font-bold text-amber-600 text-xs">
                  <i class="fa-solid fa-star text-[10px]"></i> {{ $lead->rating }}
                  @if($lead->total_reviews && $lead->total_reviews !== '0')
                    <span class="text-slate-400 font-normal text-[10px]">({{ $lead->total_reviews }})</span>
                  @endif
                </span>
              @else
                <span class="text-slate-300">-</span>
              @endif
            </td>
            <td class="px-3 py-3.5 text-center">
              @if($lead->google_maps_link)
                <a href="{{ $lead->google_maps_link }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-brand-600 transition" title="Open in Google Maps">
                  <i class="fa-solid fa-location-dot text-sm text-rose-500"></i>
                </a>
              @else
                <span class="text-slate-300">-</span>
              @endif
            </td>
            <td class="px-4 py-3.5 text-right whitespace-nowrap">
              <a href="/admin/leads/{{ $lead->id }}" class="p-1.5 text-slate-400 hover:text-brand-600 rounded-lg hover:bg-slate-100 transition inline-block" title="View 360 Profile">
                <i class="fa-solid fa-arrow-right text-xs"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
              <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
              <p class="font-bold text-sm">No solar leads found</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Asynchronous Pagination Footer --}}
  <div id="leads-pagination-footer" class="px-4 py-3.5 border-t border-slate-200 bg-slate-50 flex items-center justify-between flex-wrap gap-3">
    <p id="leads-pagination-info" class="text-xs font-semibold text-slate-500">
      Showing <span class="font-bold text-slate-800">{{ $leads->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800">{{ $leads->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ number_format($leads->total()) }}</span> entries
    </p>
    <div id="leads-pagination-links" class="flex items-center gap-1"></div>
  </div>
</div>


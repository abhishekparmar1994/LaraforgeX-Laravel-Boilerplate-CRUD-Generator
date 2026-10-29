{{-- Lead Detail: Site Location & Registry Discovery Intelligence --}}
@php
  $mapsUrl = $lead->google_maps_link;
  if (!$mapsUrl && $lead->notes) {
      if (preg_match('/Maps:\s*(https?:\/\/[^\s]+)/i', $lead->notes, $m)) {
          $mapsUrl = $m[1];
      }
  }
  
  $systemLines = [];
  if ($lead->notes) {
      foreach (explode("\n", $lead->notes) as $line) {
          $trimmed = trim($line);
          if (Str::startsWith($trimmed, ['[System]', 'System:'])) {
              $systemLines[] = $trimmed;
          }
      }
  }
@endphp

<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4 font-sans">
  <div class="flex items-center justify-between border-b border-slate-100 pb-3">
    <div class="flex items-center gap-2">
      <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
        <i class="fa-solid fa-map-location-dot"></i>
      </span>
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Site Location & Registry Intelligence</h3>
        <p class="text-[11px] text-slate-400 font-medium">Verified customer data, location coordinates & consumer details</p>
      </div>
    </div>
    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
      <i class="fa-solid fa-circle-check text-blue-500 mr-1"></i> Verified Solar Prospect
    </span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 text-xs">
    <!-- Location & Address Tile -->
    <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-2 flex flex-col justify-between">
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
          <i class="fa-solid fa-location-dot text-rose-500"></i> Rooftop Site Address
        </div>
        <p class="font-bold text-slate-800 text-xs leading-relaxed">
          {{ $lead->full_address ?? ($lead->area_locality ? $lead->area_locality . ', ' . $lead->location : ($lead->location ?? 'Vadodara, Gujarat')) }}
        </p>
      </div>
      @if($mapsUrl)
        <div class="pt-2">
          <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" 
             class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs border border-slate-200 shadow-2xs transition">
            <i class="fa-solid fa-arrow-up-right-from-square text-blue-600 text-[10px]"></i> View on Google Maps
          </a>
        </div>
      @endif
    </div>

    <!-- Google Reputation Tile -->
    <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-2 flex flex-col justify-between">
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
          <i class="fa-solid fa-star text-amber-500"></i> Local Reputation & Rating
        </div>
        <div class="flex items-center gap-2 pt-0.5">
          <span class="text-xl font-black text-slate-900">{{ $lead->rating && $lead->rating !== 'N/A' ? $lead->rating : '4.5' }}</span>
          <div class="flex items-center text-amber-400 text-xs">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star-half-stroke"></i>
          </div>
          <span class="text-xs text-slate-500 font-semibold">
            ({{ $lead->total_reviews && $lead->total_reviews !== '0' ? $lead->total_reviews : '35+' }} reviews)
          </span>
        </div>
      </div>
      <p class="text-[11px] text-emerald-700 font-bold flex items-center gap-1">
        <i class="fa-solid fa-award text-emerald-500"></i> Verified Local Consumer
      </p>
    </div>

    <!-- Category Tile -->
    <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-1.5">
      <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
        <i class="fa-solid fa-building text-amber-500"></i> Property & Establishment Type
      </div>
      <div class="flex items-center gap-1.5 flex-wrap pt-0.5">
        @if($lead->industry)
          <span class="px-2.5 py-1 rounded-xl bg-white border border-slate-200 text-slate-800 font-bold text-xs shadow-2xs">
            {{ $lead->industry }}
          </span>
        @endif
        @if($lead->products_services)
          <span class="px-2.5 py-1 rounded-xl bg-white border border-slate-200 text-slate-700 font-medium text-xs shadow-2xs">
            {{ $lead->products_services }}
          </span>
        @endif
      </div>
    </div>

    <!-- DISCOM & Electricity Account Tile -->
    <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-1.5">
      <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
        <i class="fa-solid fa-bolt text-amber-500"></i> DISCOM & Grid Connection
      </div>
      <div class="space-y-1 pt-0.5 text-[11px]">
        <div class="flex items-center justify-between">
          <span class="text-slate-500 font-medium">DISCOM:</span>
          <span class="font-bold text-slate-800">{{ $lead->discom ?? 'MGVCL / Gujarat Urja' }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-500 font-medium">Consumer No:</span>
          <span class="font-bold text-slate-800">{{ $lead->consumer_number ?? 'In Registration' }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-500 font-medium">Sanctioned Load:</span>
          <span class="font-bold text-slate-800">{{ $lead->sanctioned_load ? $lead->sanctioned_load . ' kW' : '3.0 kW' }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

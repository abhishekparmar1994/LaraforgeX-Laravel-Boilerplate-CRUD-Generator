{{-- Solar Leads 3-Column Card View --}}
<div id="leads-cards-view" class="hidden">
  <div id="leads-cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
    @forelse($leads as $lead)
      @php
        $initial = strtoupper(substr($lead->company_name ?? 'S', 0, 1));
        $stageColors = [
          'new' => 'bg-sky-50 text-sky-700 border-sky-200',
          'contacted' => 'bg-amber-50 text-amber-700 border-amber-200',
          'qualified' => 'bg-blue-50 text-blue-700 border-blue-200',
          'proposal_sent' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
          'negotiation' => 'bg-purple-50 text-purple-700 border-purple-200',
          'won' => 'bg-emerald-600 text-white border-emerald-600',
          'lost' => 'bg-rose-50 text-rose-700 border-rose-200',
        ];
        $stageLabels = [
          'new' => 'NEW LEAD',
          'contacted' => 'SITE VISITED',
          'qualified' => 'QUALIFIED',
          'proposal_sent' => 'ESTIMATE SEND',
          'negotiation' => 'DOCUMENT',
          'won' => 'COMMISSIONED',
          'lost' => 'LOST',
        ];
        $stageClass = $stageColors[$lead->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
        $stageText = $stageLabels[$lead->status] ?? strtoupper($lead->status);
        $rawPhone = preg_replace('/\D/', '', (string)($lead->phone ?? ''));
        $waPhone = strlen($rawPhone) === 10 ? '91' . $rawPhone : $rawPhone;
      @endphp
      <div class="bg-white border border-slate-200 hover:border-brand-400 rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all duration-150 flex flex-col justify-between space-y-3 group">
        <!-- Top Row: Initial + Name + Rating + Stage Pill -->
        <div class="flex items-start justify-between gap-2">
          <div class="flex items-center gap-2.5 min-w-0">
            <span class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-amber-50 group-hover:text-amber-600 text-slate-700 font-extrabold text-xs flex items-center justify-center shrink-0 transition">
              {{ $initial }}
            </span>
            <div class="min-w-0">
              <a href="/admin/leads/{{ $lead->id }}" class="font-extrabold text-xs text-slate-900 group-hover:text-brand-600 transition block truncate" title="{{ $lead->company_name }}">
                {{ $lead->company_name }}
              </a>
              <div class="flex items-center gap-1.5 mt-0.5">
                <span class="text-[11px] font-bold text-amber-500 flex items-center gap-0.5">
                  <i class="fa-solid fa-star text-[9px]"></i> {{ $lead->rating ?? '4.5' }}
                </span>
                <span class="text-[10px] text-slate-400">({{ $lead->total_reviews ?? '12' }})</span>
              </div>
            </div>
          </div>

          <span class="px-2 py-0.5 rounded-lg border text-[9px] font-extrabold tracking-wider uppercase shrink-0 {{ $stageClass }}">
            {{ $stageText }}
          </span>
        </div>

        <!-- Middle: Locality / Address + Map -->
        <div class="space-y-1 text-xs">
          <div class="flex items-center justify-between text-[11px] text-slate-600">
            <span class="truncate">{{ $lead->area_locality ?? $lead->location ?? 'Vadodara, Gujarat' }}</span>
            @if($lead->google_maps_link)
              <a href="{{ $lead->google_maps_link }}" target="_blank" rel="noopener" class="text-brand-600 hover:underline font-extrabold text-[10px] shrink-0 ml-1">MAP</a>
            @else
              <a href="https://maps.google.com/?q={{ urlencode(($lead->company_name ?? '') . ' ' . ($lead->location ?? 'Vadodara')) }}" target="_blank" rel="noopener" class="text-brand-600 hover:underline font-extrabold text-[10px] shrink-0 ml-1">MAP</a>
            @endif
          </div>

          <!-- Contact Phone & WhatsApp -->
          <div class="flex items-center justify-between pt-1">
            <span class="text-[11px] font-bold text-slate-800">
              @if($lead->phone && $lead->phone !== 'N/A')
                <a href="tel:{{ $lead->phone }}" class="hover:text-brand-600">{{ $lead->phone }}</a>
              @else
                <span class="text-slate-400 font-normal">No Phone</span>
              @endif
            </span>
            @if($waPhone)
              <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener" class="text-emerald-500 hover:text-emerald-600 transition text-xs" title="WhatsApp Chat">
                <i class="fa-brands fa-whatsapp text-sm"></i>
              </a>
            @endif
          </div>
        </div>

        <!-- Bottom: Time ago + Assigned Executive -->
        <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold text-slate-400">
          <span class="flex items-center gap-1">
            <i class="fa-regular fa-clock text-[9px]"></i>
            {{ $lead->created_at ? $lead->created_at->diffForHumans() : 'Recently' }}
          </span>
          <span class="flex items-center gap-1 text-slate-600 truncate max-w-[140px]">
            <i class="fa-regular fa-user text-[9px]"></i>
            {{ $lead->assignedDeveloper->name ?? 'Unassigned' }}
          </span>
        </div>
      </div>
    @empty
      <div class="col-span-3 p-8 text-center bg-white border border-slate-200 rounded-2xl">
        <i class="fa-solid fa-solar-panel text-amber-400 text-3xl mb-2"></i>
        <p class="text-xs font-bold text-slate-700">No solar leads match active filters.</p>
      </div>
    @endforelse
  </div>
</div>

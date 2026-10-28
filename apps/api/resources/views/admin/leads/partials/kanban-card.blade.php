@php
  $isNoWeb = strtoupper($lead->has_website) === 'NO' || empty($lead->website) || $lead->website === 'N/A';
@endphp
<div class="kanban-card bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-xs hover:shadow-md transition cursor-grab active:cursor-grabbing group relative select-none {{ $isNoWeb ? 'border-l-4 border-l-rose-500' : 'border-l-4 border-l-emerald-500' }}"
     draggable="true" data-lead-id="{{ $lead->id }}">
  
  <!-- Top: Company & Priority -->
  <div class="flex items-start justify-between gap-2">
    <a href="/admin/leads/{{ $lead->id }}" class="font-bold text-xs text-slate-900 group-hover:text-brand-600 transition leading-snug line-clamp-1" title="{{ $lead->company_name }}">
      {{ $lead->company_name }}
    </a>
    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded shrink-0 {{ $lead->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : ($lead->priority === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
      {{ $lead->priority }}
    </span>
  </div>

  <!-- Sub-info: Industry & Locality -->
  <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
    <span class="truncate max-w-[150px] font-medium text-slate-600">{{ $lead->industry ?? 'General' }}</span>
    @if($lead->rating && $lead->rating !== 'N/A')
      <span class="font-bold text-amber-600"><i class="fa-solid fa-star text-[8px]"></i> {{ $lead->rating }}</span>
    @endif
  </div>

  <!-- Website Status Indicator -->
  <div class="mt-2 flex items-center justify-between">
    @if($isNoWeb)
      <span class="inline-flex items-center gap-1 text-[9px] font-extrabold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100">
        <span class="w-1 h-1 rounded-full bg-rose-500"></span> NO Website (Hot Lead)
      </span>
    @else
      <span class="inline-flex items-center gap-1 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
        <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Has Website
      </span>
    @endif
    @if($lead->area_locality)
      <span class="text-[9px] text-slate-400 truncate max-w-[100px]">{{ $lead->area_locality }}</span>
    @endif
  </div>

  <!-- Value & Pipeline Days -->
  <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100">
    <span class="font-extrabold text-xs text-emerald-600">₹{{ number_format($lead->estimated_budget, 0) }}</span>
    <span class="text-[10px] text-slate-400 font-medium">
      <i class="fa-regular fa-clock text-[9px]"></i> {{ $lead->days_in_pipeline }}d in pipe
    </span>
  </div>

  <!-- Footer: Dev Avatar & Follow-up -->
  <div class="flex items-center justify-between mt-2 text-[10px] text-slate-400">
    <div class="flex items-center gap-1.5">
      @if($lead->assignedDeveloper)
        <span class="h-5 w-5 rounded-full bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center font-bold text-[9px]" title="{{ $lead->assignedDeveloper->name }}">
          {{ substr($lead->assignedDeveloper->name, 0, 1) }}
        </span>
        <span class="truncate max-w-[80px] font-semibold text-slate-700">{{ $lead->assignedDeveloper->name }}</span>
      @else
        <span class="italic text-slate-300">Unassigned</span>
      @endif
    </div>
    @if($lead->phone && $lead->phone !== 'N/A')
      <a href="tel:{{ $lead->phone }}" class="text-[10px] font-bold text-slate-700 hover:text-brand-600" title="{{ $lead->phone }}">
        <i class="fa-solid fa-phone text-[8px] text-slate-400"></i> {{ substr($lead->phone, -10) }}
      </a>
    @endif
  </div>

</div>


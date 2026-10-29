@php
  $isHot = $lead->priority === 'urgent' || $lead->priority === 'high';
  $estKw = $lead->estimated_budget > 0 ? round($lead->estimated_budget / 55000, 1) : 3.0;
@endphp
<div class="kanban-card bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-xs hover:shadow-md transition cursor-grab active:cursor-grabbing group relative select-none {{ $isHot ? 'border-l-4 border-l-amber-500' : 'border-l-4 border-l-emerald-500' }}"
     draggable="true" data-lead-id="{{ $lead->id }}">
  
  <!-- Top: Customer Name & Priority -->
  <div class="flex items-start justify-between gap-2">
    <a href="/admin/leads/{{ $lead->id }}" class="font-bold text-xs text-slate-900 group-hover:text-brand-600 transition leading-snug line-clamp-1" title="{{ $lead->company_name }}">
      {{ $lead->company_name }}
    </a>
    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded shrink-0 {{ $lead->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : ($lead->priority === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
      {{ $lead->priority }}
    </span>
  </div>

  <!-- Sub-info: Category & Locality -->
  <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
    <span class="truncate max-w-[150px] font-medium text-slate-600">{{ $lead->industry ?? 'Solar Rooftop' }}</span>
    <span class="font-bold text-slate-500"><i class="fa-solid fa-solar-panel text-[9px] text-amber-500"></i> {{ $estKw }} kW</span>
  </div>

  <!-- Solar Qualification Indicator -->
  <div class="mt-2 flex items-center justify-between">
    <span class="inline-flex items-center gap-1 text-[9px] font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Surya Ghar Eligible
    </span>
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


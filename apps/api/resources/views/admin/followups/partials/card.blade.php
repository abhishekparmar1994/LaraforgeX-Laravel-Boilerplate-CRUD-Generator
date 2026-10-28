{{-- Follow-up Card Component --}}
@php
  $leadName = $f->lead ? $f->lead->company_name : ($f->client ? $f->client->name : 'Solar Prospect');
  $initial = strtoupper(substr($leadName, 0, 1));
  $isOverdue = $f->scheduled_at < now()->startOfDay() && $f->status === 'scheduled';
  $isToday = $f->scheduled_at->isToday() && $f->status === 'scheduled';
  $phone = $f->lead ? $f->lead->phone : ($f->client ? $f->client->phone : null);
  $rawPhone = preg_replace('/\D/', '', (string)($phone ?? ''));
  $waPhone = strlen($rawPhone) === 10 ? '91' . $rawPhone : $rawPhone;
@endphp

<div class="bg-white border {{ $isOverdue ? 'border-rose-200 hover:border-rose-400' : ($isToday ? 'border-amber-200 hover:border-amber-400' : 'border-slate-200 hover:border-brand-400') }} rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all duration-150 flex flex-col justify-between space-y-3 group">
  <!-- Top: Avatar + Customer + Status Badge -->
  <div class="flex items-start justify-between gap-2">
    <div class="flex items-center gap-2.5 min-w-0">
      <span class="w-9 h-9 rounded-full {{ $isOverdue ? 'bg-rose-50 text-rose-600' : ($isToday ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-700') }} font-extrabold text-xs flex items-center justify-center shrink-0">
        {{ $initial }}
      </span>
      <div class="min-w-0">
        @if($f->lead)
          <a href="/admin/leads/{{ $f->lead_id }}" class="font-extrabold text-xs text-slate-900 group-hover:text-brand-600 transition block truncate" title="{{ $leadName }}">
            {{ $leadName }}
          </a>
          <span class="text-[10px] text-slate-400 block truncate">{{ $f->lead->contact_person ?? 'Solar Inquiry' }}</span>
        @elseif($f->client)
          <a href="/admin/clients/{{ $f->client_id }}" class="font-extrabold text-xs text-slate-900 group-hover:text-brand-600 transition block truncate">
            {{ $leadName }}
          </a>
          <span class="text-[10px] text-slate-400 block">Existing Client</span>
        @else
          <span class="font-extrabold text-xs text-slate-900">Prospect Consultation</span>
        @endif
      </div>
    </div>

    @if($f->status === 'completed')
      <span class="px-2 py-0.5 rounded-lg border bg-emerald-50 text-emerald-700 border-emerald-200 text-[9px] font-extrabold tracking-wider uppercase shrink-0">DONE</span>
    @elseif($isOverdue)
      <span class="px-2 py-0.5 rounded-lg border bg-rose-50 text-rose-700 border-rose-200 text-[9px] font-extrabold tracking-wider uppercase shrink-0">OVERDUE</span>
    @elseif($isToday)
      <span class="px-2 py-0.5 rounded-lg border bg-amber-50 text-amber-800 border-amber-200 text-[9px] font-extrabold tracking-wider uppercase shrink-0">TODAY</span>
    @else
      <span class="px-2 py-0.5 rounded-lg border bg-sky-50 text-sky-700 border-sky-200 text-[9px] font-extrabold tracking-wider uppercase shrink-0">UPCOMING</span>
    @endif
  </div>

  <!-- Channel & Notes -->
  <div class="space-y-1.5 text-xs">
    <div class="flex items-center gap-1.5 font-bold text-slate-700 text-[11px]">
      <i class="fa-solid {{ $f->follow_up_type === 'phone_call' || $f->follow_up_type === 'call' ? 'fa-phone text-blue-500' : ($f->follow_up_type === 'site_visit' ? 'fa-location-dot text-amber-500' : ($f->follow_up_type === 'whatsapp' ? 'fa-brands fa-whatsapp text-emerald-500' : 'fa-calendar-check text-purple-500')) }} text-xs"></i>
      <span>{{ ucfirst(str_replace('_', ' ', $f->follow_up_type)) }}</span>
      <span class="text-slate-300">•</span>
      <span class="text-slate-500 font-medium">{{ $f->scheduled_at->format('d M, h:i A') }}</span>
    </div>
    <p class="text-[11px] text-slate-500 line-clamp-2 italic bg-slate-50 p-2 rounded-xl border border-slate-100">
      "{{ $f->notes ?: 'Follow up regarding solar proposal sizing and PM Surya Ghar subsidy details.' }}"
    </p>
  </div>

  <!-- 1-Click Call & WhatsApp Row -->
  <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-100">
    <div class="flex items-center gap-1.5">
      @if($phone && $phone !== 'N/A')
        <a href="tel:{{ $phone }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-[10px] transition">
          <i class="fa-solid fa-phone text-[9px]"></i> Call
        </a>
        @if($waPhone)
          <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] transition">
            <i class="fa-brands fa-whatsapp text-[10px]"></i> WhatsApp
          </a>
        @endif
      @else
        <span class="text-[10px] text-slate-400 italic">No direct phone</span>
      @endif
    </div>

    <div class="flex items-center gap-1">
      @if($f->status !== 'completed')
        <button type="button" onclick="completeFollowup('{{ $f->id }}')" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Mark as Done">
          <i class="fa-solid fa-check text-xs"></i>
        </button>
      @endif
      @if($f->lead_id)
        <a href="/admin/leads/{{ $f->lead_id }}" class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition" title="View Lead Profile">
          <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      @endif
    </div>
  </div>
</div>

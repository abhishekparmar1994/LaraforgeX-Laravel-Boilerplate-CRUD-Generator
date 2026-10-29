{{-- Lead Detail: Scheduled Follow-ups & Activity History --}}
<div class="space-y-6">
  <!-- Scheduled Follow-ups Box -->
  <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
        <h3 class="font-extrabold text-sm text-slate-900">Scheduled Follow-ups</h3>
      </div>
      <button type="button" onclick="openFollowupModal()" class="text-xs font-bold text-amber-600 hover:underline cursor-pointer">+ Schedule</button>
    </div>

    <div class="space-y-2.5">
      @forelse($lead->followUps ?? [] as $f)
        <div id="followup-item-{{ $f->id }}" class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs space-y-2.5 transition">
          <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
              <div class="flex items-center gap-1.5 flex-wrap">
                @php
                  $typeIcons = [
                    'call' => 'fa-phone',
                    'whatsapp' => 'fa-whatsapp',
                    'email' => 'fa-envelope',
                    'meeting' => 'fa-handshake',
                    'proposal' => 'fa-solar-panel',
                    'demo' => 'fa-compass-drafting',
                    'site_survey' => 'fa-compass-drafting',
                  ];
                  $icon = $typeIcons[$f->follow_up_type] ?? 'fa-calendar-check';
                @endphp
                <span class="font-black text-slate-800 flex items-center gap-1.5">
                  <i class="fa-solid {{ $icon }} text-slate-500 text-xs"></i>
                  {{ ucfirst(str_replace('_', ' ', $f->follow_up_type)) }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">
                  {{ $f->scheduled_at ? \Carbon\Carbon::parse($f->scheduled_at)->format('M d, Y h:i A') : 'Date N/A' }}
                </span>
              </div>
              @if($f->notes)
                <p class="text-[11px] text-slate-600 mt-1 font-medium bg-white p-2 rounded-xl border border-slate-200/60">{{ $f->notes }}</p>
              @endif
            </div>

            <div class="flex items-center gap-1 shrink-0">
              <span id="fup-badge-{{ $f->id }}" class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full {{ $f->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ ucfirst($f->status) }}
              </span>
            </div>
          </div>

          @if($f->status !== 'completed')
            <div id="fup-actions-{{ $f->id }}" class="flex items-center justify-between pt-1.5 border-t border-slate-200/60 text-xs">
              <div class="flex items-center gap-2">
                <button type="button" onclick="markFollowupComplete('{{ $f->id }}')" id="fup-done-btn-{{ $f->id }}" 
                        class="font-black text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-1 cursor-pointer">
                  <i class="fa-solid fa-check text-[11px]"></i> Mark Done
                </button>
              </div>
              <div class="flex items-center gap-3">
                <button type="button" 
                        onclick="openEditFollowupModal('{{ $f->id }}', '{{ $f->follow_up_type }}', '{{ $f->scheduled_at ? \Carbon\Carbon::parse($f->scheduled_at)->format('Y-m-d\TH:i') : '' }}', '{{ $f->assigned_to_user_id }}', '{{ addslashes($f->notes ?? '') }}', '{{ $f->status }}')" 
                        class="font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1 cursor-pointer">
                  <i class="fa-solid fa-pen text-[10px]"></i> Edit
                </button>
                <button type="button" onclick="deleteFollowup('{{ $f->id }}')" 
                        class="font-bold text-rose-500 hover:text-rose-700 flex items-center gap-1 cursor-pointer">
                  <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                </button>
              </div>
            </div>
          @endif
        </div>
      @empty
        <div class="text-center py-5 text-slate-400 space-y-1">
          <i class="fa-solid fa-calendar-check text-2xl text-slate-300"></i>
          <p class="text-xs font-medium">No follow-ups scheduled yet.</p>
          <button type="button" onclick="openFollowupModal()" class="text-xs font-bold text-amber-600 hover:underline cursor-pointer">Schedule first follow-up</button>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Activity History Feed -->
  <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
    <h3 class="font-extrabold text-sm text-slate-900">Activity History</h3>
    <div class="space-y-2.5 max-h-80 overflow-y-auto">
      @forelse($lead->activities as $act)
        <div class="flex items-start gap-2.5 text-xs">
          <div class="h-6 w-6 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5">
            <i class="fa-solid fa-clock-rotate-left"></i>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-slate-700 font-medium">{{ $act->description }}</p>
            <span class="text-[10px] text-slate-400">{{ $act->created_at->diffForHumans() }}</span>
          </div>
        </div>
      @empty
        <p class="text-xs text-slate-400 italic">No activity entries logged.</p>
      @endforelse
    </div>
  </div>
</div>

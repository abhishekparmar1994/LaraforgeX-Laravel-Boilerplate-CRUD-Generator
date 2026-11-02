{{-- ── 9. LIFECYCLES, FOLLOW-UPS & SOLAR ACTIVITY FEED ──────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- Chart: Project Status Distribution --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Project Lifecycles</h3>
        <p class="text-xs text-slate-400 font-medium">EPC phase distribution</p>
      </div>
      <a href="/admin/projects" class="text-xs font-bold text-brand-600 hover:underline">View All</a>
    </div>
    <div id="project-status-chart" class="min-h-[220px]"></div>
  </div>

  {{-- Upcoming Follow-ups & Surveys --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Upcoming Site Surveys</h3>
        <p class="text-xs text-slate-400 font-medium">Client consultations & audits</p>
      </div>
      <a href="/admin/followups" class="text-xs font-bold text-brand-600 hover:underline">Schedule</a>
    </div>
    <div class="divide-y divide-slate-100">
      @forelse($upcoming_followups as $f)
        <div class="py-2.5 flex items-center justify-between gap-3">
          <div class="flex items-center gap-2.5 min-w-0">
            <div class="h-8 w-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs shrink-0">
              <i class="fa-solid {{ $f->follow_up_type === 'phone_call' ? 'fa-phone' : ($f->follow_up_type === 'email' ? 'fa-envelope' : ($f->follow_up_type === 'whatsapp' ? 'fa-brands fa-whatsapp' : 'fa-handshake')) }}"></i>
            </div>
            <div class="min-w-0">
              <p class="text-xs font-bold text-slate-800 truncate">
                {{ $f->lead ? $f->lead->company_name : ($f->client ? $f->client->name : 'Site Survey') }}
              </p>
              <p class="text-[11px] text-slate-400 truncate">
                {{ $f->scheduled_at->format('M d, H:i') }} • {{ $f->assignedTo ? $f->assignedTo->name : 'Unassigned' }}
              </p>
            </div>
          </div>
          <button onclick="completeFollowup('{{ $f->id }}')"
            class="text-[10px] bg-slate-100 hover:bg-emerald-50 hover:text-emerald-600 text-slate-600 font-bold px-2 py-1 rounded-lg transition shrink-0 cursor-pointer">
            Done
          </button>
        </div>
      @empty
        <div class="py-6 text-center text-slate-400 text-xs">No pending surveys scheduled</div>
      @endforelse
    </div>
  </div>

  {{-- Recent Activity Timeline --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Recent Solar Operations</h3>
        <p class="text-xs text-slate-400 font-medium">Real-time audit log</p>
      </div>
      <a href="/admin/activities" class="text-xs font-bold text-brand-600 hover:underline">Full Feed</a>
    </div>
    <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
      @forelse($recent_activities as $act)
        <div class="py-2.5 flex items-start gap-2.5">
          <div class="h-6 w-6 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5">
            <i class="fa-solid {{ $act->type === 'status_change' ? 'fa-arrow-right-arrow-left' : ($act->type === 'payment' ? 'fa-dollar-sign' : ($act->type === 'deployment' ? 'fa-cloud-arrow-up' : 'fa-comment-dots')) }}"></i>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-xs text-slate-700 leading-snug">{{ $act->description }}</p>
            <span class="text-[10px] text-slate-400 font-medium block mt-0.5">{{ $act->created_at->diffForHumans() }}</span>
          </div>
        </div>
      @empty
        <div class="py-6 text-center text-slate-400 text-xs">No recent activity logged</div>
      @endforelse
    </div>
  </div>

</div>


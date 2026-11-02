{{-- ── 8. TEAM LEADERBOARD & URGENT TECHNICAL TASKS ──────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  {{-- Widget: Top Solar Executives Leaderboard --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">Top Solar Executives Leaderboard</h3>
        <p class="text-xs text-slate-400 font-medium">Ranked by deal conversions and active pipeline value</p>
      </div>
      <a href="/admin/team-performance" class="text-xs font-bold text-brand-600 hover:underline">Full Standings</a>
    </div>

    <div class="divide-y divide-slate-100">
      @forelse($leaderboard as $idx => $exec)
        @php
          $medals = ['bg-amber-100 text-amber-800 border-amber-300', 'bg-slate-200 text-slate-700 border-slate-300', 'bg-amber-700/20 text-amber-900 border-amber-400/40'];
          $rankClass = $medals[$idx] ?? 'bg-slate-100 text-slate-500 border-slate-200';
        @endphp
        <div class="py-2.5 flex items-center justify-between gap-3">
          <div class="flex items-center gap-3 min-w-0">
            <span class="w-6 h-6 rounded-full border text-[10px] font-black flex items-center justify-center shrink-0 {{ $rankClass }}">
              {{ $idx + 1 }}
            </span>
            <div class="min-w-0">
              <p class="text-xs font-extrabold text-slate-800 truncate">{{ $exec['name'] }}</p>
              <p class="text-[10px] text-slate-400 truncate">{{ $exec['leads'] }} Leads • {{ $exec['won'] }} Won Deals</p>
            </div>
          </div>

          <div class="text-right shrink-0">
            <p class="text-xs font-extrabold text-emerald-700">₹{{ number_format($exec['pipeline'] / 100000, 1) }}L</p>
            <span class="text-[10px] font-bold text-slate-400">{{ $exec['rate'] }}% Conv.</span>
          </div>
        </div>
      @empty
        <div class="py-6 text-center text-slate-400 text-xs">No executive data available</div>
      @endforelse
    </div>
  </div>

  {{-- Widget: High-Priority Engineering & Field Tasks --}}
  <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-extrabold text-sm text-slate-900">High-Priority Technical Tasks</h3>
        <p class="text-xs text-slate-400 font-medium">Site audits, CEIG approvals, and subsidy disbursements</p>
      </div>
      <a href="/admin/tasks" class="text-xs font-bold text-brand-600 hover:underline">All Tasks</a>
    </div>

    <div class="divide-y divide-slate-100">
      @forelse($urgent_tasks as $task)
        @php
          $priorityColors = [
            'urgent' => 'bg-rose-50 text-rose-700 border-rose-200',
            'high' => 'bg-amber-50 text-amber-700 border-amber-200',
            'medium' => 'bg-sky-50 text-sky-700 border-sky-200',
            'low' => 'bg-slate-50 text-slate-600 border-slate-200',
          ];
          $pCol = $priorityColors[$task->priority] ?? 'bg-slate-50 text-slate-600 border-slate-200';
        @endphp
        <div class="py-2.5 flex items-center justify-between gap-3">
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-1.5 mb-0.5">
              <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.2 rounded border {{ $pCol }}">
                {{ $task->priority }}
              </span>
              <span class="text-[10px] text-slate-400 font-semibold uppercase">
                {{ str_replace('_', ' ', $task->status) }}
              </span>
            </div>
            <a href="/admin/tasks" class="text-xs font-bold text-slate-800 hover:text-brand-600 transition truncate block" title="{{ $task->title }}">
              {{ $task->title }}
            </a>
          </div>

          <div class="text-right shrink-0">
            <span class="text-[10px] text-slate-400 block">
              {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d') : 'No Due' }}
            </span>
            <span class="text-[10px] font-bold text-slate-600 block">
              {{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}
            </span>
          </div>
        </div>
      @empty
        <div class="py-6 text-center text-slate-400 text-xs">No pending engineering tasks</div>
      @endforelse
    </div>
  </div>

</div>


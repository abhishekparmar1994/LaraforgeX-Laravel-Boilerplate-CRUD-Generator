@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — Staff & Team Performance Leaderboard')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Staff Performance</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">
  <!-- Top Header & Metrics Bar -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <div class="flex items-center gap-2">
        <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
          <i class="fa-solid fa-trophy text-amber-500"></i> Staff Performance Leaderboard
        </h2>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
          {{ count($users) }} Solar Executives
        </span>
      </div>
      <p class="text-xs text-slate-500 font-medium mt-0.5">
        Ranked by conversions and commissioned solar projects — click an executive to view individual dossier
      </p>
    </div>

    <div class="flex items-center gap-2">
      <select class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 shadow-2xs focus:outline-none">
        <option>Last 30 days</option>
        <option>This Quarter</option>
        <option>All Time</option>
      </select>
    </div>
  </div>

  <!-- Leaderboard Table (Screenshot 10 Replica) -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
      <div class="flex items-center gap-2 text-xs font-bold text-slate-800 uppercase tracking-wider">
        <i class="fa-solid fa-ranking-star text-brand-500"></i>
        <span>Executive Conversion Standings</span>
      </div>
      <span class="text-[11px] text-slate-400 font-medium">Auto-calculated from closed-won project handovers</span>
    </div>

    <div class="overflow-x-auto">
      <table id="team-performance-table" class="datatable w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5 text-center w-12">#</th>
            <th class="px-4 py-3.5">Executive</th>
            <th class="px-4 py-3.5 text-center">Leads</th>
            <th class="px-4 py-3.5 text-center">Active</th>
            <th class="px-4 py-3.5 text-center">Won</th>
            <th class="px-4 py-3.5 text-center">Lost</th>
            <th class="px-4 py-3.5 text-center">Rate</th>
            <th class="px-4 py-3.5 text-center">Activities</th>
            <th class="px-4 py-3.5 text-right">Pipeline Value</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($users as $index => $u)
            @php
              $rank = $index + 1;
              $initial = strtoupper(substr($u->name, 0, 1));
              $medal = $rank === 1 ? 'bg-amber-400 text-white' : ($rank === 2 ? 'bg-slate-300 text-slate-800' : ($rank === 3 ? 'bg-amber-700 text-white' : 'bg-slate-100 text-slate-600'));
            @endphp
            <tr class="hover:bg-slate-50/80 transition">
              <td class="px-4 py-3.5 text-center" data-sort="{{ $rank }}">
                <span class="w-6 h-6 rounded-full inline-flex items-center justify-center font-black text-[10px] {{ $medal }}">
                  {{ $rank }}
                </span>
              </td>
              <td class="px-4 py-3.5" data-sort="{{ $u->name }}">
                <a href="/admin/team/{{ $u->id }}" class="flex items-center gap-2.5 group">
                  <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-extrabold text-xs flex items-center justify-center shrink-0 group-hover:bg-amber-50 group-hover:text-amber-600 transition">
                    {{ $initial }}
                  </span>
                  <div>
                    <span class="font-extrabold text-xs text-slate-900 group-hover:text-brand-600 transition block">
                      {{ $u->name }}
                    </span>
                    <span class="text-[10px] text-slate-400 block">{{ $u->email }}</span>
                  </div>
                </a>
              </td>
              <td class="px-4 py-3.5 text-center font-bold text-slate-800" data-sort="{{ $u->stats['leads'] }}">{{ $u->stats['leads'] }}</td>
              <td class="px-4 py-3.5 text-center font-bold text-slate-600" data-sort="{{ $u->stats['active'] }}">{{ $u->stats['active'] }}</td>
              <td class="px-4 py-3.5 text-center" data-sort="{{ $u->stats['won'] }}">
                <span class="px-2 py-0.5 rounded-lg font-black text-xs {{ $u->stats['won'] > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'text-slate-400' }}">
                  {{ $u->stats['won'] }}
                </span>
              </td>
              <td class="px-4 py-3.5 text-center font-semibold text-rose-500" data-sort="{{ $u->stats['lost'] }}">{{ $u->stats['lost'] }}</td>
              <td class="px-4 py-3.5 text-center" data-sort="{{ $u->stats['rate'] }}">
                <span class="font-extrabold text-xs {{ $u->stats['rate'] >= 5 ? 'text-emerald-600' : ($u->stats['rate'] > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                  {{ $u->stats['rate'] }}%
                </span>
              </td>
              <td class="px-4 py-3.5 text-center font-bold text-slate-700" data-sort="{{ $u->stats['activities'] }}">{{ $u->stats['activities'] }}</td>
              <td class="px-4 py-3.5 text-right font-extrabold text-slate-900" data-sort="{{ $u->stats['pipeline_value'] }}">
                ₹ {{ number_format($u->stats['pipeline_value'] / 100000, 2) }} L
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="p-8 text-center text-slate-400">No team members registered yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

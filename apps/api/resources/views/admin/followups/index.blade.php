@extends('admin.layouts.app')

@section('title', 'SolarPulse CRM — Follow-ups Command Center')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Follow-ups Command Center</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <div class="flex items-center gap-2">
        <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
          <i class="fa-solid fa-phone-volume text-amber-500"></i> Follow-ups Command Center
        </h2>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
          {{ number_format($counts['all'] ?? $followups->total()) }} Total
        </span>
      </div>
      <p class="text-xs text-slate-500 font-medium mt-0.5">
        Manage customer site visits, telephone consultations, WhatsApp proposals, and net-metering reviews
      </p>
    </div>
    <button type="button" onclick="$('#modal-create-followup').removeClass('hidden')"
      class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm shadow-amber-500/20 transition cursor-pointer">
      <i class="fa-solid fa-plus"></i> Schedule Follow-up
    </button>
  </div>

  <!-- Metric Counters Tabs Bar (Screenshot 5 Replica) -->
  <div class="bg-white border border-slate-200 rounded-2xl p-2 shadow-xs">
    <div class="flex items-center gap-2 overflow-x-auto text-xs font-bold scrollbar-none">
      <a href="/admin/followups"
        class="px-4 py-2 rounded-xl flex items-center gap-2 border transition {{ !request('filter') ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200' }}">
        <span>ALL</span>
        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ !request('filter') ? 'bg-slate-800 text-slate-200' : 'bg-slate-200 text-slate-700' }}">{{ $counts['all'] ?? 0 }}</span>
      </a>

      <a href="/admin/followups?filter=overdue"
        class="px-4 py-2 rounded-xl flex items-center gap-2 border transition {{ request('filter') === 'overdue' ? 'bg-rose-600 text-white border-rose-600' : 'bg-rose-50/70 hover:bg-rose-100/70 text-rose-700 border-rose-200' }}">
        <span>OVERDUE</span>
        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('filter') === 'overdue' ? 'bg-rose-700 text-white' : 'bg-rose-200 text-rose-800' }}">{{ $counts['overdue'] ?? 0 }}</span>
      </a>

      <a href="/admin/followups?filter=today"
        class="px-4 py-2 rounded-xl flex items-center gap-2 border transition {{ request('filter') === 'today' ? 'bg-amber-500 text-white border-amber-500' : 'bg-amber-50/70 hover:bg-amber-100/70 text-amber-800 border-amber-200' }}">
        <span>TODAY</span>
        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('filter') === 'today' ? 'bg-amber-600 text-white' : 'bg-amber-200 text-amber-900' }}">{{ $counts['today'] ?? 0 }}</span>
      </a>

      <a href="/admin/followups?filter=upcoming"
        class="px-4 py-2 rounded-xl flex items-center gap-2 border transition {{ request('filter') === 'upcoming' ? 'bg-blue-600 text-white border-blue-600' : 'bg-blue-50/70 hover:bg-blue-100/70 text-blue-700 border-blue-200' }}">
        <span>UPCOMING</span>
        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('filter') === 'upcoming' ? 'bg-blue-700 text-white' : 'bg-blue-200 text-blue-800' }}">{{ $counts['upcoming'] ?? 0 }}</span>
      </a>

      <a href="/admin/followups?filter=completed"
        class="px-4 py-2 rounded-xl flex items-center gap-2 border transition {{ request('filter') === 'completed' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-emerald-50/70 hover:bg-emerald-100/70 text-emerald-700 border-emerald-200' }}">
        <span>DONE</span>
        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('filter') === 'completed' ? 'bg-emerald-700 text-white' : 'bg-emerald-200 text-emerald-800' }}">{{ $counts['completed'] ?? 0 }}</span>
      </a>

      <a href="/admin/followups?status=cancelled"
        class="px-4 py-2 rounded-xl flex items-center gap-2 border transition {{ request('status') === 'cancelled' ? 'bg-slate-700 text-white border-slate-700' : 'bg-slate-100 hover:bg-slate-200 text-slate-600 border-slate-200' }}">
        <span>CANCELLED</span>
        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('status') === 'cancelled' ? 'bg-slate-800 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $counts['cancelled'] ?? 0 }}</span>
      </a>
    </div>
  </div>

  <!-- 3-Column Responsive Cards Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
    @forelse($followups as $f)
      @include('admin.followups.partials.card', ['f' => $f])
    @empty
      <div class="col-span-3 p-12 text-center bg-white border border-slate-200 rounded-3xl shadow-xs">
        <i class="fa-solid fa-calendar-check text-4xl text-slate-300 mb-2"></i>
        <h4 class="font-extrabold text-sm text-slate-800">No follow-ups in this queue</h4>
        <p class="text-xs text-slate-400 mt-1">All scheduled consultations are completed or none match the active filters.</p>
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
  @if($followups->hasPages())
    <div class="p-4 bg-white border border-slate-200 rounded-2xl flex items-center justify-between shadow-xs">
      <span class="text-xs font-semibold text-slate-500">
        Showing {{ $followups->firstItem() }} to {{ $followups->lastItem() }} of {{ $followups->total() }} follow-ups
      </span>
      <div class="flex items-center gap-1">
        {{ $followups->withQueryString()->links() }}
      </div>
    </div>
  @endif
</div>

@include('admin.followups.partials.modal')

@endsection

@section('scripts')
<script>
  function completeFollowup(id) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Complete Follow-up?',
        text: 'Mark this solar client follow-up as successfully resolved.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fa-solid fa-check mr-1"></i> Yes, complete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
      }).then(res => {
        if (res.isConfirmed) submitFollowupComplete(id);
      });
    } else if (confirm('Mark this solar follow-up as completed?')) {
      submitFollowupComplete(id);
    }
  }

  function submitFollowupComplete(id) {
    $.ajax({
      url: `/admin/followups/${id}/complete`,
      type: 'POST',
      data: { _token: '{{ csrf_token() }}' },
      success: function() {
        if (typeof window.showToast === 'function') window.showToast('success', 'Follow-up completed');
        window.location.reload();
      },
      error: function() {
        window.showToast ? window.showToast('error', 'Could not complete follow-up.') : alert('Could not complete follow-up.');
      }
    });
  }
</script>
@endsection

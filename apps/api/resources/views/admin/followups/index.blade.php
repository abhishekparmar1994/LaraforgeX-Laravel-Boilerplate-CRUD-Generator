@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Follow-ups & Reminders')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">Follow-ups</span>
  </nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Scheduled Follow-ups & Communications</h2>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Manage phone calls, meetings, demos, proposal follow-ups, and client reminders.</p>
    </div>
    <button onclick="$('#modal-create-followup').removeClass('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-sm shadow-brand-600/20 transition cursor-pointer">
      <i class="fa-solid fa-plus"></i> Schedule Follow-up
    </button>
  </div>

  <!-- Filter Bar -->
  <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
    <div class="flex items-center gap-2 overflow-x-auto text-xs font-bold">
      <a href="/admin/followups" class="px-3.5 py-2 rounded-xl border {{ !request('filter') ? 'bg-brand-50 text-brand-600 border-brand-500' : 'bg-slate-50 text-slate-600 border-slate-200' }}">All</a>
      <a href="/admin/followups?filter=today" class="px-3.5 py-2 rounded-xl border {{ request('filter') === 'today' ? 'bg-brand-50 text-brand-600 border-brand-500' : 'bg-slate-50 text-slate-600 border-slate-200' }}">Today's Due</a>
      <a href="/admin/followups?filter=upcoming" class="px-3.5 py-2 rounded-xl border {{ request('filter') === 'upcoming' ? 'bg-brand-50 text-brand-600 border-brand-500' : 'bg-slate-50 text-slate-600 border-slate-200' }}">Upcoming</a>
      <a href="/admin/followups?filter=overdue" class="px-3.5 py-2 rounded-xl border {{ request('filter') === 'overdue' ? 'bg-rose-50 text-rose-700 border-rose-300' : 'bg-slate-50 text-slate-600 border-slate-200' }}">Overdue</a>
      <a href="/admin/followups?filter=completed" class="px-3.5 py-2 rounded-xl border {{ request('filter') === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-slate-50 text-slate-600 border-slate-200' }}">Completed</a>
    </div>
  </div>

  <!-- Follow-ups Table -->
  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
          <tr>
            <th class="px-4 py-3.5">Type & Channel</th>
            <th class="px-4 py-3.5">Target Lead / Client</th>
            <th class="px-4 py-3.5">Scheduled Date & Time</th>
            <th class="px-4 py-3.5">Assigned Developer</th>
            <th class="px-4 py-3.5">Notes</th>
            <th class="px-4 py-3.5">Status</th>
            <th class="px-4 py-3.5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
          @forelse($followups as $f)
            <tr class="hover:bg-slate-50/70 transition {{ $f->status === 'completed' ? 'opacity-60 bg-slate-50/40' : '' }}">
              <td class="px-4 py-3.5">
                <span class="inline-flex items-center gap-1.5 font-bold text-slate-800">
                  <i class="fa-solid {{ $f->follow_up_type === 'phone_call' ? 'fa-phone text-blue-500' : ($f->follow_up_type === 'email' ? 'fa-envelope text-indigo-500' : ($f->follow_up_type === 'whatsapp' ? 'fa-brands fa-whatsapp text-emerald-500' : ($f->follow_up_type === 'demo' ? 'fa-laptop-code text-purple-500' : 'fa-handshake text-amber-500'))) }}"></i>
                  {{ ucfirst(str_replace('_', ' ', $f->follow_up_type)) }}
                </span>
              </td>
              <td class="px-4 py-3.5">
                @if($f->lead)
                  <a href="/admin/leads/{{ $f->lead_id }}" class="font-bold text-slate-900 hover:text-brand-600 block">{{ $f->lead->company_name }}</a>
                  <span class="text-[10px] text-slate-400">Lead • {{ $f->lead->contact_person }}</span>
                @elseif($f->client)
                  <a href="/admin/clients/{{ $f->client_id }}" class="font-bold text-slate-900 hover:text-brand-600 block">{{ $f->client->name }}</a>
                  <span class="text-[10px] text-slate-400">Client</span>
                @else
                  <span class="text-slate-400 italic">General</span>
                @endif
              </td>
              <td class="px-4 py-3.5">
                <span class="font-bold {{ $f->is_overdue ? 'text-rose-600' : 'text-slate-800' }} block">
                  {{ $f->scheduled_at->format('M d, Y H:i') }}
                </span>
                <span class="text-[10px] text-slate-400">{{ $f->scheduled_at->diffForHumans() }}</span>
              </td>
              <td class="px-4 py-3.5 font-bold text-slate-800">
                {{ $f->assignedTo ? $f->assignedTo->name : 'Unassigned' }}
              </td>
              <td class="px-4 py-3.5 text-slate-600 max-w-xs truncate">
                {{ $f->notes ?: '—' }}
              </td>
              <td class="px-4 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $f->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($f->is_overdue ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                  {{ ucfirst($f->status) }}
                </span>
              </td>
              <td class="px-4 py-3.5 text-right space-x-2">
                @if($f->status !== 'completed')
                  <button onclick="completeFollowup('{{ $f->id }}')" class="text-xs font-bold text-emerald-600 hover:underline">Complete</button>
                @endif
                <button onclick="deleteFollowup('{{ $f->id }}')" class="text-slate-400 hover:text-rose-600 transition">
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="p-8 text-center text-slate-400">No follow-ups recorded.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100">
      {{ $followups->withQueryString()->links() }}
    </div>
  </div>

</div>

<!-- Schedule Followup Modal -->
<div id="modal-create-followup" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-create-followup').addClass('hidden')"></div>
    <form action="/admin/followups" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Schedule Communication</h3>
        <button type="button" onclick="$('#modal-create-followup').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="space-y-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Communication Type *</label>
          <select name="follow_up_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="phone_call">Phone Call</option>
            <option value="email">Email</option>
            <option value="whatsapp">WhatsApp Message</option>
            <option value="meeting">Video Meeting / Zoom</option>
            <option value="demo">Product Demo</option>
            <option value="proposal_followup">Proposal Follow-up</option>
            <option value="payment_followup">Payment Reminder</option>
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Associate with Lead</label>
          <select name="lead_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="">-- None / Select Lead --</option>
            @foreach($leads as $l)
              <option value="{{ $l->id }}">{{ $l->company_name }} ({{ $l->contact_person }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Associate with Client</label>
          <select name="client_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="">-- None / Select Client --</option>
            @foreach($clients as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="font-bold text-slate-700 block mb-1">Date & Time *</label>
            <input type="datetime-local" name="scheduled_at" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
          </div>
          <div>
            <label class="font-bold text-slate-700 block mb-1">Assign Developer</label>
            <select name="assigned_to_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
              @foreach($developers as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Notes / Agenda</label>
          <textarea name="notes" rows="2" placeholder="Discussion points..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold"></textarea>
        </div>
        <input type="hidden" name="status" value="scheduled">
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-followup').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Schedule</button>
      </div>
    </form>
  </div>
</div>

<script>
  async function completeFollowup(id) {
    try {
      await axios.post(`/admin/followups/${id}/complete`);
      location.reload();
    } catch(e) {
      alert('Error updating follow-up');
    }
  }

  async function deleteFollowup(id) {
    if (confirm('Delete this follow-up?')) {
      try {
        await axios.delete(`/admin/followups/${id}`);
        location.reload();
      } catch(e) {
        alert('Failed to delete');
      }
    }
  }
</script>
@endsection

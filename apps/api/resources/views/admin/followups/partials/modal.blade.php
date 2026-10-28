{{-- Modal: Schedule Solar Follow-up --}}
<div id="modal-create-followup" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-calendar-plus"></i>
        </span>
        <h3 class="font-extrabold text-sm text-slate-900">Schedule Solar Follow-up</h3>
      </div>
      <button type="button" onclick="$('#modal-create-followup').addClass('hidden')" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form action="/admin/followups" method="POST" class="space-y-3">
      @csrf
      <input type="hidden" name="status" value="scheduled">

      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Target Solar Lead</label>
        <select name="lead_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="">Select prospective lead...</option>
          @foreach($leads as $ld)
            <option value="{{ $ld->id }}">{{ $ld->company_name }} ({{ $ld->contact_person }})</option>
          @endforeach
        </select>
      </div>

      <div class="grid grid-cols-2 gap-2.5">
        <div>
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Channel / Type</label>
          <select name="follow_up_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
            <option value="phone_call">📞 Phone Call</option>
            <option value="site_visit">📍 Site Survey</option>
            <option value="whatsapp">💬 WhatsApp Quotation</option>
            <option value="quotation_meeting">📊 Sizing Presentation</option>
          </select>
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-700 mb-1">Assigned Consultant</label>
          <select name="assigned_to_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
            <option value="">Select consultant...</option>
            @foreach($developers as $dev)
              <option value="{{ $dev->id }}">{{ $dev->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Scheduled Date & Time</label>
        <input type="datetime-local" name="scheduled_at" required
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>

      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Discussion Agenda / Notes</label>
        <textarea name="notes" rows="3" placeholder="e.g. Discuss 4.5 kW rooftop proposal, verify DISCOM net-metering feasibility..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="$('#modal-create-followup').addClass('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          <i class="fa-solid fa-plus mr-1"></i> Schedule
        </button>
      </div>
    </form>
  </div>
</div>

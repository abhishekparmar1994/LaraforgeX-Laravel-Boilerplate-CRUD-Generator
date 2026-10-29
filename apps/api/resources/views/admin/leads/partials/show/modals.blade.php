{{-- Modals: Schedule Follow-up, Convert to Solar Project, Edit Follow-up --}}

<!-- ── MODAL: SCHEDULE FOLLOW-UP ──────────────────────────────── -->
<div id="modal-add-lead-followup" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-calendar-plus"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Schedule Solar Follow-up</h3>
          <p class="text-[11px] text-slate-400 font-medium">{{ $lead->company_name }}</p>
        </div>
      </div>
      <button type="button" onclick="closeFollowupModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form action="/admin/followups" method="POST" class="space-y-3.5">
      @csrf
      <input type="hidden" name="lead_id" value="{{ $lead->id }}">
      <input type="hidden" name="status" value="scheduled">

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Follow-up Type *</label>
        <select name="follow_up_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
          <option value="call">📞 Phone Call</option>
          <option value="whatsapp">💬 WhatsApp Consultation</option>
          <option value="site_survey">🔍 Site Survey & Shadow Analysis</option>
          <option value="proposal">📄 Send Solar Sizing & Quotation</option>
          <option value="meeting">⚡ DISCOM Net Metering Feasibility</option>
          <option value="demo">🤝 Contract Signing & Subsidy Filing</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Date & Time *</label>
        <input type="datetime-local" name="scheduled_at" required value="{{ now()->addDay()->format('Y-m-d\T10:00') }}"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Assign Solar Executive</label>
        <select name="assigned_to_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
          <option value="">Current User / Unassigned</option>
          @foreach($developers as $dev)
            <option value="{{ $dev->id }}" {{ $lead->assigned_to_user_id == $dev->id ? 'selected' : '' }}>{{ $dev->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Follow-up Agenda / Notes</label>
        <textarea name="notes" rows="2" placeholder="e.g. Schedule engineer visit for terrace shadow analysis and sanctioned load check..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-amber-500"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeFollowupModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          Schedule Follow-up
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ── MODAL: CONVERT TO SOLAR CLIENT & PROJECT ────────────────── -->
<div id="modal-convert-lead" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4 font-sans max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div>
        <h3 class="font-extrabold text-base text-slate-900">Convert Lead to Commissioned Solar Plant & Client</h3>
        <p class="text-xs text-slate-400 font-medium">Creates Customer Account, Primary Contact, and Active Solar EPC Project.</p>
      </div>
      <button type="button" onclick="closeConvertModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
    </div>

    <form action="/admin/leads/{{ $lead->id }}/convert" method="POST" class="space-y-3 text-xs">
      @csrf
      <div>
        <label class="font-bold text-slate-700 block mb-1">Contact Person Job Title / Role</label>
        <input type="text" name="job_title" value="Property Owner" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800">
      </div>

      <div class="p-3.5 bg-amber-50/60 border border-amber-200 rounded-2xl space-y-2.5">
        <label class="flex items-center gap-2 font-bold text-slate-900 cursor-pointer">
          <input type="checkbox" name="create_project" value="1" checked class="rounded text-amber-600">
          <span>Create Solar Rooftop EPC Project</span>
        </label>
        <div>
          <label class="text-[11px] font-bold text-slate-500 block mb-1">Project Name</label>
          <input type="text" name="project_name" value="{{ $lead->company_name }} Solar Rooftop System" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-semibold text-xs text-slate-800">
        </div>
        <div>
          <label class="text-[11px] font-bold text-slate-500 block mb-1">Solar Installation Segment</label>
          <select name="project_type" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-semibold text-xs text-slate-800">
            <option value="Residential Rooftop (1-10 kW)" selected>Residential Rooftop (1-10 kW) — PM Surya Ghar</option>
            <option value="Commercial & Industrial (C&I > 10 kW)">Commercial & Industrial (C&I > 10 kW)</option>
            <option value="Agricultural Solar Pump (KUSUM)">Agricultural Solar Pump (KUSUM Scheme)</option>
            <option value="Ground Mount Solar Park">Ground Mount Solar Park</option>
          </select>
        </div>
      </div>

      <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
        <input type="checkbox" name="create_website" value="1" checked class="rounded text-amber-600">
        <span>Create Commissioned Plant Record (Inverter / Net Metering)</span>
      </label>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeConvertModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold shadow-sm shadow-emerald-600/20 cursor-pointer">Convert to Solar Project</button>
      </div>
    </form>
  </div>
</div>

<!-- ── MODAL: EDIT FOLLOW-UP ──────────────────────────────────── -->
<div id="modal-edit-lead-followup" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-pen-to-square"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Update Solar Follow-up</h3>
          <p class="text-[11px] text-slate-400 font-medium">{{ $lead->company_name }}</p>
        </div>
      </div>
      <button type="button" onclick="closeEditFollowupModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form id="editFollowupForm" method="POST" class="space-y-3.5">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Follow-up Type *</label>
        <select name="follow_up_type" id="edit_fup_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
          <option value="call">📞 Phone Call</option>
          <option value="whatsapp">💬 WhatsApp Consultation</option>
          <option value="site_survey">🔍 Site Survey & Shadow Analysis</option>
          <option value="proposal">📄 Send Solar Sizing & Quotation</option>
          <option value="meeting">⚡ DISCOM Net Metering Feasibility</option>
          <option value="demo">🤝 Contract Signing & Subsidy Filing</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Date & Time *</label>
        <input type="datetime-local" name="scheduled_at" id="edit_fup_datetime" required
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
        <select name="status" id="edit_fup_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
          <option value="scheduled">Scheduled</option>
          <option value="completed">Completed</option>
          <option value="rescheduled">Rescheduled</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Assign Solar Executive</label>
        <select name="assigned_to_user_id" id="edit_fup_assigned" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
          <option value="">Current User / Unassigned</option>
          @foreach($developers as $dev)
            <option value="{{ $dev->id }}">{{ $dev->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Agenda / Notes</label>
        <textarea name="notes" id="edit_fup_notes" rows="2" placeholder="e.g. Call to discuss site survey results and subsidy status..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-amber-500"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeEditFollowupModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          Update Follow-up
        </button>
      </div>
    </form>
  </div>
</div>

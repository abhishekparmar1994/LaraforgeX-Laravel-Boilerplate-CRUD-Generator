{{-- Tab 3: Follow-up — Site Visit & Consultation Scheduling --}}
<div id="tab-content-followup" class="wizard-tab-content space-y-4 hidden">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Scheduled Follow-up Date & Time</label>
      <input type="datetime-local" name="next_followup_date" id="lead-followup-date"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="next_followup_date"></p>
      @if(isset($errors) && $errors->has('next_followup_date'))
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('next_followup_date') }}</span></p>
      @endif
    </div>

    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Follow-up Type</label>
      <select name="follow_up_type" id="lead-followup-type"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        <option value="site_visit" selected>📍 Site Visit & Shadow Analysis</option>
        <option value="call">📞 Phone Consultation</option>
        <option value="whatsapp">💬 WhatsApp Quotation Sharing</option>
        <option value="quotation_meeting">📊 Detailed Sizing & ROI Presentation</option>
        <option value="document_collection">📑 PM Surya Ghar Document Collection</option>
      </select>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="follow_up_type"></p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Assigned Solar Consultant</label>
      <select name="assigned_to_user_id" id="lead-assigned-to"
        onchange="if(document.getElementById('lead-info-assigned-to')) document.getElementById('lead-info-assigned-to').value = this.value"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        <option value="">Select consultant</option>
        @if(isset($developers))
          @foreach($developers as $dev)
            <option value="{{ $dev->id }}">{{ $dev->name }}</option>
          @endforeach
        @endif
      </select>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="assigned_to_user_id"></p>
    </div>

    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Site Shadow / Roof Type</label>
      <select name="roof_type" id="lead-roof-type"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        <option value="rcc_flat" selected>RCC Flat Concrete Roof (Standard)</option>
        <option value="tin_shed">Industrial Tin / Metal Shed</option>
        <option value="tiled_roof">Clay Tiled Slope Roof</option>
        <option value="elevated_structure">Elevated Gazebo Structure (> 8 ft)</option>
      </select>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="roof_type"></p>
    </div>
  </div>

  <div>
    <label class="block text-[11px] font-bold text-slate-700 mb-1">Follow-up Agenda & Customer Instructions</label>
    <textarea name="followup_notes" id="lead-followup-notes" rows="3" placeholder="e.g. Bring drone for shadow analysis, check meter board location, discuss 3 kW vs 5 kW options..."
      class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500"></textarea>
    <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="followup_notes"></p>
  </div>
</div>
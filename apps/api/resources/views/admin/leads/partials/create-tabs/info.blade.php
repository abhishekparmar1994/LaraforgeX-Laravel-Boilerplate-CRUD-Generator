{{-- Tab 4: Info — Company, Pipeline & Status, and Notes --}}
<div id="tab-content-info" class="wizard-tab-content space-y-5 hidden">
  <!-- Company & Personal -->
  <div class="space-y-3">
    <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
      <i class="fa-solid fa-building text-amber-500"></i>
      <span>Company & Personal</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Company Name</label>
        <input type="text" name="company_name" id="lead-company-name" placeholder="Company name"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Designation</label>
        <input type="text" name="designation" id="lead-designation" placeholder="Job title"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Website</label>
        <input type="url" name="website" id="lead-website" placeholder="https://example.com"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Salutation</label>
        <select name="salutation" id="lead-salutation"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="Mr." selected>Mr.</option>
          <option value="Ms.">Ms.</option>
          <option value="Mrs.">Mrs.</option>
          <option value="Dr.">Dr.</option>
          <option value="M/s.">M/s. (Corporate)</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Pipeline & Status -->
  <div class="space-y-3 pt-2 border-t border-slate-100">
    <div class="flex items-center gap-2 text-xs font-bold text-purple-800">
      <i class="fa-solid fa-gear text-purple-500"></i>
      <span>Pipeline & Status</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Society Name</label>
        <input type="text" name="society_name" id="lead-society-name" placeholder="Housing society"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Approval Status</label>
        <select name="approval_status" id="lead-approval-status"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="">Select status</option>
          <option value="Pending" selected>Pending Verification</option>
          <option value="DISCOM Feasibility Approved">DISCOM Feasibility Approved</option>
          <option value="PM Surya Ghar In-Review">PM Surya Ghar In-Review</option>
          <option value="Net-Metering Sanctioned">Net-Metering Sanctioned</option>
        </select>
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Franchise</label>
        <select name="franchise" id="lead-franchise"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="">Select franchise</option>
          <option value="Vadodara Central" selected>Vadodara Central Hub</option>
          <option value="Ahmedabad West">Ahmedabad West</option>
          <option value="Surat South">Surat South</option>
          <option value="Rajkot Saurashtra">Rajkot Saurashtra</option>
        </select>
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">List</label>
        <select name="list" id="lead-list"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="solar sales" selected>solar sales</option>
          <option value="rooftop campaign 2026">rooftop campaign 2026</option>
          <option value="cni high tension">cni high tension</option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Stage</label>
        <select name="stage" id="lead-stage"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="Qualified" selected>Qualified</option>
          <option value="New Lead">New Lead</option>
          <option value="In Process">In Process</option>
          <option value="Site Visited">Site Visited</option>
          <option value="Estimate Send">Estimate Send</option>
          <option value="Document">Document</option>
          <option value="Not Connect">Not Connect</option>
          <option value="On Hold">On Hold</option>
          <option value="Lost">Lost</option>
          <option value="Out Area">Out Area</option>
        </select>
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Assigned To</label>
        <select name="assigned_to_user_id" id="lead-info-assigned-to"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="">Select user</option>
          @if(isset($developers))
            @foreach($developers as $dev)
              <option value="{{ $dev->id }}">{{ $dev->name }}</option>
            @endforeach
          @endif
        </select>
      </div>
    </div>
  </div>

  <!-- Notes -->
  <div class="space-y-2 pt-2 border-t border-slate-100">
    <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
      <i class="fa-solid fa-pen-to-square text-slate-400"></i>
      <span>Notes</span>
    </div>
    <textarea name="notes" id="lead-notes" rows="3" placeholder="Add notes about this lead..."
      class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500"></textarea>
  </div>
</div>

{{-- Solar Lead Multi-Step Wizard Modal --}}
<div id="modal-create-lead" class="fixed inset-0 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-3 md:p-6 hidden" style="z-index: 50;">
  <div class="bg-white rounded-3xl max-w-6xl xl:max-w-7xl w-full p-6 md:p-8 shadow-2xl border border-slate-100 space-y-4 h-[90vh] max-h-[94vh] flex flex-col transition-all">
    <!-- Top Header -->
    <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
      <div class="flex items-center gap-2.5">
        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-user-plus"></i>
        </span>
        <h3 class="font-extrabold text-base text-slate-900">Create Lead</h3>
      </div>

      <button type="button" onclick="closeCreateLeadModal()" class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition cursor-pointer">
        <i class="fa-solid fa-xmark text-sm"></i>
      </button>
    </div>

    <!-- Wizard Body: Left Vertical Tabs + Right Form Content -->
    <form id="form-create-solar-lead" action="/admin/leads" method="POST" class="flex-1 flex flex-col md:flex-row gap-6 overflow-hidden">
      @csrf

      <!-- Left Vertical Tab Menu -->
      <div class="w-full md:w-56 shrink-0 flex flex-row md:flex-col gap-1.5 border-b md:border-b-0 md:border-r border-slate-100 pr-0 md:pr-4">
        <button type="button" onclick="switchWizardTab('profile')" id="wizard-nav-profile"
          class="wizard-nav-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-sky-600 bg-sky-50 border border-sky-100 transition cursor-pointer">
          <div class="flex items-center gap-2.5">
            <i class="fa-solid fa-user text-xs w-4"></i>
            <span>Profile</span>
          </div>
          <span id="tab-err-badge-profile" class="hidden w-2 h-2 rounded-full bg-rose-500 ring-2 ring-rose-200 animate-pulse"></span>
        </button>
        <button type="button" onclick="switchWizardTab('qualifiers')" id="wizard-nav-qualifiers"
          class="wizard-nav-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
          <div class="flex items-center gap-2.5">
            <i class="fa-solid fa-sliders text-xs w-4"></i>
            <span>Qualifiers</span>
          </div>
          <span id="tab-err-badge-qualifiers" class="hidden w-2 h-2 rounded-full bg-rose-500 ring-2 ring-rose-200 animate-pulse"></span>
        </button>
        <button type="button" onclick="switchWizardTab('followup')" id="wizard-nav-followup"
          class="wizard-nav-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
          <div class="flex items-center gap-2.5">
            <i class="fa-regular fa-clock text-xs w-4"></i>
            <span>Follow-up</span>
          </div>
          <span id="tab-err-badge-followup" class="hidden w-2 h-2 rounded-full bg-rose-500 ring-2 ring-rose-200 animate-pulse"></span>
        </button>
        <button type="button" onclick="switchWizardTab('info')" id="wizard-nav-info"
          class="wizard-nav-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
          <div class="flex items-center gap-2.5">
            <i class="fa-solid fa-circle-info text-xs w-4"></i>
            <span>Info</span>
          </div>
          <span id="tab-err-badge-info" class="hidden w-2 h-2 rounded-full bg-rose-500 ring-2 ring-rose-200 animate-pulse"></span>
        </button>
      </div>

      <!-- Right Tab Panels -->
      <div class="flex-1 overflow-y-auto pr-2">
        @include('admin.leads.partials.create-tabs.profile')
        @include('admin.leads.partials.create-tabs.qualifiers')
        @include('admin.leads.partials.create-tabs.followup')
        @include('admin.leads.partials.create-tabs.info')
      </div>
    </form>

    <!-- Bottom Footer Actions -->
    <div class="flex items-center justify-between border-t border-slate-100 pt-3.5 shrink-0 mt-auto">
      <p class="text-[11px] font-medium text-slate-400 hidden sm:block">
        <i class="fa-solid fa-circle-info text-sky-500 mr-1"></i> Fill in lead details across tabs to register inquiry
      </p>
      <div class="flex items-center gap-2 ml-auto">
        <button type="button" onclick="closeCreateLeadModal()" class="px-4 py-2 text-slate-600 hover:text-slate-900 font-bold text-xs rounded-xl hover:bg-slate-50 transition cursor-pointer">
          <i class="fa-solid fa-xmark mr-1"></i> Cancel
        </button>
        <button type="button" onclick="submitCreateLeadForm()" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/20 transition cursor-pointer">
          <i class="fa-solid fa-check"></i> Create Lead
        </button>
      </div>
    </div>
  </div>
</div>

@include('admin.leads.partials.quick-add-group-modal')
@include('admin.leads.partials.create-modal-helpers')
@include('admin.leads.partials.create-modal-script')

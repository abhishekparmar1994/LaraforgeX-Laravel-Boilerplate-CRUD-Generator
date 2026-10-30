{{-- Tab 2: Qualifiers — Solar System Sizing, DISCOM, & Bill Metrics --}}
<div id="tab-content-qualifiers" class="wizard-tab-content space-y-4 hidden">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Product Group</label>
      <div class="flex gap-1.5">
        <select name="product_group" id="lead-product-group" onchange="handleProductGroupChange(this)"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="">Select product group</option>
          @if(isset($productGroups) && count($productGroups))
            @foreach($productGroups as $pg)
              <option value="{{ $pg->name }}" data-id="{{ $pg->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $pg->name }}</option>
            @endforeach
          @else
            <option value="Residential Rooftop (1-10 kW)" data-id="1" selected>Residential Rooftop (1-10 kW)</option>
            <option value="Commercial & Industrial (C&I)" data-id="2">Commercial & Industrial (C&I)</option>
            <option value="Housing Society Common Meter" data-id="3">Housing Society Common Meter</option>
            <option value="Solar Water Pump (PM KUSUM)" data-id="4">Solar Water Pump (PM KUSUM)</option>
            <option value="Ground Mounted Solar Farm" data-id="5">Ground Mounted Solar Farm</option>
          @endif
        </select>
        <button type="button" onclick="openQuickAddGroupModal('product_group')" title="Add New Product Group" class="w-8 h-8 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white flex items-center justify-center text-xs shrink-0 cursor-pointer shadow-xs transition">
          <i class="fa-solid fa-plus"></i>
        </button>
      </div>
      <input type="hidden" name="product_group_id" id="lead-product-group-id" value="{{ isset($productGroups) && count($productGroups) ? $productGroups[0]->id : 1 }}">
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="product_group"></p>
    </div>

    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Customer Group</label>
      <div class="flex gap-1.5">
        <select name="customer_group" id="lead-customer-group" onchange="handleCustomerGroupChange(this)"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="">Select customer group</option>
          @if(isset($customerGroups) && count($customerGroups))
            @foreach($customerGroups as $cg)
              <option value="{{ $cg->name }}" data-id="{{ $cg->id }}" data-product-group-id="{{ $cg->product_group_id }}" {{ $loop->first ? 'selected' : '' }}>{{ $cg->name }}</option>
            @endforeach
          @else
            <option value="Individual Homeowner" data-id="1" data-product-group-id="1" selected>Individual Homeowner</option>
            <option value="Bungalow / Villa Owner" data-id="2" data-product-group-id="1">Bungalow / Villa Owner</option>
            <option value="Commercial Enterprise" data-id="4" data-product-group-id="2">Commercial Enterprise</option>
          @endif
        </select>
        <button type="button" onclick="openQuickAddGroupModal('customer_group')" title="Add New Customer Group" class="w-8 h-8 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white flex items-center justify-center text-xs shrink-0 cursor-pointer shadow-xs transition">
          <i class="fa-solid fa-plus"></i>
        </button>
      </div>
      <input type="hidden" name="customer_group_id" id="lead-customer-group-id" value="{{ isset($customerGroups) && count($customerGroups) ? $customerGroups[0]->id : 1 }}">
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="customer_group"></p>
    </div>

    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Deal Size (INR)</label>
      <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
        <input type="number" step="0.01" name="deal_size" id="lead-deal-size" placeholder="0.00"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-7 pr-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="deal_size"></p>
      @if(isset($errors) && $errors->has('deal_size'))
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('deal_size') }}</span></p>
      @endif
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Lead Potential</label>
      <select name="lead_potential" id="lead-potential"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        <option value="">Select potential</option>
        <option value="Very High">🔥 Very High (Ready to Install)</option>
        <option value="High" selected>⚡ High (Site Survey Done)</option>
        <option value="Medium">Medium (Considering)</option>
        <option value="Low">Low (Price Inquiry)</option>
      </select>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="lead_potential"></p>
    </div>

    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Lead Source</label>
      <select name="lead_source" id="lead-source"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        <option value="PM Surya Ghar Portal" selected>PM Surya Ghar National Portal</option>
        <option value="Website">Company Website Inbound</option>
        <option value="Direct Survey">Field / Door-to-Door Survey</option>
        <option value="Referral">Customer Referral</option>
        <option value="Exhibition / Stall">Solar Expo / Stalls</option>
      </select>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="lead_source"></p>
    </div>

    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Lead Type</label>
      <select name="lead_type" id="lead-type"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        <option value="Residential" selected>Residential Rooftop</option>
        <option value="Commercial">Commercial (C&I)</option>
        <option value="Industrial">Industrial High Tension (HT)</option>
        <option value="Institutional">School / Hospital / Trust</option>
      </select>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="lead_type"></p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Monthly Bill (₹)</label>
      <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
        <input type="number" name="monthly_bill" id="lead-monthly-bill" placeholder="e.g. 4500"
          oninput="handleBillChange(this.value)"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-7 pr-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="monthly_bill"></p>
      @if(isset($errors) && $errors->has('monthly_bill'))
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('monthly_bill') }}</span></p>
      @endif
    </div>

    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Consumer Account No.</label>
      <input type="text" name="consumer_number" id="lead-consumer-no" placeholder="DISCOM consumer number"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="consumer_number"></p>
      @if(isset($errors) && $errors->has('consumer_number'))
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('consumer_number') }}</span></p>
      @endif
    </div>

    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">DISCOM</label>
      <select name="discom" id="lead-discom"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
        <option value="">Electricity board / DISCOM</option>
        <option value="MGVCL" selected>MGVCL (Madhya Gujarat Vij)</option>
        <option value="DGVCL">DGVCL (Dakshin Gujarat Vij)</option>
        <option value="PGVCL">PGVCL (Paschim Gujarat Vij)</option>
        <option value="UGVCL">UGVCL (Uttar Gujarat Vij)</option>
        <option value="Torrent Power">Torrent Power (Ahmedabad/Surat)</option>
        <option value="Tata Power">Tata Power</option>
        <option value="Adani Electricity">Adani Electricity</option>
        <option value="MSEDCL">MSEDCL (Maharashtra)</option>
        <option value="BESCOM">BESCOM (Karnataka)</option>
      </select>
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="discom"></p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <div>
      <label class="block text-[11px] font-bold text-slate-700 mb-1">Sanctioned Load (kW)</label>
      <input type="number" step="0.5" name="sanctioned_load" id="lead-sanctioned-load" placeholder="e.g. 5"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="sanctioned_load"></p>
      @if(isset($errors) && $errors->has('sanctioned_load'))
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('sanctioned_load') }}</span></p>
      @endif
    </div>
  </div>

  <!-- Tags -->
  <div class="space-y-1.5 pt-2 border-t border-slate-100">
    <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-700">
      <i class="fa-solid fa-tag text-amber-500 text-xs"></i>
      <span>Tags</span>
    </div>
    <div class="flex gap-2">
      <input type="text" id="lead-tags-input" placeholder="Type a tag and press Enter (e.g. Subsidy Eligible, RCC Roof, Urgent)"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      <button type="button" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs shrink-0 cursor-pointer">
        <i class="fa-solid fa-plus"></i>
      </button>
    </div>
    <div id="lead-tags-container" class="flex flex-wrap gap-1.5 pt-1">
      <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">
        PM Surya Ghar Eligible <button type="button" class="hover:text-rose-600">×</button>
      </span>
      <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">
        RCC Flat Roof <button type="button" class="hover:text-rose-600">×</button>
      </span>
    </div>
  </div>
</div>
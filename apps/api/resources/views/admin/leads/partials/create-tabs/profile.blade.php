{{-- Tab 1: Profile — Quick Lead Info & Indian Pincode Auto-Fill --}}
<div id="tab-content-profile" class="wizard-tab-content space-y-6">
  <!-- Quick Lead Info -->
  <div class="space-y-3">
    <div class="flex items-center gap-2 text-xs font-bold text-sky-800">
      <i class="fa-solid fa-phone text-sky-500"></i>
      <span>Quick Lead Info</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">First Name <span class="text-rose-500">*</span></label>
        <input type="text" name="first_name" id="lead-first-name" required placeholder="Enter first name"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="first_name"></p>
        @if(isset($errors) && $errors->has('first_name'))
          <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('first_name') }}</span></p>
        @endif
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Last Name</label>
        <input type="text" name="last_name" id="lead-last-name" placeholder="Enter last name"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="last_name"></p>
        @if(isset($errors) && $errors->has('last_name'))
          <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('last_name') }}</span></p>
        @endif
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Phone</label>
        <input type="tel" name="phone" id="lead-phone" placeholder="+91 XXXXX XXXXX"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="phone"></p>
        @if(isset($errors) && $errors->has('phone'))
          <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('phone') }}</span></p>
        @endif
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">WhatsApp</label>
        <input type="tel" name="whatsapp" id="lead-whatsapp" placeholder="+91 XXXXX XXXXX"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="whatsapp"></p>
        @if(isset($errors) && $errors->has('whatsapp'))
          <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('whatsapp') }}</span></p>
        @endif
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Alternate Phone</label>
        <input type="tel" name="alternate_phone" id="lead-alt-phone" placeholder="+91 XXXXX XXXXX"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="alternate_phone"></p>
        @if(isset($errors) && $errors->has('alternate_phone'))
          <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('alternate_phone') }}</span></p>
        @endif
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Email</label>
        <input type="email" name="email" id="lead-email" placeholder="email@example.com"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="email"></p>
        @if(isset($errors) && $errors->has('email'))
          <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('email') }}</span></p>
        @endif
      </div>
    </div>
  </div>

  <!-- Address -->
  <div class="space-y-3 pt-2 border-t border-slate-100">
    <div class="flex items-center gap-2 text-xs font-bold text-sky-800">
      <i class="fa-solid fa-location-dot text-sky-500"></i>
      <span>Address</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Address Line 1</label>
        <input type="text" name="address_line_1" id="lead-address-1" placeholder="Street address"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="address_line_1"></p>
        @if(isset($errors) && $errors->has('address_line_1'))
          <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('address_line_1') }}</span></p>
        @endif
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-700 mb-1">Address Line 2</label>
        <input type="text" name="address_line_2" id="lead-address-2" placeholder="Apartment, suite, society, etc."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition">
        <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 hidden" data-error-for="address_line_2"></p>
        @if(isset($errors) && $errors->has('address_line_2'))
          <p class="field-error-msg text-[11px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>{{ $errors->first('address_line_2') }}</span></p>
        @endif
      </div>
    </div>

    <!-- Pincode Auto-fill Card -->
    <div class="bg-sky-50/70 border border-sky-100 rounded-2xl p-3.5 space-y-2">
      <div class="flex items-center gap-1.5 text-[11px] font-bold text-sky-900">
        <i class="fa-solid fa-map-pin text-sky-600"></i>
        <span>Fill Pincode to auto-fill City & State</span>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5">
        <div>
          <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Pincode</label>
          <input type="text" name="pincode" id="lead-pincode" maxlength="6" placeholder="e.g., 390001"
            oninput="handlePincodeAutoFill(this.value)"
            class="w-full bg-white border border-sky-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 transition">
          <p class="field-error-msg text-[10px] font-medium text-rose-500 mt-1 hidden" data-error-for="pincode"></p>
          @if(isset($errors) && $errors->has('pincode'))
            <p class="field-error-msg text-[10px] font-medium text-rose-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[9px]"></i> <span>{{ $errors->first('pincode') }}</span></p>
          @endif
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-600 mb-0.5">City</label>
          <input type="text" name="city" id="lead-city" placeholder="Auto-filled"
            class="w-full bg-white/70 border border-sky-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none">
          <p class="field-error-msg text-[10px] font-medium text-rose-500 mt-1 hidden" data-error-for="city"></p>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-600 mb-0.5">State</label>
          <input type="text" name="state" id="lead-state" placeholder="Auto-filled"
            class="w-full bg-white/70 border border-sky-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none">
          <p class="field-error-msg text-[10px] font-medium text-rose-500 mt-1 hidden" data-error-for="state"></p>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Country</label>
          <input type="text" name="country" id="lead-country" value="India" readonly
            class="w-full bg-white/70 border border-sky-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none">
        </div>
      </div>
    </div>
  </div>
</div>
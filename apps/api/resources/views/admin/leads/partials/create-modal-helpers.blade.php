<script>
  // Pincode Auto-Fill Engine & Sizing Estimator
  const INDIA_PINCODES = {
    '390001': { city: 'Vadodara', state: 'Gujarat' }, '390007': { city: 'Vadodara', state: 'Gujarat' },
    '390011': { city: 'Vadodara', state: 'Gujarat' }, '390015': { city: 'Vadodara', state: 'Gujarat' },
    '390020': { city: 'Vadodara', state: 'Gujarat' }, '395001': { city: 'Surat', state: 'Gujarat' },
    '395007': { city: 'Surat', state: 'Gujarat' },    '380001': { city: 'Ahmedabad', state: 'Gujarat' },
    '380015': { city: 'Ahmedabad', state: 'Gujarat' },'360001': { city: 'Rajkot', state: 'Gujarat' },
    '364001': { city: 'Bhavnagar', state: 'Gujarat' },'388001': { city: 'Anand', state: 'Gujarat' },
    '392001': { city: 'Bharuch', state: 'Gujarat' },  '400001': { city: 'Mumbai', state: 'Maharashtra' },
    '411001': { city: 'Pune', state: 'Maharashtra' },  '110001': { city: 'New Delhi', state: 'Delhi' },
    '474012': { city: 'Gwalior', state: 'Madhya Pradesh' }
  };

  /**
   * Purpose: Auto-populate city & state based on standard Indian pincode.
   * Parameters: string pin
   */
  function handlePincodeAutoFill(pin) {
    const cleanPin = pin.trim();
    if (cleanPin.length === 6 && INDIA_PINCODES[cleanPin]) {
      document.getElementById('lead-city').value = INDIA_PINCODES[cleanPin].city;
      document.getElementById('lead-state').value = INDIA_PINCODES[cleanPin].state;
    }
  }

  /**
   * Purpose: Estimate solar load (kW) and budget based on monthly electric bill.
   * Parameters: number|string amount
   */
  function handleBillChange(amount) {
    const bill = parseFloat(amount) || 0;
    if (bill > 0) {
      const estimatedKw = Math.max(1, Math.round((bill / 850) * 2) / 2);
      document.getElementById('lead-sanctioned-load').value = estimatedKw;
      document.getElementById('lead-deal-size').value = estimatedKw * 55000;
    }
  }

  /**
   * Purpose: Display inline error badge and border on invalid input field.
   */
  function setFieldError(fieldName, msg) {
    const form = document.getElementById('form-create-solar-lead');
    if (!form) return;
    const input = form.querySelector(`[name="${fieldName}"]`);
    if (input) {
      input.classList.remove('border-slate-200', 'focus:border-brand-500');
      input.classList.add('border-rose-400', 'bg-rose-50/20', 'focus:border-rose-500');
      highlightTabWithErrors(input);
    }
    const errEl = form.querySelector(`[data-error-for="${fieldName}"]`);
    if (errEl) {
      errEl.innerHTML = `<i class="fa-solid fa-circle-exclamation text-[10px]"></i> <span>${msg}</span>`;
      errEl.classList.remove('hidden');
    }
  }

  function highlightTabWithErrors(input) {
    const tabContent = input.closest('.wizard-tab-content');
    if (tabContent) {
      const tabId = tabContent.id.replace('tab-content-', '');
      const badge = document.getElementById('tab-err-badge-' + tabId);
      if (badge) badge.classList.remove('hidden');
    }
  }

  function clearFieldError(fieldName) {
    const form = document.getElementById('form-create-solar-lead');
    if (!form) return;
    const input = form.querySelector(`[name="${fieldName}"]`);
    if (input) {
      input.classList.remove('border-rose-400', 'bg-rose-50/20', 'focus:border-rose-500');
      input.classList.add('border-slate-200', 'focus:border-brand-500');
      refreshTabBadgeState(input);
    }
    const errEl = form.querySelector(`[data-error-for="${fieldName}"]`);
    if (errEl) { errEl.innerHTML = ''; errEl.classList.add('hidden'); }
  }

  function refreshTabBadgeState(input) {
    const tabContent = input.closest('.wizard-tab-content');
    if (!tabContent) return;
    const hasOtherErrors = tabContent.querySelectorAll('.field-error-msg:not(.hidden)').length > 0;
    if (!hasOtherErrors) {
      const tabId = tabContent.id.replace('tab-content-', '');
      const badge = document.getElementById('tab-err-badge-' + tabId);
      if (badge) badge.classList.add('hidden');
    }
  }

  function clearAllLeadErrors() {
    const form = document.getElementById('form-create-solar-lead');
    if (!form) return;
    form.querySelectorAll('.field-error-msg').forEach(el => { el.innerHTML = ''; el.classList.add('hidden'); });
    form.querySelectorAll('input, select, textarea').forEach(inp => {
      inp.classList.remove('border-rose-400', 'bg-rose-50/20', 'focus:border-rose-500');
      inp.classList.add('border-slate-200');
    });
    ['profile', 'qualifiers', 'followup', 'info'].forEach(tab => {
      const badge = document.getElementById('tab-err-badge-' + tab);
      if (badge) badge.classList.add('hidden');
    });
  }

  function initLeadInputErrorWatchers() {
    const form = document.getElementById('form-create-solar-lead');
    if (!form || form._hasErrorWatchers) return;
    form.querySelectorAll('input, select, textarea').forEach(inp => {
      inp.addEventListener('input', () => { if (inp.name) clearFieldError(inp.name); });
      inp.addEventListener('change', () => { if (inp.name) clearFieldError(inp.name); });
    });
    form._hasErrorWatchers = true;
  }

  function validateLeadClient() {
    let isValid = true;
    let firstInvalid = null;

    const firstName = document.getElementById('lead-first-name');
    if (!firstName || !firstName.value.trim()) {
      setFieldError('first_name', 'First name is required to create a solar lead.');
      isValid = false;
      if (!firstInvalid) firstInvalid = 'first_name';
    }

    const email = document.getElementById('lead-email');
    if (email && email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
      setFieldError('email', 'Please enter a valid email address.');
      isValid = false;
      if (!firstInvalid) firstInvalid = 'email';
    }

    const pincode = document.getElementById('lead-pincode');
    if (pincode && pincode.value.trim() && pincode.value.trim().length !== 6) {
      setFieldError('pincode', 'Pincode must be exactly 6 digits.');
      isValid = false;
      if (!firstInvalid) firstInvalid = 'pincode';
    }

    if (!isValid && firstInvalid) focusFirstInvalidField(firstInvalid);
    return isValid;
  }

  function focusFirstInvalidField(fieldName) {
    const form = document.getElementById('form-create-solar-lead');
    const input = form.querySelector(`[name="${fieldName}"]`);
    if (!input) return;
    const tabContent = input.closest('.wizard-tab-content');
    if (tabContent) {
      const tabId = tabContent.id.replace('tab-content-', '');
      switchWizardTab(tabId);
    }
    input.focus();
  }

  function handleLeadServerErrors(errors) {
    let firstKey = null;
    for (const [key, msgs] of Object.entries(errors)) {
      const msg = Array.isArray(msgs) ? msgs[0] : msgs;
      setFieldError(key, msg);
      if (!firstKey) firstKey = key;
    }
    if (firstKey) focusFirstInvalidField(firstKey);
  }
</script>

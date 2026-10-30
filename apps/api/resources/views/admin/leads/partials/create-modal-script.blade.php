<script>
  let activeQuickGroupType = null;

  function switchWizardTab(tabKey) {
    document.querySelectorAll('.wizard-tab-content').forEach(el => el.classList.add('hidden'));
    const targetContent = document.getElementById('tab-content-' + tabKey);
    if (targetContent) targetContent.classList.remove('hidden');

    document.querySelectorAll('.wizard-nav-btn').forEach(btn => {
      btn.className = 'wizard-nav-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer';
    });
    const activeBtn = document.getElementById('wizard-nav-' + tabKey);
    if (activeBtn) {
      activeBtn.className = 'wizard-nav-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-sky-600 bg-sky-50 border border-sky-100 transition cursor-pointer';
    }
  }

  let ALL_CUSTOMER_GROUPS = [];

  function initCustomerGroupsStore() {
    const cgSelect = document.getElementById('lead-customer-group');
    if (!cgSelect || ALL_CUSTOMER_GROUPS.length > 0) return;
    Array.from(cgSelect.options).forEach(opt => {
      if (opt.value) {
        ALL_CUSTOMER_GROUPS.push({
          id: opt.getAttribute('data-id'),
          name: opt.value,
          product_group_id: opt.getAttribute('data-product-group-id')
        });
      }
    });
  }

  function openCreateLeadModal() {
    clearAllLeadErrors();
    document.getElementById('modal-create-lead').classList.remove('hidden');
    switchWizardTab('profile');
    initLeadInputErrorWatchers();
    initCustomerGroupsStore();
    const pgSelect = document.getElementById('lead-product-group');
    if (pgSelect) handleProductGroupChange(pgSelect);
  }

  function closeCreateLeadModal() {
    clearAllLeadErrors();
    document.getElementById('modal-create-lead').classList.add('hidden');
  }

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

  function openQuickAddGroupModal(groupType) {
    activeQuickGroupType = groupType;
    const isProd = groupType === 'product_group';
    document.getElementById('quick-add-group-title').innerText = isProd ? 'Add New Product Group' : 'Add New Customer Group';
    document.getElementById('quick-add-group-label').innerText = isProd ? 'Product Group Name' : 'Customer Group Name';
    const input = document.getElementById('quick-add-group-name');
    input.placeholder = isProd ? 'e.g., Commercial Rooftop 50kW+' : 'e.g., Educational Institutions';
    input.value = '';
    const err = document.getElementById('quick-add-group-error');
    err.classList.add('hidden');
    err.innerText = '';
    document.getElementById('modal-quick-add-group').classList.remove('hidden');
    setTimeout(() => input.focus(), 60);
  }

  function closeQuickAddGroupModal() {
    document.getElementById('modal-quick-add-group').classList.add('hidden');
    activeQuickGroupType = null;
  }

  /**
   * Purpose: Validate and save group to database table via AJAX.
   */
  async function saveQuickAddGroup() {
    const input = document.getElementById('quick-add-group-name');
    const val = input ? input.value.trim() : '';
    const err = document.getElementById('quick-add-group-error');
    if (!val) {
      if (err) { err.innerText = 'Please enter a name.'; err.classList.remove('hidden'); }
      return;
    }

    const isProd = activeQuickGroupType === 'product_group';
    const url = isProd ? '/admin/solar-product-groups' : '/admin/solar-customer-groups';
    const payload = { name: val };
    if (!isProd) {
      const pgId = document.getElementById('lead-product-group-id')?.value;
      if (pgId) payload.product_group_id = pgId;
    }

    try {
      const token = $('meta[name="csrf-token"]').attr('content');
      const res = await axios.post(url, payload, {
        baseURL: '',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': token
        }
      });
      const data = res.data?.data || { id: Date.now(), name: val };
      attachGroupToDropdown(activeQuickGroupType, data);
      closeQuickAddGroupModal();
      if (typeof window.showToast === 'function') window.showToast('success', `${val} saved to database!`);
    } catch (e) {
      const msg = e.response?.data?.errors?.name?.[0] || e.response?.data?.message || 'Failed to save group.';
      if (err) { err.innerText = msg; err.classList.remove('hidden'); }
    }
  }

  function attachGroupToDropdown(type, data) {
    const isProd = type === 'product_group';
    const select = document.getElementById(isProd ? 'lead-product-group' : 'lead-customer-group');
    if (!select) return;

    if (isProd) {
      const opt = new Option(data.name, data.name, true, true);
      opt.setAttribute('data-id', data.id);
      select.add(opt);
      select.value = data.name;
      document.getElementById('lead-product-group-id').value = data.id;
      handleProductGroupChange(select);
    } else {
      initCustomerGroupsStore();
      ALL_CUSTOMER_GROUPS.push({ id: data.id, name: data.name, product_group_id: data.product_group_id });
      const opt = new Option(data.name, data.name, true, true);
      opt.setAttribute('data-id', data.id);
      if (data.product_group_id) opt.setAttribute('data-product-group-id', data.product_group_id);
      select.add(opt);
      select.value = data.name;
      document.getElementById('lead-customer-group-id').value = data.id;
    }
    clearFieldError(type);
  }

  /**
   * Purpose: Cross-browser relational filter — rebuilds customer group options for active product group.
   */
  function handleProductGroupChange(selectEl) {
    initCustomerGroupsStore();
    const opt = selectEl.options[selectEl.selectedIndex];
    const pgId = opt ? opt.getAttribute('data-id') : '';
    document.getElementById('lead-product-group-id').value = pgId || '';

    const cgSelect = document.getElementById('lead-customer-group');
    if (!cgSelect) return;

    const currentVal = cgSelect.value;
    cgSelect.innerHTML = '<option value="">Select customer group</option>';
    let matchedOpt = null;

    ALL_CUSTOMER_GROUPS.forEach(cg => {
      if (!cg.product_group_id || String(cg.product_group_id) === String(pgId)) {
        const o = new Option(cg.name, cg.name);
        o.setAttribute('data-id', cg.id || '');
        if (cg.product_group_id) o.setAttribute('data-product-group-id', cg.product_group_id);
        cgSelect.add(o);
        if (cg.name === currentVal) matchedOpt = o;
        if (!matchedOpt) matchedOpt = o;
      }
    });

    if (matchedOpt) {
      cgSelect.value = matchedOpt.value;
      handleCustomerGroupChange(cgSelect);
    } else {
      document.getElementById('lead-customer-group-id').value = '';
    }
  }

  function handleCustomerGroupChange(selectEl) {
    const opt = selectEl.options[selectEl.selectedIndex];
    const cgId = opt ? opt.getAttribute('data-id') : '';
    document.getElementById('lead-customer-group-id').value = cgId || '';
  }

  async function submitCreateLeadForm() {
    clearAllLeadErrors();
    if (!validateLeadClient()) return;

    const form = document.getElementById('form-create-solar-lead');
    const btn = document.querySelector('button[onclick="submitCreateLeadForm()"]');
    const formData = new FormData(form);

    const a1 = document.getElementById('lead-assigned-to');
    const a2 = document.getElementById('lead-info-assigned-to');
    const val = (a1 && a1.value) ? a1.value : (a2 ? a2.value : '');
    if (val) formData.set('assigned_to_user_id', val);

    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating...';
    }

    try {
      const res = await axios.post('/admin/leads', formData, {
        baseURL: '',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (res.data && res.data.success) {
        if (typeof window.showToast === 'function') window.showToast('success', res.data.message || 'Lead registered!');
        closeCreateLeadModal();
        setTimeout(() => location.reload(), 500);
      } else {
        form.submit();
      }
    } catch(err) {
      if (err.response && err.response.status === 422 && err.response.data.errors) {
        handleLeadServerErrors(err.response.data.errors);
      } else {
        const msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Failed to create lead.';
        alert(msg);
      }
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Create Lead';
      }
    }
  }
</script>
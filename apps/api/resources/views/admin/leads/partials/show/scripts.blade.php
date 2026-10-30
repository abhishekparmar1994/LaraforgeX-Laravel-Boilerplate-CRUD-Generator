<script>
async function updateLeadField(field, val) {
  try {
    const data = {};
    data[field] = val;

    const res = await axios.put('/admin/leads/{{ $lead->id }}', data, { baseURL: '' });
    if (res.data?.success) {
      if (window.showToast) {
        window.showToast('success', 'Lead ' + field.replace(/_/g, ' ') + ' updated.');
      }

      if (field === 'status') {
        const stageBtns = document.querySelectorAll('.stage-btn');
        const stages = ['new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won'];
        const targetIdx = stages.indexOf(val);

        stageBtns.forEach((btn, idx) => {
          btn.className = 'stage-btn relative group py-2.5 px-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 transition-all cursor-pointer border';
          const badge = btn.querySelector('span');

          if (idx === targetIdx) {
            btn.classList.add('bg-amber-600', 'text-white', 'border-amber-600', 'shadow-md', 'shadow-amber-600/25', 'ring-2', 'ring-amber-500/20');
            badge.className = 'w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black bg-white text-amber-600';
            badge.innerHTML = (idx + 1);
          } else if (idx < targetIdx) {
            btn.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'hover:bg-emerald-100');
            badge.className = 'w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black bg-emerald-600 text-white';
            badge.innerHTML = '<i class="fa-solid fa-check text-[9px]"></i>';
          } else {
            btn.classList.add('bg-white', 'hover:bg-slate-100', 'text-slate-600', 'border-slate-200');
            badge.className = 'w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black bg-slate-100 text-slate-500';
            badge.innerHTML = (idx + 1);
          }
        });
      }
    }
  } catch (err) {
    console.error('Lead update error:', err);
    if (window.showToast) window.showToast('error', 'Failed to update field.');
  }
}

function openConvertModal() {
  const el = document.getElementById('modal-convert-lead');
  if (el) el.classList.remove('hidden');
}

function closeConvertModal() {
  const el = document.getElementById('modal-convert-lead');
  if (el) el.classList.add('hidden');
}

function openFollowupModal() {
  const el = document.getElementById('modal-add-lead-followup');
  if (el) el.classList.remove('hidden');
}

function closeFollowupModal() {
  const el = document.getElementById('modal-add-lead-followup');
  if (el) el.classList.add('hidden');
}

function openEditFollowupModal(id, type, datetime, assignedId, notes, status) {
  const form = document.getElementById('editFollowupForm');
  if (form) form.action = "/admin/followups/" + id;
  const typeEl = document.getElementById('edit_fup_type');
  if (typeEl && type) typeEl.value = type;
  const dtEl = document.getElementById('edit_fup_datetime');
  if (dtEl && datetime) dtEl.value = datetime;
  const assEl = document.getElementById('edit_fup_assigned');
  if (assEl) assEl.value = assignedId || '';
  const noteEl = document.getElementById('edit_fup_notes');
  if (noteEl) noteEl.value = notes || '';
  const stEl = document.getElementById('edit_fup_status');
  if (stEl && status) stEl.value = status;
  
  const modal = document.getElementById('modal-edit-lead-followup');
  if (modal) modal.classList.remove('hidden');
}

function closeEditFollowupModal() {
  const modal = document.getElementById('modal-edit-lead-followup');
  if (modal) modal.classList.add('hidden');
}

async function markFollowupComplete(id) {
  try {
    const res = await axios.post('/admin/followups/' + id + '/complete');
    if (res.data?.success) {
      const badge = document.getElementById('fup-badge-' + id);
      if (badge) {
        badge.className = 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
        badge.innerText = 'Completed';
      }
      const actions = document.getElementById('fup-actions-' + id);
      if (actions) actions.remove();
      if (window.showToast) window.showToast('success', 'Follow-up marked completed.');
    }
  } catch (err) {
    console.error('Follow-up completion error:', err);
    if (window.showToast) window.showToast('error', 'Failed to update follow-up.');
  }
}

function deleteFollowup(id) {
  window.confirmDelete({
    title: 'Delete Follow-up?',
    text: 'Are you sure you want to delete this scheduled solar follow-up?'
  }, async () => {
    try {
      const res = await axios.delete('/admin/followups/' + id, { baseURL: '' });
      if (res.data?.success) {
        const item = document.getElementById('followup-item-' + id);
        if (item) item.remove();
        if (window.showToast) window.showToast('success', 'Follow-up deleted.');
      }
    } catch (err) {
      if (window.showToast) window.showToast('error', 'Failed to delete follow-up.');
    }
  });
}

/**
 * Open Solar Sizing & PM Surya Ghar Subsidy edit modal.
 */
function openSolarSizingModal() {
  const modal = document.getElementById('modal-edit-solar-sizing');
  if (modal) {
    modal.classList.remove('hidden');
    calcSolarMetrics();
  }
}

/**
 * Close Solar Sizing edit modal.
 */
function closeSolarSizingModal() {
  const modal = document.getElementById('modal-edit-solar-sizing');
  if (modal) modal.classList.add('hidden');
}

/**
 * Apply preset sizing values (3kW, 5kW, 10kW, etc.)
 */
function applySizingPreset(kw, budget, subsidy) {
  const kwEl = document.getElementById('modal_kw');
  const bEl = document.getElementById('modal_budget');
  const sEl = document.getElementById('modal_subsidy');
  if (kwEl) kwEl.value = kw;
  if (bEl) bEl.value = budget;
  if (sEl) sEl.value = subsidy;
  calcSolarMetrics();
}

/**
 * Re-calculate solar ROI metrics in real time in the modal.
 */
function calcSolarMetrics() {
  const kw = parseFloat(document.getElementById('modal_kw')?.value) || 3.0;
  let budget = parseFloat(document.getElementById('modal_budget')?.value) || (kw * 55000);
  let subsidy = parseFloat(document.getElementById('modal_subsidy')?.value);

  if (isNaN(subsidy)) {
    subsidy = kw <= 1.0 ? 30000 : (kw <= 2.0 ? 60000 : 78000);
    const subEl = document.getElementById('modal_subsidy');
    if (subEl) subEl.value = subsidy;
  }

  const net = Math.max(0, budget - subsidy);
  const netPreview = document.getElementById('modal_net_preview');
  if (netPreview) netPreview.value = net.toLocaleString('en-IN');

  const units = Math.round(kw * 1400);
  const savings = Math.round(units * 7.5);
  const payback = savings > 0 ? (net / savings).toFixed(1) : 3.0;

  const uEl = document.getElementById('preview_units');
  const sEl = document.getElementById('preview_savings');
  const pEl = document.getElementById('preview_payback');
  if (uEl) uEl.innerText = units.toLocaleString('en-IN') + ' Units';
  if (sEl) sEl.innerText = '₹' + savings.toLocaleString('en-IN') + '/yr';
  if (pEl) pEl.innerText = '~' + payback + ' Years';
}

/**
 * Submit updated solar sizing & subsidy parameters via AJAX.
 */
async function saveSolarSizing(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSaveSizing');
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...';
  }

  try {
    const payload = {
      solar_capacity_kw: parseFloat(document.getElementById('modal_kw')?.value) || 3.0,
      estimated_budget: parseFloat(document.getElementById('modal_budget')?.value) || 0,
      subsidy_amount: parseFloat(document.getElementById('modal_subsidy')?.value) || 0,
      monthly_bill: parseFloat(document.getElementById('modal_monthly_bill')?.value) || 0,
      sanctioned_load: parseFloat(document.getElementById('modal_sanctioned_load')?.value) || 0,
      consumer_number: document.getElementById('modal_consumer_number')?.value || '',
      discom: document.getElementById('modal_discom')?.value || 'MGVCL',
    };

    const res = await axios.put('/admin/leads/{{ $lead->id }}', payload, { baseURL: '' });
    if (res.data?.success) {
      if (window.showToast) window.showToast('success', 'Solar sizing and subsidy updated!');
      closeSolarSizingModal();
      setTimeout(() => window.location.reload(), 400);
    }
  } catch (err) {
    console.error('Save sizing error:', err);
    if (window.showToast) window.showToast('error', 'Failed to update solar sizing.');
    if (btn) {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Sizing & Update';
    }
  }
}
</script>

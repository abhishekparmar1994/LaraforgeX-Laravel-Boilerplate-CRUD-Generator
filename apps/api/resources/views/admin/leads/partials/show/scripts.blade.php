<script>
async function updateLeadField(field, val) {
  try {
    const data = {};
    data[field] = val;

    const res = await axios.put('/admin/leads/{{ $lead->id }}', data);
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

async function deleteFollowup(id) {
  if (!confirm('Are you sure you want to delete this scheduled follow-up?')) return;
  try {
    const res = await axios.delete('/admin/followups/' + id);
    if (res.data?.success) {
      const item = document.getElementById('followup-item-' + id);
      if (item) item.remove();
      if (window.showToast) window.showToast('success', 'Follow-up deleted.');
    }
  } catch (err) {
    console.error('Delete follow-up error:', err);
    if (window.showToast) window.showToast('error', 'Failed to delete follow-up.');
  }
}
</script>

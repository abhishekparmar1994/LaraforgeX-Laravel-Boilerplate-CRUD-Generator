<script>
/**
 * Helper: Escape strings for safe HTML attribute and text injection in cards.
 */
function escapeCardHtml(str) {
  return String(str || '').replace(/[&<>"']/g, m => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' })[m]);
}

/**
 * Purpose: Render 3-column solar lead cards dynamically from AJAX leads payload.
 * Parameters: leads (Array)
 */
function renderCardsGrid(leads) {
  const $grid = $('#leads-cards-grid').empty();
  if (!leads || !leads.length) {
    $grid.html('<div class="col-span-3 p-8 text-center bg-white border border-slate-200 rounded-2xl"><i class="fa-solid fa-solar-panel text-amber-400 text-3xl mb-2"></i><p class="text-xs font-bold text-slate-700">No solar leads match active filters.</p></div>');
    return;
  }

  const cardsHtml = leads.map(buildLeadCardHtml).join('');
  $grid.html(cardsHtml);
}

/**
 * Purpose: Build card HTML for a single lead.
 * Parameters: lead (Object)
 * Return: string (HTML)
 */
function buildLeadCardHtml(lead) {
  const initial = (lead.company_name || 'S').charAt(0).toUpperCase();
  const rawPhone = String(lead.phone || '').replace(/\D/g, '');
  const waPhone = rawPhone.length === 10 ? '91' + rawPhone : rawPhone;
  const rating = lead.rating && lead.rating !== 'N/A' ? lead.rating : '4.5';
  const reviews = lead.total_reviews && lead.total_reviews !== '0' ? `(${lead.total_reviews})` : '(12)';
  const stageColors = {
    new: 'bg-sky-50 text-sky-700 border-sky-200',
    contacted: 'bg-amber-50 text-amber-700 border-amber-200',
    qualified: 'bg-blue-50 text-blue-700 border-blue-200',
    proposal_sent: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    negotiation: 'bg-purple-50 text-purple-700 border-purple-200',
    won: 'bg-emerald-600 text-white border-emerald-600',
    lost: 'bg-rose-50 text-rose-700 border-rose-200'
  };
  const stageLabels = {
    new: 'NEW LEAD',
    contacted: 'SITE VISITED',
    qualified: 'QUALIFIED',
    proposal_sent: 'ESTIMATE SEND',
    negotiation: 'DOCUMENT',
    won: 'COMMISSIONED',
    lost: 'LOST'
  };
  const stKey = (lead.status || 'new').toLowerCase();
  const stageClass = stageColors[stKey] || 'bg-slate-100 text-slate-700 border-slate-200';
  const stageText = stageLabels[stKey] || (lead.status || 'NEW').toUpperCase();
  const mapUrl = lead.google_maps_link || `https://maps.google.com/?q=${encodeURIComponent((lead.company_name || '') + ' ' + (lead.location || 'Vadodara'))}`;
  const location = lead.area_locality || lead.location || 'Vadodara, Gujarat';
  const repName = lead.assigned_developer ? lead.assigned_developer.name : 'Unassigned';

  return `<div class="bg-white border border-slate-200 hover:border-brand-400 rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all duration-150 flex flex-col justify-between space-y-3 group">
    <div class="flex items-start justify-between gap-2">
      <div class="flex items-center gap-2.5 min-w-0">
        <span class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-amber-50 group-hover:text-amber-600 text-slate-700 font-extrabold text-xs flex items-center justify-center shrink-0 transition">${initial}</span>
        <div class="min-w-0">
          <a href="/admin/leads/${lead.id}" class="font-extrabold text-xs text-slate-900 group-hover:text-brand-600 transition block truncate" title="${lead.company_name}">${lead.company_name}</a>
          <div class="flex items-center gap-1.5 mt-0.5">
            <span class="text-[11px] font-bold text-amber-500 flex items-center gap-0.5"><i class="fa-solid fa-star text-[9px]"></i> ${rating}</span>
            <span class="text-[10px] text-slate-400">${reviews}</span>
          </div>
        </div>
      </div>
      <span class="px-2 py-0.5 rounded-lg border text-[9px] font-extrabold tracking-wider uppercase shrink-0 ${stageClass}">${stageText}</span>
    </div>
    <div class="space-y-1 text-xs">
      <div class="flex items-center justify-between text-[11px] text-slate-600">
        <span class="truncate">${location}</span>
        <a href="${mapUrl}" target="_blank" rel="noopener" class="text-brand-600 hover:underline font-extrabold text-[10px] shrink-0 ml-1">MAP</a>
      </div>
      <div class="flex items-center justify-between pt-1">
        <span class="text-[11px] font-bold text-slate-800">${lead.phone && lead.phone !== 'N/A' ? `<a href="tel:${lead.phone}" class="hover:text-brand-600">${lead.phone}</a>` : '<span class="text-slate-400 font-normal">No Phone</span>'}</span>
        ${waPhone ? `<a href="https://wa.me/${waPhone}" target="_blank" rel="noopener" class="text-emerald-500 hover:text-emerald-600 transition text-xs" title="WhatsApp Chat"><i class="fa-brands fa-whatsapp text-sm"></i></a>` : ''}
      </div>
    </div>
    <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold text-slate-400">
      <span class="flex items-center gap-1"><i class="fa-regular fa-clock text-[9px]"></i> Active</span>
      <div class="flex items-center gap-2">
        <span class="flex items-center gap-1 text-slate-600 truncate max-w-[120px]"><i class="fa-regular fa-user text-[9px]"></i> ${repName}</span>
        <button type="button" onclick="confirmDeleteLead('${lead.id}', '${escapeCardHtml(lead.company_name).replace(/'/g, "\\'")}')" class="text-slate-400 hover:text-rose-600 transition cursor-pointer p-0.5" title="Delete Lead"><i class="fa-regular fa-trash-can text-xs"></i></button>
      </div>
    </div>
  </div>`;
}
</script>

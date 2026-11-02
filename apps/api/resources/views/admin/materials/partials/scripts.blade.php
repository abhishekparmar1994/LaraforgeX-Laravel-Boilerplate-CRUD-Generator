{{-- ── MATERIALS CATALOG CLIENT ENGINE ────────────────────────── --}}
<script>
/**
 * Purpose: Open interactive view modal and populate with material specifications.
 * Parameters: Object data
 */
function openViewMaterialModal(data) {
  if (!data) return;

  const purchase = parseFloat(data.purchase_price) || 0;
  const selling = parseFloat(data.selling_price) || 0;
  const margin = selling - purchase;
  const marginPct = purchase > 0 ? ((margin / purchase) * 100).toFixed(1) : '0';
  const gst = parseFloat(data.gst_percent) || 0;
  const totalWithGst = selling * (1 + (gst / 100));
  const unit = data.unit || 'NOS';
  const stock = parseInt(data.in_stock) || 0;

  $('#vm-name').text(data.name || 'Unnamed Material');
  $('#vm-model').text(data.model_number ? `Model: ${data.model_number}` : 'Model: N/A');
  $('#vm-category-badge').text(data.category ? data.category.title : 'General Equipment');
  $('#vm-brand-badge').text(data.company ? `${data.company.name} (${data.company.brand_tier || 'Standard'})` : 'Universal Brand');
  
  const stockClass = stock > 20 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200';
  $('#vm-stock-badge').attr('class', `px-2 py-0.5 rounded-full text-[10px] font-black border ${stockClass}`).text(`${stock} ${unit} In Stock`);

  $('#vm-specs').text(data.specifications || 'Standard specifications as per manufacturer datasheet and SolarMitra guidelines.');
  $('#vm-purchase').text(`₹${purchase.toLocaleString('en-IN', { minimumFractionDigits: 2 })}`);
  $('#vm-selling').text(`₹${selling.toLocaleString('en-IN', { minimumFractionDigits: 2 })}`);
  $('#vm-margin').text(`₹${margin.toLocaleString('en-IN', { minimumFractionDigits: 2 })}`);
  $('#vm-margin-pct').text(`${margin >= 0 ? '+' : ''}${marginPct}% margin`);
  $('#vm-total-selling').text(`₹${totalWithGst.toLocaleString('en-IN', { minimumFractionDigits: 2 })}`);
  $('#vm-gst').text(`${gst}% GST`);
  $('#vm-stock').text(`${stock} ${unit}`);
  $('#vm-warranty').text(`${data.warranty_years || 0} Years`);
  $('#vm-unit').text(unit);

  $('#vm-btn-delete').off('click').on('click', function() {
    confirmDeleteMaterial(data.id, data.name);
  });

  $('#modal-view-material').removeClass('hidden');
}

/**
 * Purpose: Confirm and execute material item deletion via SweetAlert2.
 * Parameters: string id, string name
 */
function confirmDeleteMaterial(id, name) {
  if (typeof window.confirmDelete === 'function') {
    window.confirmDelete({
      title: 'Delete Material Item?',
      text: `Are you sure you want to delete "${name}" from the catalog? This cannot be undone.`,
      confirmText: 'Yes, delete item',
    }, async () => {
      try {
        const res = await axios.delete(`/admin/materials/${id}`, { baseURL: '' });
        if (typeof window.showToast === 'function') window.showToast('success', res.data?.message || 'Material deleted successfully');
        setTimeout(() => location.reload(), 400);
      } catch (err) {
        if (typeof window.showToast === 'function') window.showToast('error', 'Failed to delete material.');
      }
    });
  } else if (confirm(`Delete material "${name}"?`)) {
    axios.delete(`/admin/materials/${id}`, { baseURL: '' }).then(() => location.reload());
  }
}
</script>


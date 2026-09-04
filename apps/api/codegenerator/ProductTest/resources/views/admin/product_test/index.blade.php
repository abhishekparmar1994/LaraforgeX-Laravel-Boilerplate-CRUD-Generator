@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Product Test Module')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Product Test</span>
</nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Product Test Management</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Manage, search, filter and export Product Test records.</p>
    </div>
    <button id="btn-create-record"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs transition shadow-sm shadow-brand-600/20">
      <i class="fa-solid fa-plus"></i> Add New ProductTest
    </button>
  </div>

  <div id="datatable-product_test"></div>
</div>

<!-- Modal Form -->
<div id="modal-product_test" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <form id="form-product_test" class="relative bg-white border border-slate-200 rounded-2xl w-full max-w-lg p-6 shadow-2xl space-y-5 font-sans">
      <input type="hidden" id="record-id">
      <h3 class="font-bold text-lg text-slate-900" id="modal-title">Create ProductTest</h3>
      <div class="space-y-4" id="form-fields-container">
        <!-- Rendered controls -->
      </div>
      <div class="flex gap-3 pt-1">
        <button type="button" class="close-modal w-1/2 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-500 text-xs font-semibold">Cancel</button>
        <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-brand-600 text-white text-xs font-bold">Save ProductTest</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
  const table = new AdminTable({
    container: '#datatable-product_test',
    columns: [
      { key: 'name', label: 'Product Name', sortable: true },
      { key: 'price', label: 'Price', sortable: true },
      { key: 'actions', label: 'Actions', sortable: false, class: 'text-right' }
    ],
    fetch: async () => {
      const res = await axios.get('/product_test');
      return res.data.data;
    },
    row: (record) => `
      <tr class="hover:bg-slate-50/60 transition">
        <td class="px-5 py-4 text-sm font-semibold text-slate-900">${record.id}</td>
        <td class="px-5 py-4 text-right">
          <button onclick="editRecord('${record.id}')" class="px-2.5 py-1 rounded bg-white border border-slate-200 text-xs font-semibold">Edit</button>
          <button onclick="deleteRecord('${record.id}')" class="px-2.5 py-1 rounded bg-rose-50 border border-rose-100 text-rose-600 text-xs font-semibold">Delete</button>
        </td>
      </tr>
    `
  });
  table.load();
});
</script>
@endsection
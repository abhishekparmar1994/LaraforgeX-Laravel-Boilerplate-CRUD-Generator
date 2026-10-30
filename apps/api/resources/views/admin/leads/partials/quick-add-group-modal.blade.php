{{-- Quick Add Modal for Product Group & Customer Group --}}
<div id="modal-quick-add-group" class="fixed inset-0 flex items-center justify-center bg-slate-950/70 backdrop-blur-xs p-4 hidden" style="z-index: 99999;">
  <div class="bg-white rounded-2xl max-w-md w-full p-5 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
          <i class="fa-solid fa-plus"></i>
        </span>
        <h4 id="quick-add-group-title" class="font-extrabold text-sm text-slate-900">Add New Group</h4>
      </div>
      <button type="button" onclick="closeQuickAddGroupModal()" class="text-slate-400 hover:text-slate-700 w-7 h-7 rounded-lg flex items-center justify-center hover:bg-slate-50 transition cursor-pointer">
        <i class="fa-solid fa-xmark text-sm"></i>
      </button>
    </div>

    <div class="space-y-1.5">
      <label id="quick-add-group-label" class="block text-[11px] font-bold text-slate-700">Group Name</label>
      <input type="text" id="quick-add-group-name" placeholder="Enter name"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition"
        onkeydown="if(event.key === 'Enter'){ event.preventDefault(); saveQuickAddGroup(); }">
      <p id="quick-add-group-error" class="text-[11px] font-medium text-rose-500 hidden"></p>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
      <button type="button" onclick="closeQuickAddGroupModal()" class="px-3.5 py-1.5 text-slate-600 hover:text-slate-900 font-bold text-xs rounded-xl hover:bg-slate-50 transition cursor-pointer">
        Cancel
      </button>
      <button type="button" onclick="saveQuickAddGroup()" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-xs transition cursor-pointer">
        <i class="fa-solid fa-check"></i> Save & Select
      </button>
    </div>
  </div>
</div>

@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Roles & Permissions')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-400 hover:text-slate-900 transition">Access Control</span>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Roles Matrix</span>
</nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Hierarchical Roles & Permissions</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Configure role hierarchies and map Spatie permissions.</p>
    </div>
    <button id="btn-create-role" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition shadow-sm shadow-indigo-600/10 whitespace-nowrap">
      <i class="fa-solid fa-plus"></i> Create Custom Role
    </button>
  </div>

  <!-- ── Filter Bar ──────────────────────────────────────────────── -->
  <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
      <!-- Name Search -->
      <div class="space-y-1">
        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Role Name</label>
        <input id="filter-role-name" type="text" placeholder="Search role name..."
               class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brand-500 transition">
      </div>
      <!-- Has Parent -->
      <div class="space-y-1">
        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Inheritance</label>
        <select id="filter-role-parent"
                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 transition">
          <option value="">All Roles</option>
          <option value="yes">Has Parent Role</option>
          <option value="no">Root Roles Only</option>
        </select>
      </div>
      <!-- Reset -->
      <div class="flex items-end">
        <button id="btn-reset-role-filters"
                class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">
          <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filters
        </button>
      </div>
    </div>
  </div>

  <!-- DataTable mount point -->
  <div id="roles-datatable"></div>
</div>

<!-- ============================================== -->
<!-- ROLE CREATION / EDITING MODAL                  -->
<!-- ============================================== -->
<div id="modal-role" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <form id="form-role" class="relative bg-white border border-slate-200 rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-5 font-sans">
      <input type="hidden" id="role-edit-id">
      <h3 class="font-bold text-lg text-slate-900" id="role-modal-title">Create Custom Role</h3>
      
      <div class="space-y-4">
        <div class="space-y-1">
          <label for="role-name" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Role Title Name</label>
          <input id="role-name" type="text" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
        </div>
        <div class="space-y-1">
          <label for="role-description" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Description</label>
          <textarea id="role-description" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2 text-sm text-slate-900 focus:outline-none focus:border-brand-500 h-20 transition"></textarea>
        </div>
        <div class="space-y-1">
          <label for="role-parent" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Parent Role (Inheritance)</label>
          <select id="role-parent" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
            <option value="">No Parent (Root Role)</option>
          </select>
        </div>
        <!-- Permissions Grid checkboxes -->
        <div class="space-y-2 border-t border-slate-100 pt-3">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Assign RBAC Permissions</label>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-48 overflow-y-auto" id="role-permissions-container"></div>
        </div>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="button" class="close-modal w-1/2 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">Cancel</button>
        <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition">Save Role Settings</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let rolesList = [];
    let permissionsList = [];

    // ── Row Renderer ───────────────────────────────────────────
    function roleRow(role) {
        const permBadges = (role.permissions || []).map(p =>
            `<span class="px-2 py-0.5 rounded bg-slate-50 border border-slate-200 text-xs text-slate-500 whitespace-nowrap">${p.name}</span>`
        ).join(' ') || '<span class="text-slate-400 italic text-xs">Direct permissions undefined</span>';

        const parentName = role.parent ? role.parent.name : '<span class="text-slate-400 italic">None</span>';
        const isSystemRole = ['administrator', 'educator', 'student'].includes(role.name);

        const actions = isSystemRole ? '' : `
            <button onclick="deleteRole('${role.id}')" class="px-2.5 py-1.5 rounded bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 font-semibold text-xs transition">Delete</button>
        `;

        return `
          <tr class="hover:bg-slate-50/60 transition">
            <td class="px-5 py-4 font-bold text-slate-900">${role.name}</td>
            <td class="px-5 py-4 text-xs text-slate-500 font-medium">${role.description || 'N/A'}</td>
            <td class="px-5 py-4 font-semibold text-slate-600">${parentName}</td>
            <td class="px-5 py-4"><div class="flex flex-wrap gap-1 max-w-md">${permBadges}</div></td>
            <td class="px-5 py-4">
              <div class="flex justify-end gap-1.5">
                <button onclick="editRole('${role.id}')" class="px-2.5 py-1.5 rounded bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-xs transition">Edit</button>
                ${actions}
              </div>
            </td>
          </tr>
        `;
    }

    // ── Init AdminTable ────────────────────────────────────────
    const rolesTable = new AdminTable({
        container: '#roles-datatable',
        columns: [
            { key: 'name',        label: 'Role Title',                sortable: true },
            { key: 'description', label: 'Description',               sortable: true,  responsive: 'sm' },
            { key: 'parent_id',   label: 'Parent Role (Inheritance)', sortable: false, responsive: 'md' },
            { key: 'permissions', label: 'Active Permissions',        sortable: false, responsive: 'lg' },
            { key: 'actions',     label: 'Actions',                   sortable: false, class: 'text-right' }
        ],
        fetch: async () => {
            const params = {};
            const name      = $('#filter-role-name').val().trim();
            const hasParent = $('#filter-role-parent').val();
            if (name)      params.name       = name;
            if (hasParent) params.has_parent = hasParent;

            const response = await axios.get('/roles', { params });
            rolesList = response.data.data.roles;
            permissionsList = response.data.data.permissions;

            // Populate parent selector options dynamically
            let parentOptions = '<option value="">No Parent (Root Role)</option>';
            rolesList.forEach(r => {
                parentOptions += `<option value="${r.id}">${r.name}</option>`;
            });
            $('#role-parent').html(parentOptions);

            return rolesList;
        },
        row: roleRow
    });

    rolesTable.load();

    // ── Server-Side Filter Logic ──────────────────────────────────
    let _roleFilterTimer;
    $('#filter-role-name').on('input', () => {
      clearTimeout(_roleFilterTimer);
      _roleFilterTimer = setTimeout(() => rolesTable.reload(), 400);
    });
    $('#filter-role-parent').on('change', () => rolesTable.reload());

    $('#btn-reset-role-filters').on('click', () => {
      $('#filter-role-name').val('');
      $('#filter-role-parent').val('');
      rolesTable.reload();
    });

    // ── Actions & Helpers ──────────────────────────────────────
    window.deleteRole = function(id) {
        Swal.fire({
            title: 'Delete Custom Role?',
            text: 'System permissions mapped to this profile will be detached.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Confirm deletion',
            background: '#ffffff',
            color: '#0f172a'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await axios.delete(`/roles/${id}`);
                    showToast('success', 'Role deleted successfully.');
                    rolesTable.reload();
                } catch (e) {
                    handleAjaxError(e);
                }
            }
        });
    }

    $('#btn-create-role').click(function() {
        $('#role-modal-title').text('Create Custom Role');
        $('#role-edit-id').val('');
        $('#role-name').val('').prop('readonly', false);
        $('#role-description').val('');
        $('#role-parent').val('');
        
        buildPermissionsCheckboxList([]);
        $('#modal-role').removeClass('hidden');
    });

    window.editRole = async function(id) {
        try {
            const role = rolesList.find(r => r.id === id);

            $('#role-modal-title').text('Edit Role Parameters');
            $('#role-edit-id').val(role.id);
            $('#role-name').val(role.name);
            if (['administrator', 'educator', 'student'].includes(role.name)) {
                $('#role-name').prop('readonly', true);
            } else {
                $('#role-name').prop('readonly', false);
            }
            $('#role-description').val(role.description);
            $('#role-parent').val(role.parent_id || '');

            const mappedPerms = role.permissions.map(p => p.name);
            buildPermissionsCheckboxList(mappedPerms);
            $('#modal-role').removeClass('hidden');
        } catch (e) {
            handleAjaxError(e);
        }
    }

    function buildPermissionsCheckboxList(rolePerms) {
        let html = '';
        permissionsList.forEach(perm => {
            const checked = rolePerms.includes(perm.name) ? 'checked' : '';
            html += `
              <label class="flex items-center text-xs text-slate-700 font-semibold cursor-pointer gap-2">
                <input type="checkbox" name="role_permissions[]" value="${perm.name}" ${checked} class="rounded border-slate-300 text-brand-600 focus:ring-0">
                ${perm.name}
              </label>
            `;
        });
        $('#role-permissions-container').html(html || '<span class="text-slate-400 italic text-xs">Permissions list empty</span>');
    }

    $('#form-role').submit(async function(e) {
        e.preventDefault();
        
        const id = $('#role-edit-id').val();
        const payload = {
            name: $('#role-name').val(),
            description: $('#role-description').val(),
            parent_id: $('#role-parent').val() || null,
            permissions: $("input[name='role_permissions[]']:checked").map(function() { return $(this).val(); }).get()
        };

        try {
            if (id) {
                await axios.put(`/roles/${id}`, payload);
                showToast('success', 'Role settings updated successfully.');
            } else {
                await axios.post('/roles', payload);
                showToast('success', 'Custom role created successfully.');
            }
            $('#modal-role').addClass('hidden');
            rolesTable.reload();
        } catch (e) {
            handleAjaxError(e);
        }
    });
});
</script>
@endsection

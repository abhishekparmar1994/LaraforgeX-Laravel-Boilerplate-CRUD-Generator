@extends('admin.layouts.app')

@section('title', 'LaraforgeX — Permissions Map')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-400 hover:text-slate-900 transition">Access Control</span>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">Permissions Map</span>
</nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-bold text-slate-900">Permissions Map</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Full registry of Spatie RBAC permissions available across all roles.</p>
    </div>
    <button id="btn-create-permission" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs transition shadow-sm shadow-brand-600/20 whitespace-nowrap">
      <i class="fa-solid fa-plus"></i> Create Permission
    </button>
  </div>

  <!-- DataTable mount point -->
  <div id="permissions-datatable"></div>
</div>

<!-- ============================================== -->
<!-- CREATE PERMISSION MODAL                        -->
<!-- ============================================== -->
<div id="modal-permission" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <form id="form-permission" class="relative bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 font-sans">
      <input type="hidden" id="permission-edit-id">
      <h3 class="font-bold text-lg text-slate-900" id="permission-modal-title">Create Permission</h3>

      <div class="space-y-4">
        <div class="space-y-1">
          <label for="permission-name" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Permission Name</label>
          <input id="permission-name" type="text" required
                 placeholder="e.g. courses.create"
                 class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 font-mono transition">
          <p class="text-[11px] text-slate-400">Use dot notation: <code class="text-brand-600">resource.action</code></p>
        </div>
        <div class="space-y-1">
          <label for="permission-guard" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Guard Name</label>
          <select id="permission-guard" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
            <option value="web">web</option>
            <option value="api">api</option>
          </select>
        </div>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="button" class="close-modal w-1/2 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">Cancel</button>
        <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition">Save Permission</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let permissionsList = [];
    let rolesList = [];
    let rolesByPermission = {};

    // ── Row Renderer ───────────────────────────────────────────
    function permissionRow(perm) {
        const roles = rolesByPermission[perm.name] || [];
        const roleBadges = roles.map(r =>
            `<span class="px-2 py-0.5 rounded-full bg-indigo-50 border border-indigo-100 text-[11px] font-semibold text-indigo-600 mr-1">${r}</span>`
        ).join('') || '<span class="text-slate-400 italic text-xs">No roles assigned</span>';

        return `
          <tr class="hover:bg-slate-50/60 transition">
            <td class="px-5 py-4 font-mono text-xs font-bold text-slate-900">${perm.name}</td>
            <td class="px-5 py-4">
              <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-500">${perm.guard_name || 'web'}</span>
            </td>
            <td class="px-5 py-4"><div class="flex flex-wrap gap-1">${roleBadges}</div></td>
            <td class="px-5 py-4 text-xs text-slate-400 font-medium">
              ${perm.created_at ? new Date(perm.created_at).toLocaleDateString() : '—'}
            </td>
            <td class="px-5 py-4 text-right">
              <div class="flex justify-end gap-1.5">
                <button onclick="editPermission('${perm.id}', '${perm.name}', '${perm.guard_name || 'web'}')"
                        class="px-2.5 py-1.5 rounded bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-xs transition">Edit</button>
                <button onclick="deletePermission('${perm.id}')"
                        class="px-2.5 py-1.5 rounded bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 font-semibold text-xs transition">Delete</button>
              </div>
            </td>
          </tr>
        `;
    }

    // ── Init AdminTable ────────────────────────────────────────
    const permissionsTable = new AdminTable({
        container: '#permissions-datatable',
        columns: [
            { key: 'name',       label: 'Permission Name', sortable: true },
            { key: 'guard_name', label: 'Guard',           sortable: true,  responsive: 'sm' },
            { key: 'roles',      label: 'Assigned Roles',  sortable: false, responsive: 'md' },
            { key: 'created_at', label: 'Created',         sortable: true,  responsive: 'lg' },
            { key: 'actions',    label: 'Actions',         sortable: false, class: 'text-right' }
        ],
        fetch: async () => {
            const response = await axios.get('/roles');
            rolesList = response.data.data.roles;
            permissionsList = response.data.data.permissions;

            // Clear lookup map
            rolesByPermission = {};
            rolesList.forEach(role => {
                (role.permissions || []).forEach(p => {
                    if (!rolesByPermission[p.name]) rolesByPermission[p.name] = [];
                    rolesByPermission[p.name].push(role.name);
                });
            });

            return permissionsList;
        },
        row: permissionRow
    });

    permissionsTable.load();

    // ── Action Handlers ────────────────────────────────────────
    $('#btn-create-permission').click(function() {
        $('#permission-modal-title').text('Create Permission');
        $('#permission-edit-id').val('');
        $('#permission-name').val('').prop('readonly', false);
        $('#permission-guard').val('web');
        $('#modal-permission').removeClass('hidden');
    });

    window.editPermission = function(id, name, guard) {
        $('#permission-modal-title').text('Edit Permission');
        $('#permission-edit-id').val(id);
        $('#permission-name').val(name).prop('readonly', true);
        $('#permission-guard').val(guard);
        $('#modal-permission').removeClass('hidden');
    }

    window.deletePermission = function(id) {
        Swal.fire({
            title: 'Delete Permission?',
            text: 'This will detach the permission from all roles.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Delete Permission',
            background: '#ffffff',
            color: '#0f172a'
        }).then(async result => {
            if (result.isConfirmed) {
                try {
                    await axios.delete(`/permissions/${id}`);
                    showToast('success', 'Permission deleted successfully.');
                    permissionsTable.reload();
                } catch (e) {
                    handleAjaxError(e);
                }
            }
        });
    }

    $('#form-permission').submit(async function(e) {
        e.preventDefault();
        const id = $('#permission-edit-id').val();
        const payload = {
            name: $('#permission-name').val(),
            guard_name: $('#permission-guard').val()
        };

        try {
            if (id) {
                await axios.put(`/permissions/${id}`, payload);
                showToast('success', 'Permission updated successfully.');
            } else {
                await axios.post('/permissions', payload);
                showToast('success', 'Permission created successfully.');
            }
            $('#modal-permission').addClass('hidden');
            permissionsTable.reload();
        } catch (e) {
            handleAjaxError(e);
        }
    });
});
</script>
@endsection

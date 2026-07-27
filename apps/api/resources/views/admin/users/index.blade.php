@extends('admin.layouts.app')

@section('title', 'LaraforgeX — User Accounts')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">User Accounts</span>
</nav>
@endsection

@section('content')
<div class="space-y-5 font-sans">

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <h2 class="text-xl font-bold text-slate-900">User Accounts</h2>
      <p class="text-xs text-slate-500 mt-0.5 font-medium">Manage profiles, access control mappings, and activation states.</p>
    </div>
    <button id="btn-create-user"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs transition shadow-sm shadow-brand-600/20 whitespace-nowrap">
      <i class="fa-solid fa-plus"></i> Add New User
    </button>
  </div>

  <!-- ── Filter Bar ──────────────────────────────────────────────── -->
  <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
      <!-- Status Filter -->
      <div class="space-y-1">
        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</label>
        <select id="filter-user-status"
                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 transition">
          <option value="">All Statuses</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
          <option value="suspended">Suspended</option>
        </select>
      </div>
      <!-- Role Filter -->
      <div class="space-y-1">
        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Role</label>
        <select id="filter-user-role"
                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brand-500 transition">
          <option value="">All Roles</option>
        </select>
      </div>
      <!-- Reset -->
      <div class="flex items-end">
        <button id="btn-reset-user-filters"
                class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">
          <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filters
        </button>
      </div>
    </div>
  </div>

  <!-- DataTable mount point -->
  <div id="users-datatable"></div>

</div>

<!-- ── USER CREATE / EDIT MODAL ───────────────────────────────── -->
<div id="modal-user" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <form id="form-user" class="relative bg-white border border-slate-200 rounded-2xl w-full max-w-lg p-6 shadow-2xl space-y-5 font-sans">
      <input type="hidden" id="user-edit-id">
      <h3 class="font-bold text-lg text-slate-900" id="user-modal-title">Create New Profile</h3>
      <div class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1">
            <label for="user-name" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Full Name</label>
            <input id="user-name" type="text" required
                   class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
          </div>
          <div class="space-y-1">
            <label for="user-email" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Email Address</label>
            <input id="user-email" type="email" required
                   class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
          </div>
        </div>
        <div class="space-y-1" id="user-password-container">
          <label for="user-password" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Password</label>
          <input id="user-password" type="password"
                 class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
        </div>
        <div class="space-y-1">
          <label for="user-status" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
          <select id="user-status" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div class="space-y-2">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-500">Roles</label>
          <div class="grid grid-cols-2 gap-2 max-h-36 overflow-y-auto" id="user-roles-container"></div>
        </div>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="button" class="close-modal w-1/2 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition">Cancel</button>
        <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition">Save User</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
  const currentUser = JSON.parse(localStorage.getItem('laraforgex_user') || '{}');
  const userRoles = currentUser.roles || [];
  const userPermissions = currentUser.permissions || [];
  const isAdmin = userRoles.includes('administrator');

  const hasPermission = (p) => userPermissions.includes(p) || isAdmin;

  const canCreate = hasPermission('users.create');
  const canEdit = hasPermission('users.edit');
  const canDelete = hasPermission('users.delete');
  const canSuspend = hasPermission('users.suspend');

  // Hide create button if they can't create
  if (!canCreate) {
    $('#btn-create-user').hide();
  }

  // Hide roles field container if they are not administrator
  if (!isAdmin) {
    $('#user-roles-container').parent().hide();
  }

  let rolesList = [];

  // ── Status badge helper ──────────────────────────────────────
  function statusBadge(status) {
    const map = {
      active:    'bg-emerald-50 border-emerald-100 text-emerald-600',
      suspended: 'bg-rose-50 border-rose-100 text-rose-600',
      inactive:  'bg-slate-100 border-slate-200 text-slate-500',
    };
    const cls = map[status] || map.inactive;
    return `<span class="px-2 py-0.5 rounded-full border text-xs font-semibold ${cls}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
  }

  // ── Row renderer ─────────────────────────────────────────────
  function userRow(user) {
    const isMe = user.id === currentUser.id;
    const isSuperAdmin = (user.roles || []).includes('administrator') || user.email === 'admin@laraforgex.com';
    const roles = (user.roles || []).map(r =>
      `<span class="px-2 py-0.5 rounded-full bg-brand-50 border border-brand-100 text-xs font-semibold text-brand-600">${r}</span>`
    ).join(' ') || '<span class="text-slate-400 italic text-xs">No roles</span>';

    let actionButtons = '';
    let editBtn = '';
    
    if (!isSuperAdmin) {
      if (canEdit) {
        editBtn = `<button onclick="editUser('${user.id}')" class="px-2.5 py-1.5 rounded bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-xs transition">Edit</button>`;
      }
      if (!isMe) {
        if (canSuspend) {
          actionButtons += user.status === 'active'
            ? `<button onclick="suspendUser('${user.id}')" class="px-2.5 py-1.5 rounded bg-amber-50 hover:bg-amber-100 border border-amber-100 text-amber-600 font-semibold text-xs transition">Suspend</button> `
            : `<button onclick="activateUser('${user.id}')" class="px-2.5 py-1.5 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-600 font-semibold text-xs transition">Activate</button> `;
        }
        if (canDelete) {
          actionButtons += `<button onclick="deleteUser('${user.id}')" class="px-2.5 py-1.5 rounded bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 font-semibold text-xs transition">Delete</button>`;
        }
      }
    } else {
      actionButtons = `<span class="px-2.5 py-1 rounded bg-slate-100 border border-slate-200 text-slate-400 font-mono font-semibold text-[10px] uppercase tracking-wider">System Admin</span>`;
    }

    return `
      <tr class="hover:bg-slate-50/60 transition">
        <td class="px-5 py-4">
          <div class="font-semibold text-slate-900 text-sm">${user.name}</div>
          <div class="text-xs text-slate-400 mt-0.5">${user.email}</div>
        </td>
        <td class="px-5 py-4"><div class="flex flex-wrap gap-1">${roles}</div></td>
        <td class="px-5 py-4">${statusBadge(user.status)}</td>
        <td class="px-5 py-4 text-xs text-slate-400">
          <div>${user.last_login_at ? new Date(user.last_login_at).toLocaleString() : 'Never'}</div>
          <div class="font-mono">${user.last_login_ip || '—'}</div>
        </td>
        <td class="px-5 py-4">
          <div class="flex justify-end gap-1.5 flex-wrap">
            ${editBtn}
            ${actionButtons}
          </div>
        </td>
      </tr>`;
  }

  // ── Init AdminTable ──────────────────────────────────────────
  const usersTable = new AdminTable({
    container : '#users-datatable',
    columns   : [
      { key: 'name',          label: 'Name & Email',  sortable: true  },
      { key: 'roles',         label: 'Roles',         sortable: false },
      { key: 'status',        label: 'Status',        sortable: true  },
      { key: 'last_login_at', label: 'Last Login',    sortable: true  },
      { key: 'actions',       label: 'Actions',       sortable: false, class: 'text-right' },
    ],
    fetch: async () => {
      const params = {};
      const status = $('#filter-user-status').val();
      const role   = $('#filter-user-role').val();
      if (status) params.status = status;
      if (role)   params.role   = role;

      if (isAdmin) {
        const [usersRes, rolesRes] = await Promise.all([
          axios.get('/users', { params }),
          axios.get('/roles'),
        ]);
        rolesList = rolesRes.data.data.roles;
        return usersRes.data.data;
      } else {
        const usersRes = await axios.get('/users', { params });
        return usersRes.data.data;
      }
    },
    row: userRow,
  });

  usersTable.load().then(() => {
    // Populate role filter from loaded roles list
    if (rolesList.length) {
      const roleOpts = rolesList.map(r => `<option value="${r.name}">${r.name}</option>`).join('');
      $('#filter-user-role').append(roleOpts);
    }
  });

  // ── Server-Side Filter Logic ──────────────────────────────────
  function applyUserFilters() {
    usersTable.reload();
  }

  $('#filter-user-status, #filter-user-role').on('change', applyUserFilters);

  $('#btn-reset-user-filters').on('click', () => {
    $('#filter-user-status').val('');
    $('#filter-user-role').val('');
    usersTable.reload();
  });

  // ── CRUD helpers ─────────────────────────────────────────────
  function buildRolesCheckboxList(userRoles) {
    const html = rolesList.map(role => {
      const checked = userRoles.includes(role.name) ? 'checked' : '';
      return `<label class="flex items-center gap-2 text-sm text-slate-700 font-medium cursor-pointer">
                <input type="checkbox" name="user_roles[]" value="${role.name}" ${checked}
                       class="rounded border-slate-300 text-brand-600 focus:ring-0">
                ${role.name}
              </label>`;
    }).join('') || '<span class="text-slate-400 italic text-xs">No roles available</span>';
    $('#user-roles-container').html(html);
  }

  window.suspendUser = id => confirmAction(
    'Suspend Account?', 'This user will lose all portal access.', 'warning', '#f59e0b',
    () => axios.post(`/users/${id}/suspend`).then(() => { showToast('success', 'User suspended.'); usersTable.reload(); })
  );

  window.activateUser = id => confirmAction(
    'Activate Account?', 'Restore full dashboard access for this user.', 'question', '#10b981',
    () => axios.post(`/users/${id}/activate`).then(() => { showToast('success', 'User activated.'); usersTable.reload(); })
  );

  window.deleteUser = id => confirmAction(
    'Delete User?', 'This is irreversible and removes all profile history.', 'warning', '#ef4444',
    () => axios.delete(`/users/${id}`).then(() => { showToast('success', 'User deleted.'); usersTable.reload(); })
  );

  function confirmAction(title, text, icon, confirmColor, action) {
    Swal.fire({ title, text, icon, showCancelButton: true,
      confirmButtonColor: confirmColor, cancelButtonColor: '#64748b',
      confirmButtonText: 'Confirm', background: '#fff', color: '#0f172a'
    }).then(r => { if (r.isConfirmed) action().catch(handleAjaxError); });
  }

  // ── Create user ──────────────────────────────────────────────
  $('#btn-create-user').click(() => {
    $('#user-modal-title').text('Create New Profile');
    $('#user-edit-id').val('');
    $('#user-name, #user-email, #user-password').val('');
    $('#user-password').prop('required', true);
    $('#user-password-container').show();
    $('#user-status').val('active');
    buildRolesCheckboxList([]);
    $('#modal-user').removeClass('hidden');
  });

  // ── Edit user ────────────────────────────────────────────────
  window.editUser = async id => {
    try {
      const { data } = await axios.get(`/users/${id}`);
      const user = data.data;
      $('#user-modal-title').text('Edit User');
      $('#user-edit-id').val(user.id);
      $('#user-name').val(user.name);
      $('#user-email').val(user.email);
      $('#user-password').val('').prop('required', false);
      $('#user-password-container').hide();
      $('#user-status').val(user.status);
      buildRolesCheckboxList(user.roles || []);
      $('#modal-user').removeClass('hidden');
    } catch (e) { handleAjaxError(e); }
  };

  // ── Form submit ──────────────────────────────────────────────
  $('#form-user').submit(async function (e) {
    e.preventDefault();
    const id = $('#user-edit-id').val();
    const payload = {
      name  : $('#user-name').val(),
      email : $('#user-email').val(),
      status: $('#user-status').val(),
      roles : $("input[name='user_roles[]']:checked").map(function () { return $(this).val(); }).get(),
    };
    if ($('#user-password').val()) payload.password = $('#user-password').val();

    try {
      await (id ? axios.put(`/users/${id}`, payload) : axios.post('/users', payload));
      showToast('success', id ? 'User updated.' : 'User created.');
      $('#modal-user').addClass('hidden');
      usersTable.reload();
    } catch (e) { handleAjaxError(e); }
  });
});
</script>
@endsection

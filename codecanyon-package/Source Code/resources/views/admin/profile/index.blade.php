@extends('admin.layouts.app')

@section('title', 'LaraforgeX — My Profile')

@section('breadcrumbs')
<nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
  <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
  <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
  <span class="text-slate-700">My Profile</span>
</nav>
@endsection

@section('content')
<div class="space-y-6 font-sans w-full">

  <!-- Page header -->
  <div>
    <h2 class="text-xl font-bold text-slate-900">My Profile</h2>
    <p class="text-xs text-slate-500 mt-0.5 font-medium">Manage your account details, security settings, and preferences.</p>
  </div>

  <!-- Profile Card -->
  <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

    <!-- Cover gradient bar -->
    <div class="theme-hero-banner h-28 bg-gradient-to-r from-brand-500 via-indigo-600 to-violet-500"></div>

    <!-- Avatar + user info -->
    <div class="px-6 pb-6">
      <div class="flex items-end justify-between -mt-12 mb-4">
        <div id="profile-avatar-large"
             class="h-24 w-24 rounded-2xl bg-gradient-to-tr from-brand-500 to-violet-500 border-4 border-white shadow-lg flex items-center justify-center text-white font-extrabold text-3xl uppercase shrink-0">
          U
        </div>
      </div>

      <div class="space-y-1 mb-6">
        <h3 class="text-2xl font-extrabold text-slate-900 leading-tight" id="profile-name">Loading…</h3>
        <p class="text-xs font-semibold text-slate-500 font-mono" id="profile-email">—</p>
        <div class="flex flex-wrap gap-1.5 pt-2" id="profile-roles-badges"></div>
      </div>

      <!-- Editable profile form -->
      <form id="form-profile" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1">
            <label for="profile-name-input" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Full Name</label>
            <input id="profile-name-input" type="text" required
                   class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
          </div>
          <div class="space-y-1">
            <label for="profile-email-input" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Email Address</label>
            <input id="profile-email-input" type="email" required
                   class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-brand-500 transition">
          </div>
        </div>

        <!-- 2FA status -->
        <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-xl">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
              <i class="fa-solid fa-shield-halved text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-bold text-slate-900">Two-Factor Authentication</p>
              <p class="text-xs text-slate-500 font-medium">Google Authenticator / TOTP</p>
            </div>
          </div>
          <div id="profile-2fa-badge"></div>
        </div>

        <div class="flex justify-end pt-2">
          <button type="submit"
                  class="px-6 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm shadow-brand-600/20">
            <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Security Stats Card -->
  <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-4">
    <h3 class="text-sm font-bold text-slate-900">Security Information</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-0.5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Last Login</p>
        <p class="text-sm font-bold text-slate-900" id="profile-last-login">—</p>
      </div>
      <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-0.5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Last IP</p>
        <p class="text-sm font-bold text-slate-900 font-mono" id="profile-last-ip">—</p>
      </div>
      <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-0.5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Account Status</p>
        <p class="text-sm font-bold text-emerald-600" id="profile-status">—</p>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    let currentUserId = null;

    // ── Load profile data from API ──────────────────────────────
    async function loadProfile() {
        try {
            const response = await axios.get('/auth/me');
            const user = response.data.data;
            currentUserId = user.id;

            // Header avatar + name
            const initial = (user.name || 'U').charAt(0).toUpperCase();
            $('#profile-avatar-large').text(initial);
            $('#profile-name').text(user.name || '—');
            $('#profile-email').text(user.email || '—');

            // Role badges
            let badges = '';
            (user.roles || []).forEach(role => {
                badges += `<span class="px-2 py-0.5 rounded-full bg-brand-50 border border-brand-100 text-[11px] font-bold text-brand-600">${role}</span>`;
            });
            $('#profile-roles-badges').html(badges);

            // Editable form fields
            $('#profile-name-input').val(user.name);
            $('#profile-email-input').val(user.email);

            // 2FA badge
            if (user.two_factor_enabled) {
                $('#profile-2fa-badge').html(
                    '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-bold text-emerald-600"><i class="fa-solid fa-circle-check text-[10px]"></i> Enabled</span>'
                );
            } else {
                $('#profile-2fa-badge').html(
                    '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-500"><i class="fa-solid fa-circle-xmark text-[10px]"></i> Not enabled</span>'
                );
            }

            // Security stats
            $('#profile-last-login').text(
                user.last_login_at ? new Date(user.last_login_at).toLocaleString() : 'Never'
            );
            $('#profile-last-ip').text(user.last_login_ip || '—');
            $('#profile-status').text(
                (user.status || 'active').charAt(0).toUpperCase() + (user.status || 'active').slice(1)
            );

        } catch (e) {
            handleAjaxError(e, 'Failed to load profile data.');
        }
    }

    // ── Profile update form submit ──────────────────────────────
    $('#form-profile').submit(async function (e) {
        e.preventDefault();
        if (!currentUserId) return;

        const payload = {
            name:  $('#profile-name-input').val(),
            email: $('#profile-email-input').val(),
        };

        try {
            await axios.put(`/users/${currentUserId}`, payload);
            showToast('success', 'Profile updated successfully.');

            // Sync localStorage session with new name/email
            const session = JSON.parse(localStorage.getItem('laraforgex_user') || '{}');
            session.name  = payload.name;
            session.email = payload.email;
            localStorage.setItem('laraforgex_user', JSON.stringify(session));

            // Refresh header
            $('#header-user-name').text(payload.name);
            $('#header-avatar').text(payload.name.charAt(0).toUpperCase());
            $('#profile-avatar-large').text(payload.name.charAt(0).toUpperCase());
            $('#profile-name').text(payload.name);
        } catch (e) {
            handleAjaxError(e, 'Failed to update profile.');
        }
    });

    loadProfile();
});
</script>
@endsection

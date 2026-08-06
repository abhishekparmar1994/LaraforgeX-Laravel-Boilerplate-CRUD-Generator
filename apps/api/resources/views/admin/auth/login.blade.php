@extends('admin.layouts.auth')

@section('title', 'LaraforgeX — Admin Login')

@section('content')
  <div
    class="min-h-screen bg-slate-50 relative flex flex-col justify-center py-12 sm:px-6 lg:px-8 overflow-hidden font-sans">

    <!-- Subtle geometric grid overlay -->
    <div
      class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-40 pointer-events-none">
    </div>

    <!-- Ambient background light glows -->
    <div class="absolute w-[600px] h-[600px] bg-brand-500/5 rounded-full blur-3xl -top-48 -left-48 pointer-events-none">
    </div>
    <div
      class="absolute w-[600px] h-[600px] bg-violet-500/5 rounded-full blur-3xl -bottom-48 -right-48 pointer-events-none">
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 space-y-6 px-4">
      <!-- Card Frame -->
      <div class="bg-white/95 border border-slate-200/80 p-8 sm:p-10 rounded-2xl shadow-xl shadow-slate-200/50 space-y-8">

        <!-- Brand Logo / Header -->
        <div class="text-center space-y-3.5">
          <div
            class="inline-flex h-12 w-12 rounded-xl bg-gradient-to-tr from-brand-600 to-violet-500 items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-brand-600/20">
            L
          </div>
          <div class="space-y-1">
            <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Welcome back</h2>
            <p id="login-subtitle" class="text-xs text-slate-400 font-bold uppercase tracking-wider">Sign in to your
              Laraforge<span class="text-brand-600">X</span> core panel</p>
          </div>
        </div>

        <!-- Login credentials form -->
        <form id="login-form" class="space-y-5">
          <div class="space-y-1.5">
            <label for="email" class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Email
              Address</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                <i class="fa-regular fa-envelope text-sm"></i>
              </span>
              <input id="email" name="email" type="email" required
                class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 transition placeholder-slate-400"
                placeholder="admin@laraforgex.com">
            </div>
          </div>

          <div class="space-y-1.5 password-container">
            <label for="password" class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Password</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                <i class="fa-solid fa-lock text-sm"></i>
              </span>
              <input id="password" name="password" type="password" required
                class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-10 py-3 text-sm text-slate-900 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 transition placeholder-slate-400"
                placeholder="••••••••••••">
              <button type="button" onclick="togglePasswordVisibility('password', 'toggle-password-icon')"
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer border-0 bg-transparent">
                <i id="toggle-password-icon" class="fa-regular fa-eye text-sm"></i>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between text-xs font-semibold">
            <label class="flex items-center text-slate-500 cursor-pointer select-none">
              <input type="checkbox" id="remember"
                class="mr-2 rounded border-slate-200 bg-white text-brand-600 focus:ring-0">
              Remember me
            </label>
            <div class="flex gap-2">
              <a href="/admin/forgot-password" class="text-slate-400 hover:text-slate-600 transition">Forgot Password?</a>
              <span class="text-slate-200">|</span>
              <a href="javascript:void(0)" id="toggle-login-mode"
                class="text-brand-600 hover:text-brand-500 transition">Magic Link</a>
            </div>
          </div>

          <div id="recaptcha-container" class="hidden my-3 flex justify-center"></div>

          <button type="submit"
            class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-sm transition shadow-lg shadow-brand-600/10 hover:shadow-brand-600/20 active:translate-y-px cursor-pointer border-0">
            <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Access Dashboard
          </button>
        </form>

        <!-- Magic Link UI block -->
        <div id="magic-link-block" class="hidden space-y-4 pt-5 border-t border-slate-100">
          <div class="p-3.5 bg-brand-50/80 border border-brand-100/50 text-xs text-brand-700 rounded-xl font-semibold">
            <i class="fa-solid fa-circle-info mr-1.5 text-brand-500"></i>
            Since mail delivery depends on local credentials, the generated magic link will be displayed below for direct
            access.
          </div>
          <div id="magic-link-display"
            class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-xs text-slate-600 break-all select-all">
            Generating link...
          </div>
          <a id="magic-link-anchor" href="#"
            class="block text-center py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition border-0">
            <i class="fa-solid fa-arrow-up-right-from-square mr-1.5"></i> Authenticate Magic Link
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Two-Factor Authentication Modal Challenge -->
  <div id="modal-2fa" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
      <!-- Overlay -->
      <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

      <!-- Modal Box -->
      <div class="relative bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5">
        <div class="text-center space-y-2">
          <div
            class="h-12 w-12 rounded-full bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 text-xl mx-auto">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <h3 class="font-bold text-lg text-slate-900">Two-Factor Authentication</h3>
          <p class="text-xs text-slate-500 font-medium">Please enter the 6-digit verification code from Google
            Authenticator.</p>
        </div>

        <form id="form-2fa" class="space-y-4">
          <div class="space-y-1">
            <input id="code-2fa" type="text" maxlength="6" pattern="[0-9]{6}" required
              class="w-full text-center tracking-widest text-lg font-mono bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-slate-900 focus:outline-none focus:border-brand-500 placeholder-slate-300 focus:placeholder-transparent"
              placeholder="000000" autocomplete="one-time-code">
          </div>

          <div class="flex gap-3">
            <button type="button" id="close-2fa"
              class="w-1/2 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold transition cursor-pointer">
              Cancel
            </button>
            <button type="submit"
              class="w-1/2 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition cursor-pointer border-0">
              Verify & Confirm
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function () {
      if (localStorage.getItem('laraforgex_auth_token')) {
        window.location.href = '/admin/dashboard';
      }

      $('#btn-fill-demo').click(function() {
        $('#email').val('admin@laraforgex.com');
        $('#password').val('password');
        showToast('success', 'Demo credentials auto-filled!');
      });

      let isMagicLinkMode = false;
      let tempToken = null;
      let tempUser = null;

      $('#toggle-login-mode').click(function () {
        isMagicLinkMode = !isMagicLinkMode;
        if (isMagicLinkMode) {
          $('.password-container').slideUp(200);
          $('#password').prop('required', false);
          $('#toggle-login-mode').text('Password');
          $('#login-subtitle').text('Request a passwordless login link.');
          $('button[type="submit"]').html('<i class="fa-solid fa-envelope mr-2"></i> Request Link');
        } else {
          $('.password-container').slideDown(200);
          $('#password').prop('required', true);
          $('#toggle-login-mode').text('Magic Link');
          $('#login-subtitle').text('Sign in to your LaraforgeX core panel');
          $('button[type="submit"]').html('<i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Access Dashboard');
          $('#magic-link-block').slideUp(200);
        }
      });

      let recaptchaEnabled = false;

      async function checkReCaptcha() {
        try {
          const res = await axios.get('/auth/captcha-config');
          if (res.data.success && res.data.data.recaptcha_enabled) {
            recaptchaEnabled = true;
            const siteKey = res.data.data.recaptcha_site_key;
            if (siteKey) {
              $('#recaptcha-container').html(`<div class="g-recaptcha" data-sitekey="${siteKey}"></div>`).removeClass('hidden');
              $('<script>', {
                src: 'https://www.google.com/recaptcha/api.js',
                async: true,
                defer: true
              }).appendTo('head');

            }
          }
        } catch (e) {
          console.error('Failed to load captcha config', e);
        }
      }

      checkReCaptcha();

      $('#login-form').submit(async function (e) {
        e.preventDefault();

        const email = $('#email').val();
        const password = $('#password').val();
        const remember = $('#remember').is(':checked');
        const recaptchaToken = (recaptchaEnabled && typeof grecaptcha !== 'undefined') ? grecaptcha.getResponse() : null;

        if (isMagicLinkMode) {
          try {
            const response = await axios.post('/auth/magic-link', {
              email,
              'g-recaptcha-response': recaptchaToken
            });
            if (response.data.success) {
              const magicLink = response.data.data.link;
              const parsedUrl = new URL(magicLink);
              const localVerifyUrl = `/api/v1/auth/magic-login${parsedUrl.search}`;

              $('#magic-link-display').text(magicLink);
              $('#magic-link-anchor').attr('href', '#').off('click').on('click', async function (e) {
                e.preventDefault();
                try {
                  const verifyResponse = await axios.get(localVerifyUrl);
                  if (verifyResponse.data.success) {
                    handleLoginSuccess(verifyResponse.data.data);
                  }
                } catch (err) {
                  handleAjaxError(err);
                }
              });

              $('#magic-link-block').slideDown(200);
              showToast('success', 'Magic login link generated successfully!');
            }
          } catch (err) {
            if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
            handleAjaxError(err);
          }
        } else {
          try {
            const response = await axios.post('/auth/login', {
              email,
              password,
              remember,
              'g-recaptcha-response': recaptchaToken
            });
            if (response.data.success) {
              const data = response.data.data;

              if (data.user.two_factor_enabled) {
                tempToken = data.token;
                tempUser = data.user;
                $('#modal-2fa').removeClass('hidden');
                $('#code-2fa').focus();
              } else {
                handleLoginSuccess(data);
              }
            }
          } catch (err) {
            if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
            handleAjaxError(err);
          }
        }
      });

      $('#form-2fa').submit(async function (e) {
        e.preventDefault();
        const code = $('#code-2fa').val();

        try {
          const response = await axios.post('/auth/2fa/verify', { code }, {
            headers: {
              'Authorization': `Bearer ${tempToken}`
            }
          });

          if (response.data.success) {
            localStorage.setItem('laraforgex_auth_token', tempToken);
            localStorage.setItem('laraforgex_user', JSON.stringify(tempUser));
            showToast('success', 'Two factor authenticated successfully!');
            setTimeout(() => {
              window.location.href = '/admin/dashboard';
            }, 1000);
          }
        } catch (err) {
          handleAjaxError(err, '2FA authentication check failed. Invalid code.');
        }
      });

      $('#close-2fa').click(function () {
        $('#modal-2fa').addClass('hidden');
        tempToken = null;
        $('#code-2fa').val('');
      });

      function handleLoginSuccess(data) {
        localStorage.setItem('laraforgex_auth_token', data.token);
        localStorage.setItem('laraforgex_user', JSON.stringify(data.user));

        showToast('success', 'Authentication verified! Redirecting...');
        setTimeout(() => {
          window.location.href = '/admin/dashboard';
        }, 1200);
      }
    });
  </script>
@endsection
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
        <div id="login-error-container" class="hidden">
          <div
            class="p-4 bg-rose-50 border border-rose-200/90 rounded-2xl flex items-start gap-3 shadow-xs animate-shake">
            <span
              class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 font-bold text-sm">
              <i class="fa-solid fa-triangle-exclamation"></i>
            </span>
            <div class="flex-1 min-w-0">
              <h4 class="text-xs font-bold text-rose-900 leading-tight">Authentication Failed</h4>
              <p id="login-error-text" class="text-xs font-semibold text-rose-700 mt-1 leading-snug break-words"></p>
              <ul id="login-error-list"
                class="hidden list-disc list-inside text-[11px] font-semibold text-rose-600 mt-1.5 space-y-0.5"></ul>
            </div>
            <button type="button" onclick="$('#login-error-container').slideUp(180)"
              class="text-rose-400 hover:text-rose-600 transition cursor-pointer text-xs p-1 -mr-1 -mt-1 border-0 bg-transparent">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
        </div>

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
            <p id="email-error-msg" class="hidden text-[11px] font-semibold text-rose-600 mt-1 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
              <span class="msg-content"></span>
            </p>
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
              <button type="button" onclick="togglePasswordVisibility('password')"
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer border-0 bg-transparent"
                title="Toggle password visibility" aria-label="Toggle password visibility">
                <span id="password-eye"><i class="fa-regular fa-eye text-sm"></i></span>
                <span id="password-eye-slash" class="hidden"><i class="fa-regular fa-eye-slash text-sm"></i></span>
              </button>
            </div>
            <p id="password-error-msg"
              class="hidden text-[11px] font-semibold text-rose-600 mt-1 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
              <span class="msg-content"></span>
            </p>
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

  @include('admin.auth.partials.two-factor-modal')
@endsection

@section('scripts')
  <script>
    $(document).ready(function () {
      let isMagicLinkMode = false;
      let tempToken = null;
      let tempUser = null;
      let recaptchaEnabled = false;

      /**
       * Purpose: Toggle between standard password login and magic link modes.
       */
      $('#toggle-login-mode').click(function () {
        isMagicLinkMode = !isMagicLinkMode;
        if (isMagicLinkMode) {
          $('.password-container').slideUp(200);
          $('#password').prop('required', false);
          $('#toggle-login-mode').text('Password');
          $('#login-subtitle').text('Request a passwordless login link.');
          $('button[type="submit"]').html('<i class="fa-solid fa-envelope mr-2"></i> Request Link');
          return;
        }
        $('.password-container').slideDown(200);
        $('#password').prop('required', true);
        $('#toggle-login-mode').text('Magic Link');
        $('#login-subtitle').text('Sign in to your LaraforgeX core panel');
        $('button[type="submit"]').html('<i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Access Dashboard');
        $('#magic-link-block').slideUp(200);
      });

      /**
       * Purpose: Check if Google reCAPTCHA is enabled on the server and inject script.
       * Side Effects: DOM modification, script injection.
       */
      async function checkReCaptcha() {
        try {
          const res = await axios.get('/auth/captcha-config');
          if (res.data?.success && res.data?.data?.recaptcha_enabled) {
            recaptchaEnabled = true;
            const siteKey = res.data.data.recaptcha_site_key;
            if (siteKey) {
              $('#recaptcha-container').html(`<div class="g-recaptcha" data-sitekey="${siteKey}"></div>`).removeClass('hidden');
              $('<script>', { src: 'https://www.google.com/recaptcha/api.js', async: true, defer: true }).appendTo('head');
            }
          }
        } catch (e) {
          console.error('Failed to load captcha config', e);
        }
      }

      /**
       * Purpose: Highlight invalid fields and populate error list.
       * Parameters: fieldErrors (object), mainMsg (string)
       * Return: boolean (whether list items were added)
       */
      function renderFieldErrors(fieldErrors, mainMsg) {
        const $list = $('#login-error-list').empty().addClass('hidden');
        if (!fieldErrors || typeof fieldErrors !== 'object' || Array.isArray(fieldErrors)) {
          $('#email, #password').addClass('border-rose-400 ring-2 ring-rose-500/20');
          return false;
        }

        let hasList = false;
        for (const [field, errs] of Object.entries(fieldErrors)) {
          const fieldMsg = Array.isArray(errs) ? errs[0] : errs;
          if (fieldMsg && fieldMsg !== mainMsg) {
            $list.append(`<li>${fieldMsg}</li>`);
            hasList = true;
          }
          if (field === 'email') {
            $('#email').addClass('border-rose-400 ring-2 ring-rose-500/20');
            $('#email-error-msg').removeClass('hidden').find('.msg-content').text(fieldMsg);
          }
          if (field === 'password') {
            $('#password').addClass('border-rose-400 ring-2 ring-rose-500/20');
            $('#password-error-msg').removeClass('hidden').find('.msg-content').text(fieldMsg);
          }
        }
        if (hasList) $list.removeClass('hidden');
        return hasList;
      }

      /**
       * Purpose: Display authentication/validation error in the alert card and trigger toast.
       * Parameters: msg (string), fieldErrors (object)
       * Side Effects: Updates DOM alert container, triggers animation, shows toast.
       */
      function showLoginError(msg, fieldErrors = {}) {
        const displayMsg = msg || 'Invalid login credentials.';
        $('#login-error-text').text(displayMsg);
        renderFieldErrors(fieldErrors, displayMsg);

        const $alert = $('#login-error-container');
        $alert.removeClass('hidden').hide().slideDown(180);
        $alert.find('> div').removeClass('animate-shake');
        void $alert[0].offsetWidth;
        $alert.find('> div').addClass('animate-shake');

        if (typeof window.showToast === 'function') {
          window.showToast('error', displayMsg, 'Authentication Failed');
        }
      }

      /**
       * Purpose: Clear active error styles and hide inline alert banner.
       */
      function clearLoginErrors() {
        $('#login-error-container').slideUp(150);
        $('#email, #password').removeClass('border-rose-400 ring-2 ring-rose-500/20');
        $('#email-error-msg, #password-error-msg').addClass('hidden').find('.msg-content').text('');
      }

      /**
       * Purpose: Safely extract error message and field errors from an Axios error object.
       * Parameters: err (Error), fallbackMsg (string)
       * Return: Object { msg: string, fieldErrors: object }
       */
      function extractErrorData(err, fallbackMsg) {
        let msg = fallbackMsg;
        let fieldErrors = {};
        if (err.response?.data) {
          msg = err.response.data.message || msg;
          if (err.response.data.errors && typeof err.response.data.errors === 'object') {
            fieldErrors = err.response.data.errors;
          }
        } else if (err.message) {
          msg = err.message;
        }
        return { msg, fieldErrors };
      }

      /**
       * Purpose: Handle standard password-based login submission.
       * Parameters: $btn (jQuery), origBtnHtml (string), payload (object)
       */
      async function handlePasswordLoginSubmit($btn, origBtnHtml, payload) {
        try {
          const response = await axios.post('/auth/login', payload);
          if (!response.data.success) {
            $btn.prop('disabled', false).html(origBtnHtml);
            showLoginError(response.data.message || 'Login failed.');
            return;
          }

          const data = response.data.data;
          if (data.user?.two_factor_enabled) {
            $btn.prop('disabled', false).html(origBtnHtml);
            tempToken = data.token;
            tempUser = data.user;
            $('#modal-2fa').removeClass('hidden');
            $('#code-2fa').focus();
            return;
          }

          $btn.html('<i class="fa-solid fa-check mr-2"></i> Verified! Redirecting...');
          handleLoginSuccess(data);
        } catch (err) {
          $btn.prop('disabled', false).html(origBtnHtml);
          if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
          const { msg, fieldErrors } = extractErrorData(err, 'Invalid login credentials.');
          showLoginError(msg, fieldErrors);
        }
      }

      /**
       * Purpose: Handle passwordless magic link request.
       * Parameters: $btn (jQuery), origBtnHtml (string), email (string), recaptchaToken (string)
       */
      async function handleMagicLinkSubmit($btn, origBtnHtml, email, recaptchaToken) {
        try {
          const res = await axios.post('/auth/magic-link', { email, 'g-recaptcha-response': recaptchaToken });
          $btn.prop('disabled', false).html(origBtnHtml);
          if (!res.data.success) {
            showLoginError(res.data.message || 'Failed to generate link.');
            return;
          }
          const magicLink = res.data.data.link;
          const verifyUrl = `/api/v1/auth/magic-login${new URL(magicLink).search}`;
          $('#magic-link-display').text(magicLink);
          $('#magic-link-anchor').attr('href', '#').off('click').on('click', async function (e) {
            e.preventDefault();
            try {
              const vRes = await axios.get(verifyUrl);
              if (vRes.data.success) handleLoginSuccess(vRes.data.data);
            } catch (err) {
              const { msg } = extractErrorData(err, 'Magic link verification failed.');
              showLoginError(msg);
            }
          });
          $('#magic-link-block').slideDown(200);
          showToast('success', 'Magic login link generated successfully!');
        } catch (err) {
          $btn.prop('disabled', false).html(origBtnHtml);
          if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
          const { msg, fieldErrors } = extractErrorData(err, 'Failed to generate magic link.');
          showLoginError(msg, fieldErrors);
        }
      }

      /**
       * Purpose: Handle session persistence and redirect upon verified login.
       * Parameters: data (object) containing token and user
       * Side Effects: Writes to localStorage, sets session cookie, updates axios headers, redirects page.
       */
      function handleLoginSuccess(data) {
        try {
          if (data.token) {
            localStorage.setItem('laraforgex_auth_token', data.token);
            document.cookie = `laraforgex_auth_token=${encodeURIComponent(data.token)}; path=/; max-age=86400; SameSite=Lax`;
          }
          if (data.user) {
            localStorage.setItem('laraforgex_user', JSON.stringify(data.user));
          }
          if (window.axios && data.token) {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
          }
        } catch (e) {
          console.warn('Storage persistence warning:', e);
        }

        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Authentication verified! Redirecting to dashboard...');
        }

        setTimeout(() => {
          window.location.replace('/admin/dashboard');
        }, 300);
      }

      /**
       * Purpose: Handle 2FA verification submission.
       * Parameters: $btn (jQuery), origBtn (string), code (string)
       */
      async function handleTwoFactorSubmit($btn, origBtn, code) {
        try {
          const res = await axios.post('/auth/2fa/verify', { code }, {
            headers: { 'Authorization': `Bearer ${tempToken}` }
          });
          if (res.data.success) {
            handleLoginSuccess({ token: tempToken, user: tempUser });
            return;
          }
          $btn.prop('disabled', false).html(origBtn);
          showLoginError(res.data.message || '2FA Verification failed.');
        } catch (err) {
          $btn.prop('disabled', false).html(origBtn);
          const { msg } = extractErrorData(err, 'Invalid 2FA code.');
          showLoginError(msg);
        }
      }

      // Live typing error clearers
      $('#email, #password').on('input', function () {
        $(this).removeClass('border-rose-400 ring-2 ring-rose-500/20');
        if ($(this).attr('id') === 'email') $('#email-error-msg').addClass('hidden');
        if ($(this).attr('id') === 'password') $('#password-error-msg').addClass('hidden');
      });

      // Submit handlers
      $('#login-form').submit(function (e) {
        e.preventDefault();
        clearLoginErrors();

        const $btn = $(this).find('button[type="submit"]');
        const origBtnHtml = $btn.html();
        const email = $('#email').val();
        const password = $('#password').val();
        const remember = $('#remember').is(':checked');
        const recaptchaToken = (recaptchaEnabled && typeof grecaptcha !== 'undefined') ? grecaptcha.getResponse() : null;

        $btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Signing in...');

        if (isMagicLinkMode) {
          handleMagicLinkSubmit($btn, origBtnHtml, email, recaptchaToken);
        } else {
          handlePasswordLoginSubmit($btn, origBtnHtml, { email, password, remember, 'g-recaptcha-response': recaptchaToken });
        }
      });

      $('#form-2fa').submit(function (e) {
        e.preventDefault();
        const code = $('#code-2fa').val();
        const $btn = $(this).find('button[type="submit"]');
        const origBtn = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Verifying...');
        handleTwoFactorSubmit($btn, origBtn, code);
      });

      $('#close-2fa').click(function () {
        $('#modal-2fa').addClass('hidden');
        tempToken = null;
        $('#code-2fa').val('');
      });

      checkReCaptcha();
    });
  </script>
@endsection
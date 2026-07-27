@extends('admin.layouts.auth')

@section('title', 'LaraforgeX — Reset Password')

@section('content')
<div class="min-h-screen bg-slate-50 relative flex flex-col justify-center py-12 sm:px-6 lg:px-8 overflow-hidden font-sans">
  
  <!-- Subtle geometric grid overlay -->
  <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-40 pointer-events-none"></div>
  
  <!-- Ambient background light glows -->
  <div class="absolute w-[600px] h-[600px] bg-brand-500/5 rounded-full blur-3xl -top-48 -left-48 pointer-events-none"></div>
  <div class="absolute w-[600px] h-[600px] bg-violet-500/5 rounded-full blur-3xl -bottom-48 -right-48 pointer-events-none"></div>

  <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 space-y-6 px-4">
    <!-- Card Frame -->
    <div class="bg-white/95 border border-slate-200/80 p-8 sm:p-10 rounded-2xl shadow-xl shadow-slate-200/50 space-y-8">
      
      <!-- Brand Logo / Header -->
      <div class="text-center space-y-3.5">
        <div class="inline-flex h-12 w-12 rounded-xl bg-gradient-to-tr from-brand-600 to-violet-500 items-center justify-center text-white font-extrabold text-2xl shadow-lg shadow-brand-600/20">
          L
        </div>
        <div class="space-y-1">
          <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Reset Password</h2>
          <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Set a new secure password for Laraforge<span class="text-brand-600">X</span></p>
        </div>
      </div>

      <!-- Reset Password form -->
      <form id="reset-form" class="space-y-5">
        <input type="hidden" id="token" name="token">
        
        <div class="space-y-1.5">
          <label for="email" class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Email Address</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
              <i class="fa-regular fa-envelope text-sm"></i>
            </span>
            <input id="email" name="email" type="email" required readonly 
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-500 focus:outline-none placeholder-slate-400 cursor-not-allowed">
          </div>
        </div>

        <div class="space-y-1.5">
          <label for="password" class="text-[10px] font-bold uppercase tracking-widest text-slate-400">New Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
              <i class="fa-solid fa-lock text-sm"></i>
            </span>
            <input id="password" name="password" type="password" required 
              class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-10 py-3 text-sm text-slate-900 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 transition placeholder-slate-400" 
              placeholder="••••••••••••">
            <button type="button" onclick="togglePasswordVisibility('password', 'toggle-password-icon-1')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer border-0 bg-transparent">
              <i id="toggle-password-icon-1" class="fa-regular fa-eye text-sm"></i>
            </button>
          </div>
        </div>

        <div class="space-y-1.5">
          <label for="password_confirmation" class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Confirm New Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
              <i class="fa-solid fa-lock text-sm"></i>
            </span>
            <input id="password_confirmation" name="password_confirmation" type="password" required 
              class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-10 py-3 text-sm text-slate-900 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 transition placeholder-slate-400" 
              placeholder="••••••••••••">
            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'toggle-password-icon-2')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer border-0 bg-transparent">
              <i id="toggle-password-icon-2" class="fa-regular fa-eye text-sm"></i>
            </button>
          </div>
        </div>

        <div id="recaptcha-container" class="hidden my-3 flex justify-center"></div>

        <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-sm transition shadow-lg shadow-brand-600/10 hover:shadow-brand-600/20 active:translate-y-px cursor-pointer border-0">
          <i class="fa-solid fa-lock-open mr-2"></i> Update Password
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let recaptchaEnabled = false;

    async function checkReCaptcha() {
        try {
            const res = await axios.get('/auth/captcha-config');
            if (res.data.success && res.data.data.recaptcha_enabled) {
                recaptchaEnabled = true;
                const siteKey = res.data.data.recaptcha_site_key;
                if (siteKey) {
                    $('#recaptcha-container').html(`<div class="g-recaptcha" data-sitekey="${siteKey}"></div>`).removeClass('hidden');
                    const script = document.createElement('script');
                    script.src = 'https://www.google.com/recaptcha/api.js';
                    script.async = true;
                    script.defer = true;
                    document.head.appendChild(script);
                }
            }
        } catch (e) {
            console.error('Failed to load captcha config', e);
        }
    }

    checkReCaptcha();

    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    const email = urlParams.get('email');

    if (!token || !email) {
        showToast('error', 'Invalid password reset parameters. Please check your recovery link.');
    } else {
        $('#token').val(token);
        $('#email').val(email);
    }

    $('#reset-form').submit(async function(e) {
        e.preventDefault();
        const recaptchaToken = (recaptchaEnabled && typeof grecaptcha !== 'undefined') ? grecaptcha.getResponse() : null;

        const payload = {
            email: $('#email').val(),
            token: $('#token').val(),
            password: $('#password').val(),
            password_confirmation: $('#password_confirmation').val(),
            'g-recaptcha-response': recaptchaToken
        };

        try {
            const response = await axios.post('/auth/reset-password', payload);
            if (response.data.success) {
                showToast('success', 'Password reset successfully! Redirecting to login...');
                setTimeout(() => {
                    window.location.href = '/admin/login';
                }, 1200);
            }
        } catch (err) {
            if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
            handleAjaxError(err);
        }
    });
});
</script>
@endsection

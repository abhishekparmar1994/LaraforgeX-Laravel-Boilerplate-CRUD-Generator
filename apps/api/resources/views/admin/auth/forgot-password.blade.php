@extends('admin.layouts.auth')

@section('title', 'LaraforgeX — Forgot Password')

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
          <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Forgot Password</h2>
          <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Recover your Laraforge<span class="text-brand-600">X</span> dashboard access</p>
        </div>
      </div>

      <!-- Forgot password form -->
      <form id="forgot-form" class="space-y-5">
        <div class="space-y-1.5">
          <label for="email" class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Email Address</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
              <i class="fa-regular fa-envelope text-sm"></i>
            </span>
            <input id="email" name="email" type="email" required 
              class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 transition placeholder-slate-400" 
              placeholder="admin@laraforgex.com">
          </div>
        </div>

        <div id="recaptcha-container" class="hidden my-3 flex justify-center"></div>

        <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-sm transition shadow-lg shadow-brand-600/10 hover:shadow-brand-600/20 active:translate-y-px cursor-pointer border-0">
          <i class="fa-solid fa-paper-plane mr-2"></i> Request Reset Token
        </button>

        <div class="text-center pt-2">
          <a href="/admin/login" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Login</a>
        </div>
      </form>

      <!-- Token display block -->
      <div id="reset-token-block" class="hidden space-y-4 pt-5 border-t border-slate-100">
        <div class="p-3.5 bg-brand-50/80 border border-brand-100/50 text-xs text-brand-700 rounded-xl font-semibold">
          <i class="fa-solid fa-circle-info mr-1.5 text-brand-500"></i>
          Since SMTP mail is locally stubbed, click the link below to verify your password reset:
        </div>
        <div id="token-display" class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-xs text-slate-600 break-all select-all">
          Generating token...
        </div>
        <a id="reset-page-anchor" href="#" class="block text-center py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition border-0">
          <i class="fa-solid fa-key mr-1.5"></i> Go to Reset Password Page
        </a>
      </div>
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

    $('#forgot-form').submit(async function(e) {
        e.preventDefault();
        const email = $('#email').val();
        const recaptchaToken = (recaptchaEnabled && typeof grecaptcha !== 'undefined') ? grecaptcha.getResponse() : null;

        try {
            const response = await axios.post('/auth/forgot-password', { 
                email,
                'g-recaptcha-response': recaptchaToken 
            });
            if (response.data.success) {
                const token = response.data.data.token;
                const resetLink = `${window.location.origin}/admin/reset-password?token=${token}&email=${encodeURIComponent(email)}`;
                
                $('#token-display').text(resetLink);
                $('#reset-page-anchor').attr('href', resetLink);
                $('#reset-token-block').slideDown(200);

                showToast('success', 'Reset token generated successfully!');
            }
        } catch (err) {
            if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
            handleAjaxError(err);
        }
    });
});
</script>
@endsection

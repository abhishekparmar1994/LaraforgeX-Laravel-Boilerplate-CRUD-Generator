{{-- Two-Factor Authentication Modal Challenge --}}
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
        <p class="text-xs text-slate-500 font-medium">Please enter the 6-digit verification code from Google Authenticator.</p>
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


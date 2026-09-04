<!-- ── Professional Floating AI CRM Copilot Widget ───────────────────────────── -->
<div id="laraforgex-floating-chat-root" class="z-50 font-sans">

  <!-- 1. Sleek Floating Trigger Pill Button -->
  <button id="btn-toggle-floating-chat"
    class="fixed bottom-5 right-5 z-50 rounded-full bg-slate-900 hover:bg-slate-800 text-white flex items-center gap-2.5 shadow-xl shadow-slate-900/20 hover:shadow-2xl hover:shadow-brand-500/10 border border-slate-700/60 transition-all duration-200 cursor-pointer active:scale-95 group focus:outline-none"
    style="height: 42px; padding: 0 14px;"
    title="Open AI CRM Assistant">
    <div class="relative flex items-center justify-center shrink-0">
      <img src="{{ asset('assets/image/ai_avatar.jpg') }}" alt="AI"
        class="rounded-full object-cover ring-2 ring-brand-500 shadow-xs"
        style="width: 26px; height: 26px; min-width: 26px;"
        onerror="this.style.display='none'; document.getElementById('fc-fallback-icon').style.display='block';">
      <i id="fc-fallback-icon" class="fa-solid fa-sparkles text-brand-400 text-xs hidden"></i>
      <span class="absolute -top-0.5 -right-0.5 flex" style="width: 8px; height: 8px;">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full bg-emerald-500 ring-1 ring-slate-900" style="width: 8px; height: 8px;"></span>
      </span>
    </div>
    <span class="text-xs font-semibold text-white tracking-tight flex items-center gap-1.5">
      Ask LaraforgeX AI
    </span>
  </button>

  <!-- 2. Chatbox Popup Window -->
  <div id="floating-chatbox-modal"
    class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-50 bg-white rounded-2xl shadow-2xl border border-slate-200/90 flex flex-col overflow-hidden opacity-0 pointer-events-none translate-y-6 scale-95 origin-bottom-right"
    style="width: 350px; height: 500px; max-width: calc(100vw - 1.5rem); max-height: calc(100vh - 4rem);">

    <!-- Header -->
    <div id="fc-modal-header" class="bg-slate-900 px-3.5 py-2.5 text-white flex items-center justify-between border-b border-slate-800 shrink-0 select-none" style="height: 48px;">
      <div class="flex items-center gap-2.5">
        <div class="relative shrink-0" style="width: 30px; height: 30px;">
          <img src="{{ asset('assets/image/ai_avatar.jpg') }}" alt="AI"
            class="rounded-full object-cover ring-1 ring-white/20 shadow-xs"
            style="width: 30px; height: 30px;">
          <span class="absolute bottom-0 right-0 rounded-full bg-emerald-400 ring-2 ring-slate-900" style="width: 7px; height: 7px;"></span>
        </div>
        <div>
          <h3 class="text-xs font-bold text-white tracking-tight leading-tight">LaraforgeX AI</h3>
          <p class="text-[10px] text-slate-400 leading-tight">CRM Intelligence Copilot</p>
        </div>
      </div>
      <div class="flex items-center gap-1">
        <button id="btn-fc-history"
          class="text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-800 transition cursor-pointer border-0 bg-transparent text-xs"
          title="Chat History">
          <i class="fa-solid fa-clock-rotate-left"></i>
        </button>
        <button id="btn-fc-new"
          class="text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-800 transition cursor-pointer border-0 bg-transparent text-xs"
          title="New Chat">
          <i class="fa-solid fa-plus text-[10px]"></i>
        </button>
        <button id="btn-fc-close"
          class="text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-800 transition cursor-pointer border-0 bg-transparent text-sm ml-0.5"
          title="Close">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    </div>

    <!-- View A: Active Chat View -->
    <div id="fc-chat-view" class="flex-1 flex flex-col overflow-hidden">
      <div id="fc-messages" class="flex-1 overflow-y-auto p-3.5 space-y-3 text-xs bg-slate-50/50">
        <div id="fc-welcome-box" class="space-y-2.5 pt-1">
          <p class="text-xs font-semibold text-slate-800 leading-relaxed">
            Hello! I'm LaraforgeX AI. 👋 Wondering what's here? I'll help you explore.
          </p>
          <div class="flex flex-wrap gap-1.5">
            <button class="fc-chip text-[11px] font-medium text-slate-700 bg-white hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 rounded-lg px-2.5 py-1.5 transition shadow-2xs cursor-pointer">
              📊 Total Leads
            </button>
            <button class="fc-chip text-[11px] font-medium text-slate-700 bg-white hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 rounded-lg px-2.5 py-1.5 transition shadow-2xs cursor-pointer">
              📍 Vadodara Leads
            </button>
            <button class="fc-chip text-[11px] font-medium text-slate-700 bg-white hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 rounded-lg px-2.5 py-1.5 transition shadow-2xs cursor-pointer">
              🎯 Qualified Leads
            </button>
          </div>
        </div>
      </div>

      <!-- Bottom Input Card -->
      <div class="p-2.5 bg-white border-t border-slate-100 shrink-0">
        <form id="fc-form" class="bg-slate-100 rounded-2xl p-2 border border-slate-200/50 focus-within:border-brand-500 focus-within:bg-white transition-all">
          <textarea id="fc-prompt" rows="1" required placeholder="Ask a question..."
            class="w-full bg-transparent border-0 text-xs text-slate-800 placeholder-slate-400 focus:outline-none resize-none leading-relaxed"></textarea>
          <div class="flex items-center justify-between pt-1">
            <button type="button" id="fc-clip-btn" class="text-slate-400 hover:text-slate-600 transition text-xs border-0 bg-transparent cursor-pointer p-0.5" title="New conversation">
              <i class="fa-solid fa-paperclip"></i>
            </button>
            <button type="submit" id="fc-submit-btn"
              class="rounded-full bg-slate-900 hover:bg-black active:scale-95 text-white flex items-center justify-center transition shadow-xs cursor-pointer border-0 disabled:opacity-40 shrink-0"
              style="width: 26px; height: 26px;">
              <i class="fa-solid fa-arrow-up text-[10px]"></i>
            </button>
          </div>
        </form>
        <p class="text-center text-[10px] text-slate-400 pt-1.5 pb-0 leading-tight">
          This chat is recorded. By chatting, you agree to the <span class="underline">AI Terms</span>. <strong class="text-slate-600">LaraforgeX</strong>
        </p>
      </div>
    </div>

    <!-- View B: Chat History View -->
    <div id="fc-history-view" class="flex-1 flex flex-col overflow-hidden bg-white hidden">
      <div class="p-2.5 px-3 border-b border-slate-100 flex items-center justify-between bg-slate-50 shrink-0">
        <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
          <i class="fa-solid fa-clock-rotate-left text-brand-600 text-[11px]"></i> History
        </span>
        <button id="btn-fc-back" class="text-xs text-brand-600 hover:text-brand-700 font-semibold border-0 bg-transparent cursor-pointer flex items-center gap-1">
          <span>Back to Chat</span> <i class="fa-solid fa-chevron-right text-[9px]"></i>
        </button>
      </div>
      <div id="fc-history-list" class="flex-1 overflow-y-auto p-2 space-y-1 text-xs">
        <div class="p-4 text-center text-slate-400 text-xs">Loading history...</div>
      </div>
    </div>

  </div>
</div>

<style>
  #floating-chatbox-modal { transition: opacity 0.2s ease, transform 0.2s ease; }
  #fc-messages table { width: 100%; border-collapse: collapse; margin: 0.4rem 0; border-radius: 0.5rem; overflow: hidden; font-size: 0.7rem; background: white; border: 1px solid #e2e8f0; }
  #fc-messages th { background-color: #f8fafc; color: #334155; font-weight: 700; padding: 0.4rem 0.5rem; border: 1px solid #e2e8f0; text-align: left; }
  #fc-messages td { padding: 0.35rem 0.5rem; border: 1px solid #f1f5f9; color: #475569; }
  #fc-messages tr:nth-child(even) td { background-color: #f8fafc; }
  #fc-messages a { color: #2563eb; text-decoration: underline; font-weight: 600; word-break: break-all; }
  @keyframes pulse-glow { 0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); } 50% { box-shadow: 0 0 0 6px rgba(37, 99, 235, 0); } }
  .pulse-copilot { animation: pulse-glow 2s infinite; }
</style>

<script src="{{ asset('assets/js/floating_ai_agent.js') }}?v={{ file_exists(public_path('assets/js/floating_ai_agent.js')) ? filemtime(public_path('assets/js/floating_ai_agent.js')) : time() }}"></script>
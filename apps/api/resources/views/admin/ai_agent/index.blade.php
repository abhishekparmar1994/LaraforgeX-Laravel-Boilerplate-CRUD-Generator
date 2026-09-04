@extends('admin.layouts.app')

@section('title', 'LaraforgeX — AI CRM Intelligence Agent')

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">CRM</a>
    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
    <span class="text-slate-700">AI Intelligence Agent</span>
  </nav>
@endsection

@section('content')
  <div class="h-[calc(100vh-8.5rem)] flex gap-4 font-sans antialiased overflow-hidden">

    <!-- ── 1. CONVERSATION SESSIONS SIDEBAR ────────────────────────────── -->
    <div class="w-72 bg-white border border-slate-200/90 rounded-2xl flex flex-col shadow-xs overflow-hidden shrink-0">
      <div class="p-3.5 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div
            class="h-8 w-8 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white text-xs shadow-xs">
            <i class="fa-solid fa-robot"></i>
          </div>
          <div>
            <h2 class="text-xs font-extrabold text-slate-900 leading-tight">AI Assistant</h2>
            <p class="text-[10px] text-slate-400 font-medium">CRM Intelligence</p>
          </div>
        </div>
        <button id="btn-new-chat"
          class="px-2.5 py-1.5 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-600 text-xs font-bold transition flex items-center gap-1.5 border-0 cursor-pointer">
          <i class="fa-solid fa-plus text-[10px]"></i>
          <span>New</span>
        </button>
      </div>

      <!-- Sessions List -->
      <div id="conversation-list" class="flex-1 overflow-y-auto p-2.5 space-y-1.5 text-xs">
        <div class="p-4 text-center text-slate-400 text-xs">Loading sessions...</div>
      </div>
    </div>

    <!-- ── 2. MAIN CHAT TIMELINE ──────────────────────────────────────── -->
    <div class="flex-1 bg-white border border-slate-200/90 rounded-2xl flex flex-col shadow-xs overflow-hidden">
      <!-- Chat Header -->
      <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-2.5">
          <div class="h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></div>
          <div>
            <h1 id="active-chat-title" class="text-xs font-extrabold text-slate-900">CRM Intelligence Console</h1>
            <p class="text-[10px] text-slate-400 font-medium">Connected directly to live LaraforgeX CRM Database</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <span
            class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60">
            <i class="fa-solid fa-shield-halved mr-1"></i> Strict Tool Sandboxing
          </span>
        </div>
      </div>

      <!-- Messages Container -->
      <div id="chat-messages" class="flex-1 overflow-y-auto p-5 space-y-4">
        <!-- Welcome Hero Message -->
        <div id="welcome-hero" class="max-w-xl mx-auto my-8 text-center space-y-4">
          <div
            class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-violet-600 flex items-center justify-center text-white text-2xl mx-auto shadow-lg shadow-brand-500/20">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
          </div>
          <div class="space-y-1">
            <h3 class="text-base font-extrabold text-slate-900">Ask anything about your CRM</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Get real-time statistics, inspect qualified leads, check
              uncontacted opportunities, and analyze conversion performance.</p>
          </div>

          <!-- Quick Prompt Chips -->
          <div class="flex flex-wrap justify-center gap-2 pt-2">
            <button
              class="quick-chip px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 text-xs font-semibold text-slate-700 transition cursor-pointer">
              📊 How many leads do we have?
            </button>
            <button
              class="quick-chip px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 text-xs font-semibold text-slate-700 transition cursor-pointer">
              🎯 Show me qualified leads
            </button>
            <button
              class="quick-chip px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 text-xs font-semibold text-slate-700 transition cursor-pointer">
              ⏰ Leads uncontacted in 7 days
            </button>
          </div>
        </div>
      </div>


      <!-- Input Form -->
      <div class="p-3.5 border-t border-slate-100 bg-slate-50/40">
        <form id="chat-form" class="flex gap-2.5 items-end">
          <div class="flex-1 relative">
            <textarea id="prompt-input" rows="1" required
              placeholder="Ask about your leads, conversion rates, pipeline values..."
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-800 focus:outline-none focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 transition resize-none placeholder-slate-400"></textarea>
          </div>
          <button type="submit" id="btn-submit"
            class="px-4 py-3 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-xs shadow-md shadow-brand-600/20 active:translate-y-px transition flex items-center gap-2 shrink-0 border-0 cursor-pointer disabled:opacity-50">
            <span>Send</span>
            <i class="fa-solid fa-paper-plane text-[11px]"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/marked@12.0.2/marked.min.js"></script>
  <style>
    .markdown-body table {
      width: 100%;
      border-collapse: collapse;
      margin: 0.75rem 0;
      border-radius: 0.75rem;
      overflow: hidden;
      font-size: 0.75rem;
      background: white;
      border: 1px solid #e2e8f0;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .markdown-body th {
      background-color: #f8fafc;
      color: #334155;
      font-weight: 700;
      padding: 0.6rem 0.85rem;
      border: 1px solid #e2e8f0;
      text-align: left;
    }

    .markdown-body td {
      padding: 0.55rem 0.85rem;
      border: 1px solid #f1f5f9;
      color: #475569;
    }

    .markdown-body tr:nth-child(even) td {
      background-color: #f8fafc/60;
    }

    .markdown-body tr:hover td {
      background-color: #f0fdf4;
    }

    .markdown-body a {
      color: #4f46e5;
      text-decoration: underline;
      font-weight: 600;
      word-break: break-all;
    }

    .markdown-body a:hover {
      color: #4338ca;
    }

    .markdown-body ul {
      list-style-type: disc;
      padding-left: 1.25rem;
      margin: 0.5rem 0;
    }

    .markdown-body ol {
      list-style-type: decimal;
      padding-left: 1.25rem;
      margin: 0.5rem 0;
    }

    .markdown-body li {
      margin: 0.25rem 0;
    }

    @keyframes shimmer {
      0% {
        transform: translateX(-100%);
      }

      100% {
        transform: translateX(100%);
      }
    }

    .animate-shimmer {
      animation: shimmer 1.6s infinite linear;
    }

    @keyframes pulse-glow {

      0%,
      100% {
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.45);
      }

      50% {
        box-shadow: 0 0 0 8px rgba(79, 70, 229, 0);
      }
    }

    .pulse-avatar {
      animation: pulse-glow 2s infinite;
    }
  </style>
  <script>
    $(document).ready(function () {
      let activeConversationId = null;

      loadConversations();

      $('#btn-new-chat').on('click', function () {
        activeConversationId = null;
        $('#active-chat-title').text('New Conversation');
        $('#chat-messages').html($('#welcome-hero').parent().html());
        attachQuickChips();
        $('.session-item').removeClass('bg-brand-50/80 border-brand-200 text-brand-700 font-bold');
        $('#prompt-input').focus();
      });

      function attachQuickChips() {
        $('.quick-chip').off('click').on('click', function () {
          const text = $(this).text().replace(/^[^\w]+/, '').trim();
          $('#prompt-input').val(text);
          $('#chat-form').submit();
        });
      }
      attachQuickChips();

      async function loadConversations() {
        try {
          const res = await axios.get('/agent/conversations');
          const list = res.data.data || [];
          const $el = $('#conversation-list');
          $el.empty();

          if (list.length === 0) {
            $el.html('<p class="p-4 text-center text-slate-400 text-xs">No conversations yet.</p>');
            return;
          }

          list.forEach(c => {
            const item = $(`
              <div class="session-item flex items-center justify-between p-2.5 rounded-xl border border-transparent hover:bg-slate-100 transition cursor-pointer group" data-id="${c.id}">
                <div class="flex items-center gap-2 truncate pr-2">
                  <i class="fa-regular fa-message text-slate-400 text-[11px]"></i>
                  <span class="truncate">${escapeHtml(c.title || 'Conversation')}</span>
                </div>
                <button class="btn-delete-conv opacity-0 group-hover:opacity-100 text-slate-300 hover:text-rose-500 transition border-0 bg-transparent cursor-pointer p-1">
                  <i class="fa-regular fa-trash-can text-[10px]"></i>
                </button>
              </div>
            `);

            item.on('click', function (e) {
              if ($(e.target).closest('.btn-delete-conv').length) return;
              openConversation(c.id, c.title);
            });

            item.find('.btn-delete-conv').on('click', async function (e) {
              e.stopPropagation();
              if (!confirm('Delete this conversation?')) return;
              try {
                await axios.delete(`/agent/conversations/${c.id}`);
                if (activeConversationId === c.id) $('#btn-new-chat').click();
                loadConversations();
              } catch (err) {
                alert('Failed to delete conversation.');
              }
            });

            $el.append(item);
          });
        } catch (e) {
          console.error('Failed to load conversations', e);
        }
      }

      async function openConversation(id, title) {
        activeConversationId = id;
        $('#active-chat-title').text(title || 'Conversation');
        $('.session-item').removeClass('bg-brand-50/80 border-brand-200 text-brand-700 font-bold');
        $(`.session-item[data-id="${id}"]`).addClass('bg-brand-50/80 border-brand-200 text-brand-700 font-bold');

        try {
          const res = await axios.get(`/agent/conversations/${id}`);
          const conv = res.data.data;
          const $box = $('#chat-messages');
          $box.empty();

          if (conv && conv.messages) {
            conv.messages.forEach(m => {
              if (m.role === 'user') appendUserMessage(m.content);
              else if (m.role === 'assistant') appendAssistantMessage(m.content);
            });
          }
          scrollToBottom();
        } catch (e) {
          console.error('Failed to load conversation history', e);
        }
      }

      // Send on Enter (Shift+Enter for newline)
      $('#prompt-input').on('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
          e.preventDefault();
          $('#chat-form').submit();
        }
      });

      $('#prompt-input').on('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
      });

      let statusInterval = null;
      const statusPhrases = [
        '🧠 Analyzing your request...',
        '🔍 Querying CRM database & live tables...',
        '⚡ Evaluating pipeline data & records...',
        '✨ Synthesizing intelligent response...'
      ];

      function showAiLoadingBubble() {
        hideAiLoadingBubble();
        let phraseIdx = 0;

        $('#chat-messages').append(`
            <div id="ai-loading-bubble" class="flex items-start gap-3 my-2">
              <div class="h-8 w-8 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center text-xs shadow-md shadow-brand-500/20 shrink-0 mt-0.5 pulse-avatar">
                <i class="fa-solid fa-robot animate-bounce"></i>
              </div>
              <div class="max-w-md bg-white border border-slate-200/90 rounded-2xl rounded-tl-sm p-4 text-xs shadow-xs space-y-3">
                <div class="flex items-center gap-2.5">
                  <div class="flex space-x-1 items-center">
                    <span class="w-2 h-2 rounded-full bg-brand-600 animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-2 h-2 rounded-full bg-violet-600 animate-bounce" style="animation-delay: 300ms"></span>
                  </div>
                  <span id="ai-live-status" class="font-bold text-slate-700 text-xs tracking-tight">
                    🧠 Analyzing your request...
                  </span>
                </div>
                <div class="space-y-1.5 pt-1">
                  <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-brand-400 to-transparent animate-shimmer" style="background-size: 200% 100%;"></div>
                  </div>
                  <div class="h-1.5 w-3/4 bg-slate-100 rounded-full overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-indigo-400 to-transparent animate-shimmer" style="background-size: 200% 100%;"></div>
                  </div>
                </div>
              </div>
            </div>
          `);
        scrollToBottom();

        statusInterval = setInterval(() => {
          phraseIdx = (phraseIdx + 1) % statusPhrases.length;
          $('#ai-live-status').fadeOut(150, function () {
            $(this).text(statusPhrases[phraseIdx]).fadeIn(150);
          });
        }, 1600);
      }

      function hideAiLoadingBubble() {
        if (statusInterval) {
          clearInterval(statusInterval);
          statusInterval = null;
        }
        $('#ai-loading-bubble').remove();
      }

      $('#chat-form').submit(async function (e) {
        e.preventDefault();
        const prompt = $('#prompt-input').val().trim();
        if (!prompt) return;

        appendUserMessage(prompt);
        $('#prompt-input').val('').css('height', 'auto');
        $('#welcome-hero').remove();
        scrollToBottom();

        // Trigger rich animated loading
        $('#btn-submit').prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Searching...</span>');
        showAiLoadingBubble();

        try {
          const res = await axios.post('/agent/chat', {
            message: prompt,
            conversation_id: activeConversationId,
            current_route: window.location.pathname
          });

          const data = res.data.data;
          activeConversationId = data.conversation_id;

          hideAiLoadingBubble();
          appendAssistantMessage(data.message, data.tool_activity, data.actions);
          loadConversations();
        } catch (err) {
          hideAiLoadingBubble();
          console.error(err);
          const msg = err.response?.data?.message || 'The AI Agent encountered an error communicating with the CRM.';
          appendErrorMessage(msg);
        } finally {
          hideAiLoadingBubble();
          $('#btn-submit').prop('disabled', false).html('<span>Send</span> <i class="fa-solid fa-paper-plane text-[11px]"></i>');
          scrollToBottom();
        }
      });

      function appendUserMessage(text) {
        $('#chat-messages').append(`
          <div class="flex justify-end">
            <div class="max-w-xl bg-gradient-to-r from-brand-600 to-indigo-600 text-white rounded-2xl rounded-tr-sm px-4 py-3 text-xs shadow-xs leading-relaxed">
              ${escapeHtml(text)}
            </div>
          </div>
        `);
      }

      function appendAssistantMessage(text, tools = [], actions = []) {
        let actionsHtml = '';
        if (actions && actions.length > 0) {
          actionsHtml = `
            <div class="flex flex-wrap gap-2 pt-3 border-t border-slate-100 mt-3">
              ${actions.map(a => `<a href="${escapeHtml(a.url)}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 text-xs font-bold text-slate-700 transition"><i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> ${escapeHtml(a.label)}</a>`).join('')}
            </div>
          `;
        }

        $('#chat-messages').append(`
          <div class="flex items-start gap-3">
            <div class="h-8 w-8 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center text-xs shadow-xs shrink-0 mt-0.5">
              <i class="fa-solid fa-robot"></i>
            </div>
            <div class="max-w-2xl bg-slate-50 border border-slate-200/90 text-slate-800 rounded-2xl rounded-tl-sm p-4 text-xs shadow-xs leading-relaxed space-y-2">
              <div class="markdown-body space-y-2 leading-relaxed font-sans">${formatMarkdown(text)}</div>
              ${actionsHtml}
            </div>
          </div>
        `);
      }

      function appendErrorMessage(text) {
        $('#chat-messages').append(`
          <div class="flex items-start gap-3">
            <div class="h-8 w-8 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
              <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="max-w-2xl bg-rose-50/80 border border-rose-200 text-rose-900 rounded-2xl p-4 text-xs">
              ${escapeHtml(text)}
            </div>
          </div>
        `);
      }

      function formatMarkdown(text) {
        if (!text) return '';

        // Clean squashed table rows (e.g. '||' without newlines)
        let cleanText = text.replace(/\|\|\s*\|/g, '|\n|').replace(/\|\|\s*/g, '|\n| ');

        if (typeof marked !== 'undefined' && marked.parse) {
          try {
            marked.setOptions({
              breaks: true,
              gfm: true
            });
            return marked.parse(cleanText);
          } catch (e) {
            console.error('Marked parse error', e);
          }
        }

        let parsed = cleanText;

        // Fallback markdown table parser
        parsed = parsed.replace(/(?:\|[^\n]+\|\r?\n?)+/g, function (tableMatch) {
          const lines = tableMatch.trim().split(/\r?\n/).filter(l => l.trim().startsWith('|'));
          if (lines.length < 2) return tableMatch;

          let html = '<div class="overflow-x-auto my-3 rounded-xl border border-slate-200/90 shadow-2xs"><table class="w-full text-xs text-left border-collapse">';
          const headerCols = lines[0].split('|').slice(1, -1).map(c => c.trim());
          html += '<thead class="bg-slate-100/90 text-slate-800 font-bold border-b border-slate-200"><tr>';
          headerCols.forEach(c => html += `<th class="px-3.5 py-2.5 border-r border-slate-200 last:border-r-0">${escapeHtml(c)}</th>`);
          html += '</tr></thead><tbody>';

          const dataLines = lines.slice(2);
          dataLines.forEach((rowLine, rIdx) => {
            const cols = rowLine.split('|').slice(1, -1).map(c => c.trim());
            html += `<tr class="${rIdx % 2 === 0 ? 'bg-white' : 'bg-slate-50/50'} hover:bg-brand-50/30 transition border-b border-slate-100 last:border-b-0">`;
            cols.forEach(c => {
              let cellContent = escapeHtml(c);
              cellContent = cellContent.replace(/\[(.*?)\]\((https?:\/\/[^\s\)]+)\)/g, '<a href="$2" target="_blank" class="text-brand-600 hover:text-brand-700 underline font-semibold break-all">$1</a>');
              html += `<td class="px-3.5 py-2 border-r border-slate-100 last:border-r-0 text-slate-700">${cellContent}</td>`;
            });
            html += '</tr>';
          });

          html += '</tbody></table></div>';
          return html;
        });

        parsed = parsed.replace(/\[(.*?)\]\((https?:\/\/[^\s\)]+)\)/g, '<a href="$2" target="_blank" class="text-brand-600 hover:text-brand-700 underline font-semibold break-all">$1</a>');
        parsed = parsed.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-slate-900">$1</strong>');
        parsed = parsed.replace(/\*(.*?)\*/g, '<em>$1</em>');
        parsed = parsed.replace(/`([^`]+)`/g, '<code class="bg-slate-200/80 text-slate-800 px-1.5 py-0.5 rounded text-[11px] font-mono">$1</code>');
        parsed = parsed.replace(/^### (.*$)/gim, '<h3 class="font-extrabold text-slate-900 text-xs tracking-tight mt-3 mb-1">$1</h3>');
        parsed = parsed.replace(/^## (.*$)/gim, '<h2 class="font-extrabold text-slate-900 text-sm tracking-tight mt-3 mb-1">$1</h2>');
        parsed = parsed.replace(/^# (.*$)/gim, '<h1 class="font-black text-slate-900 text-base tracking-tight mt-4 mb-1.5">$1</h1>');
        parsed = parsed.replace(/^[•\-\*] (.*$)/gim, '<li class="ml-4 list-disc text-slate-700 my-0.5">$1</li>');
        parsed = parsed.replace(/\n\n/g, '<br><br>');

        return parsed;
      }

      function escapeHtml(string) {
        const entityMap = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
        return String(string).replace(/[&<>"']/g, s => entityMap[s]);
      }

      function scrollToBottom() {
        const $c = $('#chat-messages');
        $c.scrollTop($c[0].scrollHeight);
      }
    });
  </script>
@endsection
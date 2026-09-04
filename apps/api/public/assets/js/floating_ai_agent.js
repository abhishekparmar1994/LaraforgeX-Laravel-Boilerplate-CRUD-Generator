/**
 * LaraforgeX Floating AI CRM Copilot Controller
 */
(function () {
  let fcConvId = null;
  let fcInterval = null;
  const phrases = ['Analyzing...', 'Querying CRM tables...', 'Evaluating metrics...', 'Synthesizing response...'];

  function initChatbox() {
    $('#btn-toggle-floating-chat').on('click', toggleModal);
    $('#btn-fc-close').on('click', closeModal);
    $('#btn-fc-new, #fc-clip-btn').on('click', resetChat);
    $('#btn-fc-history').on('click', showHistoryView);
    $('#btn-fc-back').on('click', showChatView);

    attachChips();
    $('#fc-prompt').on('keydown', function (e) {
      if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); $('#fc-form').submit(); }
    }).on('input', function () {
      this.style.height = 'auto';
      this.style.height = Math.min(this.scrollHeight, 90) + 'px';
    });

    $('#fc-form').on('submit', handleSend);
  }

  function attachChips() {
    $('.fc-chip').off('click').on('click', function () {
      $('#fc-prompt').val($(this).text().replace(/^[^\w]+/, '').trim());
      $('#fc-form').submit();
    });
  }

  function toggleModal() {
    if ($('#floating-chatbox-modal').hasClass('opacity-0')) openModal(); else closeModal();
  }

  function openModal() {
    $('#floating-chatbox-modal').removeClass('opacity-0 pointer-events-none translate-y-6 scale-95');
    $('#btn-toggle-floating-chat').addClass('scale-0 pointer-events-none');
    setTimeout(() => $('#fc-prompt').focus(), 150);
  }

  function closeModal() {
    $('#floating-chatbox-modal').addClass('opacity-0 pointer-events-none translate-y-6 scale-95');
    $('#btn-toggle-floating-chat').removeClass('scale-0 pointer-events-none');
  }

  function showHistoryView() {
    $('#fc-chat-view').addClass('hidden');
    $('#fc-history-view').removeClass('hidden');
    loadHistory();
  }

  function showChatView() {
    $('#fc-history-view').addClass('hidden');
    $('#fc-chat-view').removeClass('hidden');
    setTimeout(() => $('#fc-prompt').focus(), 100);
  }

  async function loadHistory() {
    const $list = $('#fc-history-list');
    $list.html('<div class="p-4 text-center text-slate-400 text-xs"><i class="fa-solid fa-circle-notch fa-spin mr-1"></i> Loading history...</div>');
    try {
      const res = await axios.get('/agent/conversations');
      const list = res.data.data || [];
      if (!list.length) {
        $list.html('<p class="p-4 text-center text-slate-400 text-xs">No saved conversations yet.</p>');
        return;
      }
      $list.empty();
      list.forEach(c => {
        const isAct = c.id === fcConvId;
        const timeBadge = formatHistoryDate(c.updated_at || c.created_at);
        const $el = $(`
          <div class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition text-xs group ${isAct ? 'bg-brand-50 text-brand-700 font-bold' : 'hover:bg-slate-100 text-slate-700'}">
            <div class="flex items-start gap-2 truncate pr-2 min-w-0">
              <i class="fa-regular fa-message text-[11px] mt-0.5 shrink-0 ${isAct ? 'text-brand-600' : 'text-slate-400'}"></i>
              <div class="truncate min-w-0">
                <p class="truncate leading-tight">${escapeHtml(c.title || 'Conversation')}</p>
                <span class="text-[9px] text-slate-400 font-medium block mt-0.5">${timeBadge}</span>
              </div>
            </div>
            <button class="fc-del-btn opacity-0 group-hover:opacity-100 text-slate-300 hover:text-rose-500 p-1 border-0 bg-transparent cursor-pointer transition shrink-0">
              <i class="fa-regular fa-trash-can text-[10px]"></i>
            </button>
          </div>
        `);
        $el.on('click', e => {
          if (!$(e.target).closest('.fc-del-btn').length) openHistoryItem(c.id);
        });
        $el.find('.fc-del-btn').on('click', async e => {
          e.stopPropagation();
          if (!confirm('Delete conversation?')) return;
          try {
            await axios.delete(`/agent/conversations/${c.id}`);
            if (fcConvId === c.id) resetChat();
            loadHistory();
          } catch (err) {}
        });
        $list.append($el);
      });
    } catch (e) {
      $list.html('<p class="p-4 text-center text-rose-500 text-xs">Failed to load history.</p>');
    }
  }

  async function openHistoryItem(id) {
    fcConvId = id;
    showChatView();
    $('#fc-messages').html('<div class="p-4 text-center text-slate-400 text-xs"><i class="fa-solid fa-circle-notch fa-spin mr-1"></i> Loading conversation...</div>');
    try {
      const res = await axios.get(`/agent/conversations/${id}`);
      const conv = res.data.data;
      $('#fc-messages').empty();
      if (conv && conv.messages && conv.messages.length) {
        conv.messages.forEach(m => {
          if (m.role === 'user') appendUserMsg(m.content, m.created_at);
          else if (m.role === 'assistant') appendBotMsg(m.content, [], m.created_at);
        });
      } else {
        resetChat();
      }
      scrollFc();
    } catch (e) {
      $('#fc-messages').html('<p class="p-4 text-center text-rose-500 text-xs">Failed to load chat.</p>');
    }
  }

  function resetChat() {
    fcConvId = null;
    showChatView();
    $('#fc-messages').html(`
      <div id="fc-welcome-box" class="space-y-2.5 pt-1">
        <p class="text-xs font-semibold text-slate-800 leading-relaxed">Hello! I'm LaraforgeX AI. 👋 Wondering what's here? I'll help you explore.</p>
        <div class="flex flex-wrap gap-1.5">
          <button class="fc-chip text-[11px] font-medium text-slate-700 bg-white hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 rounded-lg px-2.5 py-1.5 transition shadow-2xs cursor-pointer">📊 Total Leads</button>
          <button class="fc-chip text-[11px] font-medium text-slate-700 bg-white hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 rounded-lg px-2.5 py-1.5 transition shadow-2xs cursor-pointer">📍 Vadodara Leads</button>
          <button class="fc-chip text-[11px] font-medium text-slate-700 bg-white hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 border border-slate-200 rounded-lg px-2.5 py-1.5 transition shadow-2xs cursor-pointer">🎯 Qualified Leads</button>
        </div>
      </div>
    `);
    attachChips();
    $('#fc-prompt').focus();
  }

  async function handleSend(e) {
    e.preventDefault();
    const prompt = $('#fc-prompt').val().trim();
    if (!prompt) return;

    $('#fc-welcome-box').remove();
    appendUserMsg(prompt, new Date().toISOString());
    $('#fc-prompt').val('').css('height', 'auto');
    scrollFc();

    $('#fc-submit-btn').prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin text-[10px]"></i>');
    showLoading();

    try {
      const res = await axios.post('/agent/chat', {
        message: prompt,
        conversation_id: fcConvId,
        current_route: window.location.pathname
      });
      const data = res.data.data;
      fcConvId = data.conversation_id;
      hideLoading();
      appendBotMsg(data.message, data.actions, new Date().toISOString());
    } catch (err) {
      hideLoading();
      appendBotMsg(err.response?.data?.message || 'Error communicating with AI assistant.', [], new Date().toISOString());
    } finally {
      hideLoading();
      $('#fc-submit-btn').prop('disabled', false).html('<i class="fa-solid fa-arrow-up text-[10px]"></i>');
      scrollFc();
    }
  }

  function appendUserMsg(txt, time) {
    const timeLabel = formatTime(time);
    $('#fc-messages').append(`
      <div class="flex flex-col items-end space-y-0.5">
        <div class="max-w-[85%] bg-brand-600 text-white rounded-2xl rounded-tr-xs px-3.5 py-2 text-xs shadow-xs leading-relaxed break-words font-normal">
          ${escapeHtml(txt)}
        </div>
        <span class="text-[9px] text-slate-400 pr-1 select-none font-medium">${timeLabel}</span>
      </div>
    `);
  }

  function appendBotMsg(txt, actions = [], time) {
    let actHtml = '';
    if (actions && actions.length > 0) {
      actHtml = `<div class="flex flex-wrap gap-1.5 pt-2 border-t border-slate-100 mt-2">${actions.map(a => `<a href="${escapeHtml(a.url)}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-700 hover:text-brand-600 transition">${escapeHtml(a.label)}</a>`).join('')}</div>`;
    }
    const timeLabel = formatTime(time);
    $('#fc-messages').append(`
      <div class="flex items-start gap-2.5">
        <img src="/assets/image/ai_avatar.jpg" alt="AI" class="rounded-full object-cover shrink-0 mt-0.5 border border-slate-200" style="width: 24px; height: 24px;">
        <div class="max-w-[88%] space-y-0.5">
          <div class="bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-tl-xs p-3 text-xs leading-relaxed space-y-1.5 shadow-2xs">
            <div class="leading-relaxed font-sans">${parseMarkdown(txt)}</div>
            ${actHtml}
          </div>
          <span class="text-[9px] text-slate-400 pl-1 select-none font-medium">${timeLabel}</span>
        </div>
      </div>
    `);
  }

  function showLoading() {
    hideLoading();
    let idx = 0;
    $('#fc-messages').append(`
      <div id="fc-loader" class="flex items-start gap-2.5 my-1">
        <img src="/assets/image/ai_avatar.jpg" alt="AI" class="rounded-full object-cover shrink-0 mt-0.5 border border-slate-200 pulse-copilot" style="width: 24px; height: 24px;">
        <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-xs p-2.5 text-xs flex items-center gap-2 shadow-2xs">
          <span class="w-1.5 h-1.5 rounded-full bg-brand-600 animate-bounce"></span>
          <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce" style="animation-delay:150ms"></span>
          <span class="w-1.5 h-1.5 rounded-full bg-violet-600 animate-bounce" style="animation-delay:300ms"></span>
          <span id="fc-loader-txt" class="font-semibold text-slate-700 text-xs">Analyzing...</span>
        </div>
      </div>
    `);
    scrollFc();
    fcInterval = setInterval(() => {
      idx = (idx + 1) % phrases.length;
      $('#fc-loader-txt').text(phrases[idx]);
    }, 1500);
  }

  function hideLoading() {
    if (fcInterval) { clearInterval(fcInterval); fcInterval = null; }
    $('#fc-loader').remove();
  }

  function formatTime(iso) {
    const d = iso ? new Date(iso) : new Date();
    return isNaN(d.getTime()) ? '' : d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true });
  }

  function formatHistoryDate(iso) {
    if (!iso) return '';
    const d = new Date(iso), now = new Date();
    if (isNaN(d.getTime())) return '';
    const timeStr = d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true });
    if (d.toDateString() === now.toDateString()) return `Today • ${timeStr}`;
    const yest = new Date(now);
    yest.setDate(now.getDate() - 1);
    if (d.toDateString() === yest.toDateString()) return `Yesterday • ${timeStr}`;
    return `${d.toLocaleDateString([], { month: 'short', day: 'numeric' })} • ${timeStr}`;
  }

  function parseMarkdown(txt) {
    if (!txt) return '';
    if (typeof marked !== 'undefined' && marked.parse) {
      try { return marked.parse(txt.replace(/\|\|\s*\|/g, '|\n|').replace(/\|\|\s*/g, '|\n| ')); } catch (e) {}
    }
    return escapeHtml(txt).replace(/\n/g, '<br>');
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, s => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[s]);
  }

  function scrollFc() {
    const $c = $('#fc-messages');
    if ($c.length) $c.scrollTop($c[0].scrollHeight);
  }

  $(document).ready(initChatbox);
})();

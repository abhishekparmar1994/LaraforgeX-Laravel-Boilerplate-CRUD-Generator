@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Website: ' . $website->name)

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/websites" class="hover:text-brand-600 transition">Websites</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">{{ $website->name }}</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- Header Card -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-start gap-4 min-w-0">
        <div class="h-14 w-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 font-extrabold text-xl flex items-center justify-center shrink-0">
          <i class="fa-solid fa-globe"></i>
        </div>
        <div class="min-w-0">
          <div class="flex items-center gap-2.5 flex-wrap">
            <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $website->name }}</h2>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $website->status === 'live' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-brand-50 text-brand-700 border-brand-200' }}">
              {{ ucfirst($website->status) }}
            </span>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
              {{ $website->technology ?: 'Custom Architecture' }}
            </span>
          </div>
          <p class="text-xs text-slate-500 font-medium mt-1">
            Client: 
            @if($website->client)
              <a href="/admin/clients/{{ $website->client_id }}" class="text-brand-600 font-bold hover:underline">{{ $website->client->name }}</a>
            @else
              <span>N/A</span>
            @endif
            @if($website->domain) • <a href="{{ $website->url ?: 'https://' . $website->domain }}" target="_blank" class="text-emerald-600 font-bold hover:underline"><i class="fa-solid fa-link text-[10px]"></i> {{ $website->domain }}</a> @endif
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button onclick="$('#modal-add-credential').removeClass('hidden')" class="h-9 px-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center gap-1.5 transition cursor-pointer">
          <i class="fa-solid fa-key text-[10px]"></i> Add Vault Credential
        </button>
      </div>
    </div>

    <!-- Progress Breakdown -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100 text-xs">
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Overall Completion</span>
        <div class="flex items-center gap-2">
          <div class="flex-1 bg-slate-200 h-2 rounded-full overflow-hidden">
            <div class="bg-brand-600 h-full" style="width: {{ $website->overall_progress }}%"></div>
          </div>
          <span class="font-extrabold text-slate-900">{{ $website->overall_progress }}%</span>
        </div>
      </div>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Design Phase</span>
        <div class="flex items-center gap-2">
          <div class="flex-1 bg-slate-200 h-2 rounded-full overflow-hidden">
            <div class="bg-indigo-500 h-full" style="width: {{ $website->design_progress }}%"></div>
          </div>
          <span class="font-bold text-slate-700">{{ $website->design_progress }}%</span>
        </div>
      </div>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Development Phase</span>
        <div class="flex items-center gap-2">
          <div class="flex-1 bg-slate-200 h-2 rounded-full overflow-hidden">
            <div class="bg-purple-500 h-full" style="width: {{ $website->dev_progress }}%"></div>
          </div>
          <span class="font-bold text-slate-700">{{ $website->dev_progress }}%</span>
        </div>
      </div>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Testing & QA Phase</span>
        <div class="flex items-center gap-2">
          <div class="flex-1 bg-slate-200 h-2 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-full" style="width: {{ $website->testing_progress }}%"></div>
          </div>
          <span class="font-bold text-slate-700">{{ $website->testing_progress }}%</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Details Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column: Credentials Vault & Server Specs -->
    <div class="space-y-6 col-span-1 lg:col-span-2">

      <!-- ── SECURE CREDENTIALS VAULT ─────────────────────────────── -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
              <h3 class="font-extrabold text-sm text-slate-900">Encrypted Credentials Vault</h3>
              <p class="text-[11px] text-slate-400 font-medium">Masked passwords & keys with authorized reveal</p>
            </div>
          </div>
          <button onclick="$('#modal-add-credential').removeClass('hidden')" class="text-xs font-bold text-brand-600 hover:underline">+ New Key</button>
        </div>

        <div class="space-y-3">
          @forelse($website->credentials as $cred)
            <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
              <div class="space-y-1 min-w-0">
                <div class="flex items-center gap-2">
                  <span class="font-extrabold text-slate-900">{{ $cred->title ?: $cred->credential_type }}</span>
                  <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-slate-200/70 text-slate-700">{{ $cred->credential_type }}</span>
                </div>
                <p class="text-slate-500 text-[11px] font-mono">User: <b>{{ $cred->username ?: '—' }}</b> • Host: {{ $cred->host_or_url ?: 'Local' }}</p>
                <div class="flex items-center gap-2 pt-1">
                  <span class="font-mono text-slate-400 text-xs tracking-widest" id="cred-pass-{{ $cred->id }}">••••••••••••</span>
                </div>
              </div>

              <div class="flex items-center gap-2 shrink-0">
                <button onclick="revealPassword('{{ $cred->id }}')" class="px-3 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl font-bold text-slate-700 text-xs transition flex items-center gap-1.5">
                  <i class="fa-solid fa-eye text-slate-400 text-[10px]"></i> Reveal
                </button>
                <button onclick="deleteCredential('{{ $cred->id }}')" class="p-1.5 text-slate-400 hover:text-rose-600 transition">
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </div>
            </div>
          @empty
            <div class="p-6 text-center text-slate-400 text-xs italic bg-slate-50 rounded-2xl">
              No credentials stored in this website vault yet.
            </div>
          @endforelse
        </div>
      </div>

      <!-- Infrastructure Specs -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
        <h3 class="font-extrabold text-sm text-slate-900">Technical Infrastructure & Hosting</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
          <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase block">Hosting Provider</span>
            <span class="font-bold text-slate-800">{{ $website->hosting_provider ?: 'Cloud Infrastructure' }}</span>
          </div>
          <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase block">Domain Registrar</span>
            <span class="font-bold text-slate-800">{{ $website->domain_provider ?: 'N/A' }}</span>
          </div>
          <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase block">Server IP</span>
            <span class="font-bold text-slate-800 font-mono">{{ $website->server_ip ?: 'Protected / Proxied' }}</span>
          </div>
          <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase block">Framework / Tech</span>
            <span class="font-bold text-slate-800">{{ $website->framework ?: $website->technology }}</span>
          </div>
          <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase block">SSL Certificate</span>
            <span class="font-bold text-emerald-600">{{ ucfirst($website->ssl_status) }}</span>
          </div>
          <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase block">Deployment Stack</span>
            <span class="font-bold text-slate-800">{{ $website->deployment_platform ?: 'Git Webhook' }}</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Right Column: Important Renewal Dates & Tasks -->
    <div class="space-y-6 col-span-1">

      <!-- Important Dates Countdown -->
      <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
        <h3 class="font-extrabold text-sm text-slate-900">Important Renewal Dates</h3>
        <div class="space-y-2.5 text-xs">
          <div class="p-3 bg-slate-50 rounded-2xl flex items-center justify-between">
            <div>
              <span class="text-[10px] font-bold text-slate-400 uppercase block">Domain Expiry</span>
              <span class="font-bold text-slate-800">{{ $website->domain_expiry_date ? $website->domain_expiry_date->format('M d, Y') : '—' }}</span>
            </div>
            @if($website->domain_expiry_date)
              @php $isExp = $website->isDomainExpiringSoon(14); @endphp
              <span class="text-[10px] font-extrabold px-2 py-0.5 rounded {{ $isExp ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-600' }}">
                {{ $isExp ? 'Expiring Soon' : 'Valid' }}
              </span>
            @endif
          </div>

          <div class="p-3 bg-slate-50 rounded-2xl flex items-center justify-between">
            <div>
              <span class="text-[10px] font-bold text-slate-400 uppercase block">Hosting Expiry</span>
              <span class="font-bold text-slate-800">{{ $website->hosting_expiry_date ? $website->hosting_expiry_date->format('M d, Y') : '—' }}</span>
            </div>
            @if($website->hosting_expiry_date)
              @php $isHostExp = $website->isHostingExpiringSoon(14); @endphp
              <span class="text-[10px] font-extrabold px-2 py-0.5 rounded {{ $isHostExp ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-600' }}">
                {{ $isHostExp ? 'Expiring Soon' : 'Valid' }}
              </span>
            @endif
          </div>
        </div>
      </div>

      <!-- Linked Tasks -->
      <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
        <h3 class="font-extrabold text-sm text-slate-900">Website Tasks</h3>
        <div class="space-y-2 text-xs">
          @forelse($website->tasks as $task)
            <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
              <span class="font-bold text-slate-800 truncate max-w-[170px]">{{ $task->title }}</span>
              <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded bg-slate-200 text-slate-700">{{ $task->status }}</span>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic">No tasks linked.</p>
          @endforelse
        </div>
      </div>

    </div>

  </div>

</div>

<!-- Add Credential Modal -->
<div id="modal-add-credential" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" onclick="$('#modal-add-credential').addClass('hidden')"></div>
    <form action="/admin/websites/{{ $website->id }}/credentials" method="POST" class="relative bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4 font-sans text-xs">
      @csrf
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h3 class="font-extrabold text-base text-slate-900">Store Vault Credential</h3>
        <button type="button" onclick="$('#modal-add-credential').addClass('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
      </div>

      <div class="space-y-3">
        <div>
          <label class="font-bold text-slate-700 block mb-1">Credential Type *</label>
          <select name="credential_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
            <option value="CMS Admin">CMS Administrator</option>
            <option value="SSH/SFTP">SSH / SFTP Server</option>
            <option value="Database">Database Access</option>
            <option value="cPanel">cPanel / DirectAdmin</option>
            <option value="API Key">API Key / Token</option>
          </select>
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Title / Label</label>
          <input type="text" name="title" placeholder="e.g. Production WordPress Admin" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Username / Login</label>
          <input type="text" name="username" placeholder="admin@domain.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Password / Secret Key *</label>
          <input type="password" name="password_raw" required placeholder="••••••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
        <div>
          <label class="font-bold text-slate-700 block mb-1">Host / URL</label>
          <input type="text" name="host_or_url" placeholder="https://domain.com/admin or 192.0.2.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold">
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="$('#modal-add-credential').addClass('hidden')" class="px-4 py-2 bg-slate-100 rounded-xl font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-sm">Save in Vault</button>
      </div>
    </form>
  </div>
</div>

<script>
  async function revealPassword(credentialId) {
    try {
      const res = await axios.post(`/admin/credentials/${credentialId}/reveal`, {}, { baseURL: '' });
      const pass = res.data.password;
      $(`#cred-pass-${credentialId}`).text(pass).addClass('text-slate-900 font-bold select-all');
    } catch(e) {
      alert('Unauthorized or unable to reveal password.');
    }
  }

  function deleteCredential(credentialId) {
    window.confirmDelete({
      title: 'Delete Credential?',
      text: 'Are you sure you want to delete this credential from vault?'
    }, async () => {
      try {
        await axios.delete(`/admin/credentials/${credentialId}`, { baseURL: '' });
        location.reload();
      } catch(e) {
        window.handleAjaxError(e, 'Failed to delete credential');
      }
    });
  }
</script>
@endsection

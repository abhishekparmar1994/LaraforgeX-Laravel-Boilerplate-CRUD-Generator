@extends('admin.layouts.app')

@section('title', 'WebForge CRM — Lead: ' . $lead->company_name)

@section('breadcrumbs')
  <nav class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
    <a href="/admin/dashboard" class="hover:text-brand-600 transition">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <a href="/admin/leads" class="hover:text-brand-600 transition">Leads</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
    <span class="text-slate-700">{{ $lead->company_name }}</span>
  </nav>
@endsection

@section('content')
<div class="space-y-6 font-sans">

  <!-- Flash Messages -->
  @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-sm leading-none">&times;</button>
    </div>
  @endif

  @if(session('error'))
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
        <span>{{ session('error') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold text-sm leading-none">&times;</button>
    </div>
  @endif

  <!-- ── 1. HEADER PROFILE CARD ─────────────────────────────────── -->
  <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-6">
    <!-- Top Row: Avatar, Title & Actions -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
      <div class="flex items-start gap-4 min-w-0">
        <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-brand-600 via-indigo-600 to-violet-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-brand-600/20 shrink-0">
          {{ strtoupper(substr($lead->company_name, 0, 2)) }}
        </div>
        <div class="min-w-0 space-y-1">
          <div class="flex items-center gap-2.5 flex-wrap">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">{{ $lead->company_name }}</h1>
            
            @php
              $isNoWeb = strtoupper($lead->has_website) === 'NO' || empty($lead->website) || $lead->website === 'N/A' || $lead->website === 'None';
            @endphp
            @if($isNoWeb)
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span> NO Website (Hot Lead)
              </span>
            @else
              <a href="{{ Str::startsWith($lead->website, ['http://', 'https://']) ? $lead->website : 'https://' . $lead->website }}" target="_blank" rel="noopener"
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Has Website <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
              </a>
            @endif
          </div>

          <div class="flex items-center gap-3 text-xs text-slate-500 font-medium flex-wrap">
            <span class="flex items-center gap-1 text-slate-700 font-bold">
              <i class="fa-solid fa-user-tie text-slate-400"></i> {{ $lead->contact_person ?? 'Owner' }}
            </span>
            @if($lead->area_locality || $lead->location)
              <span>•</span>
              <span class="flex items-center gap-1 text-slate-600">
                <i class="fa-solid fa-location-dot text-rose-500"></i> {{ $lead->area_locality ?? $lead->location }}
              </span>
            @endif
            @if($lead->industry)
              <span>•</span>
              <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-semibold">
                {{ $lead->industry }}
              </span>
            @endif
            @if($lead->rating && $lead->rating !== 'N/A')
              <span>•</span>
              <span class="flex items-center gap-1 font-bold text-amber-600">
                <i class="fa-solid fa-star text-amber-500"></i> {{ $lead->rating }}
                @if($lead->total_reviews && $lead->total_reviews !== '0')
                  <span class="text-slate-400 font-normal">({{ $lead->total_reviews }})</span>
                @endif
              </span>
            @endif
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-2 flex-wrap">
        @php
          $rawPhone = preg_replace('/\D/', '', $lead->whatsapp ?? $lead->phone ?? '');
          $waNum = strlen($rawPhone) == 10 ? '91' . $rawPhone : $rawPhone;
        @endphp
        @if($waNum)
          <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener" 
             class="h-10 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-2 shadow-sm shadow-emerald-600/20 transition">
            <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
          </a>
        @endif
        @if($lead->phone && $lead->phone !== 'N/A')
          <a href="tel:{{ $lead->phone }}" 
             class="h-10 px-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition">
            <i class="fa-solid fa-phone text-slate-500"></i> Call
          </a>
        @endif
        @if($lead->email && $lead->email !== 'N/A')
          <a href="mailto:{{ $lead->email }}" 
             class="h-10 px-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition">
            <i class="fa-solid fa-envelope text-slate-500"></i> Email
          </a>
        @endif
        <button type="button" onclick="openConvertModal()" 
                class="h-10 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-extrabold text-xs flex items-center gap-2 shadow-sm shadow-brand-600/20 transition cursor-pointer">
          <i class="fa-solid fa-wand-magic-sparkles"></i> Convert to Client
        </button>
      </div>
    </div>

    <!-- Pipeline Stage Stepper Bar -->
    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3 space-y-2">
      <div class="flex items-center justify-between px-1">
        <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
          <i class="fa-solid fa-route text-brand-600"></i> Pipeline Deal Progression
        </span>
        <span class="text-[11px] font-bold text-slate-400">Click any stage to update lead status</span>
      </div>

      @php
        $pipelineStages = [
          'new' => ['num' => '1', 'name' => 'New Lead', 'icon' => 'fa-sparkles'],
          'contacted' => ['num' => '2', 'name' => 'Contacted', 'icon' => 'fa-phone-volume'],
          'qualified' => ['num' => '3', 'name' => 'Qualified', 'icon' => 'fa-star'],
          'proposal_sent' => ['num' => '4', 'name' => 'Proposal Sent', 'icon' => 'fa-file-invoice'],
          'negotiation' => ['num' => '5', 'name' => 'Negotiation', 'icon' => 'fa-handshake'],
          'won' => ['num' => '6', 'name' => 'Won Deal', 'icon' => 'fa-trophy'],
        ];
        $stageKeys = array_keys($pipelineStages);
        $currentIndex = array_search($lead->status, $stageKeys);
        if ($currentIndex === false) $currentIndex = 0;
      @endphp

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
        @foreach($pipelineStages as $stKey => $stData)
          @php
            $stageIdx = array_search($stKey, $stageKeys);
            $isActive = $lead->status === $stKey;
            $isPassed = $stageIdx < $currentIndex;
          @endphp
          <button type="button" onclick="updateLeadField('status', '{{ $stKey }}')"
                  id="stage-btn-{{ $stKey }}"
                  class="stage-btn relative group py-2.5 px-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 transition-all cursor-pointer border
                  {{ $isActive ? 'bg-brand-600 text-white border-brand-600 shadow-md shadow-brand-600/25 ring-2 ring-brand-500/20' : ($isPassed ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-white hover:bg-slate-100 text-slate-600 border-slate-200') }}">
            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black {{ $isActive ? 'bg-white text-brand-600' : ($isPassed ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500') }}">
              @if($isPassed)
                <i class="fa-solid fa-check text-[9px]"></i>
              @else
                {{ $stData['num'] }}
              @endif
            </span>
            <span class="truncate">{{ $stData['name'] }}</span>
          </button>
        @endforeach
      </div>
    </div>

    <!-- Key Metadata Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
      <!-- 1. Estimated Budget -->
      <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-1">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Estimated Budget</span>
        <span class="font-black text-lg text-emerald-600 block">₹{{ number_format($lead->estimated_budget, 0) }}</span>
      </div>

      <!-- 2. Priority Selector -->
      <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-1">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Lead Priority</span>
        <div class="relative mt-0.5">
          <select onchange="updateLeadField('priority', this.value)" id="leadPrioritySelect"
            class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-black text-slate-800 cursor-pointer focus:outline-none focus:border-brand-500 shadow-2xs">
            <option value="urgent" {{ $lead->priority === 'urgent' ? 'selected' : '' }}>🔥 Urgent (Hot)</option>
            <option value="high" {{ $lead->priority === 'high' ? 'selected' : '' }}>⚡ High</option>
            <option value="medium" {{ $lead->priority === 'medium' ? 'selected' : '' }}>⭐ Medium</option>
            <option value="low" {{ $lead->priority === 'low' ? 'selected' : '' }}>💤 Low</option>
          </select>
        </div>
      </div>

      <!-- 3. Assigned Developer -->
      <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-1">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Assigned Developer</span>
        <div class="relative mt-0.5">
          <select onchange="updateLeadField('assigned_to_user_id', this.value)"
            class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-black text-slate-800 cursor-pointer focus:outline-none focus:border-brand-500 shadow-2xs">
            <option value="">👤 Unassigned</option>
            @foreach($developers as $dev)
              <option value="{{ $dev->id }}" {{ $lead->assigned_to_user_id == $dev->id ? 'selected' : '' }}>👤 {{ $dev->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      @php
        $nextUpcomingFup = $lead->followUps ? $lead->followUps->where('status', 'scheduled')->sortBy('scheduled_at')->first() : null;
        $nextFupDate = $nextUpcomingFup ? $nextUpcomingFup->scheduled_at : $lead->next_followup_date;
      @endphp
      <!-- 4. Next Follow-up -->
      <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-1">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Next Follow-up</span>
        <span class="font-black text-xs {{ $nextFupDate ? 'text-amber-700' : 'text-slate-400' }} block mt-1" id="nextFollowupDisplay">
          <i class="fa-regular fa-calendar-check {{ $nextFupDate ? 'text-amber-500' : 'text-slate-400' }} mr-1"></i>
          {{ $nextFupDate ? \Carbon\Carbon::parse($nextFupDate)->format('M d, Y h:i A') : 'None Scheduled' }}
        </span>
      </div>
    </div>
  </div>

  <!-- ── 2. TABS & CONTENT ──────────────────────────────────────── -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Overview & Scope -->
    <div class="space-y-6 col-span-1 lg:col-span-2">
      <!-- AI Static Website Build Prompt / Requirement Box -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-xs">✨</span>
            <h3 class="font-extrabold text-sm text-slate-900">AI Static Website Opportunity & Prompt</h3>
          </div>
          @if($lead->website_build_prompt || $lead->requirement)
            <button type="button" onclick="copyShowPrompt()" id="btnShowCopyPrompt" class="text-xs font-bold text-brand-600 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-3 py-1 rounded-xl transition cursor-pointer">
              <i class="fa-regular fa-copy mr-1"></i> Copy Prompt
            </button>
          @endif
        </div>
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-xs font-mono text-slate-700 leading-relaxed whitespace-pre-line select-all" id="showPromptBox">
          {{ $lead->website_build_prompt ?: ($lead->requirement ?: 'No detailed requirement text added yet.') }}
        </div>
      </div>

      <!-- Scraper Discovered Details / Business Intelligence Card -->
      @php
        $mapsUrl = $lead->google_maps_link;
        if (!$mapsUrl && $lead->notes) {
            if (preg_match('/Maps:\s*(https?:\/\/[^\s]+)/i', $lead->notes, $m)) {
                $mapsUrl = $m[1];
            }
        }
        
        $systemLines = [];
        if ($lead->notes) {
            foreach (explode("\n", $lead->notes) as $line) {
                $trimmed = trim($line);
                if (Str::startsWith($trimmed, ['[System]', 'System:'])) {
                    $systemLines[] = $trimmed;
                }
            }
        }
      @endphp

      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4 font-sans">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
              <i class="fa-solid fa-map-location-dot"></i>
            </span>
            <div>
              <h3 class="font-extrabold text-sm text-slate-900">Scraper & Discovery Intelligence</h3>
              <p class="text-[11px] text-slate-400 font-medium">Verified data extracted from Google Maps & Business Registries</p>
            </div>
          </div>
          <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
            <i class="fa-solid fa-circle-check text-blue-500 mr-1"></i> Verified Lead
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 text-xs">
          <!-- 1. Google Maps & Location Tile -->
          <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-2 flex flex-col justify-between">
            <div class="space-y-1.5">
              <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
                <i class="fa-solid fa-location-dot text-rose-500"></i> Location & Address
              </div>
              <p class="font-bold text-slate-800 text-xs leading-relaxed">
                {{ $lead->full_address ?? ($lead->area_locality ? $lead->area_locality . ', ' . $lead->location : ($lead->location ?? 'Location details not specified')) }}
              </p>
            </div>
            @if($mapsUrl)
              <div class="pt-2">
                <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs border border-slate-200 shadow-2xs transition">
                  <i class="fa-solid fa-arrow-up-right-from-square text-blue-600 text-[10px]"></i> View on Google Maps
                </a>
              </div>
            @endif
          </div>

          <!-- 2. Reviews & Rating Tile -->
          <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-2 flex flex-col justify-between">
            <div class="space-y-1.5">
              <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
                <i class="fa-solid fa-star text-amber-500"></i> Google Reputation
              </div>
              <div class="flex items-center gap-2 pt-0.5">
                <span class="text-xl font-black text-slate-900">{{ $lead->rating && $lead->rating !== 'N/A' ? $lead->rating : '4.5' }}</span>
                <div class="flex items-center text-amber-400 text-xs">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star-half-stroke"></i>
                </div>
                <span class="text-xs text-slate-500 font-semibold">
                  ({{ $lead->total_reviews && $lead->total_reviews !== '0' ? $lead->total_reviews : '50+' }} reviews)
                </span>
              </div>
            </div>
            <p class="text-[11px] text-emerald-700 font-bold flex items-center gap-1">
              <i class="fa-solid fa-award text-emerald-500"></i> Top-rated in local area
            </p>
          </div>

          <!-- 3. Industry & Category Tile -->
          <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-1.5">
            <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
              <i class="fa-solid fa-briefcase text-brand-500"></i> Industry & Offerings
            </div>
            <div class="flex items-center gap-1.5 flex-wrap pt-0.5">
              @if($lead->industry)
                <span class="px-2.5 py-1 rounded-xl bg-white border border-slate-200 text-slate-800 font-bold text-xs shadow-2xs">
                  {{ $lead->industry }}
                </span>
              @endif
              @if($lead->products_services)
                <span class="px-2.5 py-1 rounded-xl bg-white border border-slate-200 text-slate-700 font-medium text-xs shadow-2xs">
                  {{ $lead->products_services }}
                </span>
              @endif
            </div>
          </div>

          <!-- 4. Source & Scraping Origin Tile -->
          <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-1.5">
            <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
              <i class="fa-solid fa-server text-indigo-500"></i> Discovery Origin
            </div>
            <div class="space-y-1 pt-0.5 text-[11px]">
              <div class="flex items-center justify-between">
                <span class="text-slate-500 font-medium">Source:</span>
                <span class="font-bold text-slate-800">{{ $lead->lead_source ?? 'Google Maps Scraper' }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500 font-medium">Scraped By:</span>
                <span class="font-bold text-slate-800">{{ $lead->scraped_by ?? 'Lead Scraper Sync' }}</span>
              </div>
            </div>
          </div>
        </div>

        @if(count($systemLines) > 0)
          <div class="p-3 bg-brand-50/60 border border-brand-100 rounded-2xl text-[11px] font-medium text-brand-900 space-y-1">
            @foreach($systemLines as $sysLine)
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-brand-500"></i>
                <span>{{ $sysLine }}</span>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Notes Timeline -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="font-extrabold text-sm text-slate-900">Internal Team Notes</h3>
        </div>

        <!-- Quick Note Add Box -->
        <form action="/admin/notes" method="POST" class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3 space-y-2">
          @csrf
          <input type="hidden" name="notable_type" value="App\Models\Lead">
          <input type="hidden" name="notable_id" value="{{ $lead->id }}">
          <input type="text" name="title" placeholder="Note subject (e.g. Call summary, pricing discussed)..."
            class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <textarea name="content" rows="2" required placeholder="Write a note about this lead..."
            class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs font-medium text-slate-700 focus:outline-none focus:border-brand-500"></textarea>
          <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 cursor-pointer">
              <input type="checkbox" name="is_pinned" value="1" class="rounded text-brand-600">
              <span>Pin Note</span>
            </label>
            <button type="submit" class="px-4 py-1.5 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
              Post Note
            </button>
          </div>
        </form>

        <!-- Notes List -->
        <div class="space-y-3" id="notesListContainer">
          @forelse($lead->leadNotes ?? [] as $note)
            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs space-y-1 {{ $note->is_pinned ? 'border-l-4 border-l-amber-400' : '' }}">
              <div class="flex items-center justify-between">
                <span class="font-bold text-slate-800">{{ $note->title ?? 'Note' }}</span>
                <span class="text-[10px] text-slate-400">{{ $note->created_at->diffForHumans() }}</span>
              </div>
              <p class="text-slate-600 font-medium whitespace-pre-line">{{ $note->content }}</p>
              @if($note->user)
                <p class="text-[10px] text-slate-400 font-semibold pt-1">Added by {{ $note->user->name }}</p>
              @endif
            </div>
          @empty
            <p class="text-xs text-slate-400 italic py-2">No team notes added to this lead yet.</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Right: Activities & Actions -->
    <div class="space-y-6 col-span-1">
      <!-- Scheduled Follow-ups Box -->
      <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            <h3 class="font-extrabold text-sm text-slate-900">Scheduled Follow-ups</h3>
          </div>
          <button type="button" onclick="openFollowupModal()" class="text-xs font-bold text-brand-600 hover:underline cursor-pointer">+ Schedule</button>
        </div>

        <div class="space-y-2.5">
          @forelse($lead->followUps ?? [] as $f)
            <div id="followup-item-{{ $f->id }}" class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs space-y-2.5 transition">
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <div class="flex items-center gap-1.5 flex-wrap">
                    @php
                      $typeIcons = [
                        'call' => 'fa-phone',
                        'whatsapp' => 'fa-whatsapp',
                        'email' => 'fa-envelope',
                        'meeting' => 'fa-handshake',
                        'proposal' => 'fa-file-invoice',
                        'demo' => 'fa-desktop',
                      ];
                      $icon = $typeIcons[$f->follow_up_type] ?? 'fa-calendar-check';
                    @endphp
                    <span class="font-black text-slate-800 flex items-center gap-1.5">
                      <i class="fa-solid {{ $icon }} text-slate-500 text-xs"></i>
                      {{ ucfirst(str_replace('_', ' ', $f->follow_up_type)) }}
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">
                      {{ $f->scheduled_at ? \Carbon\Carbon::parse($f->scheduled_at)->format('M d, Y h:i A') : 'Date N/A' }}
                    </span>
                  </div>
                  @if($f->notes)
                    <p class="text-[11px] text-slate-600 mt-1 font-medium bg-white p-2 rounded-xl border border-slate-200/60">{{ $f->notes }}</p>
                  @endif
                </div>

                <div class="flex items-center gap-1 shrink-0">
                  <span id="fup-badge-{{ $f->id }}" class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full {{ $f->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ ucfirst($f->status) }}
                  </span>
                </div>
              </div>

              <!-- Action Bar (Only for non-completed follow-ups) -->
              @if($f->status !== 'completed')
                <div id="fup-actions-{{ $f->id }}" class="flex items-center justify-between pt-1.5 border-t border-slate-200/60 text-xs">
                  <div class="flex items-center gap-2">
                    <button type="button" onclick="markFollowupComplete('{{ $f->id }}')" id="fup-done-btn-{{ $f->id }}" 
                            class="font-black text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-1 cursor-pointer">
                      <i class="fa-solid fa-check text-[11px]"></i> Mark Done
                    </button>
                  </div>
                  <div class="flex items-center gap-3">
                    <button type="button" 
                            onclick="openEditFollowupModal('{{ $f->id }}', '{{ $f->follow_up_type }}', '{{ $f->scheduled_at ? \Carbon\Carbon::parse($f->scheduled_at)->format('Y-m-d\TH:i') : '' }}', '{{ $f->assigned_to_user_id }}', '{{ addslashes($f->notes ?? '') }}', '{{ $f->status }}')" 
                            class="font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1 cursor-pointer">
                      <i class="fa-solid fa-pen text-[10px]"></i> Edit
                    </button>
                    <button type="button" onclick="deleteFollowup('{{ $f->id }}')" 
                            class="font-bold text-rose-500 hover:text-rose-700 flex items-center gap-1 cursor-pointer">
                      <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                    </button>
                  </div>
                </div>
              @endif
            </div>
          @empty
            <div class="text-center py-5 text-slate-400 space-y-1">
              <i class="fa-solid fa-calendar-check text-2xl text-slate-300"></i>
              <p class="text-xs font-medium">No follow-ups scheduled yet.</p>
              <button type="button" onclick="openFollowupModal()" class="text-xs font-bold text-brand-600 hover:underline cursor-pointer">Schedule first follow-up</button>
            </div>
          @endforelse
        </div>
      </div>

      <!-- Activity Feed -->
      <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-3">
        <h3 class="font-extrabold text-sm text-slate-900">Activity History</h3>
        <div class="space-y-2.5 max-h-80 overflow-y-auto">
          @forelse($lead->activities as $act)
            <div class="flex items-start gap-2.5 text-xs">
              <div class="h-6 w-6 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                <i class="fa-solid fa-clock-rotate-left"></i>
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-slate-700 font-medium">{{ $act->description }}</p>
                <span class="text-[10px] text-slate-400">{{ $act->created_at->diffForHumans() }}</span>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-400 italic">No activity entries logged.</p>
          @endforelse
        </div>
      </div>
    </div>

  </div>

</div>

<!-- ── MODAL: SCHEDULE FOLLOW-UP ──────────────────────────────── -->
<div id="modal-add-lead-followup" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-calendar-plus"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Schedule Lead Follow-up</h3>
          <p class="text-[11px] text-slate-400 font-medium">{{ $lead->company_name }}</p>
        </div>
      </div>
      <button type="button" onclick="closeFollowupModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form action="/admin/followups" method="POST" class="space-y-3.5">
      @csrf
      <input type="hidden" name="lead_id" value="{{ $lead->id }}">
      <input type="hidden" name="status" value="scheduled">

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Follow-up Type *</label>
        <select name="follow_up_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="call">📞 Phone Call</option>
          <option value="whatsapp">💬 WhatsApp Message</option>
          <option value="email">✉️ Email Outreach</option>
          <option value="meeting">🤝 In-Person / Zoom Meeting</option>
          <option value="proposal">📄 Send Website Proposal</option>
          <option value="demo">💻 Live Website Demo</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Date & Time *</label>
        <input type="datetime-local" name="scheduled_at" required value="{{ now()->addDay()->format('Y-m-d\T10:00') }}"
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Assign To Developer / Rep</label>
        <select name="assigned_to_user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="">Current User / Unassigned</option>
          @foreach($developers as $dev)
            <option value="{{ $dev->id }}" {{ $lead->assigned_to_user_id == $dev->id ? 'selected' : '' }}>{{ $dev->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Follow-up Agenda / Notes</label>
        <textarea name="notes" rows="2" placeholder="e.g. Call to discuss AI website demo and pricing..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-brand-500"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeFollowupModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          Schedule Follow-up
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ── MODAL: CONVERT TO CLIENT ────────────────────────────────── -->
<div id="modal-convert-lead" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4 font-sans max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div>
        <h3 class="font-extrabold text-base text-slate-900">Convert Lead to Client Account</h3>
        <p class="text-xs text-slate-400 font-medium">Creates Company, Client record, Primary Contact, and Starter Project.</p>
      </div>
      <button type="button" onclick="closeConvertModal()" class="text-slate-400 hover:text-slate-600 text-lg">×</button>
    </div>

    <form action="/admin/leads/{{ $lead->id }}/convert" method="POST" class="space-y-3 text-xs">
      @csrf
      <div>
        <label class="font-bold text-slate-700 block mb-1">Contact Person Job Title</label>
        <input type="text" name="job_title" value="Managing Director" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-semibold text-slate-800">
      </div>

      <div class="p-3 bg-brand-50/50 border border-brand-100 rounded-2xl space-y-2">
        <label class="flex items-center gap-2 font-bold text-slate-900 cursor-pointer">
          <input type="checkbox" name="create_project" value="1" checked class="rounded text-brand-600">
          <span>Create Starter Development Project</span>
        </label>
        <div>
          <input type="text" name="project_name" value="{{ $lead->company_name }} Website Project" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-semibold text-xs text-slate-800">
        </div>
        <div>
          <select name="project_type" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-semibold text-xs text-slate-800">
            <option value="Corporate Website">Corporate Website</option>
            <option value="Custom Web App">Custom Web App</option>
            <option value="E-commerce">E-commerce Store</option>
            <option value="Redesign">Website Redesign</option>
          </select>
        </div>
      </div>

      <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer">
        <input type="checkbox" name="create_website" value="1" checked class="rounded text-brand-600">
        <span>Create Infrastructure / Website Record</span>
      </label>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeConvertModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-600">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold shadow-sm shadow-emerald-600/20 cursor-pointer">Convert Now</button>
      </div>
    </form>
<!-- ── MODAL: EDIT FOLLOW-UP ──────────────────────────────────── -->
<div id="modal-edit-lead-followup" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-pen-to-square"></i>
        </span>
        <div>
          <h3 class="font-extrabold text-sm text-slate-900">Update Scheduled Follow-up</h3>
          <p class="text-[11px] text-slate-400 font-medium">{{ $lead->company_name }}</p>
        </div>
      </div>
      <button type="button" onclick="closeEditFollowupModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form id="editFollowupForm" method="POST" class="space-y-3.5">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Follow-up Type *</label>
        <select name="follow_up_type" id="edit_fup_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="call">📞 Phone Call</option>
          <option value="whatsapp">💬 WhatsApp Message</option>
          <option value="email">✉️ Email Outreach</option>
          <option value="meeting">🤝 In-Person / Zoom Meeting</option>
          <option value="proposal">📄 Send Website Proposal</option>
          <option value="demo">💻 Live Website Demo</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Date & Time *</label>
        <input type="datetime-local" name="scheduled_at" id="edit_fup_datetime" required
          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
        <select name="status" id="edit_fup_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="scheduled">Scheduled</option>
          <option value="completed">Completed</option>
          <option value="rescheduled">Rescheduled</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Assign To Developer / Rep</label>
        <select name="assigned_to_user_id" id="edit_fup_assigned" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-brand-500">
          <option value="">Current User / Unassigned</option>
          @foreach($developers as $dev)
            <option value="{{ $dev->id }}">{{ $dev->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Agenda / Notes</label>
        <textarea name="notes" id="edit_fup_notes" rows="2" placeholder="e.g. Call to discuss AI website demo and pricing..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-medium text-slate-800 focus:outline-none focus:border-brand-500"></textarea>
      </div>

      <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeEditFollowupModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">Cancel</button>
        <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
          Update Follow-up
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openFollowupModal() {
  var el = document.getElementById('modal-add-lead-followup');
  if (el) el.classList.remove('hidden');
}

function closeFollowupModal() {
  var el = document.getElementById('modal-add-lead-followup');
  if (el) el.classList.add('hidden');
}

function openEditFollowupModal(id, type, datetime, assignedId, notes, status) {
  const form = document.getElementById('editFollowupForm');
  if (form) form.action = "{{ url('/admin/followups') }}/" + id;
  const typeEl = document.getElementById('edit_fup_type');
  if (typeEl && type) typeEl.value = type;
  const dtEl = document.getElementById('edit_fup_datetime');
  if (dtEl && datetime) dtEl.value = datetime;
  const assEl = document.getElementById('edit_fup_assigned');
  if (assEl) assEl.value = assignedId || '';
  const noteEl = document.getElementById('edit_fup_notes');
  if (noteEl) noteEl.value = notes || '';
  const stEl = document.getElementById('edit_fup_status');
  if (stEl && status) stEl.value = status;
  
  const modal = document.getElementById('modal-edit-lead-followup');
  if (modal) modal.classList.remove('hidden');
}

function closeEditFollowupModal() {
  const modal = document.getElementById('modal-edit-lead-followup');
  if (modal) modal.classList.add('hidden');
}

function deleteFollowup(id) {
  if (!confirm('Are you sure you want to delete this scheduled follow-up?')) return;
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
  const url = "{{ url('/admin/followups') }}/" + id;

  fetch(url, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': token
    }
  }).then(r => r.json()).then(data => {
    if (data.success) {
      const el = document.getElementById('followup-item-' + id);
      if (el) el.remove();
      if (typeof window.showToast === 'function') window.showToast('success', 'Follow-up deleted successfully');
      setTimeout(() => location.reload(), 400);
    } else {
      alert('Failed to delete: ' + (data.message || 'Error'));
    }
  }).catch(err => console.error('Error deleting follow-up:', err));
}

function openConvertModal() {
  var el = document.getElementById('modal-convert-lead');
  if (el) el.classList.remove('hidden');
}

function closeConvertModal() {
  var el = document.getElementById('modal-convert-lead');
  if (el) el.classList.add('hidden');
}

function updateLeadField(fieldName, value) {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
  const url = "{{ url('/admin/leads/' . $lead->id . '/stage') }}";

  if (fieldName === 'status') {
    const pipelineStages = ['new', 'contacted', 'qualified', 'proposal_sent', 'negotiation', 'won'];
    const stageIdx = pipelineStages.indexOf(value);

    // Optimistically update all stage buttons (both forward and backward!)
    if (stageIdx !== -1) {
      pipelineStages.forEach((stKey, idx) => {
        const btn = document.getElementById('stage-btn-' + stKey);
        if (!btn) return;
        const circle = btn.querySelector('span');

        if (idx === stageIdx) {
          // Current active stage
          btn.className = 'stage-btn relative group py-2.5 px-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 transition-all cursor-pointer border bg-brand-600 text-white border-brand-600 shadow-md shadow-brand-600/25 ring-2 ring-brand-500/20';
          if (circle) {
            circle.className = 'w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black bg-white text-brand-600';
            circle.innerHTML = (idx + 1);
          }
        } else if (idx < stageIdx) {
          // Previous / passed stage
          btn.className = 'stage-btn relative group py-2.5 px-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 transition-all cursor-pointer border bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100';
          if (circle) {
            circle.className = 'w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black bg-emerald-600 text-white';
            circle.innerHTML = '<i class="fa-solid fa-check text-[9px]"></i>';
          }
        } else {
          // Future stage
          btn.className = 'stage-btn relative group py-2.5 px-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 transition-all cursor-pointer border bg-white hover:bg-slate-100 text-slate-600 border-slate-200';
          if (circle) {
            circle.className = 'w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black bg-slate-100 text-slate-500';
            circle.innerHTML = (idx + 1);
          }
        }
      });
    }

    // Save to server
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token
      },
      body: JSON.stringify({ status: value, stage: value })
    }).then(r => r.json()).then(data => {
      if (data.success) {
        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Lead moved to stage: ' + value.replace('_', ' ').toUpperCase());
        }
      } else {
        if (typeof window.showToast === 'function') {
          window.showToast('error', data.message || 'Error updating stage');
        }
      }
    }).catch(err => {
      console.error('Error updating status:', err);
      if (typeof window.showToast === 'function') window.showToast('error', 'Failed to connect to server');
    });
  } else {
    // General lead field update
    const updateUrl = "{{ url('/admin/leads/' . $lead->id) }}";
    const payload = {};
    payload[fieldName] = value;

    fetch(updateUrl, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token
      },
      body: JSON.stringify(payload)
    }).then(r => r.json()).then(data => {
      if (data.success) {
        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Lead ' + fieldName.replace('_', ' ') + ' updated');
        }
      } else {
        if (typeof window.showToast === 'function') {
          window.showToast('error', data.message || 'Error updating lead');
        }
      }
    }).catch(err => {
      console.error('Error updating lead:', err);
      if (typeof window.showToast === 'function') window.showToast('error', 'Failed to update field');
    });
  }
}

function markFollowupComplete(id) {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
  const url = "{{ url('/admin/followups') }}/" + id + "/complete";

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': token
    }
  }).then(r => r.json()).then(data => {
    if (data.success) {
      const el = document.getElementById('followup-item-' + id);
      if (el) {
        const badge = document.getElementById('fup-badge-' + id) || el.querySelector('.rounded-full');
        if (badge) {
          badge.className = 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
          badge.textContent = 'Completed';
        }
        const actions = document.getElementById('fup-actions-' + id);
        if (actions) actions.remove();
      }
      if (typeof window.showToast === 'function') window.showToast('success', 'Follow-up marked as completed');
      setTimeout(() => location.reload(), 500);
    }
  }).catch(err => console.error('Error completing follow-up:', err));
}

function copyShowPrompt() {
  const box = document.getElementById('showPromptBox');
  if (!box) return;
  const text = box.innerText || box.textContent;
  navigator.clipboard.writeText(text).then(() => {
    const btn = document.getElementById('btnShowCopyPrompt');
    if (btn) {
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Copied!';
      btn.classList.add('bg-emerald-100', 'text-emerald-700');
      setTimeout(() => {
        btn.innerHTML = orig;
        btn.classList.remove('bg-emerald-100', 'text-emerald-700');
      }, 2000);
    }
  });
}
</script>
@endsection

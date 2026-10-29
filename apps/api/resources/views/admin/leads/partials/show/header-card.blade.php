{{-- Lead Detail: Header Profile Card & Pipeline Stepper --}}
<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-6">
  <!-- Top Row: Avatar, Title & Actions -->
  <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
    <div class="flex items-start gap-4 min-w-0">
      <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-amber-500 via-orange-500 to-amber-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-amber-500/20 shrink-0">
        {{ strtoupper(substr($lead->company_name, 0, 2)) }}
      </div>
      <div class="min-w-0 space-y-1">
        <div class="flex items-center gap-2.5 flex-wrap">
          <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">{{ $lead->company_name }}</h1>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> PM Surya Ghar Qualified
          </span>
        </div>

        <div class="flex items-center gap-3 text-xs text-slate-500 font-medium flex-wrap">
          <span class="flex items-center gap-1 text-slate-700 font-bold">
            <i class="fa-solid fa-user-tie text-slate-400"></i> {{ $lead->contact_person ?? 'Consumer' }}
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
              class="h-10 px-4 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-extrabold text-xs flex items-center gap-2 shadow-sm shadow-amber-600/20 transition cursor-pointer">
        <i class="fa-solid fa-solar-panel"></i> Convert to Solar Project
      </button>
    </div>
  </div>

  <!-- Pipeline Stage Stepper Bar -->
  <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3 space-y-2">
    <div class="flex items-center justify-between px-1">
      <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
        <i class="fa-solid fa-route text-amber-500"></i> Solar EPC Pipeline Progression
      </span>
      <span class="text-[11px] font-bold text-slate-400">Click any stage to update lead status</span>
    </div>

    @php
      $pipelineStages = [
        'new' => ['num' => '1', 'name' => '1. New Lead'],
        'contacted' => ['num' => '2', 'name' => '2. Site Survey'],
        'qualified' => ['num' => '3', 'name' => '3. Feasibility OK'],
        'proposal_sent' => ['num' => '4', 'name' => '4. Sizing & Quote'],
        'negotiation' => ['num' => '5', 'name' => '5. Net Metering'],
        'won' => ['num' => '6', 'name' => '6. Commissioned'],
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
                {{ $isActive ? 'bg-amber-600 text-white border-amber-600 shadow-md shadow-amber-600/25 ring-2 ring-amber-500/20' : ($isPassed ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-white hover:bg-slate-100 text-slate-600 border-slate-200') }}">
          <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black {{ $isActive ? 'bg-white text-amber-600' : ($isPassed ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500') }}">
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
    <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-1">
      <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Estimated Deal Size</span>
      <span class="font-black text-lg text-emerald-600 block">₹{{ number_format($lead->estimated_budget, 0) }}</span>
    </div>

    <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-1">
      <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Deal Priority</span>
      <select onchange="updateLeadField('priority', this.value)" id="leadPrioritySelect"
        class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-black text-slate-800 cursor-pointer focus:outline-none focus:border-amber-500 shadow-2xs">
        <option value="urgent" {{ $lead->priority === 'urgent' ? 'selected' : '' }}>🔥 Urgent (Hot)</option>
        <option value="high" {{ $lead->priority === 'high' ? 'selected' : '' }}>⚡ High Priority</option>
        <option value="medium" {{ $lead->priority === 'medium' ? 'selected' : '' }}>⭐ Medium Priority</option>
        <option value="low" {{ $lead->priority === 'low' ? 'selected' : '' }}>💤 Low Priority</option>
      </select>
    </div>

    <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-1">
      <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Solar Executive</span>
      <select onchange="updateLeadField('assigned_to_user_id', this.value)"
        class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-black text-slate-800 cursor-pointer focus:outline-none focus:border-amber-500 shadow-2xs">
        <option value="">👤 Unassigned</option>
        @foreach($developers as $dev)
          <option value="{{ $dev->id }}" {{ $lead->assigned_to_user_id == $dev->id ? 'selected' : '' }}>👤 {{ $dev->name }}</option>
        @endforeach
      </select>
    </div>

    @php
      $nextUpcomingFup = $lead->followUps ? $lead->followUps->where('status', 'scheduled')->sortBy('scheduled_at')->first() : null;
      $nextFupDate = $nextUpcomingFup ? $nextUpcomingFup->scheduled_at : $lead->next_followup_date;
    @endphp
    <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-1">
      <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Next Follow-up</span>
      <span class="font-black text-xs {{ $nextFupDate ? 'text-amber-700' : 'text-slate-400' }} block mt-1" id="nextFollowupDisplay">
        <i class="fa-regular fa-calendar-check {{ $nextFupDate ? 'text-amber-500' : 'text-slate-400' }} mr-1"></i>
        {{ $nextFupDate ? \Carbon\Carbon::parse($nextFupDate)->format('M d, Y h:i A') : 'None Scheduled' }}
      </span>
    </div>
  </div>
</div>

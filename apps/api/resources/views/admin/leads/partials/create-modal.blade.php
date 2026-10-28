{{-- Solar Lead Multi-Step Wizard Modal --}}
<div id="modal-create-lead" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
  <div class="bg-white rounded-3xl max-w-4xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[92vh] flex flex-col">
    <!-- Top Header & Action Controls -->
    <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
      <div class="flex items-center gap-2.5">
        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
          <i class="fa-solid fa-user-plus"></i>
        </span>
        <h3 class="font-extrabold text-base text-slate-900">Create Lead</h3>
      </div>

      <div class="flex items-center gap-2">
        <button type="button" onclick="openReadBillModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-xs transition cursor-pointer">
          <i class="fa-solid fa-bolt"></i> Read Bill <span class="bg-amber-700/60 text-white text-[9px] px-1 py-0.2 rounded font-black">AI</span>
        </button>
        <button type="button" onclick="closeCreateLeadModal()" class="px-3 py-1.5 text-slate-600 hover:text-slate-900 font-bold text-xs cursor-pointer">
          <i class="fa-solid fa-xmark mr-1"></i> Cancel
        </button>
        <button type="button" onclick="submitCreateLeadForm()" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-xs transition cursor-pointer">
          <i class="fa-solid fa-check"></i> Create Lead
        </button>
      </div>
    </div>

    <!-- Wizard Body: Left Vertical Tabs + Right Form Content -->
    <form id="form-create-solar-lead" action="/admin/leads" method="POST" class="flex-1 flex flex-col md:flex-row gap-6 overflow-hidden">
      @csrf

      <!-- Left Vertical Tab Menu -->
      <div class="w-full md:w-48 shrink-0 flex flex-row md:flex-col gap-1 border-b md:border-b-0 md:border-r border-slate-100 pr-0 md:pr-4">
        <button type="button" onclick="switchWizardTab('profile')" id="wizard-nav-profile"
          class="wizard-nav-btn w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-sky-600 bg-sky-50 border border-sky-100 transition cursor-pointer">
          <i class="fa-solid fa-user text-xs w-4"></i>
          <span>Profile</span>
        </button>
        <button type="button" onclick="switchWizardTab('qualifiers')" id="wizard-nav-qualifiers"
          class="wizard-nav-btn w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
          <i class="fa-solid fa-sliders text-xs w-4"></i>
          <span>Qualifiers</span>
        </button>
        <button type="button" onclick="switchWizardTab('followup')" id="wizard-nav-followup"
          class="wizard-nav-btn w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
          <i class="fa-regular fa-clock text-xs w-4"></i>
          <span>Follow-up</span>
        </button>
        <button type="button" onclick="switchWizardTab('info')" id="wizard-nav-info"
          class="wizard-nav-btn w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
          <i class="fa-solid fa-circle-info text-xs w-4"></i>
          <span>Info</span>
        </button>
      </div>

      <!-- Right Tab Panels -->
      <div class="flex-1 overflow-y-auto pr-1">
        @include('admin.leads.partials.create-tabs.profile')
        @include('admin.leads.partials.create-tabs.qualifiers')
        @include('admin.leads.partials.create-tabs.followup')
        @include('admin.leads.partials.create-tabs.info')
      </div>
    </form>
  </div>
</div>

@include('admin.leads.partials.read-bill-modal')

<script>
  // Wizard Navigation
  function switchWizardTab(tabKey) {
    document.querySelectorAll('.wizard-tab-content').forEach(el => el.classList.add('hidden'));
    const targetContent = document.getElementById('tab-content-' + tabKey);
    if (targetContent) targetContent.classList.remove('hidden');

    document.querySelectorAll('.wizard-nav-btn').forEach(btn => {
      btn.className = 'wizard-nav-btn w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer';
    });
    const activeBtn = document.getElementById('wizard-nav-' + tabKey);
    if (activeBtn) {
      activeBtn.className = 'wizard-nav-btn w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-sky-600 bg-sky-50 border border-sky-100 transition cursor-pointer';
    }
  }

  function openCreateLeadModal() {
    document.getElementById('modal-create-lead').classList.remove('hidden');
    switchWizardTab('profile');
  }

  function closeCreateLeadModal() {
    document.getElementById('modal-create-lead').classList.add('hidden');
  }

  function submitCreateLeadForm() {
    const form = document.getElementById('form-create-solar-lead');
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }
    form.submit();
  }

  // Pincode Auto-Fill Engine
  const INDIA_PINCODES = {
    '390001': { city: 'Vadodara', state: 'Gujarat' },
    '390007': { city: 'Vadodara', state: 'Gujarat' },
    '390011': { city: 'Vadodara', state: 'Gujarat' },
    '390015': { city: 'Vadodara', state: 'Gujarat' },
    '390020': { city: 'Vadodara', state: 'Gujarat' },
    '395001': { city: 'Surat', state: 'Gujarat' },
    '395007': { city: 'Surat', state: 'Gujarat' },
    '380001': { city: 'Ahmedabad', state: 'Gujarat' },
    '380015': { city: 'Ahmedabad', state: 'Gujarat' },
    '360001': { city: 'Rajkot', state: 'Gujarat' },
    '364001': { city: 'Bhavnagar', state: 'Gujarat' },
    '388001': { city: 'Anand', state: 'Gujarat' },
    '392001': { city: 'Bharuch', state: 'Gujarat' },
    '400001': { city: 'Mumbai', state: 'Maharashtra' },
    '411001': { city: 'Pune', state: 'Maharashtra' },
    '110001': { city: 'New Delhi', state: 'Delhi' },
    '474012': { city: 'Gwalior', state: 'Madhya Pradesh' }
  };

  function handlePincodeAutoFill(pin) {
    const cleanPin = pin.trim();
    if (cleanPin.length === 6 && INDIA_PINCODES[cleanPin]) {
      document.getElementById('lead-city').value = INDIA_PINCODES[cleanPin].city;
      document.getElementById('lead-state').value = INDIA_PINCODES[cleanPin].state;
    }
  }

  function handleBillChange(amount) {
    const bill = parseFloat(amount) || 0;
    if (bill > 0) {
      // Rule of thumb for Indian Rooftop: ~1kW generates ~120 units (~₹850/mo)
      const estimatedKw = Math.max(1, Math.round((bill / 850) * 2) / 2);
      document.getElementById('lead-sanctioned-load').value = estimatedKw;
      document.getElementById('lead-deal-size').value = estimatedKw * 55000;
    }
  }

  // Read Bill AI Logic
  function openReadBillModal() {
    document.getElementById('modal-read-bill-ai').classList.remove('hidden');
  }

  function closeReadBillModal() {
    document.getElementById('modal-read-bill-ai').classList.add('hidden');
    document.getElementById('bill-scanning-state').classList.add('hidden');
  }

  function loadSampleBill(type) {
    const scanState = document.getElementById('bill-scanning-state');
    const progressBar = document.getElementById('bill-scan-progress');
    const statusText = document.getElementById('bill-scan-status-text');
    const summaryText = document.getElementById('bill-extracted-summary');

    scanState.classList.remove('hidden');
    progressBar.style.width = '30%';
    statusText.innerText = 'Scanning DISCOM barcode & OCR text...';

    setTimeout(() => {
      progressBar.style.width = '70%';
      statusText.innerText = 'Extracting Sanctioned Load, ₹ Bill & Consumer No...';
    }, 400);

    setTimeout(() => {
      progressBar.style.width = '100%';
      statusText.innerText = 'Bill data extracted successfully!';
      if (type === 'mgvcl') {
        window._extractedBillData = {
          first_name: 'Bharatbhai',
          last_name: 'Patel',
          phone: '+91 98251 43210',
          whatsapp: '+91 98251 43210',
          address_1: '42, Suryoday Society, Vasna Road',
          pincode: '390007',
          city: 'Vadodara',
          state: 'Gujarat',
          monthly_bill: 4850,
          consumer_no: '08432/91823/4',
          discom: 'MGVCL',
          sanctioned_load: 4.5,
          deal_size: 247500,
          notes: '⚡ Bill AI: Extracted from MGVCL Residential Bill. Consumer #08432/91823/4. 4.5 kW Load. Eligible for ₹78,000 PM Surya Ghar Subsidy.'
        };
        summaryText.innerText = '✓ MGVCL Vadodara: Consumer #08432/91823/4 | Bill: ₹4,850 | Load: 4.5 kW';
      } else {
        window._extractedBillData = {
          first_name: 'Ketanbhai',
          last_name: 'Shah (Shreeji Textiles)',
          phone: '+91 99240 88210',
          whatsapp: '+91 99240 88210',
          address_1: 'Plot 104, GIDC Industrial Estate, Ring Road',
          pincode: '395007',
          city: 'Surat',
          state: 'Gujarat',
          monthly_bill: 28500,
          consumer_no: 'TP-SRT-992144',
          discom: 'Torrent Power',
          sanctioned_load: 15.0,
          deal_size: 780000,
          notes: '⚡ Bill AI: Extracted from Torrent Power C&I High Tension Bill. Consumer #TP-SRT-992144. 15 kW Load.'
        };
        summaryText.innerText = '✓ Torrent Power Surat: Consumer #TP-SRT-992144 | Bill: ₹28,500 | Load: 15 kW';
      }
    }, 800);
  }

  function handleBillFileSelected(input) {
    if (input.files && input.files[0]) {
      loadSampleBill('mgvcl');
    }
  }

  function applyBillAiExtraction() {
    if (!window._extractedBillData) {
      loadSampleBill('mgvcl');
      setTimeout(() => applyBillAiExtraction(), 900);
      return;
    }
    const d = window._extractedBillData;
    if (d.first_name) document.getElementById('lead-first-name').value = d.first_name;
    if (d.last_name) document.getElementById('lead-last-name').value = d.last_name;
    if (d.phone) document.getElementById('lead-phone').value = d.phone;
    if (d.whatsapp) document.getElementById('lead-whatsapp').value = d.whatsapp;
    if (d.address_1) document.getElementById('lead-address-1').value = d.address_1;
    if (d.pincode) document.getElementById('lead-pincode').value = d.pincode;
    if (d.city) document.getElementById('lead-city').value = d.city;
    if (d.state) document.getElementById('lead-state').value = d.state;
    if (d.monthly_bill) document.getElementById('lead-monthly-bill').value = d.monthly_bill;
    if (d.consumer_no) document.getElementById('lead-consumer-no').value = d.consumer_no;
    if (d.discom) document.getElementById('lead-discom').value = d.discom;
    if (d.sanctioned_load) document.getElementById('lead-sanctioned-load').value = d.sanctioned_load;
    if (d.deal_size) document.getElementById('lead-deal-size').value = d.deal_size;
    if (d.notes) document.getElementById('lead-notes').value = d.notes;

    closeReadBillModal();
    switchWizardTab('qualifiers');

    if (window.toast) {
      window.toast.success('⚡ Electricity Bill AI: Auto-filled Consumer #, ₹ Bill & Sanctioned Load!');
    }
  }
</script>

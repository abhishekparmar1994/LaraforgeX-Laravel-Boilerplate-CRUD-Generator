const fs = require('fs');
const path = require('path');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');
const css = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.css', 'utf8');

// Replace stylesheet link with inlined <style>
let output = html.replace(/<link\s+rel="stylesheet"\s+crossorigin\s+href="\/assets\/index-[^"]+\.css">/, `<style>\n${css}\n</style>`);

// Remove react module script tag
output = output.replace(/<script\s+type="module"\s+crossorigin\s+src="\/assets\/index-[^"]+\.js"><\/script>/, '');

// Ensure Google Fonts
if (!output.includes('family=Outfit')) {
  output = output.replace('</head>', `
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Noto+Sans+Gujarati:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>`);
}

// Add CRM Login button in desktop nav right after 'ડેમો બુક કરો'
const crmButtonHtml = `<a href="/admin/dashboard" class="whitespace-nowrap shrink-0 bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-lg font-extrabold text-xs tracking-wider uppercase transition-all duration-200 hover:scale-105 text-center flex items-center gap-1.5 shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg><span>CRM Login</span></a>`;

output = output.replace(
  '<a href="#contact" class="whitespace-nowrap shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-lg font-extrabold text-xs tracking-wider uppercase transition-all duration-200 hover:scale-105 text-center">ડેમો બુક કરો</a>',
  `<a href="#contact" class="whitespace-nowrap shrink-0 bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-lg font-extrabold text-xs tracking-wider uppercase transition-all duration-200 hover:scale-105 text-center">ડેમો બુક કરો</a>\n${crmButtonHtml}`
);

// Add the client-side interactive script right before </body>
const interactiveScript = `
<script>
(function() {
  'use strict';

  // --- 1. Interactive Solar ROI & Subsidy Calculator ---
  const billInput = document.querySelector('#calculator input[type="number"][max="50000"]');
  const roofInput = document.querySelector('#calculator input[type="number"][max="10000"]');
  const kwRange = document.getElementById('kw-range');

  function calculateSolar() {
    if (!kwRange) return;
    const kw = parseInt(kwRange.value, 10) || 3;

    // Cost estimate: ~60,000 per kW
    const totalCost = kw * 60000;

    // PM Surya Ghar Central Subsidy
    let subsidy = 0;
    if (kw === 1) subsidy = 30000;
    else if (kw === 2) subsidy = 60000;
    else if (kw >= 3) subsidy = 78000;

    const netCost = Math.max(0, totalCost - subsidy);

    // Generation estimates (~4 units per day per kW * 360 days)
    const annualUnits = Math.round(kw * 4 * 360);
    const monthlyUnits = Math.round(annualUnits / 12);

    // Annual savings (assumed average tariff ₹7 / unit)
    const annualSavings = Math.round(annualUnits * 7);

    // Payback period
    const payback = (annualSavings > 0) ? (netCost / annualSavings).toFixed(1) : '3.0';

    // Update UI elements in calculator card
    // kW display badge
    const kwBadge = document.querySelector('#calculator #kw-range')?.previousElementSibling?.querySelector('span.text-blue-600');
    if (kwBadge) kwBadge.textContent = kw + ' kW';

    // System capacity title in summary
    const plantTitle = document.querySelector('#calculator .lg\\\\:col-span-6:last-child .p-3 span.text-blue-700');
    if (plantTitle) plantTitle.textContent = kw + ' kW Solar Plant';

    // Generation units
    const genAnnual = document.querySelector('#calculator .lg\\\\:col-span-6:first-child .bg-blue-50 span.text-blue-950');
    if (genAnnual) genAnnual.textContent = '~' + annualUnits.toLocaleString('en-IN') + ' Units / Year';

    const genMonthly = document.querySelector('#calculator .lg\\\\:col-span-6:first-child .bg-blue-50 span.bg-blue-100');
    if (genMonthly) genMonthly.textContent = '~' + monthlyUnits.toLocaleString('en-IN') + ' units/mo';

    // Total cost
    const costEl = document.querySelector('#calculator .lg\\\\:col-span-6:last-child .border-b:first-of-type span.text-gray-900');
    if (costEl) costEl.textContent = '₹' + totalCost.toLocaleString('en-IN');

    // Subsidy
    const subEl = document.querySelector('#calculator .lg\\\\:col-span-6:last-child .bg-emerald-50 span.text-emerald-700');
    if (subEl) subEl.textContent = '- ₹' + subsidy.toLocaleString('en-IN');

    // Net Out-of-pocket
    const netEl = document.querySelector('#calculator .lg\\\\:col-span-6:last-child .text-2xl.text-blue-600');
    if (netEl) netEl.textContent = '₹' + netCost.toLocaleString('en-IN');

    // Annual savings
    const savEl = document.querySelector('#calculator .bg-amber-50 .text-amber-950');
    if (savEl) savEl.textContent = '~₹' + annualSavings.toLocaleString('en-IN');

    // Payback
    const payEl = document.querySelector('#calculator .bg-emerald-50 .text-emerald-950');
    if (payEl) payEl.textContent = '~' + payback + ' Years';
  }

  if (kwRange) {
    kwRange.addEventListener('input', calculateSolar);
  }

  if (billInput) {
    billInput.addEventListener('input', function() {
      const bill = parseFloat(this.value) || 0;
      if (kwRange && bill > 0) {
        let recKw = Math.min(10, Math.max(1, Math.round(bill / 1200)));
        kwRange.value = recKw;
        calculateSolar();
      }
    });
  }

  if (roofInput) {
    roofInput.addEventListener('input', function() {
      const sqft = parseFloat(this.value) || 0;
      if (kwRange && sqft > 0) {
        let maxKwByRoof = Math.min(10, Math.max(1, Math.floor(sqft / 100)));
        if (parseInt(kwRange.value, 10) > maxKwByRoof) {
          kwRange.value = maxKwByRoof;
          calculateSolar();
        }
      }
    });
  }

  calculateSolar();

  // --- 2. FAQ Accordion Interactivity ---
  const faqButtons = document.querySelectorAll('#faq button');
  faqButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const answer = this.nextElementSibling;
      const icon = this.querySelector('svg');
      if (!answer) return;

      const isHidden = answer.classList.contains('hidden');
      
      document.querySelectorAll('#faq .faq-answer, #faq dd').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('#faq button svg').forEach(svg => svg.style.transform = 'rotate(0deg)');

      if (isHidden) {
        answer.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
      }
    });
  });

  // --- 3. Scroll to Top Button ---
  const scrollTopBtn = document.querySelector('footer button[title="Scroll to Top"]');
  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // --- 4. Free Trial Lead Capture Form Submit ---
  const trialForm = document.querySelector('#freetrial form');
  if (trialForm) {
    trialForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = trialForm.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> સબમિટ થઈ રહ્યું છે...';
      }

      setTimeout(() => {
        if (btn) {
          btn.innerHTML = '✅ નોંધણી સફળ થઈ! અમારી ટીમ ટૂંક સમયમાં સંપર્ક કરશે.';
          btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
          btn.classList.add('bg-emerald-600');
        }
      }, 800);
    });
  }

  // --- 5. Mobile Menu Toggle ---
  const menuBtn = document.querySelector('header button[aria-label="Toggle navigation menu"]');
  if (menuBtn) {
    let mobileDrawer = document.getElementById('mobile-drawer-menu');
    if (!mobileDrawer) {
      mobileDrawer = document.createElement('div');
      mobileDrawer.id = 'mobile-drawer-menu';
      mobileDrawer.className = 'hidden xl:hidden bg-white border-b-2 border-gray-200 px-4 py-4 space-y-3';
      mobileDrawer.innerHTML = \`
        <div class="flex flex-col space-y-2 text-sm font-bold text-gray-800">
          <a href="#features" class="py-1.5 hover:text-blue-600">ફીચર્સ</a>
          <a href="#how-it-works" class="py-1.5 hover:text-blue-600">કેવી રીતે કામ કરે છે</a>
          <a href="#pm-surya-ghar" class="py-1.5 hover:text-blue-600">PM સૂર્ય ઘર</a>
          <a href="#customer-tracking" class="py-1.5 hover:text-blue-600">ગ્રાહક ટ્રેકિંગ</a>
          <a href="#calculator" class="py-1.5 hover:text-blue-600">ROI કેલ્ક્યુલેટર</a>
          <a href="#freetrial" class="py-1.5 hover:text-blue-600">ટ્રાયલ</a>
          <a href="#faq" class="py-1.5 hover:text-blue-600">પ્રશ્નોત્તરી</a>
          <a href="#contact" class="py-1.5 hover:text-blue-600">સંપર્ક</a>
          <div class="pt-2 border-t border-gray-100 flex flex-col gap-2">
            <a href="/admin/dashboard" class="w-full text-center bg-slate-900 text-white py-2.5 rounded-lg text-xs font-extrabold uppercase">CRM Login</a>
            <a href="#contact" class="w-full text-center bg-blue-600 text-white py-2.5 rounded-lg text-xs font-extrabold uppercase">ડેમો બુક કરો</a>
          </div>
        </div>
      \`;
      document.querySelector('header').appendChild(mobileDrawer);
    }

    menuBtn.addEventListener('click', () => {
      mobileDrawer.classList.toggle('hidden');
    });
  }

})();
</script>
`;

output = output.replace('</body>', `${interactiveScript}\n</body>`);

// Write root standalone file
fs.writeFileSync('c:/laragon/www/LaraforgeX/frontend_website.html', output, 'utf8');

// Write Laravel views
const targetDir = 'c:/laragon/www/LaraforgeX/apps/api/resources/views/frontend';
if (!fs.existsSync(targetDir)) {
  fs.mkdirSync(targetDir, { recursive: true });
}
fs.writeFileSync(path.join(targetDir, 'index.php'), output, 'utf8');

console.log('Successfully updated frontend_website.html & apps/api/resources/views/frontend/index.php');

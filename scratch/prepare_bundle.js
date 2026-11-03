const fs = require('fs');

let js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro.js', 'utf8');

// 1. Add CRM Login button to desktop navbar
const desktopTarget = 'children:r("nav.bookDemo")})]}),s.jsxs("div",{className:"flex items-center gap-2 xl:hidden"';
const desktopReplacement = 'children:r("nav.bookDemo")}),s.jsx("a",{href:"/admin/dashboard",className:"whitespace-nowrap shrink-0 bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-lg font-extrabold text-xs tracking-wider uppercase transition-all duration-200 hover:scale-105 text-center flex items-center gap-1.5 shadow-sm",children:"CRM Login"})]}),s.jsxs("div",{className:"flex items-center gap-2 xl:hidden"';

if (!js.includes(desktopTarget)) {
  console.error('Desktop target not found!');
  process.exit(1);
}
js = js.replace(desktopTarget, desktopReplacement);

// 2. Add CRM Login button to mobile drawer
const mobileTarget = 'children:r("hero.primaryCta")}),s.jsxs("a",{href:A.whatsappDemoUrl';
const mobileReplacement = 'children:r("hero.primaryCta")}),s.jsx("a",{href:"/admin/dashboard",className:"w-full text-center bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-lg font-extrabold text-sm tracking-wide transition-all",children:"CRM Login"}),s.jsxs("a",{href:A.whatsappDemoUrl';

if (!js.includes(mobileTarget)) {
  console.error('Mobile target not found!');
  process.exit(1);
}
js = js.replace(mobileTarget, mobileReplacement);

// Write to public assets
const publicJsDir = 'c:/laragon/www/LaraforgeX/apps/api/public/assets/js';
if (!fs.existsSync(publicJsDir)) {
  fs.mkdirSync(publicJsDir, { recursive: true });
}
fs.writeFileSync(publicJsDir + '/frontend.js', js, 'utf8');
console.log('Successfully wrote apps/api/public/assets/js/frontend.js');

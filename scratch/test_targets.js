const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro.js', 'utf8');

// Target 1: Desktop navbar after book demo button
// Search for: children:r("nav.bookDemo")})]}),s.jsxs("div",{className:"flex items-center gap-2 xl:hidden"
const target1 = 'children:r("nav.bookDemo")})]}),s.jsxs("div",{className:"flex items-center gap-2 xl:hidden"';
console.log('Target 1 exists:', js.includes(target1));

// Target 2: Mobile drawer
const target2 = 'children:r("hero.primaryCta")}),s.jsxs("a",{href:A.whatsappDemoUrl';
console.log('Target 2 exists:', js.includes(target2));

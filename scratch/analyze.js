const fs = require('fs');

const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

// Find section IDs and routes
const sectionIds = [...js.matchAll(/id:\s*["']([^"']+)["']/g)].map(m => m[1]);
const hrefs = [...new Set([...js.matchAll(/href:\s*["']#([^"']+)["']/g)].map(m => m[1]))];
const routes = [...new Set([...js.matchAll(/path:\s*["']([^"']+)["']/g)].map(m => m[1]))];

console.log('--- ANCHORS / SECTIONS ---');
console.log(hrefs);

console.log('--- ROUTES ---');
console.log(routes);

// Look for language dictionaries or main feature lists
const featuresMatches = [...js.matchAll(/features|pricing|calculator|testimonials|pm-surya-ghar|faq/gi)];
console.log('Found keyword matches:', featuresMatches.length);


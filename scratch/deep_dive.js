const fs = require('fs');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');

// Find all sections
const sectionMatches = [...html.matchAll(/<section[^>]*id="([^"]+)"[^>]*>/g)];
console.log('Sections found:', sectionMatches.map(m => m[1]));

// Headings
const headers = [...html.matchAll(/<h[1-3][^>]*>([\s\S]*?)<\/h[1-3]>/g)].map(m => m[1].replace(/<[^>]+>/g, '').trim());
console.log('\n--- HEADINGS ---');
headers.forEach((h, i) => {
  if (h) console.log(`${i+1}. ${h}`);
});

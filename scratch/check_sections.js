const fs = require('fs');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');

const rootStart = html.indexOf('<div id="root">');
const rootEnd = html.lastIndexOf('</div>');

console.log('Root start:', rootStart);
console.log('Root end:', rootEnd);
console.log('Length of inner HTML:', rootEnd - rootStart);

// Check if all sections are inside root
['features', 'how-it-works', 'pm-surya-ghar', 'customer-tracking', 'calculator', 'freetrial', 'contact', 'faq'].forEach(id => {
  console.log(`Section #${id} exists in HTML:`, html.includes(`id="${id}"`));
});


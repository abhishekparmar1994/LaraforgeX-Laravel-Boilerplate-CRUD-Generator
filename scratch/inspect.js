const fs = require('fs');
const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');

console.log('Total HTML length:', html.length);
const rootIdx = html.indexOf('<div id="root">');
console.log('Root index:', rootIdx);
console.log('Preview:', html.substring(rootIdx, rootIdx + 500));


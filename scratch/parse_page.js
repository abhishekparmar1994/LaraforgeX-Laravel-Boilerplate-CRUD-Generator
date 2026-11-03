const fs = require('fs');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');

const headMatch = html.match(/<head>([\s\S]*?)<\/head>/);
const bodyMatch = html.match(/<body[^>]*>([\s\S]*?)<\/body>/);

console.log('Head length:', headMatch ? headMatch[1].length : 0);
console.log('Body length:', bodyMatch ? bodyMatch[1].length : 0);


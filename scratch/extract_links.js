const fs = require('fs');
const html1 = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');
const html2 = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/privacy_policy.html', 'utf8');

function extractLinks(html) {
  const links = [...html.matchAll(/href="([^"#][^"]*)"/g)].map(m => m[1]);
  return [...new Set(links)];
}

console.log('Homepage Links:', extractLinks(html1));
console.log('Privacy Links:', extractLinks(html2));


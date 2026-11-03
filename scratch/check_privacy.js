const fs = require('fs');
const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/privacy_policy.html', 'utf8');

console.log('Privacy HTML length:', html.length);
const rootStart = html.indexOf('<div id="root">');
console.log('Privacy preview:', html.substring(rootStart, rootStart + 600));


const fs = require('fs');

const js = fs.readFileSync('c:/laragon/www/LaraforgeX/apps/api/public/assets/js/frontend.js', 'utf8');

// Check the hydration line
const hydrateIdx = js.indexOf('hydrateRoot');
console.log('hydrateIdx:', hydrateIdx);
console.log(js.substring(hydrateIdx - 50, hydrateIdx + 200));

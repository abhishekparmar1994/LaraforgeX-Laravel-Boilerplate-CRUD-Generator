const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

// Check for router patterns
const routerMatches = js.match(/\/privacy-policy|\/terms|\/demo|\/login|\/register|\/dashboard/g);
console.log('Router matches:', routerMatches);

// Check languages
const guMatch = js.includes('ગુજરાતી');
const hiMatch = js.includes('हिन्दी');
const enMatch = js.includes('English');
console.log('Language support:', { guMatch, hiMatch, enMatch });


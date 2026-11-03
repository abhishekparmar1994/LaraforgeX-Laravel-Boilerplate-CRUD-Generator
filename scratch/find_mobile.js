const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro.js', 'utf8');

// Look for mobile drawer in lx component (lines 284000-289000)
const snippet = js.substring(285000, 290000);
console.log(snippet);

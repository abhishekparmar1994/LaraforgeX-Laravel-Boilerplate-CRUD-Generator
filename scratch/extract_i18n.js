const fs = require('fs');

const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

// Find language objects or keys
// Look for strings like "Grow Your Solar Business Smarter" or translations
const enIdx = js.indexOf('Grow Your Solar Business Smarter');
console.log('Found English heading at:', enIdx);
if (enIdx !== -1) {
  console.log(js.substring(enIdx - 200, enIdx + 400));
}


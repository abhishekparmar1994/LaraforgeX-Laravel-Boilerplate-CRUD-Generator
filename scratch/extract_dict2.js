const fs = require('fs');

const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');
const pos = js.indexOf('roiCalculator:');
console.log('pos:', pos);
if (pos !== -1) {
  console.log(js.substring(pos - 300, pos + 300));
}


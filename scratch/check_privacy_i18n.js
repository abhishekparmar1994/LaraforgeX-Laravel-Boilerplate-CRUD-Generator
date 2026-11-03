const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

// Search for privacy policy component or route
const privIdx = js.indexOf('Privacy Policy');
console.log('privIdx:', privIdx);
const privOccurrences = [...js.matchAll(/privacy-policy/g)].map(m => m.index);
console.log('privacy-policy occurrences:', privOccurrences);

privOccurrences.forEach(idx => {
  console.log('--- Context at', idx, '---');
  console.log(js.substring(idx - 100, idx + 300));
});

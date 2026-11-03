const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

const mxIdx = js.indexOf('function Mx');
const jIdx = js.indexOf(',j={gu:{', mxIdx);
console.log('jIdx:', jIdx);

const endJ = js.indexOf(';return s.jsxs("div"', jIdx);
console.log('endJ:', endJ);

const jCode = js.substring(jIdx + 1, endJ);
console.log('jCode snippet:', jCode.substring(0, 300));

try {
  const fn = new Function(jCode + '; return j;');
  const j = fn();
  console.log('Privacy policy languages:', Object.keys(j));
  console.log('Sample gu:', Object.keys(j.gu));
  fs.writeFileSync('c:/laragon/www/LaraforgeX/scratch/privacy_translations.json', JSON.stringify(j, null, 2), 'utf8');
  console.log('Saved privacy_translations.json!');
} catch (e) {
  console.error('Error:', e);
}

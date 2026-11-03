const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

// Find where i0 (start of Gujarati dictionary) starts
// Let's search backwards from N0={
const n0Idx = js.indexOf('N0={nav:i0');
console.log('n0Idx:', n0Idx);

// Look backwards for i0={
const i0Idx = js.lastIndexOf('i0={', n0Idx);
console.log('i0Idx:', i0Idx);

const sxIdx = js.indexOf('sx={gu:{translation:');
const sxEnd = js.indexOf(';', sxIdx);

// Let's extract from i0Idx to sxEnd
const codeToEval = js.substring(i0Idx, sxEnd + 1);

try {
  // Run inside vm or Function
  const fn = new Function(codeToEval + '\nreturn sx;');
  const sx = fn();
  console.log('SUCCESS! Extracted sx with keys:', Object.keys(sx));
  console.log('gu keys:', Object.keys(sx.gu.translation));
  console.log('hi keys:', Object.keys(sx.hi.translation));
  console.log('en keys:', Object.keys(sx.en.translation));
  
  fs.writeFileSync('c:/laragon/www/LaraforgeX/scratch/translations.json', JSON.stringify({
    gu: sx.gu.translation,
    hi: sx.hi.translation,
    en: sx.en.translation
  }, null, 2), 'utf8');
  console.log('Wrote translations.json successfully!');
} catch (e) {
  console.error('Eval error:', e);
}

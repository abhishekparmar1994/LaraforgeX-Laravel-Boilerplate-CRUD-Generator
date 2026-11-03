const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

const sxIdx = js.indexOf('sx={gu:{translation:');
console.log('sxIdx:', sxIdx);
if (sxIdx !== -1) {
  console.log(js.substring(sxIdx - 200, sxIdx + 200));
}

// Find N0 assignment
const guVarMatch = js.substring(sxIdx, sxIdx + 50).match(/gu:\{translation:([a-zA-Z0-9_$]+)\}/);
console.log('guVar:', guVarMatch ? guVarMatch[1] : null);

const hiVarMatch = js.substring(sxIdx, sxIdx + 50).match(/hi:\{translation:([a-zA-Z0-9_$]+)\}/);
console.log('hiVar:', hiVarMatch ? hiVarMatch[1] : null);

const enVarMatch = js.substring(sxIdx, sxIdx + 50).match(/en:\{translation:([a-zA-Z0-9_$]+)\}/);
console.log('enVar:', enVarMatch ? enVarMatch[1] : null);

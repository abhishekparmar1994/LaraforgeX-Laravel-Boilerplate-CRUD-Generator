const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');
const jIdx = js.indexOf(',j={gu:{', 430352);
const endJ = js.indexOf(';return s.jsxs("div"', jIdx);
const jCode = js.substring(jIdx + 1, endJ);
const unquoted = [...jCode.matchAll(/icon\s*:\s*([a-zA-Z0-9_$]+)/g)].map(m => m[1]);

const dummyDefs = [...new Set(unquoted)].map(v => `const ${v} = "${v}";`).join('\n') + '\nconst n = "gu";\n';
const fixedCode = dummyDefs + '\n' + jCode + '\nreturn j;';
const fn = new Function(fixedCode);
const j = fn();
console.log('Privacy policy languages extracted successfully:', Object.keys(j));
fs.writeFileSync('c:/laragon/www/LaraforgeX/scratch/privacy_translations.json', JSON.stringify(j, null, 2), 'utf8');
console.log('Saved privacy_translations.json!');

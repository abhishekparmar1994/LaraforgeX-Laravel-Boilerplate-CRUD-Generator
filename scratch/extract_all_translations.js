const fs = require('fs');

const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

// Find all occurrences of translation definitions
// Look for where i18n is initialized or where resources are defined
// e.g., resources: { en: { ... }, gu: { ... }, hi: { ... } }
const i18nInit = js.indexOf('init({');
console.log('i18nInit at:', i18nInit);

if (i18nInit !== -1) {
  console.log('Around init:', js.substring(i18nInit - 200, i18nInit + 500));
}

// Also search for "Gujarati" or "English" resource keys
const guIdx = js.indexOf('ગુજરાતી');
console.log('guIdx:', guIdx);
if (guIdx !== -1) {
  console.log('Around guIdx:', js.substring(guIdx - 100, guIdx + 300));
}

const fs = require('fs');

const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

// Search for the translation keys
// Notice earlier: g:"ગ્રાહક ટ્રેકિંગ",roiCalculator:"ROI કેલ્ક્યુલેટર"
const guStart = js.indexOf('{nav:{features:"');
console.log('guStart:', guStart);

// Let's search for "SolarPro & SolarSass" translations
const matches = [...js.matchAll(/([a-zA-Z0-9_]+)\s*=\s*\{nav:\{features:/g)];
console.log('Translation objects:', matches.map(m => m[0]));


const fs = require('fs');

let css = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.css', 'utf8');

const fontImport = `@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700;800&family=Noto+Sans+Gujarati:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap');\n\n`;

const fontOverride = `
body, html, [class*="font-outfit"] {
  font-family: 'Outfit', 'Noto Sans Gujarati', 'Noto Sans Devanagari', 'Nirmala UI', sans-serif !important;
}
`;

const finalCss = fontImport + css + '\n' + fontOverride;

const publicCssDir = 'c:/laragon/www/LaraforgeX/apps/api/public/assets/css';
if (!fs.existsSync(publicCssDir)) {
  fs.mkdirSync(publicCssDir, { recursive: true });
}
fs.writeFileSync(publicCssDir + '/frontend.css', finalCss, 'utf8');
console.log('Successfully wrote apps/api/public/assets/css/frontend.css');

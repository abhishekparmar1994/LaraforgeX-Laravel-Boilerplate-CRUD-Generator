const fs = require('fs');
const path = require('path');

// 1. Read sources
const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');
const privacyHtml = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/privacy_policy.html', 'utf8');
const css = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.css', 'utf8');

// Font declarations & styling overrides
const fontStyle = `
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700;800&family=Noto+Sans+Gujarati:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
  body, html, [class*="font-outfit"], button, input, textarea, select {
    font-family: 'Outfit', 'Noto Sans Gujarati', 'Noto Sans Devanagari', 'Nirmala UI', sans-serif !important;
  }
</style>
`;

// Helper to clean up HTML head
function processHtml(content, isPrivacy = false) {
  let res = content;

  // Replace old css & js tags
  res = res.replace(/<link\s+rel="stylesheet"\s+crossorigin\s+href="\/assets\/index-[^"]+\.css">/g, '');
  res = res.replace(/<script\s+type="module"\s+crossorigin\s+src="\/assets\/index-[^"]+\.js"><\/script>/g, '');

  // Add stylesheet link and fonts to head
  const headInject = `
  <link rel="stylesheet" href="/assets/css/frontend.css">
  ${fontStyle}
</head>`;
  res = res.replace('</head>', headInject);

  // Add the script before </body>
  const scriptInject = `
  <script type="module" src="/assets/js/frontend.js"></script>
</body>`;
  res = res.replace('</body>', scriptInject);

  return res;
}

const mainOutput = processHtml(html, false);
const privacyOutput = processHtml(privacyHtml, true);

// 2. Write to Laravel Views
const viewsDir = 'c:/laragon/www/LaraforgeX/apps/api/resources/views/frontend';
if (!fs.existsSync(viewsDir)) {
  fs.mkdirSync(viewsDir, { recursive: true });
}
fs.writeFileSync(path.join(viewsDir, 'index.php'), mainOutput, 'utf8');
fs.writeFileSync(path.join(viewsDir, 'privacy.php'), privacyOutput, 'utf8');
console.log('Saved Laravel views: index.php and privacy.php');

// 3. For Standalone HTML files (frontend_website.html & privacy_policy.html)
// Inline CSS so they work completely self-contained when double-clicked
function processStandalone(content) {
  let res = content;
  res = res.replace(/<link\s+rel="stylesheet"\s+crossorigin\s+href="\/assets\/index-[^"]+\.css">/g, '');
  res = res.replace(/<script\s+type="module"\s+crossorigin\s+src="\/assets\/index-[^"]+\.js"><\/script>/g, '');

  const inlinedCss = `
  ${fontStyle}
  <style>
  ${css}
  </style>
</head>`;
  res = res.replace('</head>', inlinedCss);

  // In standalone, reference the script from relative path or server
  const scriptTag = `
  <script type="module" src="./apps/api/public/assets/js/frontend.js"></script>
</body>`;
  res = res.replace('</body>', scriptTag);
  return res;
}

fs.writeFileSync('c:/laragon/www/LaraforgeX/frontend_website.html', processStandalone(html), 'utf8');
fs.writeFileSync('c:/laragon/www/LaraforgeX/privacy_policy.html', processStandalone(privacyHtml), 'utf8');
console.log('Saved standalone files: frontend_website.html and privacy_policy.html');

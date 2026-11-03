const fs = require('fs');
const path = require('path');

const targetDir = 'c:/laragon/www/LaraforgeX/apps/api/resources/views/frontend';
if (!fs.existsSync(targetDir)) {
  fs.mkdirSync(targetDir, { recursive: true });
}

const indexHtml = fs.readFileSync('c:/laragon/www/LaraforgeX/frontend_website.html', 'utf8');
const privacyHtml = fs.readFileSync('c:/laragon/www/LaraforgeX/privacy_policy.html', 'utf8');

fs.writeFileSync(path.join(targetDir, 'index.blade.php'), indexHtml, 'utf8');
fs.writeFileSync(path.join(targetDir, 'privacy.blade.php'), privacyHtml, 'utf8');

console.log('Saved frontend views to apps/api/resources/views/frontend/');


const fs = require('fs');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');

const imgs = [...html.matchAll(/<img[^>]+src="([^">]+)"/g)].map(m => m[1]);
console.log('Image URLs found:', imgs);


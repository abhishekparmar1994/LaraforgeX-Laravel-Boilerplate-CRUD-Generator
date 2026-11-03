const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro.js', 'utf8');
const imgMatches = [...js.matchAll(/src\s*:\s*"([^"]+)"/g)];
console.log('Images in solartrackpro.js:', imgMatches.length);
imgMatches.forEach(m => console.log(m[1]));

const extMatches = [...js.matchAll(/\.(png|jpg|jpeg|webp|svg)/gi)];
console.log('Extensions found:', extMatches.length);

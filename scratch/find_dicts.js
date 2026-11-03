const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

const sxIdx = js.indexOf('sx={gu:{translation:');
const beforeSx = js.substring(0, sxIdx);

const n0Idx = beforeSx.lastIndexOf('N0={');
console.log('N0={ at:', n0Idx);
if (n0Idx !== -1) {
  console.log('N0 snippet:', beforeSx.substring(n0Idx, n0Idx + 300));
}

const dollar0Idx = beforeSx.lastIndexOf('$0={');
console.log('$0={ at:', dollar0Idx);
if (dollar0Idx !== -1) {
  console.log('$0 snippet:', beforeSx.substring(dollar0Idx, dollar0Idx + 300));
}

const nxIdx = beforeSx.lastIndexOf('nx={');
console.log('nx={ at:', nxIdx);
if (nxIdx !== -1) {
  console.log('nx snippet:', beforeSx.substring(nxIdx, nxIdx + 300));
}

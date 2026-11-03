const fs = require('fs');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');

const calcIdx = html.indexOf('id="calculator"');
if (calcIdx !== -1) {
  // Find opening of section
  const secStart = html.lastIndexOf('<section', calcIdx);
  const secEnd = html.indexOf('</section>', calcIdx) + '</section>'.length;
  console.log('Calculator section found:', secStart, secEnd);
  const calcHtml = html.substring(secStart, secEnd);
  fs.writeFileSync('c:/laragon/www/LaraforgeX/scratch/calculator.html', calcHtml, 'utf8');
  console.log('Saved calculator.html with length:', calcHtml.length);
} else {
  console.log('Calculator not found');
}


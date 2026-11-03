const fs = require('fs');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');

const faqIdx = html.indexOf('id="faq"');
if (faqIdx !== -1) {
  const secStart = html.lastIndexOf('<section', faqIdx);
  const secEnd = html.indexOf('</section>', faqIdx) + '</section>'.length;
  console.log('FAQ section found:', secStart, secEnd);
  const faqHtml = html.substring(secStart, secEnd);
  fs.writeFileSync('c:/laragon/www/LaraforgeX/scratch/faq.html', faqHtml, 'utf8');
  console.log('Saved faq.html with length:', faqHtml.length);
}


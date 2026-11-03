const fs = require('fs');
const js = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.js', 'utf8');

const jIdx = js.indexOf(',j={gu:{', 430352);
const endJ = js.indexOf(';return s.jsxs("div"', jIdx);

let jCode = js.substring(jIdx + 1, endJ);

// We can replace identifiers used as icon components: icon:Ga -> icon:"EyeOff"
// Let's create a proxy / dummy for all undeclared vars
try {
  const dummyHandler = {
    get: (target, prop) => prop
  };
  const dummyProxy = new Proxy({}, dummyHandler);
  
  // Wrap in a function with a Proxy sandbox or define common lucide icons
  const fn = new Function('proxy', `
    const handler = { get: (t, p) => p };
    const p = new Proxy({}, handler);
    with(p) {
      ${jCode}
      return j;
    }
  `);
  const j = fn(dummyProxy);
  console.log('SUCCESS! Privacy policy languages:', Object.keys(j));
  console.log('Gu title:', j.gu.title);
  console.log('Hi title:', j.hi.title);
  console.log('En title:', j.en.title);
  console.log('Gu highlights:', j.gu.highlights ? j.gu.highlights.length : 0);
  console.log('Gu faqs:', j.gu.faqs ? j.gu.faqs.length : 0);

  // Clean icons to strings
  for (const lang of ['gu', 'hi', 'en']) {
    if (j[lang] && j[lang].highlights) {
      j[lang].highlights.forEach((h, idx) => {
        h.icon = 'icon_' + idx;
      });
    }
  }

  fs.writeFileSync('c:/laragon/www/LaraforgeX/scratch/privacy_translations.json', JSON.stringify(j, null, 2), 'utf8');
  console.log('Wrote privacy_translations.json!');
} catch (e) {
  console.error('Error with proxy:', e);
}

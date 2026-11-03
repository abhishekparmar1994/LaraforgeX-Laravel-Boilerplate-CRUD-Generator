const fs = require('fs');

const filePath = 'c:/laragon/www/LaraforgeX/apps/api/public/assets/js/frontend.js';
let js = fs.readFileSync(filePath, 'utf8');

const oldMount = 'const rn=document.getElementById("root");rn&&rn.hasChildNodes()?js.hydrateRoot(rn,s.jsx(Ji.StrictMode,{children:s.jsx(jc,{})})):rn&&js.createRoot(rn).render(s.jsx(Ji.StrictMode,{children:s.jsx(jc,{})}));';

const newMount = `
const rn = document.getElementById("root");
try {
  if (rn && rn.hasChildNodes()) {
    js.hydrateRoot(rn, s.jsx(jc, {}));
  } else if (rn) {
    js.createRoot(rn).render(s.jsx(jc, {}));
  }
} catch (err) {
  console.warn("Hydration failed, mounting fresh with createRoot:", err);
  if (rn) {
    rn.innerHTML = "";
    js.createRoot(rn).render(s.jsx(jc, {}));
  }
}
`;

if (js.includes(oldMount)) {
  js = js.replace(oldMount, newMount);
  fs.writeFileSync(filePath, js, 'utf8');
  console.log('Successfully updated mount logic in frontend.js!');
} else {
  console.error('oldMount string not found!');
}

const fs = require('fs');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/solartrackpro_clean.html', 'utf8');

const headEnd = html.indexOf('</head>');
console.log('HEAD:');
console.log(html.substring(0, headEnd + 7));

const bodyEnd = html.indexOf('</body>');
console.log('END OF BODY:');
console.log(html.substring(bodyEnd - 300, html.length));

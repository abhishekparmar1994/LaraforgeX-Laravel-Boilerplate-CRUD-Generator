const fs = require('fs');

const html = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/privacy_policy.html', 'utf8');
const css = fs.readFileSync('c:/laragon/www/LaraforgeX/scratch/index.css', 'utf8');

// Replace stylesheet link with inlined <style>
let output = html.replace(/<link\s+rel="stylesheet"\s+crossorigin\s+href="\/assets\/index-[^"]+\.css">/, `<style>\n${css}\n</style>`);

// Remove react module script tag
output = output.replace(/<script\s+type="module"\s+crossorigin\s+src="\/assets\/index-[^"]+\.js"><\/script>/, '');

fs.writeFileSync('c:/laragon/www/LaraforgeX/privacy_policy.html', output, 'utf8');
console.log('Successfully created c:/laragon/www/LaraforgeX/privacy_policy.html, size:', output.length);


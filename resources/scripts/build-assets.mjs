import { cp, mkdir, readFile, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';

const assets = [
    ['node_modules/bootstrap-icons/font', 'webroot/assets/npm-asset/bootstrap-icons/font'],
    ['node_modules/jquery/dist/jquery.min.js', 'webroot/assets/npm-asset/jquery/dist/jquery.min.js'],
    ['node_modules/piexifjs/piexif.js', 'webroot/assets/npm-asset/piexifjs/piexif.js'],
    ['node_modules/sortablejs/Sortable.js', 'webroot/assets/npm-asset/sortablejs/Sortable.js'],
];

const libsBundleSources = [
    'node_modules/bootstrap-icons/font/bootstrap-icons.css',
    'node_modules/aos/dist/aos.css',
    'node_modules/tiny-slider/dist/tiny-slider.css',
    'node_modules/plyr/dist/plyr.css',
    'node_modules/prismjs/themes/prism.css',
    'node_modules/nouislider/dist/nouislider.css',
];

const libsBundleDestination = 'webroot/css/libs.bundle.css';

const rewriteBootstrapIconsFontUrls = (cssText) => cssText
    .replaceAll('url("./fonts/bootstrap-icons.woff2?', 'url("/assets/npm-asset/bootstrap-icons/font/fonts/bootstrap-icons.woff2?')
    .replaceAll('url("./fonts/bootstrap-icons.woff?', 'url("/assets/npm-asset/bootstrap-icons/font/fonts/bootstrap-icons.woff?')
    .replaceAll("url('./fonts/bootstrap-icons.woff2?", "url('/assets/npm-asset/bootstrap-icons/font/fonts/bootstrap-icons.woff2?")
    .replaceAll("url('./fonts/bootstrap-icons.woff?", "url('/assets/npm-asset/bootstrap-icons/font/fonts/bootstrap-icons.woff?");

const stripSourceMapComments = (cssText) => cssText.replace(/\/\*# sourceMappingURL=.*?\*\//g, '');

const buildLibsBundle = async () => {
    const chunks = [];

    for (const sourcePath of libsBundleSources) {
        let cssText = await readFile(resolve(sourcePath), 'utf8');
        cssText = stripSourceMapComments(cssText);

        if (sourcePath.includes('bootstrap-icons.css')) {
            cssText = rewriteBootstrapIconsFontUrls(cssText);
        }

        chunks.push(`/* ${sourcePath} */\n${cssText.trim()}\n`);
    }

    const outputPath = resolve(libsBundleDestination);
    await mkdir(dirname(outputPath), { recursive: true });
    await writeFile(outputPath, `${chunks.join('\n')}\n`, 'utf8');
};

for (const [source, destination] of assets) {
    const destinationPath = resolve(destination);
    await mkdir(dirname(destinationPath), { recursive: true });
    await cp(resolve(source), destinationPath, { recursive: true });
}

await buildLibsBundle();
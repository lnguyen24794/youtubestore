/**
 * Optimize theme images: resize oversized and generate WebP (next-gen format).
 * Run: npm run images:optimize (from theme root)
 * Dry run: npm run images:optimize:dry
 */

const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const THEME_DIR = path.resolve(__dirname, '..');
const IMAGES_DIR = path.join(THEME_DIR, 'assets', 'images');
const MAX_WIDTH = 1920;
const WEBP_QUALITY = 85;
const JPEG_QUALITY = 85;
const PNG_QUALITY = 85;

const DRY_RUN = process.argv.includes('--dry-run');
const EXTS = ['.jpg', '.jpeg', '.png'];

function getAllImageFiles(dir, list = []) {
    if (!fs.existsSync(dir)) return list;
    const entries = fs.readdirSync(dir, { withFileTypes: true });
    for (const e of entries) {
        const full = path.join(dir, e.name);
        if (e.isDirectory()) {
            if (e.name !== 'node_modules' && e.name !== 'upload') getAllImageFiles(full, list);
        } else if (EXTS.includes(path.extname(e.name).toLowerCase())) {
            list.push(full);
        }
    }
    return list;
}

async function processFile(filePath) {
    const ext = path.extname(filePath).toLowerCase();
    const dir = path.dirname(filePath);
    const base = path.basename(filePath, ext);
    const webpPath = path.join(dir, base + '.webp');

    const stat = fs.statSync(filePath);
    let meta;
    try {
        meta = await sharp(filePath).metadata();
    } catch (err) {
        console.warn('Skip (read error):', filePath, err.message);
        return { skipped: true, error: err.message };
    }

    const width = meta.width || 0;
    const height = meta.height || 0;
    const needResize = width > MAX_WIDTH;
    const webpExists = fs.existsSync(webpPath);
    const webpNewer = webpExists && fs.statSync(webpPath).mtime >= stat.mtime;
    const needWebP = !webpExists || !webpNewer;

    const out = { path: filePath, width, height, resized: false, webp: false };

    if (DRY_RUN) {
        if (needResize) out.wouldResize = true;
        if (needWebP) out.wouldWebP = true;
        return out;
    }

    if (!needResize && !needWebP) return out;

    try {
        let pipeline = sharp(filePath);
        if (needResize) {
            pipeline = pipeline.resize(MAX_WIDTH, null, { withoutEnlargement: true });
            out.resized = true;
        }

        if (needResize) {
            if (ext === '.png') {
                pipeline = pipeline.png({ quality: PNG_QUALITY });
            } else {
                pipeline = pipeline.jpeg({ quality: JPEG_QUALITY });
            }
            const buf = await pipeline.toBuffer();
            fs.writeFileSync(filePath, buf);
        }

        if (needWebP) {
            const webpPipeline = sharp(filePath).webp({ quality: WEBP_QUALITY });
            if (needResize) {
                await webpPipeline.resize(MAX_WIDTH, null, { withoutEnlargement: true }).toFile(webpPath);
            } else {
                await webpPipeline.toFile(webpPath);
            }
            out.webp = true;
        }
    } catch (err) {
        console.warn('Error:', filePath, err.message);
        out.error = err.message;
    }
    return out;
}

async function main() {
    console.log('Images dir:', IMAGES_DIR);
    console.log('Max width:', MAX_WIDTH, '| WebP quality:', WEBP_QUALITY, DRY_RUN ? '| DRY RUN' : '');
    const files = getAllImageFiles(IMAGES_DIR);
    console.log('Found', files.length, 'image(s)\n');

    const results = { processed: 0, resized: 0, webp: 0, skipped: 0, errors: 0 };
    for (const f of files) {
        const rel = path.relative(THEME_DIR, f);
        const r = await processFile(f);
        if (r.skipped || r.error) {
            results.skipped++;
            if (r.error) results.errors++;
            continue;
        }
        results.processed++;
        if (r.resized || r.wouldResize) results.resized++;
        if (r.webp || r.wouldWebP) results.webp++;
        const msg = [rel];
        if (r.wouldResize || r.resized) msg.push('resize');
        if (r.wouldWebP || r.webp) msg.push('webp');
        console.log('  ', msg.join(' | '));
    }

    console.log('\nDone:', results.processed, 'processed,', results.resized, 'resized,', results.webp, 'webp,', results.skipped, 'skipped,', results.errors, 'errors.');
}

main().catch((err) => {
    console.error(err);
    process.exit(1);
});

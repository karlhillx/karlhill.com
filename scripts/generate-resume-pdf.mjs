/**
 * Render a letter-size résumé HTML file to PDF via Puppeteer (Chrome),
 * then attach intentional document metadata with pdf-lib.
 *
 * Usage:
 *   node scripts/generate-resume-pdf.mjs <input.html> <output.pdf>
 */
import { pathToFileURL } from 'node:url';
import path from 'node:path';
import fs from 'node:fs';
import os from 'node:os';
import puppeteer from 'puppeteer';
import { PDFDocument } from 'pdf-lib';

const [, , inputArg, outputArg] = process.argv;

if (!inputArg || !outputArg) {
    console.error('Usage: node scripts/generate-resume-pdf.mjs <input.html> <output.pdf>');
    process.exit(1);
}

const inputPath = path.resolve(inputArg);
const outputPath = path.resolve(outputArg);

if (!fs.existsSync(inputPath)) {
    console.error(`Input HTML not found: ${inputPath}`);
    process.exit(1);
}

fs.mkdirSync(path.dirname(outputPath), { recursive: true });

// Prefer a stable cache (Cursor sandbox cache often breaks Chrome frameworks).
if (!process.env.PUPPETEER_CACHE_DIR) {
    process.env.PUPPETEER_CACHE_DIR = path.join(os.homedir(), '.cache', 'puppeteer');
}

const macChrome = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const launchOptions = {
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--font-render-hinting=none'],
};

if (fs.existsSync(macChrome)) {
    launchOptions.executablePath = macChrome;
} else {
    launchOptions.channel = 'chrome';
}

const browser = await puppeteer.launch(launchOptions);

let pdfBytes;
try {
    const page = await browser.newPage();
    await page.goto(pathToFileURL(inputPath).href, {
        waitUntil: 'networkidle0',
    });
    await page.evaluate(async () => {
        if (document.fonts?.ready) {
            await document.fonts.ready;
        }
    });

    pdfBytes = await page.pdf({
        format: 'Letter',
        printBackground: true,
        preferCSSPageSize: true,
        // Embed HTML structure so ATS / accessibility tools follow DOM reading order
        // instead of pure geometric (left-to-right) extraction across the sidebar.
        tagged: true,
        margin: { top: '0', right: '0', bottom: '0', left: '0' },
    });
} finally {
    await browser.close();
}

const pdfDoc = await PDFDocument.load(pdfBytes);

pdfDoc.setTitle('Karl Hill — Resume');
pdfDoc.setAuthor('Karl Hill');
pdfDoc.setSubject('Software Engineering Leadership Resume');
pdfDoc.setKeywords([
    'Software Engineering Leadership',
    'Platform Engineering',
    'DevSecOps',
    'Cloud-Native',
    'Aerospace',
]);

const withMeta = await pdfDoc.save({ useObjectStreams: false });
fs.writeFileSync(outputPath, withMeta);

console.log(`Wrote ${outputPath}`);

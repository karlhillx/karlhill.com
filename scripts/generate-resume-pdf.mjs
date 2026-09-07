/**
 * Render a letter-size résumé HTML file to PDF via Playwright (Chromium),
 * then attach intentional document metadata with pdf-lib.
 *
 * Usage:
 *   node scripts/generate-resume-pdf.mjs <input.html> <output.pdf>
 */
import { pathToFileURL } from 'node:url';
import path from 'node:path';
import fs from 'node:fs';
import { chromium } from 'playwright';
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

const macChrome = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const launchOptions = {
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--font-render-hinting=none'],
};
if (fs.existsSync(macChrome)) {
    launchOptions.executablePath = macChrome;
}

const browser = await chromium.launch(launchOptions);

let pdfBytes;
try {
    const page = await browser.newPage();
    await page.goto(pathToFileURL(inputPath).href, {
        waitUntil: 'networkidle',
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
        tagged: true,
        margin: { top: '0', right: '0', bottom: '0', left: '0' },
    });
} finally {
    await browser.close();
}

const pdfDoc = await PDFDocument.load(pdfBytes);

pdfDoc.setTitle('Karl Hill — Resume');
pdfDoc.setAuthor('Karl Hill');
pdfDoc.setSubject(
    'Staff Aerospace Software Engineer — Engineering Manager trajectory · Jacobs · NASA Goddard'
);
pdfDoc.setKeywords([
    'Karl Hill',
    'Karl M. Hill',
    'Engineering Manager',
    'Staff Aerospace Software Engineer',
    'Platform Engineering',
    'DevSecOps',
    'Cloud-Native',
    'Aerospace',
    'Defense',
    'Mission software',
    'NASA Goddard',
    'Jacobs National Security',
    'Washington DC',
]);

const withMeta = await pdfDoc.save({ useObjectStreams: false });
fs.writeFileSync(outputPath, withMeta);

console.log(`Wrote ${outputPath}`);

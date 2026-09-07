/**
 * Render a letter-size résumé HTML file to PDF via Playwright (Chromium),
 * then attach intentional document metadata with pdf-lib.
 *
 * Usage:
 *   node scripts/generate-resume-pdf.mjs <input.html> <output.pdf>
 *
 * The navy sidebar is painted as a solid CSS panel in Chromium (no type).
 * Sidebar copy is drawn afterward with embedded Lato so it stays vector-sharp
 * and lands after the main column in the content stream (ATS-friendly order).
 */
import { pathToFileURL, fileURLToPath } from 'node:url';
import path from 'node:path';
import fs from 'node:fs';
import { chromium } from 'playwright';
import fontkit from '@pdf-lib/fontkit';
import { PDFDocument, rgb } from 'pdf-lib';
import { decompress as decompressWoff2 } from 'wawoff2';

const [, , inputArg, outputArg] = process.argv;

if (!inputArg || !outputArg) {
    console.error('Usage: node scripts/generate-resume-pdf.mjs <input.html> <output.pdf>');
    process.exit(1);
}

const inputPath = path.resolve(inputArg);
const outputPath = path.resolve(outputArg);
const base = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');

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

async function loadLato(weight) {
    const file = path.join(
        base,
        'node_modules/@fontsource/lato/files',
        `lato-latin-${weight}-normal.woff2`,
    );
    const woff2 = fs.readFileSync(file);
    return Buffer.from(await decompressWoff2(woff2));
}

const browser = await chromium.launch(launchOptions);

let pdfBytes;
let sidebar;
try {
    const page = await browser.newPage();
    await page.setViewportSize({ width: 816, height: 1056 });
    await page.goto(pathToFileURL(inputPath).href, {
        waitUntil: 'networkidle',
    });
    await page.evaluate(async () => {
        if (document.fonts?.ready) {
            await document.fonts.ready;
        }
    });

    sidebar = await page.evaluate(() => {
        const raw = document.getElementById('resume-sidebar-data')?.textContent;
        if (!raw) {
            throw new Error('Missing #resume-sidebar-data');
        }
        return JSON.parse(raw);
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
pdfDoc.registerFontkit(fontkit);

const [latoRegularBytes, latoBoldBytes] = [
    await loadLato(400),
    await loadLato(700),
];
const lato = await pdfDoc.embedFont(latoRegularBytes, { subset: true });
const latoBold = await pdfDoc.embedFont(latoBoldBytes, { subset: true });

const page1 = pdfDoc.getPage(0);
const { width: pageW, height: pageH } = page1.getSize();

// Match resources/views/resume/pdf.blade.php sidebar tokens (inches → pt).
const sidebarW = 2.42 * 72;
const padX = 0.3 * 72;
const padTop = 0.45 * 72;
const ink = rgb(1, 1, 1);
const left = pageW - sidebarW + padX;
const maxTextW = sidebarW - padX * 2;
const ruleColor = rgb(1, 1, 1);

let y = pageH - padTop;

function drawTitle(text) {
    const size = 8;
    page1.drawText(text.toUpperCase(), {
        x: left,
        y: y - size,
        size,
        font: latoBold,
        color: ink,
        letterSpacing: 0.64, // ~0.08em at 8pt
    });
    y -= size + 4.3; // padding-bottom ~0.06in
    page1.drawLine({
        start: { x: left, y },
        end: { x: left + maxTextW, y },
        thickness: 0.7,
        color: ruleColor,
        opacity: 0.28,
    });
    y -= 0.11 * 72; // margin below rule
}

function drawLines(lines, { size = 8.25, bold = false, leading = 1.45, gap = 0.065 * 72 } = {}) {
    const font = bold ? latoBold : lato;
    for (const line of lines) {
        const wrapped = wrapText(line, font, size, maxTextW);
        for (const part of wrapped) {
            page1.drawText(part, {
                x: left,
                y: y - size,
                size,
                font,
                color: ink,
                opacity: bold ? 1 : 0.92,
            });
            y -= size * leading;
        }
        y -= Math.max(0, gap - size * (leading - 1));
    }
}

function wrapText(text, font, size, maxWidth) {
    const words = String(text).split(/\s+/).filter(Boolean);
    if (words.length === 0) {
        return [''];
    }
    const lines = [];
    let current = words[0];
    for (let i = 1; i < words.length; i++) {
        const next = `${current} ${words[i]}`;
        if (font.widthOfTextAtSize(next, size) <= maxWidth) {
            current = next;
        } else {
            lines.push(current);
            current = words[i];
        }
    }
    lines.push(current);
    return lines;
}

drawTitle('Details');
drawLines(
    [sidebar.location, sidebar.phone, sidebar.email].filter(Boolean),
    { size: 8.25, leading: 1.45, gap: 0.065 * 72 },
);

y -= 0.3 * 72 - 0.065 * 72; // sidebar-block + sidebar-block spacing
drawTitle('Links');
for (const link of sidebar.links ?? []) {
    drawLines([link.label], { size: 8.5, bold: true, leading: 1.2, gap: 0.02 * 72 });
    y += 0.02 * 72; // tighten label→url
    drawLines([link.url], { size: 7.5, leading: 1.35, gap: 0.065 * 72 });
}

y -= 0.3 * 72 - 0.065 * 72;
drawTitle('Core Competencies');
for (const item of sidebar.expertise ?? []) {
    const size = 8;
    const bullet = '•';
    const indent = 10;
    const wrapped = wrapText(item, lato, size, maxTextW - indent);
    wrapped.forEach((part, index) => {
        if (index === 0) {
            page1.drawText(bullet, {
                x: left,
                y: y - size,
                size: size * 0.85,
                font: lato,
                color: ink,
            });
        }
        page1.drawText(part, {
            x: left + indent,
            y: y - size,
            size,
            font: lato,
            color: ink,
        });
        y -= size * 1.32 + (index === wrapped.length - 1 ? 0.05 * 72 : 0);
    });
}

// Masthead already carries clickable contact/links for the main column.
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

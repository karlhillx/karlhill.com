/**
 * Render a letter-size résumé HTML file to PDF via Playwright (Chromium),
 * then attach intentional document metadata with pdf-lib.
 *
 * Usage:
 *   node scripts/generate-resume-pdf.mjs <input.html> <output.pdf>
 *
 * The navy sidebar is a solid CSS panel in Chromium (no HTML type). Sidebar
 * copy is drawn afterward as Lato glyph outlines so it stays vector-sharp,
 * matches the main column face, and does not enter the PDF text stream —
 * Chromium otherwise y/x-sorts sidebar + main text and interleaves them for ATS.
 */
import { pathToFileURL, fileURLToPath } from 'node:url';
import path from 'node:path';
import fs from 'node:fs';
import { chromium } from 'playwright';
import fontkit from '@pdf-lib/fontkit';
import {
    PDFDocument,
    popGraphicsState,
    pushGraphicsState,
    rgb,
    scale as scaleOp,
    translate,
} from 'pdf-lib';
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

// Decompress sequentially — parallel wawoff2 calls can corrupt output.
const latoRegularBytes = await loadLato(400);
const latoBoldBytes = await loadLato(700);
const latoFk = fontkit.create(latoRegularBytes);
const latoBoldFk = fontkit.create(latoBoldBytes);

const page1 = pdfDoc.getPage(0);
const { width: pageW, height: pageH } = page1.getSize();

const sidebarW = 2.42 * 72;
const padX = 0.3 * 72;
const padTop = 0.45 * 72;
const ink = rgb(1, 1, 1);
const left = pageW - sidebarW + padX;
const maxTextW = sidebarW - padX * 2;

let y = pageH - padTop;

function textWidth(text, fkFont, size, letterSpacing = 0) {
    const s = size / fkFont.unitsPerEm;
    const run = fkFont.layout(String(text));
    if (run.glyphs.length === 0) {
        return 0;
    }
    const advances = run.glyphs.reduce((sum, glyph) => sum + glyph.advanceWidth * s, 0);
    return advances + letterSpacing * Math.max(0, run.glyphs.length - 1);
}

function wrapText(text, fkFont, size, maxWidth) {
    const words = String(text).split(/\s+/).filter(Boolean);
    if (words.length === 0) {
        return [''];
    }
    const lines = [];
    let current = words[0];
    for (let i = 1; i < words.length; i++) {
        const next = `${current} ${words[i]}`;
        if (textWidth(next, fkFont, size) <= maxWidth) {
            current = next;
        } else {
            lines.push(current);
            current = words[i];
        }
    }
    lines.push(current);
    return lines;
}

/** Draw Lato as filled glyph outlines (vector, not extractable text). */
function drawOutlinedText(text, x, baseline, size, fkFont, { letterSpacing = 0 } = {}) {
    const s = size / fkFont.unitsPerEm;
    let cx = x;
    for (const glyph of fkFont.layout(String(text)).glyphs) {
        const svg = glyph.path?.toSVG?.();
        if (svg) {
            // fontkit paths are y-up; pdf-lib SVG parsing is y-down — flip with scale.
            page1.pushOperators(pushGraphicsState(), translate(cx, baseline), scaleOp(s, -s));
            page1.drawSvgPath(svg, { x: 0, y: 0, borderWidth: 0, color: ink });
            page1.pushOperators(popGraphicsState());
        }
        cx += glyph.advanceWidth * s + letterSpacing;
    }
    return cx - x;
}

function drawTitle(text) {
    const size = 8.5;
    const label = String(text).toUpperCase();
    drawOutlinedText(label, left, y - size, size, latoBoldFk, { letterSpacing: 0.7 });
    y -= size + 5;
    page1.drawLine({
        start: { x: left, y },
        end: { x: left + maxTextW, y },
        thickness: 0.75,
        color: ink,
        opacity: 0.3,
    });
    y -= 10;
}

function drawLines(lines, { size = 8.75, bold = false, leading = 1.4, gapAfter = 6 } = {}) {
    const fkFont = bold ? latoBoldFk : latoFk;
    for (const line of lines) {
        for (const part of wrapText(line, fkFont, size, maxTextW)) {
            drawOutlinedText(part, left, y - size, size, fkFont);
            y -= size * leading;
        }
        y -= gapAfter;
    }
}

drawTitle('Details');
drawLines([sidebar.location, sidebar.phone, sidebar.email].filter(Boolean), {
    size: 8.75,
    gapAfter: 5,
});

y -= 14;
drawTitle('Links');
for (const link of sidebar.links ?? []) {
    drawLines([link.label], { size: 9, bold: true, leading: 1.25, gapAfter: 1 });
    drawLines([link.url], { size: 8, leading: 1.35, gapAfter: 8 });
}

y -= 14;
drawTitle('Core Competencies');
for (const item of sidebar.expertise ?? []) {
    const size = 8.5;
    const indent = 11;
    const wrapped = wrapText(item, latoFk, size, maxTextW - indent);
    wrapped.forEach((part, index) => {
        if (index === 0) {
            drawOutlinedText('•', left, y - size, size, latoBoldFk);
        }
        drawOutlinedText(part, left + indent, y - size, size, latoFk);
        y -= size * 1.36;
    });
    y -= 3.5;
}

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

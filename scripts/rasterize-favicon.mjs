#!/usr/bin/env node
/**
 * Rasterize public/img/favicon.svg to a transparent 1024 PNG via Playwright.
 * Output path is argv[1], default public/img/.favicon-master.png
 */
import { chromium } from 'playwright';
import { readFile, mkdir } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const svgPath = resolve(root, 'public/img/favicon.svg');
const outPath = resolve(process.argv[2] || resolve(root, 'public/img/.favicon-master.png'));
const size = 1024;

const svg = await readFile(svgPath);
const href = `data:image/svg+xml;base64,${svg.toString('base64')}`;

const browser = await chromium.launch();
const page = await browser.newPage({
    viewport: { width: size, height: size },
    deviceScaleFactor: 1,
});

await page.setContent(`<!doctype html>
<html><head><style>
  html, body { margin: 0; width: ${size}px; height: ${size}px; background: transparent; }
  img { display: block; width: ${size}px; height: ${size}px; }
</style></head>
<body><img src="${href}" width="${size}" height="${size}" alt=""></body></html>`, {
    waitUntil: 'load',
});
await page.locator('img').waitFor();
await mkdir(dirname(outPath), { recursive: true });
await page.screenshot({ path: outPath, omitBackground: true });
await browser.close();

console.log(`rasterized ${size}×${size} → ${outPath}`);

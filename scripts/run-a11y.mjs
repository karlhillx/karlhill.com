#!/usr/bin/env node
/**
 * WCAG2 A/AA axe audit across the same URL set formerly covered by pa11y-ci.
 * Uses Playwright (already required for e2e + resume PDF) so we do not pull
 * Puppeteer's vulnerable extract-zip transitive.
 *
 * Usage: node scripts/run-a11y.mjs
 * Expects the app on PLAYWRIGHT_BASE_URL (default http://127.0.0.1:8000).
 */
import { readFileSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { chromium } from 'playwright';
import AxeBuilder from '@axe-core/playwright';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const base = (process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000').replace(/\/$/, '');

const config = JSON.parse(readFileSync(path.join(root, '.pa11yci.json'), 'utf8'));
const urls = (config.urls || []).map((url) => url.replace(/^https?:\/\/[^/]+/, base));

const browser = await chromium.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage'],
});
// AxeBuilder requires a page from browser.newContext(), not browser.newPage().
const context = await browser.newContext();

const failures = [];

try {
    for (const url of urls) {
        const page = await context.newPage();
        const response = await page.goto(url, { waitUntil: 'networkidle', timeout: 30_000 });
        const status = response?.status() ?? 0;

        if (status >= 500) {
            failures.push({ url, error: `HTTP ${status}` });
            await page.close();
            continue;
        }

        await page.addStyleTag({
            content: `*,*::before,*::after{animation:none!important;transition:none!important;scroll-behavior:auto!important}`,
        });

        let builder = new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa']);
        if (url.includes('/now')) {
            builder = builder.exclude('.booking-embed');
        }

        const results = await builder.analyze();
        const serious = results.violations.filter((v) =>
            ['critical', 'serious'].includes(v.impact || '')
        );

        if (serious.length > 0) {
            failures.push({ url, violations: serious });
            console.error(`✗ ${url}`);
            for (const v of serious) {
                console.error(`  [${v.impact}] ${v.id}: ${v.help}`);
            }
        } else {
            console.log(`✓ ${url}`);
        }

        await page.close();
    }
} finally {
    await context.close();
    await browser.close();
}

if (failures.length > 0) {
    console.error(`\n${failures.length} URL(s) failed axe WCAG2 A/AA (serious/critical).`);
    process.exit(1);
}

console.log(`\nAll ${urls.length} URLs passed.`);

import { expect, test } from '@playwright/test';
import { writeFile } from 'node:fs/promises';

async function compareRendering(before, after, testInfo) {
    if (!before.equals(after)) {
        for (const [name, image] of [
            ['before.png', before],
            ['after.png', after],
        ]) {
            const path = testInfo.outputPath(name);
            await writeFile(path, image);
            await testInfo.attach(name, { path, contentType: 'image/png' });
        }
    }
    expect(after.equals(before), 'The restored page should render identically').toBe(true);
}

// Same-run comparisons avoid OS-specific font rasterization baselines.
for (const width of [390, 1440]) {
    for (const theme of ['light', 'dark']) {
        test(`writing reset restores its visual state at ${width}px in ${theme}`, async ({
            page,
        }, testInfo) => {
            await page.setViewportSize({ width, height: 900 });
            await page.emulateMedia({ colorScheme: theme, reducedMotion: 'reduce' });
            await page.goto('/blog');
            await page.evaluate(() => document.fonts.ready);
            await page.mouse.move(0, 0);
            const main = page.locator('main');
            const options = { animations: 'disabled', caret: 'hide', scale: 'css' };
            const before = await main.screenshot(options);
            await page.locator('[data-post-list] a[href$="/blog/tag/engineering"]').first().click();
            await page
                .getByRole('navigation', { name: 'Filter by tag' })
                .getByRole('link', { name: 'All', exact: true })
                .click();
            await expect(page.locator('.writing-series')).toHaveCount(1);
            await page.evaluate(() => document.fonts.ready);
            await page.mouse.move(0, 0);
            await compareRendering(before, await main.screenshot(options), testInfo);
        });
    }

    test(`theme switching restores the hero at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 900 });
        await page.emulateMedia({ colorScheme: 'dark', reducedMotion: 'reduce' });
        await page.goto('/');
        await page.evaluate(() => document.fonts.ready);
        await page.mouse.move(0, 0);
        const hero = page.locator('#hero');
        const options = { animations: 'disabled', caret: 'hide', scale: 'css' };
        const before = await hero.screenshot(options);
        await page.getByRole('button', { name: 'Switch to light theme' }).click();
        await expect(page.locator('html')).toHaveAttribute('data-theme', 'light');
        const light = await hero.screenshot(options);
        expect(light.equals(before), 'Light and dark themes must differ').toBe(false);
        await page.getByRole('button', { name: 'Switch to dark theme' }).click();
        await expect(page.locator('html')).toHaveAttribute('data-theme', 'dark');
        await page.mouse.move(0, 0);
        await compareRendering(before, await hero.screenshot(options), testInfo);
    });
}

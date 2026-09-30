import { expect, test } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

test('featured work appears in the first viewport on desktop and immediately after mobile hero', async ({
    page,
    isMobile,
}) => {
    await page.goto('/');
    const bounds = await page.evaluate(() => ({
        hero: document.querySelector('#hero').getBoundingClientRect().bottom,
        work: document.querySelector('#work h2').getBoundingClientRect().top,
        portrait: document.querySelector('.portfolio-portrait').getBoundingClientRect().width,
        height: innerHeight,
    }));
    expect(bounds.portrait).toBeLessThanOrEqual(52);
    expect(bounds.work - bounds.hero).toBeLessThan(120);
    expect(bounds.work).toBeLessThan(isMobile ? bounds.height + 120 : bounds.height);
    await expect(page.locator('#work > .site-shell > .portfolio-grid > article')).toHaveCount(6);
    for (const article of await page.locator('#work article').all()) {
        await expect(article.locator('.portfolio-card__brief')).toBeVisible();
        await expect(article.locator('.portfolio-card__impact')).toBeVisible();
        await expect(article.getByRole('link', { name: /Read case study/ })).toBeVisible();
    }
});

test('every portfolio image loads without broken sources or horizontal page overflow', async ({
    page,
}) => {
    for (const path of ['/', '/work', '/work/the-dry-standard', '/work/developer-tooling']) {
        await page.goto(path);
        for (const image of await page.locator('main img[src]').all()) {
            await image.scrollIntoViewIfNeeded();
            await expect
                .poll(() => image.evaluate((el) => el.complete && el.naturalWidth > 0))
                .toBe(true);
        }
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(
            true
        );
        await expect(page.getByRole('heading', { level: 1 })).toHaveCount(1);
    }
});

for (const theme of ['light', 'dark']) {
    test(`portfolio and product pages meet WCAG AA in ${theme} mode`, async ({ page }) => {
        await page.emulateMedia({ colorScheme: theme, reducedMotion: 'reduce' });
        for (const path of ['/', '/work', '/work/the-dry-standard', '/work/developer-tooling']) {
            await page.goto(path);
            const results = await new AxeBuilder({ page })
                .withTags(['wcag2a', 'wcag2aa', 'wcag21aa'])
                .analyze();
            expect(results.violations, JSON.stringify(results.violations, null, 2)).toEqual([]);
        }
    });
}

test('portfolio content and collection navigation work without JavaScript', async ({
    browser,
    baseURL,
    isMobile,
}) => {
    const context = await browser.newContext({
        javaScriptEnabled: false,
        viewport: { width: isMobile ? 390 : 1440, height: 900 },
    });
    const page = await context.newPage();
    try {
        await page.goto(`${baseURL}/work`);
        const products = page
            .getByRole('navigation', { name: 'Portfolio sections' })
            .getByRole('link', { name: 'Independent Products', exact: true });
        await products.click();
        await expect(page).toHaveURL(/#products$/);
        await expect(page.locator('#products h2')).toBeVisible();
        await page
            .locator('#the-dry-standard')
            .getByRole('link', { name: /Read case study/ })
            .click();
        await expect(page).toHaveURL(/\/work\/the-dry-standard$/);
        await expect(
            page.getByRole('heading', { name: /Editorial source, generated catalog/ })
        ).toBeVisible();
    } finally {
        await context.close();
    }
});

test('case studies support keyboard discovery and return to their collection', async ({ page }) => {
    await page.goto('/work');
    const cta = page.locator('#the-dry-standard').getByRole('link', { name: /Read case study/ });
    await cta.focus();
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/work\/the-dry-standard$/);
    const collection = page
        .getByRole('navigation', { name: 'Breadcrumb', exact: true })
        .getByRole('link', { name: 'Independent Products' });
    await collection.focus();
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/work#products$/);
    await expect(page.locator('#the-dry-standard')).toBeVisible();
});

test('command search finds the tooling collection by repository name', async ({ page }) => {
    await page.goto('/');
    await page.keyboard.press('Control+K');
    await page.locator('#command-input').fill('pipeguard');
    await expect(page.getByRole('option', { name: /Developer tooling/ })).toBeVisible();
});

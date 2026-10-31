import { expect, test } from '@playwright/test';

test('headings and body use Pathway Extreme at 83% width', async ({ page }) => {
    await page.goto('/');
    await page.evaluate(() => document.fonts.ready);
    const fonts = await page.evaluate(() => {
        const statement = getComputedStyle(document.querySelector('.opening__statement'));
        const body = getComputedStyle(document.body);
        return {
            heading: statement.fontFamily,
            body: body.fontFamily,
            stretch: body.fontStretch,
            statementWeight: statement.fontWeight,
            bodyWeight: body.fontWeight,
            loaded: [...document.fonts].some(
                (font) => font.family === 'Pathway Extreme Variable' && font.status === 'loaded'
            ),
        };
    });
    expect(fonts.heading).toContain('Pathway Extreme Variable');
    expect(fonts.body).toContain('Pathway Extreme Variable');
    expect(fonts.stretch).toBe('83%');
    expect(fonts.statementWeight).toBe('300');
    expect(fonts.bodyWeight).toBe('400');
    expect(fonts.heading).not.toContain('Newsreader');
    expect(fonts.heading).not.toContain('Albert Sans');
    expect(fonts.loaded).toBe(true);
});

test('writing filters, reset, reload, and history keep the whole page consistent', async ({
    page,
}) => {
    await page.goto('/blog');
    const count = await page.locator('[data-post-list] > li').count();
    await expect(page.locator('.writing-series')).toHaveCount(1);
    await page.locator('[data-post-list] a[href$="/blog/tag/engineering"]').first().click();
    await expect(page).toHaveURL(/\/blog\/tag\/engineering$/);
    const filters = page.getByRole('navigation', { name: 'Filter by tag' });
    await expect(filters.locator('[aria-current="page"]')).toContainText(/engineering/i);
    await expect(page.locator('.writing-series')).toHaveCount(0);
    await filters.getByRole('link', { name: 'All', exact: true }).click();
    await expect(page).toHaveURL(/\/blog$/);
    await expect(filters).toHaveCount(0);
    await expect(page.getByRole('navigation', { name: 'Breadcrumb' })).not.toContainText(
        /engineering/i
    );
    await expect(page.locator('link[rel="canonical"]')).toHaveAttribute('href', /\/blog$/);
    await expect(page.locator('[data-post-list] > li')).toHaveCount(count);
    await expect(page.locator('.writing-series')).toHaveCount(1);
    await page.reload();
    await expect(page.locator('.writing-series')).toHaveCount(1);
    await page.goBack();
    await expect(page).toHaveURL(/\/blog\/tag\/engineering$/);
    await expect(filters.locator('[aria-current="page"]')).toContainText(/engineering/i);
    await page.goForward();
    await expect(page).toHaveURL(/\/blog$/);
    await expect(filters).toHaveCount(0);
    await expect(page.locator('.writing-series')).toHaveCount(1);
});

test('mobile teasers, collection choices, and contact actions stay easy to reach', async ({
    page,
}) => {
    for (const width of [320, 390]) {
        await page.setViewportSize({ width, height: 844 });
        await page.goto('/');
        await page.evaluate(() => document.fonts.ready);
        for (const card of await page.locator('#work article').all()) {
            expect((await card.boundingBox()).height).toBeLessThanOrEqual(
                width === 320 ? 820 : 700
            );
        }
        const firstLink = page.locator('#work article').first().getByRole('link');
        expect(await firstLink.evaluate((el) => el.getBoundingClientRect().top)).toBeLessThan(2200);
        await expect(page.locator('[data-analytics-location="footer-home"]')).toBeVisible();
        await expect(page.locator('[data-contact-form]')).toHaveCount(0);
        await expect(page.locator('#book')).toHaveCount(0);
        await page.goto('/work');
        const rail = page.getByRole('navigation', { name: 'Portfolio sections' });
        for (const name of ['Mission', 'Tools', 'NASA', 'Products', 'Earlier']) {
            const box = await rail.getByRole('link', { name, exact: true }).boundingBox();
            expect(box.x).toBeGreaterThanOrEqual(0);
            expect(box.x + box.width).toBeLessThanOrEqual(width);
        }
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(
            true
        );
    }
});

test('case studies put the problem and decisions before supporting visuals', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/work/jacobs-mission-software');
    await page.evaluate(() => document.fonts.ready);
    expect(
        await page.locator('#problem').evaluate((el) => el.getBoundingClientRect().top)
    ).toBeLessThan(1200);
    const ids = await page
        .locator('#problem, #decisions, #delivery-system, #snapshot, #outcome')
        .evaluateAll((nodes) => nodes.map((node) => node.id));
    expect(ids).toEqual(['problem', 'decisions', 'delivery-system', 'snapshot', 'outcome']);
    await expect(page.locator('.case-study-logo-plate__mark')).toHaveCount(0);
    await expect(page.locator('.case-study-flow')).toHaveCount(1);
});

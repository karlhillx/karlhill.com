import { expect, test } from '@playwright/test';

test('delivery pillar numbers have space from desktop dividers', async ({ page }) => {
    await page.goto('/');
    await page.setViewportSize({ width: 1280, height: 900 });
    const pillars = page.locator('.portfolio-delivery__pillars li');
    await expect(pillars).toHaveCount(3);
    for (const pillar of (await pillars.all()).slice(1)) {
        const inset = await pillar.evaluate((el) => {
            const number = el.querySelector('.portfolio-delivery__num');
            return number.getBoundingClientRect().left - el.getBoundingClientRect().left;
        });
        expect(inset).toBeGreaterThanOrEqual(16);
    }
    await page.setViewportSize({ width: 390, height: 844 });
    for (const pillar of await pillars.all()) {
        expect(await pillar.evaluate((el) => parseFloat(getComputedStyle(el).paddingLeft))).toBe(0);
    }
});

test('Dry Standard homepage callout shows an uncropped product preview', async ({ page }) => {
    await page.goto('/');
    const callout = page.locator('.portfolio-product-callout');
    const image = callout.getByRole('img', { name: /The Dry Standard/ });
    await image.scrollIntoViewIfNeeded();
    await expect(image).toBeVisible();
    await expect(callout.getByRole('link', { name: /Read the case study/ })).toHaveAttribute(
        'href',
        /\/work\/the-dry-standard$/
    );
    await expect
        .poll(() => image.evaluate((el) => el.complete && el.naturalWidth > 0))
        .toBe(true);
    const ratio = await image.evaluate((el) => {
        const bounds = el.getBoundingClientRect();
        return bounds.width / bounds.height;
    });
    expect(ratio).toBeCloseTo(1200 / 675, 2);
    expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(
        true
    );
});

test('wide cards separate the visual from the summary only in two-column layouts', async ({
    page,
}) => {
    for (const route of ['/', '/work']) {
        await page.goto(route);
        const visual = page.locator('#jacobs-mission-software .portfolio-card__visual');
        for (const width of [390, 768, 1280]) {
            await page.setViewportSize({ width, height: 900 });
            const border = await visual.evaluate((el) => {
                const style = getComputedStyle(el);
                return { width: parseFloat(style.borderRightWidth), style: style.borderRightStyle };
            });
            expect(border.width).toBe(width >= 768 ? 1 : 0);
            if (width >= 768) {
                expect(border.style).toBe('solid');
            }
        }
    }
});

test('hero actions have breathing room and desktop index has a labeled location', async ({
    page,
}) => {
    await page.goto('/');
    await page.evaluate(() => document.fonts.ready);
    const spacing = await page.locator('[data-home-actions]').evaluate((actions) => {
        const proof = actions.previousElementSibling;
        return actions.getBoundingClientRect().top - proof.getBoundingClientRect().bottom;
    });
    expect(spacing).toBeGreaterThanOrEqual(16);

    await page.setViewportSize({ width: 1280, height: 900 });
    const footer = page.locator('.portfolio-hero__index-footer');
    await expect(footer).toBeVisible();
    await expect(footer).toContainText('Based in');
    await expect(footer).toContainText('Washington, DC');
    expect(await footer.evaluate((el) => parseFloat(getComputedStyle(el).borderTopWidth))).toBe(1);
    const destinations = page.locator('.nav-destinations');
    await expect(destinations.getByRole('link')).toHaveText(['Work', 'Writing', 'About', 'Resume']);
    await destinations.getByRole('link', { name: 'Resume', exact: true }).click();
    await expect(destinations.getByRole('link', { name: 'Resume', exact: true })).toHaveAttribute(
        'aria-current',
        'page'
    );
});

test('search has a visible close action and returns focus to its invoker', async ({ page }) => {
    await page.goto('/');
    const trigger = page.getByRole('button', { name: 'Search pages and sections', exact: true });
    await expect(trigger).toBeVisible();
    const bounds = await trigger.boundingBox();
    expect(bounds.width).toBeGreaterThanOrEqual(44);
    expect(bounds.height).toBeGreaterThanOrEqual(44);
    await trigger.click();
    await expect(page.locator('#command-input')).toBeFocused();
    await page.getByRole('button', { name: 'Close search', exact: true }).click();
    await expect(page.locator('#command-palette')).toBeHidden();
    await expect(trigger).toBeFocused();
    await trigger.click();
    await page.keyboard.press('Escape');
    await expect(page.locator('#command-palette')).toBeHidden();
    await expect(trigger).toBeFocused();
});

test('contact disclosure works without JavaScript and exposes server errors', async ({
    browser,
    baseURL,
}) => {
    const context = await browser.newContext({
        javaScriptEnabled: false,
        viewport: { width: 390, height: 844 },
    });
    try {
        const page = await context.newPage();
        await page.goto(baseURL);
        await expect(page.locator('#contact-form')).toBeHidden();
        await page.locator('.contact-message summary').click();
        await expect(page.locator('#contact-form')).toBeVisible();
        await page.locator('#contact-name').fill('Test visitor');
        await page.locator('#contact-email').fill('invalid-email');
        await page.locator('#contact-message').fill('A message with enough text.');
        await page.locator('#contact-form').evaluate((form) => {
            form.noValidate = true;
        });
        await page.locator('#contact-submit').click();
        await expect(page).toHaveURL(/#contact-form$/);
        await expect(page.locator('.contact-message')).toHaveAttribute('open');
        await expect(page.locator('#contact-email-error')).toBeVisible();
        await expect(page.locator('#contact-message')).toHaveValue('A message with enough text.');
    } finally {
        await context.close();
    }
});

test('mobile writing chapters stack and case-study disclosure has usable touch targets', async ({
    page,
}) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/blog');
    const cards = await page.locator('.series-chapters__card').all();
    expect(cards.length).toBeGreaterThan(1);
    const first = await cards[0].boundingBox();
    const second = await cards[1].boundingBox();
    expect(second.y).toBeGreaterThanOrEqual(first.y + first.height);
    expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(
        true
    );
    await expect(page.getByText('Swipe to browse')).toHaveCount(0);

    await page.goto('/work/jacobs-mission-software');
    const toc = page.locator('.article-toc-mobile');
    await expect(toc).not.toHaveAttribute('open');
    await toc.locator('summary').click();
    await expect(toc).toHaveAttribute('open');
    for (const link of await toc.getByRole('link').all()) {
        expect((await link.boundingBox()).height).toBeGreaterThanOrEqual(44);
    }
    await toc.getByRole('link', { name: 'Scope', exact: true }).click();
    await expect(page.locator('#scope')).toBeVisible();
    await expect(page.locator('#scope')).toContainText('My scope');
    await expect(page.locator('#scope')).toContainText('Broader influence');
    await expect(toc).not.toHaveAttribute('open');
});

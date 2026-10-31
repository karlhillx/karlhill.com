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

test('Dry Standard homepage story shows an uncropped product preview', async ({ page }) => {
    await page.goto('/');
    const callout = page.locator('#the-dry-standard');
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
    for (const route of ['/work']) {
        await page.goto(route);
        for (const width of [390, 768, 1280]) {
            await page.setViewportSize({ width, height: 900 });
            const layout = await page.locator('#jacobs-mission-software').evaluate((card) => {
                const visual = card.querySelector('.portfolio-card__visual');
                const body = card.querySelector('.portfolio-card__body');
                const visualBox = visual.getBoundingClientRect();
                const bodyBox = body.getBoundingClientRect();
                return {
                    stacked: visualBox.bottom <= bodyBox.top + 1,
                    beside: visualBox.right <= bodyBox.left + 1 && Math.abs(visualBox.top - bodyBox.top) < 24,
                };
            });
            if (width >= 768) {
                expect(layout.beside).toBe(true);
            } else {
                expect(layout.stacked).toBe(true);
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
    const place = page.locator('.opening__facts');
    await expect(place).toBeVisible();
    await expect(place).toContainText('Based in');
    await expect(place).toContainText('Washington, DC');
    await expect(place).toContainText('Jacobs / BlackLynx');
    await expect(place).toContainText('NASA Goddard via SSAI');
    expect(
        await place
            .locator('div')
            .last()
            .evaluate((el) => parseFloat(getComputedStyle(el).borderTopWidth))
    ).toBe(1);
    const destinations = page.locator('.nav-destinations');
    await expect(destinations.getByRole('link')).toHaveText([
        'Work',
        'Open Source',
        'About',
        'Writing',
        'Resume',
    ]);
    await destinations.getByRole('link', { name: 'Resume', exact: true }).click();
    await expect(destinations.getByRole('link', { name: 'Resume', exact: true })).toHaveAttribute(
        'aria-current',
        'page'
    );
});

test('homepage copy stays inside the gutter at phone and desktop widths', async ({ page }) => {
    await page.goto('/');
    await page.evaluate(() => document.fonts.ready);
    for (const width of [320, 390, 768, 1280, 1440]) {
        await page.setViewportSize({ width, height: 900 });
        const measure = await page.evaluate(() => {
            const shell = document.querySelector('.opening .site-shell');
            const copy = document.querySelector('.opening__copy');
            const facts = document.querySelector('.opening__facts');
            const pad = parseFloat(getComputedStyle(shell).paddingLeft);
            return {
                pad,
                copyLeft: copy.getBoundingClientRect().left,
                factsLeft: facts.getBoundingClientRect().left,
                overflow: document.documentElement.scrollWidth > innerWidth + 1,
            };
        });
        expect(measure.pad).toBeGreaterThanOrEqual(16);
        expect(measure.copyLeft).toBeGreaterThanOrEqual(measure.pad - 1);
        expect(measure.factsLeft).toBeGreaterThanOrEqual(measure.pad - 1);
        expect(measure.overflow).toBe(false);
    }
});

test('theme toggle meets the touch target and there is no command palette', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('#command-palette')).toHaveCount(0);
    const trigger = page.getByRole('button', { name: /Switch to (light|dark) theme/ });
    await expect(trigger).toBeVisible();
    const bounds = await trigger.boundingBox();
    expect(bounds.width).toBeGreaterThanOrEqual(44);
    expect(bounds.height).toBeGreaterThanOrEqual(44);
    await trigger.focus();
    await expect(trigger).toBeFocused();
});

test('contact is email only and needs no JavaScript', async ({ browser, baseURL }) => {
    const context = await browser.newContext({
        javaScriptEnabled: false,
        viewport: { width: 390, height: 844 },
    });
    try {
        const page = await context.newPage();
        await page.goto(baseURL);
        await expect(page.locator('#contact-form')).toHaveCount(0);
        await expect(page.locator('#book')).toHaveCount(0);
        const email = page.locator('#contact a[href^="mailto:"]');
        await expect(email).toBeVisible();
        await expect(email).toHaveAttribute('href', /^mailto:/);
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

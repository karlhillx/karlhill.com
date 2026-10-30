import { expect, test } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

test('featured projects expose meaningful links without oversized teasers', async ({
    page,
    isMobile,
}) => {
    await page.goto('/');
    await page.evaluate(() => document.fonts.ready);
    const bounds = await page.evaluate(() => ({
        hero: document.querySelector('#hero').getBoundingClientRect().bottom,
        work: document.querySelector('#work h2').getBoundingClientRect().top,
        portrait: document.querySelector('.opening__photo').getBoundingClientRect().width,
        height: innerHeight,
    }));
    expect(bounds.portrait).toBeGreaterThan(140);
    expect(bounds.portrait).toBeLessThanOrEqual(isMobile ? 400 : 420);
    expect(bounds.work).toBeGreaterThan(bounds.hero - 8);
    await expect(page.locator('#work .studies__list > article')).toHaveCount(3);
    for (const article of await page.locator('#work .studies__list > article').all()) {
        await expect(article.locator('.study__body')).toBeVisible();
        await expect(article.locator('.study__impact')).toBeVisible();
        await expect(article.getByRole('link', { name: /Read case study/ })).toBeVisible();
        await expect(article.getByRole('link')).toHaveCount(1);
    }
    await expect(page.locator('#notes .portfolio-writing-link')).toHaveCount(3);
    await page.goto('/work');
    await expect(page.locator('.tooling-proof__index')).toHaveCount(1);
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
        await page.addInitScript((choice) => {
            localStorage.setItem('theme', choice);
        }, theme);
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
            .getByRole('link', { name: 'Products', exact: true });
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

test('hero hierarchy and work rail hold across responsive widths', async ({ page }) => {
    await page.goto('/');
    await page.evaluate(() => document.fonts.ready);
    const statement = page.locator('.opening__statement');
    await expect(statement).toHaveText('Engineering complex systems.');

    for (const width of [320, 375, 430, 768, 1024, 1280, 1440]) {
        await page.setViewportSize({ width, height: 900 });
        const typeSizes = await page.evaluate(() => ({
            statement: parseFloat(
                getComputedStyle(document.querySelector('.opening__statement')).fontSize
            ),
            role: parseFloat(getComputedStyle(document.querySelector('.opening__role')).fontSize),
        }));
        expect(typeSizes.statement).toBeGreaterThan(typeSizes.role);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(
            true
        );
    }

    await page.goto('/work');
    const rail = page.locator('.portfolio-nav__links');
    const links = rail.getByRole('link');
    await expect(links).toHaveCount(5);

    for (const width of [320, 375, 430, 768, 1024, 1280, 1440]) {
        await page.setViewportSize({ width, height: 900 });
        await expect(links.first()).toHaveAttribute('aria-current', 'location');
        const flexWrap = await rail.evaluate((element) => getComputedStyle(element).flexWrap);
        expect(flexWrap).toBe('nowrap');

        await rail.evaluate((element) => {
            element.scrollLeft = 0;
        });
        const firstVisible = await links.first().evaluate((element) => {
            const link = element.getBoundingClientRect();
            const container = element.parentElement.getBoundingClientRect();
            return link.left >= container.left && link.right <= container.right;
        });
        expect(firstVisible).toBe(true);

        await rail.evaluate((element) => {
            element.scrollLeft = element.scrollWidth;
        });
        const lastVisible = await links.last().evaluate((element) => {
            const link = element.getBoundingClientRect();
            const container = element.parentElement.getBoundingClientRect();
            return link.left >= container.left && link.right <= container.right;
        });
        expect(lastVisible).toBe(true);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(
            true
        );
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

    await page.goto('/work/jacobs-mission-software');
    await expect(page.locator('.case-study-masthead__title')).toBeVisible();
    const hierarchy = await page.evaluate(() => {
        const role = document.querySelector('.case-study-masthead__role');
        const evidence = document.querySelector('.case-study-evidence');
        return role && evidence
            ? role.getBoundingClientRect().top < evidence.getBoundingClientRect().top
            : false;
    });
    expect(hierarchy).toBe(true);
});

test('primary actions and resume jump links meet touch-size minimums', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/');
    const primaryAction = page.getByRole('link', { name: /Explore Projects/ });
    expect((await primaryAction.boundingBox()).height).toBeGreaterThanOrEqual(44);

    await page.goto('/resume');
    for (const link of await page.locator('.resume-jump-list a').all()) {
        expect((await link.boundingBox()).height).toBeGreaterThanOrEqual(44);
    }
});

test('work index reaches the tooling case study without a command palette', async ({ page }) => {
    await page.goto('/work');
    await expect(page.locator('#command-palette')).toHaveCount(0);
    await page.locator('#developer-tooling').getByRole('link', { name: /Read case study/ }).click();
    await expect(page).toHaveURL(/\/work\/developer-tooling$/);
    await expect(page.getByRole('heading', { level: 1 })).toContainText(/feedback loop/i);
});

test('open source shows only the three selected tools in order', async ({ page }) => {
    for (const path of ['/work', '/work/developer-tooling', '/open-source', '/resume']) {
        await page.goto(path);
        const lists = page.locator(
            '.tooling-proof__index, .tooling-directory, section[aria-labelledby="resume-open-source"] ul'
        );
        expect(await lists.count()).toBeGreaterThan(0);
        await expect(lists).toHaveCount(1);
        for (const list of await lists.all()) {
            expect(
                await list
                    .locator('a')
                    .evaluateAll((links) => links.map((link) => link.getAttribute('href')))
            ).toEqual(
                path === '/resume'
                    ? [
                          'https://github.com/karlhillx/bb-run',
                          'https://github.com/karlhillx/testrisk',
                      ]
                    : [
                          'https://github.com/karlhillx/bb-run',
                          'https://github.com/karlhillx/testrisk',
                          'https://github.com/karlhillx/pipeguard',
                      ]
            );
        }
        await expect(page.locator('main')).not.toContainText(/sim-rs|driftlens|drift-rs/);
    }
    await expect(
        page.locator('#credentials').getByRole('link', { name: /SAFe.*Agilist/ })
    ).toHaveAttribute(
        'href',
        'https://www.credly.com/badges/55c2cb68-b3d6-4da6-8f76-dd6ff2fa37b4/public_url'
    );
});

test('contact is an email link and needs no JavaScript', async ({
    browser,
    baseURL,
    isMobile,
}) => {
    const context = await browser.newContext({
        javaScriptEnabled: false,
        viewport: { width: isMobile ? 320 : 1440, height: 900 },
    });
    const page = await context.newPage();
    try {
        await page.goto(`${baseURL}/`);
        await expect(page.locator('#book')).toHaveCount(0);
        await expect(page.locator('.booking-embed__frame')).toHaveCount(0);
        await expect(page.locator('#contact-form')).toHaveCount(0);
        await expect(page.locator('#contact a[href^="mailto:"]')).toBeVisible();
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(
            true
        );
    } finally {
        await context.close();
    }
});

test('legacy delivery bookmarks reach the practices within the Jacobs study', async ({ page }) => {
    for (const path of ['/delivery', '/lead']) {
        await page.goto(path);
        await expect(page).toHaveURL(/\/work\/jacobs-mission-software#delivery-practices$/);
        await expect(page.locator('#delivery-practices')).toBeVisible();
        await expect(page.locator('#definition-of-done')).toBeVisible();
    }
});

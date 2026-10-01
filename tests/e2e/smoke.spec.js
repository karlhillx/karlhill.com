import { expect, test } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

/** Shared axe scan — serious/critical WCAG2 A/AA findings. */
async function assertA11y(page, { exclude = [] } = {}) {
    // Freeze motion so axe samples real painted colors (not mid-animation layers).
    // Use a same-origin stylesheet — inline <style> is blocked by Dry Standard CSP.
    const origin = new URL(page.url()).origin;
    await page.addStyleTag({
        url: `${origin}/css/a11y-motion-freeze.css`,
    });

    let builder = new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa']);
    for (const selector of exclude) {
        builder = builder.exclude(selector);
    }
    const results = await builder.analyze();
    const serious = results.violations.filter((v) =>
        ['critical', 'serious'].includes(v.impact || '')
    );
    expect(serious, JSON.stringify(serious, null, 2)).toEqual([]);
}

test.describe('smoke + a11y', () => {
    test('navigation stays complete across desktop, tablet, and mobile', async ({ page }) => {
        await page.goto('/work');
        const primary = page.getByRole('navigation', { name: 'Primary', exact: true });
        // Text links appear from lg (1024px); below that the drawer carries them.
        const DRAWER_MAX = 1024;
        for (const width of [320, 390, 820, 1024, 1280, 1440]) {
            await page.setViewportSize({ width, height: 900 });
            if (width < DRAWER_MAX) {
                await primary.getByRole('button', { name: 'Open menu', exact: true }).click();
            }
            for (const name of ['Work', 'Writing', 'About', 'Contact']) {
                await expect(
                    primary.getByRole('link', { name, exact: true }).filter({ visible: true })
                ).toBeVisible();
            }
            // Resume is available in both navigation layouts.
            const resume = primary
                .getByRole('link', { name: 'Resume', exact: true })
                .filter({ visible: true });
            await expect(resume).toHaveCount(1);
            for (const name of ['Writing', 'Resume']) {
                await expect(
                    page.locator('footer').getByRole('link', { name, exact: true })
                ).toBeVisible();
            }
            await expect(
                primary.getByRole('link', { name: /Recruiter|Certifications/ })
            ).toHaveCount(0);
            if (width < DRAWER_MAX) {
                await page.keyboard.press('Escape');
                await expect(page.locator('#nav-toggle')).toHaveAttribute('aria-expanded', 'false');
                await expect(page.locator('#nav-toggle')).toBeFocused();
            }
            expect(
                await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)
            ).toBe(true);
        }
        await page.setViewportSize({ width: 820, height: 900 });
        await primary.getByRole('button', { name: 'Open menu', exact: true }).click();
        await page.setViewportSize({ width: 1440, height: 900 });
        await expect(page.locator('#mobile-menu')).toBeHidden();
        await expect(page.locator('#nav-toggle')).toHaveAttribute('aria-expanded', 'false');
    });

    test('portfolio navigation tracks visible sections without hiding content', async ({
        page,
    }) => {
        await page.goto('/work');
        const navigation = page.getByRole('navigation', { name: 'Portfolio sections' });
        for (const [label, target] of [
            ['Products', 'products'],
            ['Tools', 'open-source'],
            ['NASA', 'nasa'],
            ['Earlier', 'earlier'],
            ['Mission', 'work'],
        ]) {
            const link = navigation.getByRole('link', { name: label, exact: true });
            expect((await link.boundingBox()).height).toBeGreaterThanOrEqual(44);
            await link.focus();
            await page.keyboard.press('Enter');
            await expect(page).toHaveURL(new RegExp(`#${target}$`));
            await expect(link).toHaveAttribute('aria-current', 'location');
            await expect
                .poll(() =>
                    page.evaluate(() =>
                        Math.abs(
                            document.querySelector('.portfolio-nav').getBoundingClientRect().top -
                                document
                                    .querySelector('nav[aria-label="Primary"]')
                                    .getBoundingClientRect().bottom
                        )
                    )
                )
                .toBeLessThanOrEqual(2);
            const bounds = await page.evaluate(
                (target) => ({
                    section: document
                        .getElementById(target)
                        .querySelector('h2')
                        .getBoundingClientRect().top,
                    nav: document.querySelector('.portfolio-nav').getBoundingClientRect().bottom,
                    header: document
                        .querySelector('nav[aria-label="Primary"]')
                        .getBoundingClientRect().bottom,
                    bar: document.querySelector('.portfolio-nav').getBoundingClientRect().top,
                }),
                target
            );
            expect(bounds.section).toBeGreaterThanOrEqual(bounds.nav - 2);
            expect(Math.abs(bounds.bar - bounds.header)).toBeLessThanOrEqual(2);
        }
        await page.locator('#chapters').scrollIntoViewIfNeeded();
        await expect(navigation.getByRole('link', { name: 'NASA', exact: true })).toHaveAttribute(
            'aria-current',
            'location'
        );
        for (const id of ['work', 'nasa', 'chapters', 'products', 'open-source', 'earlier']) {
            await expect(page.locator(`#${id}`)).toBeVisible();
        }
        await expect(page.locator('#open-source h2')).toHaveText('Developer Tooling / Open Source');
        await assertA11y(page);
    });

    test('portfolio hierarchy exposes project links and five footer utilities', async ({
        page,
    }) => {
        await page.goto('/');
        await expect(page.locator('[data-home-actions] a[href="/work"]')).toBeVisible();
        await expect(page.locator('#work h3 a')).toHaveText([
            'Read case study: Mission software at scale',
            'Read case study: Flood Mapping System',
            'Read case study: NASA satellite-data search',
        ]);
        await expect(page.locator('h3#work-card-title-jacobs-mission-software')).toBeVisible();
        await page.goto('/work');
        // Collection order on /work: mission → NASA → tooling → products.
        await expect(page.locator('main .portfolio-card h3 a')).toHaveText([
            'Read case study: Mission software at scale',
            'Read case study: Flood Mapping System',
            'Read case study: NASA satellite-data search',
            'Read case study: Engineering the feedback loop',
            'Read case study: The Dry Standard',
        ]);
        const title = page
            .locator('#the-dry-standard')
            .getByRole('link', { name: /Read case study/ });
        await expect(title).toHaveAttribute('href', /\/work\/the-dry-standard$/);
        await title.focus();
        await expect(title).toBeFocused();
        const footer = page
            .locator('footer')
            .getByRole('navigation', { name: 'Site', exact: true });
        await expect(footer.getByRole('link')).toHaveCount(5);
        for (const name of ['Writing', 'Resume', 'GitHub', 'LinkedIn', 'Privacy']) {
            await expect(footer.getByRole('link', { name: new RegExp(`^${name}`) })).toBeVisible();
        }
        await page.emulateMedia({ media: 'print' });
        await page.goto('/work');
        await expect(page.locator('.portfolio-nav')).toBeHidden();
        await expect(page.locator('#products')).toBeVisible();
    });

    test('home loads and exposes hire CTAs', async ({ page }) => {
        await page.goto('/');
        await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
        await expect(page.locator('[data-home-actions] a')).toHaveCount(2);
        await expect(page.locator('[data-home-actions] a[href="/#contact"]')).toBeVisible();
        await expect(page.locator('a[href="/kit"]')).toHaveCount(0);
        await expect(page.locator('#system')).toBeVisible();
        await expect(page.locator('#system').getByRole('link')).toHaveAttribute(
            'href',
            '/work/jacobs-mission-software#delivery-system'
        );
        await expect(page.locator('[data-delivery-map]')).toHaveCount(0);
        await expect(page.locator('#contact-form, [data-contact-form]').first()).toBeVisible();
        await assertA11y(page);
    });

    test('legacy now bookmark opens booking alongside contact', async ({ page }) => {
        await page.goto('/now#book');
        await expect(page).toHaveURL(/\/#book$/);
        await expect(page.locator('.contact-booking')).toHaveAttribute('open');
        await expect(page.locator('#book')).toBeVisible();
        await expect(page.locator('.booking-embed__frame')).toHaveAttribute(
            'src',
            /calendly|cal\.com/
        );
        await assertA11y(page, { exclude: ['.booking-embed'] });
    });

    test('work stays curated while supporting studies remain available on resume', async ({
        page,
    }) => {
        await page.goto('/work');
        await expect(page.locator('.site-toolbar')).toHaveCount(0);
        await expect(page.locator('#chapters')).toBeVisible();
        await expect(page.getByRole('heading', { name: 'Also at Goddard' })).toHaveCount(0);
        for (const slug of ['nasa-earth-observatory', 'direct-readout-laboratory', 'esscor']) {
            await expect(page.locator(`main a[href="/work/${slug}"]`)).toHaveCount(0);
        }
        await page.goto('/resume');
        for (const slug of ['nasa-earth-observatory', 'direct-readout-laboratory', 'esscor']) {
            await expect(page.locator(`main a[href="/work/${slug}"]`)).toBeVisible();
        }

        await page.goto('/work/laads-daac');
        await expect(page.locator('[data-lightbox-open]').first()).toBeVisible();
        await page.locator('[data-lightbox-open]').first().click();
        await expect(page.locator('[data-media-lightbox]')).toBeVisible();
        await page.keyboard.press('Escape');
        await assertA11y(page);
    });

    test('contact validation errors are accessible', async ({ page }) => {
        const response = await page.goto('/__a11y/contact-errors');
        test.skip(response?.status() === 404, 'A11Y_FIXTURES not enabled on this server');
        await expect(page.locator('[aria-invalid="true"]').first()).toBeVisible();
        await expect(page.locator('#a11y-name-error')).toBeVisible();
        await assertA11y(page);
    });

    test('conversion events reach the analytics provider with placement props', async ({
        page,
    }) => {
        // Keep the real provider script out so our stub is what receives events.
        await page.route(/plausible\.io|googletagmanager\.com/, (route) => route.abort());
        await page.addInitScript(() => {
            window.__events = [];
            window.plausible = (name, options) => window.__events.push({ name, options });
        });
        await page.goto('/about');

        // Stop the CTA from navigating so we can inspect the event it fired.
        await page.evaluate(() => {
            document.addEventListener('click', (e) => e.preventDefault(), { capture: true });
        });
        await page.locator('footer [data-analytics-event="booking_cta_clicked"]').first().click();

        const events = await page.evaluate(() => window.__events);
        expect(events).toContainEqual({
            name: 'booking_cta_clicked',
            options: { props: { page: '/about', location: 'footer' } },
        });

        await page.goto('/#book');
        const scheduled = await page.evaluate(() => {
            window.__events = [];
            window.postMessage({ event: 'calendly.event_scheduled' }, window.location.origin);
            return new Promise((resolve) =>
                setTimeout(() => resolve(window.__events.map((e) => e.name)), 50)
            );
        });
        // Same-origin messages must NOT count as a booking — only Calendly's origin does.
        expect(scheduled).not.toContain('booking_completed');
    });

    test('legacy kit resolves to background and hiring information', async ({ page }) => {
        await page.goto('/kit');
        await expect(page).toHaveURL(/\/about$/);
        await expect(page.getByRole('heading', { name: 'About', exact: true })).toBeVisible();
        await expect(page.locator('#focus')).toContainText('simpler developer workflows');
        await expect(page.locator('#approach')).toContainText('Principal Software Engineer');
        await expect(
            page.locator('main').getByRole('link', { name: 'Resume', exact: true })
        ).toBeVisible();
        await assertA11y(page);
    });

    test('command palette marks off-site results', async ({ page }) => {
        await page.goto('/');
        await page.keyboard.press('Control+K');
        await expect(page.locator('#command-palette')).toBeVisible();

        const linkedin = page.getByRole('option', { name: /linkedin/i });
        await expect(linkedin.locator('.command-result__ext')).toHaveText('↗');
        await expect(linkedin).toContainText('opens in a new tab');

        await expect(
            page.getByRole('option', { name: /switch theme/i }).locator('.command-result__ext')
        ).toHaveCount(0);
    });
});

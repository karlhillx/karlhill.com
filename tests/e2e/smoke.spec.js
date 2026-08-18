import { expect, test } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

/** Shared axe scan — serious/critical WCAG2 A/AA findings. */
async function assertA11y(page, { exclude = [] } = {}) {
    // Freeze motion so axe samples real painted colors (not mid-animation layers).
    await page.addStyleTag({
        content: `
          *, *::before, *::after {
            animation: none !important;
            transition: none !important;
            scroll-behavior: auto !important;
          }
        `,
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
    test('home loads and exposes hire CTAs', async ({ page }) => {
        await page.goto('/');
        await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
        await expect(page.locator('.hero-cta a[href="/now#book"]')).toBeVisible();
        await expect(page.locator('#contact-form, [data-contact-form]').first()).toBeVisible();
        await assertA11y(page);
    });

    test('now booking embed anchors and iframe', async ({ page }) => {
        await page.goto('/now#book');
        await expect(page.locator('#book')).toBeVisible();
        await expect(page.locator('.booking-embed__frame')).toHaveAttribute('src', /calendly|cal\.com/);
        await assertA11y(page, { exclude: ['.booking-embed'] });
    });

    test('work filters and lightbox chrome', async ({ page }) => {
        await page.goto('/work');
        await expect(page.locator('.site-toolbar--sticky')).toBeVisible();
        await expect(page.locator('.tag-filter--scroll')).toBeVisible();

        await page.goto('/work/nasa-earth-observatory');
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

    test('recruiter kit one-pager', async ({ page }) => {
        await page.goto('/kit');
        await expect(page.getByRole('heading', { name: /recruiter kit/i })).toBeVisible();
        await expect(page.getByRole('link', { name: /download resume pdf/i })).toBeVisible();
        await expect(page.locator('.kit-doc')).toBeVisible();
        await assertA11y(page);
    });
});

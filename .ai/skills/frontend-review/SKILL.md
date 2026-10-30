---
name: frontend-review
description: Review karlhill.com UI for type, spacing, contrast, and responsive layout using the rendered page. Use when editing CSS, Blade layout, or visual content.
---

# Frontend review

## When to use

A change touches `resources/css`, Blade layout, or copy that affects wrapping. Skip it for PHP-only changes with no visible output.

## Steps

1. Read `.ai/rules/design-system.md`.
2. Note the routes that render the edited templates.
3. Serve the app (`composer dev` or the existing `php artisan serve`). Cache-bust with `?v=` because HTML is cached.
4. Open the affected route. For a theme or color change, check dark and light. For layout, check 390px and 1440px.
5. Measure, do not guess. In the browser, read computed `font-size`, `line-height`, `letter-spacing`, and `scrollWidth` versus viewport width on the heading, body, and any uppercase label you changed.
6. Look at the screenshot for clipped glyphs, a heading that collides with the sticky nav, a caption separated from its image, and text that overflows the card.
7. Run `npm run build` if CSS changed. Run `python3 scripts/check-dead-css.py` if you added or removed class selectors.

## Report

- What you opened, at which width and theme.
- Computed values that changed, with the element.
- What the screenshot showed.
- What you did not open.

Do not say the page was reviewed if you only read the CSS.

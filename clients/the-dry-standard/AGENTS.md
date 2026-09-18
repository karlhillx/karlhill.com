# OpenClaw / agent memory — The Dry Standard

Read this file before researching, drafting, or publishing anything for The Dry Standard. Do not rely on prior chat context.

## What this is

An editorial publication about beverages at **≤0.5% ABV**, staged at `/clients/the-dry-standard/` inside the karlhill.com Laravel repo. The visual and legal parent is a client preview (Laravel sends `X-Robots-Tag: noindex` on `/clients/*`). On-page SEO, feeds, and structured data are implemented so the site can move to its own domain later.

The differentiator is aggressive: **dealcoholized** is not a synonym for **non-alcoholic**.

## What qualifies

A product may be reviewed if it is at or below 0.5% ABV.

It may be labeled **Dealcoholized: Yes** only when a cited source shows alcohol was removed from an alcoholic (or high-proof) liquid. Vacuum distillation, spinning cone, reverse osmosis, membrane / cold filtration, reverse distillation, and other named removal processes count.

Formulated zero-proof drinks are in scope as contrasts. Mark them **Dealcoholized: No**.

If the factory floor is unpublished, mark **Dealcoholized: Not verified**. Never upgrade a marketing zero to a method.

## Voice

Knowledgeable, adult, editorial, sophisticated, direct, curious. Slightly opinionated about taste. Never preachy about alcohol or sobriety. Never wellness-influencer. Never generic AI marketing copy. Avoid hype.

Tasting notes are opinion. Production facts are sourced or omitted.

## You must never fabricate

ABV, manufacturing process, alcohol-removal technique, origin, ingredients, nutrition, producer, price, or availability.

If sources disagree, record a `discrepancies` entry. Do not pick a winner.

If a fact cannot be sourced, delete the field.

## Workflow

1. Take a product from `data/review-queue.yaml` (prefer `priority: high`, status `queued`).
2. Confirm the product exists.
3. Research primary sources (producer, importer, press, retailer technical sheets).
4. Verify ABV ≤ 0.5%.
5. Decide dealcoholized: yes / no / not-verified.
6. Collect only sourced metadata.
7. Draft the review markdown in `content/reviews/{slug}.md`.
8. Run `php artisan dry-standard:validate {slug} --publish`.
9. If it fails, set `status: needs-review` and stop. Do not publish to satisfy the calendar.
10. If it passes, `php artisan dry-standard:publish {slug}`.
11. Rebuild updates category pages, brand pages, RSS, sitemap, and `catalog.json`.
12. Record the publish (the command writes `data/publish-log.yaml` and updates the queue).

Duplicate slugs and duplicate queue product+brand pairs are rejected.

## Models

`data/config.yaml` selects model roles. Prefer local models for research synthesis, classification, drafting, editing, tagging, internal links, and duplicate detection. Use a cloud fallback only when `models.fallback` is explicitly enabled. The PHP app does not call models; you do.

## File map

- Reviews: `clients/the-dry-standard/content/reviews/`
- Product stills: `clients/the-dry-standard/media/reviews/{slug}.jpg`
- Master product table: `clients/the-dry-standard/data/master-products.csv` (internal `ID` and `SKU` columns; do not render)
- Queue: `clients/the-dry-standard/data/review-queue.yaml`
- Config: `clients/the-dry-standard/data/config.yaml`
- Schema reminder: `clients/the-dry-standard/data/schema/review.schema.yaml`
- Public site: generated HTML under `clients/the-dry-standard/`
- Commands: `php artisan dry-standard:*`

## Site structure

`/`, `/reviews/`, `/reviews/{wine|beer|spirits|cocktails|cider}/`, `/reviews/{category}/{slug}/`, `/guides/`, `/brands/`, `/methods/`, `/about/`.

No dates in review URLs.

## Schedule

Default: Monday, Wednesday, Friday, three reviews a week. Change it in config. If validation fails or no draft is ready, skip. Never invent facts to fill a slot.

## Architecture constraint

This is a static client site plus a PHP builder, matching karlhill.com. Do not introduce a second framework, a database, or ecommerce.

# OpenClaw / agent memory — The Dry Standard

Read this file before researching, drafting, or publishing anything for The Dry Standard. Do not rely on prior chat context.

## What this is

An editorial publication about beverages at **≤0.5% ABV**, staged at `/clients/the-dry-standard/` inside the karlhill.com Laravel repo. The visual and legal parent is a client preview (Laravel sends `X-Robots-Tag: noindex` on `/clients/*`). On-page SEO, feeds, and structured data are implemented so the site can move to its own domain later.

The differentiator is aggressive: **dealcoholized** is not a synonym for **non-alcoholic**. Classify by production type, and keep a separate verified field.

## What qualifies

A product may be reviewed if it is at or below 0.5% ABV.

Set `production_type` to one of:

- **dealcoholized** — a cited source shows alcohol was removed from an alcoholic (or high-proof) liquid. Named methods are vacuum distillation, spinning cone column, reverse osmosis, membrane / cold filtration, osmotic distillation, or another documented removal process. Do not invent "reverse distillation" as a standard method name; if a producer uses that phrase, keep it in the method field and classify the facet as other.
- **alternative** — built as a non-alcoholic analogue from the start (flavors, botanicals, extracts, distillates, juice). A botanical "whiskey alternative" is Alternative, not "Dealcoholized: No."
- **naturally-low-alcohol** — fermented or otherwise traditionally produced, but finishes at ≤0.5% ABV without a separate removal step (arrested fermentation, specialized yeasts, limited fermentation).
- **hybrid** — dealcoholized material plus other defining non-alcoholic ingredients, or more than one production approach. Standard must-back-addition after removal stays dealcoholized.
- **not-verified** — the factory floor is unpublished. Never upgrade a marketing zero to a method.

Set `verified: yes` only when a cited source supports that type. Otherwise `verified: no`.

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
5. Decide production_type and verified.
6. Collect only sourced metadata.
7. Draft the review markdown in `content/reviews/{slug}.md`.
8. Photograph or obtain a still: editorial photo of the tasted bottle, or producer/importer press art with `image_source_url`. Confirm the label. Run `php artisan dry-standard:audit-stills {slug}`.
9. Run `php artisan dry-standard:validate {slug} --publish`.
10. If it fails, set `status: needs-review` and stop. Do not publish to satisfy the calendar.
11. If it passes, `php artisan dry-standard:publish {slug}`.
12. `php artisan dry-standard:build` syncs `data/catalog.sqlite` and `data/products.csv`. Pages render live from the catalog.
13. Record the publish (the command writes `data/publish-log.yaml` and updates the queue).

Duplicate slugs and duplicate queue product+brand pairs are rejected.

## Models

`data/config.yaml` selects model roles. Prefer local models for research synthesis, classification, drafting, editing, tagging, internal links, and duplicate detection. Use a cloud fallback only when `models.fallback` is explicitly enabled. The PHP app does not call models; you do.

## File map

- Reviews (editorial draft/import): `clients/the-dry-standard/content/reviews/`
- Product stills: `clients/the-dry-standard/media/reviews/{slug}.jpg` (audit with `php artisan dry-standard:audit-stills`)
- Product database: `clients/the-dry-standard/data/catalog.sqlite` (runtime source of truth; generated)
- Spreadsheet export: `clients/the-dry-standard/data/products.csv`
- Purchase ledger: `clients/the-dry-standard/data/master-products.csv` (internal `ID` and sourced `EAN`; do not render)
- Queue: `clients/the-dry-standard/data/review-queue.yaml`
- Config: `clients/the-dry-standard/data/config.yaml`
- Schema reminder: `clients/the-dry-standard/data/schema/review.schema.yaml`
- Public site: Laravel renders `/clients/the-dry-standard/` live from the catalog
- Commands: `php artisan dry-standard:*`

## Site structure

`/`, `/reviews/`, `/reviews/{wine|beer|spirits|cocktails|cider}/`, `/reviews/{category}/{slug}/`, `/guides/`, `/brands/`, `/methods/`, `/best/`, `/best/{category}/`, `/about/`.

No dates in review URLs.

## Schedule

Default: Monday, Wednesday, Friday, three reviews a week. Change it in config. If validation fails or no draft is ready, skip. Never invent facts to fill a slot.

## Architecture constraint

This is a Laravel client preview. Product data lives in one SQLite catalog (`data/catalog.sqlite`), with `data/products.csv` as the spreadsheet export. Markdown reviews are the editorial draft/import format. Do not introduce a second framework or ecommerce. Do not generate or check in HTML pages.

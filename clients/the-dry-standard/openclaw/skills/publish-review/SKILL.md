# Skill: publish a Dry Standard review

## When to use

A product in `data/review-queue.yaml` should move to a public review page.

## Steps

1. Open `clients/the-dry-standard/AGENTS.md` and `EDITORIAL.md`.
2. Confirm the product exists and is ≤0.5% ABV from primary sources.
3. Classify `production_type` (`dealcoholized`, `alternative`, `naturally-low-alcohol`, `hybrid`, or `not-verified`) and set `verified` separately.
4. Write `content/reviews/{slug}.md` using `data/schema/review.schema.yaml`.
5. Add a still at `media/reviews/{slug}.jpg`. Prefer an editorial photo of the tasted bottle on paper. Producer or importer press art is allowed with `image_source_url`. Never use a retailer store photo.
6. Set `image`, `image_alt`, `image_credit`, `image_source` (`editorial`, `producer`, or `importer`), and `image_sku_confirmed: yes` only after looking at the label.
7. Attach `sources` with `claims` for every factual field you filled.
8. Leave tasting notes in `nose` / `palate` / `finish` — those are editorial, not sourced facts.
9. `php artisan dry-standard:audit-stills {slug}` then `php artisan dry-standard:validate {slug} --publish`
10. On failure: set `status: needs-review`, write what is missing, stop.
11. On success: `php artisan dry-standard:publish {slug}` (add `--force` only when an editor overrides the calendar; add `--commit` only when a git snapshot is requested).
12. Never create a second review for the same slug.

## Models

Use whatever `data/config.yaml` lists for `models.research`, `models.drafting`, `models.editing`, and `models.validation`. Default is local for all; fallback disabled.

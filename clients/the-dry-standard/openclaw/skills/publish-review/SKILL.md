# Skill: publish a Dry Standard review

## When to use

A product in `data/review-queue.yaml` should move to a public review page.

## Steps

1. Open `clients/the-dry-standard/AGENTS.md` and `EDITORIAL.md`.
2. Confirm the product exists and is ≤0.5% ABV from primary sources.
3. Classify `dealcoholized` as `yes`, `no`, or `not-verified`.
4. Write `content/reviews/{slug}.md` using `data/schema/review.schema.yaml`.
5. Attach `sources` with `claims` for every factual field you filled.
6. Leave tasting notes in `nose` / `palate` / `finish` — those are editorial, not sourced facts.
7. `php artisan dry-standard:validate {slug} --publish`
8. On failure: set `status: needs-review`, write what is missing, stop.
9. On success: `php artisan dry-standard:publish {slug}` (add `--force` only when an editor overrides the calendar; add `--commit` only when a git snapshot is requested).
10. Never create a second review for the same slug.

## Models

Use whatever `data/config.yaml` lists for `models.research`, `models.drafting`, `models.editing`, and `models.validation`. Default is local for all; fallback disabled.

# The Dry Standard

Editorial review site for dealcoholized and non-alcoholic drinks at **0.5% ABV or less**, staged at `/clients/the-dry-standard/`.

Positioning: **the standard for what remains after the alcohol is gone.**

This is not a shop. It is a small autonomous publication: a review queue, sourced facts, tasting notes, and a rebuildable static site that follows the karlhill.com client-preview convention.

## Start here

| Document | What it is |
| --- | --- |
| [AGENTS.md](AGENTS.md) | Durable instructions for OpenClaw and any future agent |
| [EDITORIAL.md](EDITORIAL.md) | Voice, dealcoholized vs formulated, scoring |
| [PUBLISHING.md](PUBLISHING.md) | Queue, validation, schedule, commands |
| [SITE.md](SITE.md) | URLs, files, data model |

## Commands

```bash
php artisan dry-standard:status
php artisan dry-standard:validate
php artisan dry-standard:validate leitz-eins-zwei-zero-riesling --publish
php artisan dry-standard:build
php artisan dry-standard:queue "Athletic Brewing Run Wild IPA" --brand="Athletic Brewing" --category=beer --priority=high
php artisan dry-standard:publish {slug}
php artisan dry-standard:publish {slug} --force
```

`--force` ignores the weekday / weekly quota. Publication still stops if factual validation fails.

## Layout

```
content/reviews/*.md     # review source of truth (YAML frontmatter + body)
media/reviews/{slug}.jpg # editorial product stills
content/guides/*.md
content/methods/*.md
content/pages/about.md
data/config.yaml         # cadence, models, categories, URLs
data/master-products.csv # one row per unique product, internal ID + sourced EAN, sorted by times purchased
data/review-queue.yaml
data/publish-log.yaml
src/                     # PHP builder (DryStandard\)
```

HTML, `feed.xml`, `sitemap.xml`, and `catalog.json` are generated. Edit markdown, then build.

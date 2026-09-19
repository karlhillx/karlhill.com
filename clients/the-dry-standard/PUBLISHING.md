# Publishing system

OpenClaw (or a human) researches and writes. PHP validates, syncs the catalog, and logs. Local models do not get a write path around the validator.

## States

`queued` → `researching` → `draft` → `validated` → `scheduled` → `published`

`needs-review` is a sink when facts are thin or sources conflict too hard to publish.

## Config

`data/config.yaml`:

```yaml
publishing:
  enabled: true
  reviews_per_week: 3
  days:
    - monday
    - wednesday
    - friday

models:
  research: local
  drafting: local
  editing: local
  validation: local
  fallback: disabled
```

Change the cadence there. Do not hard-code it into an agent prompt.

## Queue

`data/review-queue.yaml` holds discovered products. Adding a product does not publish it.

```bash
php artisan dry-standard:queue "Giesen 0% Sauvignon Blanc" --brand=Giesen --category=wine
```

## Hallucination brake

`php artisan dry-standard:validate {slug} --publish` fails when:

- a factual field has no source claim
- ABV is missing a source
- a classified `production_type` has no production_type, dealcoholized, or method source
- `abv_numeric` is over 0.5
- required tasting/verdict fields are empty

`dry-standard:publish` runs the same check and **refuses** a failed review. A closed calendar slot exits 0 and prints why it skipped.

## Build

```bash
php artisan dry-standard:build
```

Syncs markdown reviews, the purchase ledger, and the queue into `data/catalog.sqlite`, then writes `data/products.csv`. Pages, the feed, sitemap, and `catalog.json` render live from the catalog.

## Git

`php artisan dry-standard:publish {slug} --commit` will stage `clients/the-dry-standard` and commit. Default is no commit. OpenClaw may pass `--commit` when the operator wants an automatic snapshot.

## Duplicate protection

- One markdown file per slug.
- Queue add rejects the same product+brand pair.
- `data/catalog.sqlite` is the product database. `catalog.json` remains the public machine index.

## Future

`/best/` is generated from published scores. Affiliate tags and price tracking can read the catalog without changing the review schema. Ecommerce is out of scope.

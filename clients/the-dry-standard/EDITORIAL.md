# Editorial handbook

## Positioning

**The Dry Standard** — the standard for what remains after the alcohol is gone.

Alternate deck: independent reviews of dealcoholized beer, wine, spirits, and cocktails.

## Production type

Classify by how the drink was made. Keep verification separate.

| Production type | Meaning |
| --- | --- |
| Dealcoholized | Started as an alcoholic beer, wine, spirit, or fermented base, then had alcohol removed or reduced. |
| Alternative | Created from the outset as a non-alcoholic analogue using flavors, botanicals, extracts, distillates, or juice. |
| Naturally low alcohol | Fermented or otherwise traditionally produced, but finishes at ≤0.5% ABV without a separate dealcoholization step. |
| Hybrid | Combines dealcoholized material with other defining non-alcoholic ingredients, or uses more than one production approach. Standard grape-must back-addition after removal stays Dealcoholized. |
| Not verified | Production method cannot be reliably established. |

`verified: yes` only when a cited source supports the type. `verified: no` belongs with Not verified, or with a classified type that still needs a stronger source.

Do not print "Dealcoholized: No." A botanical whiskey analogue is Alternative. A real whiskey that had ethanol removed is Dealcoholized.

Do not use "dealcoholized" as a compliment. It is a process claim.

## Tone

Write like Punch or Wine Enthusiast covering a cellar, not like a mocktail brand. Short sentences when the fact is sharp. No exclamation marks. No "journey." No "clean." No "mindful lifestyle."

Taste can be tart. Facts cannot be.

## Scoring

100-point quality scale. See `/about/` for the rubric. Do not score "how close is this to booze?" as the primary axis.

## Names

`brand` is the producer or label house. `product` is the SKU name. Do not repeat the brand in `product`. Do not append "Non-Alcoholic Wine" (or beer, cider, …) — `category` already says that.

Keep the house in `product` only when it is the official SKU (Guinness 0.0) or when it is a sub-brand and `brand` is the parent (Noughty under Thomson & Scott; Eins-Zwei-Zero under Leitz; WiesenObst under Jörg Geiger).

`title` is the public headline, usually `{brand} {product}`. The purchase ledger `Product` column matches `product`, not `title`.

## Review skeleton

1. Product overview
2. How was it made?
3. Product facts
4. Tasting notes (nose, palate, finish)
5. How to drink it
6. Verdict
7. Sources

## Product images

Save one still per review at `media/reviews/{slug}.jpg` (JPEG source) plus a generated 3:4 WebP.

Allowed sources, in order:

1. **Editorial** — a photograph of the bottle or can that was tasted, on paper, label readable.
2. **Producer** — press or product photography from the brand site, with `image_source_url` to that page.
3. **Importer** — press photography from the importer, with `image_source_url`.

Do not use retailer, marketplace, or delivery-app photography. Do not reuse a still across SKUs. Do not publish unlabeled mockups, lifestyle tablescapes, or another product's bottle. An empty frame is better than a wrong-SKU bottle. The still must show the **entire bottle or can** and the **full front label** — not a shoulder crop, label close-up, or logo fragment. Flatten stills onto paper (`rgb(243,239,230)`), not pure white.

Frontmatter:

```yaml
image: media/reviews/{slug}.jpg
image_alt: "{Brand} {Product} bottle"
image_credit: Editorial still | Product photo via {producer-or-importer-domain}
image_source: editorial | producer | importer
image_source_url: https://…   # required unless image_source is editorial
image_sku_confirmed: yes      # set only after looking at the label
```

`php artisan dry-standard:audit-stills` flags retailer credits, byte-identical files, stills under 500px, dark studio voids, lifestyle scenes, unlabeled mockups, and close-ups of part of a bottle. `dry-standard:validate {slug} --publish` and `dry-standard:publish` refuse those errors. `dry-standard:build` still syncs the existing cellar; it does not mass-fail on legacy stills.

WebP output letterboxes non-3:4 JPEGs onto paper (`rgb(243,239,230)`). Uniform near-white cutouts are flooded onto the same paper. Dark studio packshots are not auto-filled — a black can would disappear — so recrop or reshoot those.

If the file is missing, the page still builds; the cellar shows an empty frame. Do not set `image:` until the still is a confirmed SKU.

## Sources

`id` is our internal product identifier from `data/master-products.csv`. `ean` is a sourced barcode when we have one. Do not invent either. Do not print them on the public site.

Every factual field in frontmatter must be listed under a source `claims` array. Allowed claim tokens include `abv`, `method`, `origin`, `producer`, `ingredients`, `calories`, `sugar`, `price`, `availability`, `volume`, `production_type`, `dealcoholized`, `base_beverage`, `country`, `region`.

Retailer copy is weaker than a producer technical page. Use it for price and availability; do not let it invent a still.

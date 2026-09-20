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

100-point quality scale. See `/about/` for the rubric. Do not score "how close is this to booze?" as the primary axis. Likeness and `structure` (whether alcohol's usual body, acid, tannin, bitterness, heat, dryness, or finish is recreated) are separate from the number. A drink can be enjoyable and not wine-like, or wine-like and flawed.

## Names

`brand` is the producer or label house. `product` is the SKU name. Do not repeat the brand in `product`. Do not append "Non-Alcoholic Wine" (or beer, cider, …) — `category` already says that.

Keep the house in `product` only when it is the official SKU (Guinness 0.0) or when it is a sub-brand and `brand` is the parent (Noughty under Thomson & Scott; Eins-Zwei-Zero under Leitz; WiesenObst under Jörg Geiger).

`title` is the public headline, usually `{brand} {product}`. The purchase ledger `Product` column matches `product`, not `title`.

## Review skeleton

1. The wine (or beer, spirit, cider, drink — by category)
2. How was it made?
3. Product facts
4. Tasting notes (nose, palate, finish, structure)
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

## Writing the essay

The markdown body is the review. The page labels it **The wine** (or beer, spirit, cider, drink). Never call it a product overview.

### What belongs in the essay

Origin and how it was made, in one tight beat. Then the glass. Then the argument — usually where dealcoholized drinks succeed or fail (aroma vs palate, structure vs juice, sweetness vs acid).

Do not restate `nose` / `palate` / `finish` word for word in the body. The tasting fields do the fruit-by-fruit work. The essay makes the case.

### What belongs outside the essay

| Put it here | Keep it out of the essay, summary, and lede |
| --- | --- |
| `price`, `availability`, `purchase_links` | Retailer names, shelf prices, store SKUs |
| `producer` | Unrelated businesses (hotel chains, conglomerates that did not make the drink) |
| `sources` | Citation titles and URLs |
| `nose` / `palate` / `finish` | A second pasted copy of the tasting note |

A dollar figure is a fact. Write `$12.99 (Total Wine, 750 ml)` in `price`. Do not write "under $13" or "sold at Total Wine" in the body.

### After a tasting

Paraphrase the editor's notes. Do not paste them. Do not invent fruit, faults, or texture the notes did not give.

If aroma and palate diverge on wine-likeness (or beer-likeness, etc.), print the split as two data points. Do not average them into one "wine-like" score. That gap is the useful number.

Sensory shorthand (berry-forward, tart, off-dry) can sit in the prose. After a tasting, fill `structure`: whether the drink recreates the body, acidity, tannin, bitterness, heat, dryness, or finish alcohol normally supplies. That is separate from the quality score and from "does it taste like wine?"

Do not invent sensory notes. Ground them in the editor's tasting, sourced producer facts, recurring third-party impressions, or a clearly labeled inference. Plain language first. Do not force a long aroma list. Do not hide faults. Do not punish the absence of ethanol by itself. Do not over-praise.

Full style guide, scoring guardrails, and sensory vocabulary: `openclaw/skills/write-review/reference.md`.

Once the bottle has been tasted, retailer tasting copy is not our note. Drop `discrepancies` that are only competing impressions of flavor.

### Shape

1. Method in two or three short sentences. Do not say "fermented and aged" twice.
2. The glass: structure and the tell.
3. The argument.

Current model for the essay/facts split: `content/reviews/st-regis-non-alcoholic-rose.md`. For a fully sourced, method-forward wine, also read `noughty-sparkling-chardonnay.md` and `leitz-eins-zwei-zero-riesling.md`.

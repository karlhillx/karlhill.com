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

## Review skeleton

1. Product overview
2. How was it made?
3. Product facts
4. Tasting notes (nose, palate, finish)
5. How to drink it
6. Verdict
7. Sources

## Product images

Save one editorial still per review at `media/reviews/{slug}.jpg` (3:4, bottle or can on paper). Set `image`, `image_alt`, and `image_credit` in frontmatter. Do not scrape brand photography. If the file is missing, the page still builds; the cellar just shows an empty frame.

## Sources

`id` is our internal product identifier from `data/master-products.csv`. `ean` is a sourced barcode when we have one. Do not invent either. Do not print them on the public site.

Every factual field in frontmatter must be listed under a source `claims` array. Allowed claim tokens include `abv`, `method`, `origin`, `producer`, `ingredients`, `calories`, `sugar`, `price`, `availability`, `volume`, `production_type`, `dealcoholized`, `base_beverage`, `country`, `region`.

Retailer copy is weaker than a producer technical page. Use it for price and availability; do not let it invent a still.

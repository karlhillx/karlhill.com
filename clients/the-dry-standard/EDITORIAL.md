# Editorial handbook

## Positioning

**The Dry Standard** — the standard for what remains after the alcohol is gone.

Alternate deck: independent reviews of dealcoholized beer, wine, spirits, and cocktails.

## Dealcoholized vs formulated

| Badge | Meaning |
| --- | --- |
| Dealcoholized: Yes | A cited source describes alcohol removal from a beer, wine, spirit, or high-proof extract. |
| Dealcoholized: No — formulated as a zero-proof alternative | Built as a non-alcoholic drink from the start. |
| Dealcoholized: Not verified | NA marketing without a method we can cite. |

Do not use "dealcoholized" as a compliment. It is a process claim.

## Tone

Write like Punch or Wine Enthusiast covering a cellar, not like a mocktail brand. Short sentences when the fact is sharp. No exclamation marks. No "journey." No "clean." No "mindful lifestyle."

Taste can be tart. Facts cannot be.

## Scoring

100-point quality scale. See `/about/` for the rubric. Do not score "how close is this to booze?" as the primary axis.

## Review skeleton

1. Product overview
2. Is it actually dealcoholized?
3. Product facts
4. Tasting notes (nose, palate, finish)
5. How to drink it
6. Verdict
7. Sources

## Product images

Save one editorial still per review at `media/reviews/{slug}.jpg` (3:4, bottle or can on paper). Set `image`, `image_alt`, and `image_credit` in frontmatter. Do not scrape brand photography. If the file is missing, the page still builds; the cellar just shows an empty frame.

## Sources

`id` is our internal product identifier from `data/master-products.csv`. `ean` is a sourced barcode when we have one. Do not invent either. Do not print them on the public site.

Every factual field in frontmatter must be listed under a source `claims` array. Allowed claim tokens include `abv`, `method`, `origin`, `producer`, `ingredients`, `calories`, `sugar`, `price`, `availability`, `volume`, `dealcoholized`, `base_beverage`, `country`, `region`.

Retailer copy is weaker than a producer technical page. Use it for price and availability; do not let it invent a still.

# Skill: write a Dry Standard review

## When to use

Drafting or revising `clients/the-dry-standard/content/reviews/{slug}.md`, rewriting tasting notes from an editor, or tightening a published review.

**Read [reference.md](reference.md) before writing.** That file is the review style guide, scoring guardrails, and sensory vocabulary. Do not skip it.

Publishing is a separate skill: `publish-review`.

## Also read

1. `clients/the-dry-standard/AGENTS.md`
2. `clients/the-dry-standard/EDITORIAL.md`
3. `clients/the-dry-standard/data/schema/review.schema.yaml`

Do not rely on prior chat context.

## Map the guide onto this site

The public page does not have Overview / Appearance / Taste headings. Fold the guide into the fields we actually render.

| Guide section | Where it goes |
| --- | --- |
| Overview | Essay body (heading: **The wine** / beer / spirit / cider / drink). Identity block already prints ABV — do not recap the number. |
| Appearance | One or two sentences in the essay, only if observed. Skip if unknown. |
| Aroma | `nose` |
| Taste + Mouthfeel | `palate` — mouthfeel is required here or in the essay |
| Finish | `finish` |
| Structural authenticity | `structure` (see below) and the essay argument |
| Classification | `production_type` + `verified` |
| Score | `rating` (quality). Likeness and structure are not the score. |

Never title the essay "Product overview."

Retailers, shelf prices, store SKUs, and unrelated businesses stay out of the essay, summary, and lede. They belong in `price`, `availability`, `purchase_links`, `producer`, or `sources`.

## Classification enum

The guide's categories map to frontmatter as follows. Never guess.

| Guide term | `production_type` |
| --- | --- |
| Dealcoholized | `dealcoholized` |
| Fermented / controlled fermentation | `naturally-low-alcohol` |
| Alcohol alternative | `alternative` |
| Mix of removal plus other defining ingredients | `hybrid` |
| Unknown / not verified | `not-verified` |

RTD cocktails still need a production type for the base. `category` is `cocktails`. Do not label an alternative as dealcoholized. Never infer dealcoholization from "alcohol-free," "non-alcoholic," "zero proof," or "zero alcohol."

`verified: yes` only when a cited source supports the type.

## Structural authenticity

`structure` is a short editorial field, separate from the quality score and separate from "does it taste like wine?"

It measures whether the drink recreates the **body, acidity, tannin, bitterness, heat, dryness, or finish** that alcohol normally contributes.

Write it when the bottle has been tasted. Example:

```yaml
structure: "Aroma fairly wine-like. Palate less so — tart, balsamic, lean, without alcohol's weight or warmth."
```

Likeness can split (nose vs palate). Quality can be high while structure is weak, and the reverse. Do not average those into one "wine-like" score.

## Sensory rules (do not drift)

- Do not invent notes. Ground them in the editor's tasting, producer facts, recurring third-party impressions, or a clearly labeled inference.
- Paraphrase editor notes. Do not paste them. Do not add fruit, faults, or texture they did not give.
- Plain language first. Broad descriptors before obscure ones. Do not force five aroma notes.
- Mouthfeel and balance are required. Do not call a finish "long" unless it persists.
- Do not hide faults. Do not punish the absence of ethanol by itself.
- Do not over-praise. No exceptional / luxurious / exquisite / masterfully crafted / perfect / elevated / premium experience without a defensible reason.
- No fake precision, marketing paste, or generic AI wine prose. Vocabulary and faults list: [reference.md](reference.md).

## Drafting

1. Confirm the product exists and is ≤0.5% ABV from primary sources.
2. Classify `production_type` and set `verified` separately.
3. Fill sourced facts only. If a fact cannot be sourced, omit the field.
4. If sources disagree on a **fact**, add `discrepancies`. Flavor impressions are not a fact dispute.
5. Write the essay: method once; then the glass; then the argument (likeness split, structure, balance, faults).
6. Write `nose`, `palate`, `finish`, and after a tasting `structure`.
7. `summary` is the lede: process + what the glass does. No retailer. No price.
8. Disclose free samples (`acquisition`, `disclosure_note`). Never change score or wording for a commercial relationship.
9. Run the Final Review Test in [reference.md](reference.md). Then stop. Validate via `publish-review`.

## Model shape

Essay vs facts: `content/reviews/st-regis-non-alcoholic-rose.md`

## Checklist

- [ ] Read [reference.md](reference.md)
- [ ] Production type correct; alternative not called dealcoholized
- [ ] Essay has no retailer, price, or store SKU
- [ ] No invented sensory notes
- [ ] Mouthfeel, balance, and finish discussed
- [ ] `structure` filled after a tasting
- [ ] Likeness split printed if aroma and palate disagree
- [ ] Flaws named when present
- [ ] Every filled fact has a source `claims` token
- [ ] Final Review Test passes

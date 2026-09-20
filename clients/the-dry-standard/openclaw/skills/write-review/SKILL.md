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
| Structural authenticity | `profile` chips + `structure` (legacy) + the essay argument |
| Classification | `production_type` + `verified` |
| Score | `rating` (quality). Likeness and structure are not the score. |

### At-a-glance panel (required after a tasting)

Scannable decision fields, rendered above the essay. Plain English. Middot chips, not critic shorthand.

| Field | Purpose |
| --- | --- |
| `sensory` | Canonical flavor descriptors with locations (nose/palate/finish). Prefer over free-text. |
| `tastes` | Legacy short flavor tags (strawberry, balsamic). 3–6 max. Mapped to `sensory` when possible. |
| `structure_scales` | Normalized structure (sweetness, body, acidity, …). Prefer over free-text. |
| `profile` | Legacy structure chips. Mapped to `structure_scales` when possible. |
| `mouthfeel` | One short line. Must not copy `palate`. |
| `highlight` | What stands out — one sentence. |
| `likeness` | How wine/beer/spirit-like. Separate from `rating`. Must not copy `verdict`. |
| `assessments` | Optional qualitative scores (likeness/authenticity/balance/…). |
| `drink_if_you_like` | Comparable styles, optional. |
| `best_for` | Perfect for / occasion. |
| `product_id` / `identifiers` / `provenance` | Product identity and evidence — fill whenever sourced. |

Public glance labels: **Flavor profile** (sensory) · **Structure** (scales) · Nose / Palate / Finish (prose).

`structure` remains valid as a short authenticity note and as a fallback for `likeness` when `likeness` is empty. Do not treat the three as interchangeable: `rating` = quality, `likeness` = resemblance, `profile`/`structure_scales` = structural authenticity.

Never invent descriptors. Never promote AI inference to `provenance.confidence: verified`.

Never title the essay "Product overview."

Retailers, shelf prices, store SKUs, and unrelated businesses stay out of the essay, summary, and lede. They belong in `price`, `availability`, `purchase_links`, `producer`, or `sources`. Buy-link policy: producer/brand official URLs only in `purchase_links` this phase; name retailers in `availability` without hrefs until a partner agreement — see `COMMERCE.md`.

The essay is a **Quick review: 1,200–2,000 characters** (markdown body only, excluding frontmatter). More detail than a 50-word wine note; shorter than a feature. Short paragraphs. No filler.

Soft length targets (characters; validator warns, does not block):

| Field | Range |
| --- | --- |
| Verdict | 150–300 |
| Nose | 100–250 |
| Palate | 200–400 |
| Finish | 100–250 |
| Full review (prose sections + essay) | ~2,500–3,500 |

Do not invent ABV, method, ingredients, or tasting notes. Do not upgrade inferred provenance to verified. Do not overwrite human tasting notes with model guesses. Leave `assessments` empty unless the editor scored them.

Workflow statuses (aliases normalize at runtime): `draft` → `researched` / `tasted` / `reviewed` → `approved` / `validated` → `published`. Publish only after `php artisan dry-standard:validate {slug} --publish`.

ABV display labels are only `0.0%`, `<0.5%`, or `Not published`. Keep precise values in `abv_numeric`. Descriptor chips use ids from `descriptors.yaml` / aliases in `descriptor-aliases.yaml` — prose stays free.

Cover aroma, palate, mouthfeel, finish, balance, structural authenticity, and production provenance in prose. Do not rehash UPC, ABV disputes, g/L sugar, method temperatures, or ingredient lists already covered by identity, How it was made, At a glance, Product facts, or `discrepancies`. Do not paste `nose` / `palate` / `finish` line for line.

Shape: provenance beat → glass (aroma → palate / mouthfeel / balance → finish) → likeness / structure argument.

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

Separate from the quality score and from likeness.

- `profile` — chips for sweetness, acid, body, tannin, etc. (glance panel **Structure**).
- `structure` — optional short prose note; also legacy fallback for `likeness`.
- `likeness` — how wine/beer/spirit-like (glance panel heading is category-specific).

It measures whether the drink recreates the **body, acidity, tannin, bitterness, heat, dryness, or finish** that alcohol normally contributes.

Example after a tasting:

```yaml
profile:
  - Off-dry
  - Bright acidity
  - Light-medium body
  - Low tannin
mouthfeel: "Lean and crisp at first, with a slight drying grip on the finish."
likeness: "The aroma is more convincing than the palate. Acidity supplies some wine structure; the finish may read as kombucha-like."
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
5. Write the essay (1,200–2,000 characters): provenance once; then aroma, palate, mouthfeel, balance, finish; then likeness / structure. Short paragraphs. No filler. No sidebar rehash.
6. Fill the glance panel: `tastes`, `profile`, `mouthfeel`, `highlight`, `likeness` (and `drink_if_you_like` when useful).
7. Write concise `nose`, `palate`, `finish`, and optionally `structure` — scannable, not a second essay.
8. `summary` is the lede: process + what the glass does. No retailer. No price.
9. Disclose free samples (`acquisition`, `disclosure_note`). Never change score or wording for a commercial relationship.
10. Run the Final Review Test in [reference.md](reference.md). Then stop. Validate via `publish-review`.

## Model shape

Glance panel + essay: `content/reviews/site-riesling-dealcoholized.md`

## Checklist

- [ ] Read [reference.md](reference.md)
- [ ] Production type correct; alternative not called dealcoholized
- [ ] Essay is 1,200–2,000 characters (body only); short paragraphs; no filler
- [ ] Essay covers aroma, palate, mouthfeel, finish, balance, structure, provenance without sidebar dump
- [ ] Essay has no retailer, price, or store SKU
- [ ] No invented sensory notes
- [ ] Glance fields filled after a tasting (`tastes`, `profile`, `mouthfeel`, `highlight`, `likeness`)
- [ ] Mouthfeel, balance, and finish discussed
- [ ] Likeness split printed if aroma and palate disagree
- [ ] Flaws named when present
- [ ] Every filled fact has a source `claims` token
- [ ] Final Review Test passes

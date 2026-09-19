# Skill: suggest Dry Standard taxonomy

## When to use

Brand names are drifting (same house under two slugs), a style is mapping to `other`, or `php artisan dry-standard:status` shows completeness holes that look like classification errors rather than missing sources.

This skill **suggests**. It does not edit reviews, YAML, or the catalog. The validator remains the write gate.

## Steps

1. Open `clients/the-dry-standard/AGENTS.md`, `data/schema/brands.yaml`, and `data/schema/styles.yaml`.
2. Run `php artisan dry-standard:status` and note completeness, not just queue length.
3. List published brands from the catalog (`brand` + stored `brand_slug`). Flag:
   - Two `brand` strings that slug to different pages but share a producer or house name.
   - A review whose `brand` is already listed as an alias but still needs a frontmatter cleanup.
   - A new alias candidate for `brands.yaml` (example: Weingut Josef Leitz → `leitz`).
4. List styles. Flag:
   - `style_slug` = `other` with a repeatable glass name in `style`, `subcategory`, or `product`.
   - A closed-vocab slug used across the wrong category (IPA on wine).
   - A proposed new `styles.yaml` entry only when at least two published bottles would share it.
5. List method facets. Flag alternatives counted as unpublished removal (`not-applicable` is correct for formulated drinks).
6. Write a suggestion list: proposed YAML rows, review slugs to retag, and what **not** to change (review URLs, production_type enum, sourced facts).
7. Stop. Do not apply aliases or `style_slug` overrides until an editor asks.

## Do not

- Invent a method, origin, or brand legal name.
- Collapse distinct producers because the marketing names look similar.
- Encode production type into a style (“Dealcoholized Riesling” is Riesling).
- Publish, upsert the catalog, or rewrite tasting notes.

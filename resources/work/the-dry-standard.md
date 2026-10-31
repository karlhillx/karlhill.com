---
updated: '2026-10-31'
lede: An independent publication and product I founded, edit, and engineer. Markdown reviews are the source. Publish validates them and writes the SQLite catalog the public site reads.
role: Founder, editor, and product engineer — end-to-end product ownership.
leadership:
  mode: Independent product ownership
  team: Independently built and operated
  unblocked: A category where alcohol level, production method, and taste get collapsed into one listing.
  decision: Keep researched facts and firsthand scores separate, and refuse to publish until validation passes.
problem:
- Production method, alcohol level, and sensory judgment are different facts, and listings usually collapse them.
- A publication needs an editorial workflow. A catalog needs records that search and comparison can explain.
- Saving a file, or deploying it early, must not put an untasted score on the public site.
decisions:
- Treat markdown as the editorial source and a generated SQLite catalog as the read model. Public page requests do not write that catalog.
- Require a firsthand tasting before a public 0–100 score. Research records can publish as profiles and stay off scored rankings.
- Fail closed in validation. Publish assigns the durable product id and builds the catalog; a failed build restores the previous review and catalog.
- Render public pages from PHP templates. Blade stays on mail, errors, and the private operations dashboard.
- Keep homepage scheduling in the application database, outside the catalog, so a scheduled tasting stays off the public site until its publish time.
outcome:
- A live Laravel 13 application at drinkdrystandard.com, on PHP 8.5, with Vite-built CSS and JavaScript.
- Search, comparison, reviews, guides, and bounded reader feeds read one generated catalog.
- I own the domain rules, the publish gate, the interface, deployment, and the release that is running.
metrics: []
diagram:
  title: From editorial source to the public catalog
  caption: Markdown is the source. Publish validates it and writes the catalog. Public requests read that catalog.
  zones:
  - label: Editorial source
    steps:
    - Markdown reviews and guides
    - Sourced facts and discrepancies
    - Firsthand tasting state
  - label: Validate and publish
    steps:
    - Fail closed on unsourced facts
    - Assign a durable product id
    - Build the SQLite catalog
  - label: Public product
    steps:
    - PHP pages read the catalog
    - Search, comparison, and guides
    - Scheduled tastings stay off until publish time
---

The Dry Standard reviews drinks at 0.5% ABV or less. Dealcoholized is not a synonym for nonalcoholic. The model keeps production type, the evidence for that type, and the tasting record as separate facts. A researched profile can publish. A public 0–100 score requires a firsthand tasting.

I am the editor and the engineer. The live site is the product. The application repository stays private.

## Editorial source and the catalog

Reviews and guides are markdown. `product_id` is the durable key, assigned when a review publishes. The file does not carry a second public id.

`php artisan dry-standard:validate` reads the candidate and does not write the catalog. It refuses publication when a factual field has no source, ABV is not verifiably at or below 0.5%, a classified production type has no supporting source, required tasting fields are empty, or the bottle still fails its image checks. A public rating also requires `tasting_status` of `tasted` or `retasted` and an explicit firsthand tasting event.

`php artisan dry-standard:publish` runs that gate, persists the product id with the published status, and builds. `php artisan dry-standard:build` syncs published markdown into `data/catalog.sqlite` and writes operator-side CSV exports. The build stops if any published file fails the same checks. If publication bookkeeping fails, the previous review and catalog are restored.

Public traffic does not take a shortcut around that path. A page request does not write the catalog. Forms land in a private inbox and never publish. Saved bottles stay on the device; the site looks up only those slugs. Reader feeds are short and include firsthand tasted reviews, not research profiles. Bulk catalog download stays off.

Homepage scheduling is the piece that stays outside the catalog. Pins and holds live in the Laravel application database. A scheduled review becomes public at its clock time in America/New_York. Pushing the file earlier does not put the score on the site, and a rebuild does not copy a still-scheduled file into the catalog. An already-live research profile keeps serving the published version until that time.

## How the application is split

The stack is Laravel 13 and PHP 8.5. Domain code lives in `DryStandard\`: product identity, production type, evidence, tasting state, and scores. Finder, archive queries, comparison, recommendations, and the Artisan publish and validate commands sit on that model.

Public HTML is PHP templates in `src/views/`. Blade is mail, HTTP errors, and the private `/ops/` dashboard. CSS and JavaScript are authored under `assets/` and built with Vite. Node 24 is the local, container, and CI runtime.

Bottle stills are committed JPEGs. WebP derivatives are generated into a cache. A request may write a missing derivative. It still may not write the catalog.

Search, comparison, product pages, Best, Learn, and the cellar read the same catalog. Faceted query URLs are browsing tools. Curated collections and category pages are the durable, indexable landings. Research-only records stay off Best and off rankings by score.

## What I run

A deploy rebuilds the Vite assets and the catalog, including published-record validation. `php artisan dry-standard:release-check` compares the application build, the Vite manifest, the catalog, and the recorded release id with the revision I expect. Pest covers the domain rules. I run those checks before a change that needs them.

The limit is the same as the advantage. One person keeps the model, the editorial record, and the running site aligned. Explore the [live product](https://drinkdrystandard.com/).

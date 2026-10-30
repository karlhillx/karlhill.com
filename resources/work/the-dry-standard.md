---
updated: '2026-09-30'
lede: An independent production application I founded, edit, and engineer. The work is the domain model, the publishing pipeline, the public interface, and the operational checks that keep them consistent.
role: Founder, designer, and engineer — end-to-end product ownership.
leadership:
  mode: Independent product ownership
  team: Independently built and operated
  unblocked: Inconsistent product claims and a category that is difficult to search or compare.
  decision: Model products, evidence, and tasting state separately rather than make a review blog carry the entire domain.
problem:
- Production method, alcohol level, and sensory judgment are different kinds of information, but are often conflated.
- A publication needs an editorial workflow; a useful database also needs consistent records, queryable attributes, and explainable discovery.
decisions:
- Separate human-authored editorial source from a generated SQLite runtime catalog.
- Keep producer facts, their sources, and firsthand tasting scores distinct; researched records do not automatically qualify for scored rankings.
- Build discovery and comparison on the domain model, with curated indexable landing pages rather than treating every faceted URL as an SEO page.
- Own publishing validation, frontend assets, deployment, and release verification as part of the product.
outcome:
- A live Laravel product at drinkdrystandard.com, combining editorial publishing with structured search and comparison.
- One maintained model supports discovery, product pages, editorial operations, and public data surfaces.
- The running product shows the path from domain research through classification to production.
metrics: []
diagram:
  title: From editorial source to product discovery
  caption: Simplified content architecture. Publication builds the catalog; public GET requests read it rather than mutate it.
  zones:
  - label: Editorial source
    steps:
    - Product identity and classification
    - Evidence and producer claims
    - Firsthand tasting state
  - label: Publish and build
    steps:
    - Validate publication readiness
    - Build SQLite catalog
    - Generate public data
  - label: Public product
    steps:
    - Search and intent finder
    - Reviews and comparisons
    - Curated discovery pages
---

The Dry Standard is a publication and structured database for drinks at 0.5% ABV or less. It is also a full-stack product-engineering project: deciding what the domain means, designing how people explore it, and building the systems that keep publication and operation consistent.

## A model that preserves meaning

Alcohol level does not explain how a beverage was made. A dealcoholized wine and a formulated alternative can occupy the same retail category while representing different production histories. The model treats product identity, production type, method, evidence, tasting state, and scores as distinct concerns.

That separation matters in the interface. A researched fact should not look like a firsthand tasting result. Scored rankings require tasted or retasted records; research-only records can still contribute useful factual information without implying a sensory judgment.

## Editorial source, generated catalog

Editors work with authored review and guide content. Publication validation and a build step turn that source into a SQLite runtime catalog, exports, and public payloads. The Laravel application reads the catalog for public requests.

This separates editorial work from serving traffic. The catalog is a generated read model, not an alternative source of truth that public page requests silently update.

The application includes a private operations dashboard, publishing commands, and explicit release checks. These are product features for the operator, not just deployment chores.

## Search and discovery

Search spans product and brand names, styles, methods, classifications, and tasted flavor terms. An intent finder and side-by-side comparisons offer different ways into the same catalog.

The information architecture distinguishes temporary exploration from durable editorial destinations. Faceted query URLs support browsing; curated collections and substantive category pages provide indexable landings. Structured metadata and canonical URL decisions belong to the model and publishing workflow rather than being added after launch.

## Why the catalog is generated

Editorial markdown is the source of truth. A build validates it and writes a SQLite catalog, exports, and the payloads the public site reads. Search, comparison, product pages, and curated collections all query that catalog. A public request does not write it.

The tradeoff is deliberate. A change is not visible until validation and the build succeed, so the site cannot drift into a second, quieter editor. The cost is a publish step. The gain is one schema for discovery and one place to decide what a researched fact is allowed to look like.

This is not a distributed platform. It is one Laravel application, one catalog, and one public renderer.

## The interface is part of the model

Public pages are PHP templates with Vite-built CSS and JavaScript. Cards, search, comparison, and curated landings are how the taxonomy is explained, not a skin added afterward. A researched record and a tasted score have to look different, or the classification work is wasted. Blade is limited to mail, errors, and the private operations dashboard.

## Production ownership

The stack is Laravel, SQLite, PHP templates, and Vite-built CSS and JavaScript. Blade serves mail, errors, and the operations dashboard. The product runs on its own domain, not as a portfolio preview.

Release verification checks the application build, asset manifest, and catalog against the deployed release. I own that path as founder, editor, and principal engineer: domain rules, the interface, publish checks, and what is actually running. The limit is the same as the advantage. One person can keep the model and the product aligned. The work does not pretend to be a staffed platform.

## Technical proof

Explore the [live product](https://drinkdrystandard.com/). The sections above describe the classification model, the generated catalog, and how the public application reads it.

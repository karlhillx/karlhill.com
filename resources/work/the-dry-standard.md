---
updated: '2026-09-30'
lede: Independent non-alcoholic drinks publication and structured product database built around transparent classification, production methods, provenance, tasting data, and product discovery.
role: Founder, Architect, and Lead Engineer — product architecture, data modeling, automated validation, and web engineering.
leadership:
  mode: End-to-end product architecture and software engineering
  team: Independent product operation with automated editorial pipelines
  unblocked: Fragmented category data, ambiguous dealcoholization terminology, and unverified retail claims.
  decision: Build a rigorous schema-first taxonomy, automated validation tooling, and instant search rather than a generic blog.
problem:
- The non-alcoholic beverage space suffers from unclear category definitions, mixing dealcoholized wines and beers with formulated botanical alternatives.
- Factual claims around dealcoholization methods, residual sugar, and actual ABV are frequently unverified or inconsistent across retailers.
decisions:
- Design a structured taxonomy classifying beverages by precise production method (vacuum distillation, spinning cone, reverse osmosis, formulation).
- Build automated quality gates that validate schema completeness, factual source citations, and asset budgets before publication.
- Implement sub-second multi-facet search and product discovery with rich Schema.org JSON-LD for search engine indexing.
outcome:
- The live publication is operating at [drinkdrystandard.com](https://drinkdrystandard.com/) with structured product reviews, style guides, and instant comparison tools.
- Production evidence modeling separating sensory evaluation from technical producer facts.
- Full-stack web engineering demonstrating product design, data modeling, automated validation, and independent operations.
metrics: []
---

The Dry Standard is an independent publication and structured beverage database covering dealcoholized wines, non-alcoholic beers, and formulated spirits at 0.5% ABV or less. The live application runs at [drinkdrystandard.com](https://drinkdrystandard.com/).

Rather than a generic review blog, the product is built around a structured domain model that enforces transparent classification, verified production methods, and repeatable sensory scoring.

## Information architecture and domain modeling

The fundamental challenge in the non-alcoholic category is taxonomic ambiguity. Traditional wine, brewing, and distillation terminology breaks down when applied to dealcoholized products.

The Dry Standard solves this through an explicit data model:
- Separating true dealcoholized beverages (wine or beer brewed traditionally and dealcoholized via vacuum distillation, spinning cone column, or reverse osmosis) from formulated alternatives.
- Structured tracking of base ingredients, regional origin, organic certification, residual sugar, and caloric content.
- Strict separation between firsthand sensory tasting notes and third-party technical claims.

## Automated validation and quality gates

Content integrity is guaranteed through automated CLI validation tools:
- Automated schema validation ensures every published review has verified ABV certifications, dealcoholization methodology citations, and producer provenance.
- Publishing pipelines block releases if factual sources are missing or if unverified retailer links are detected.
- Automated responsive asset processing ensures high-performance WebP/AVIF generation with strict bundle budgets.

## Search, discovery, and performance

The web application is engineered for instant exploration:
- Multi-facet client-side filtering by style, production technique, country of origin, and ABV floor.
- Comprehensive Schema.org `Review`, `Product`, `WebSite`, and `Organization` structured data graph for rich search results.
- Resilient, fast-loading architecture with strict Content Security Policy, zero external trackers, and offline-ready caching.

# Site map

Public base: `/clients/the-dry-standard/`

| Path | Purpose |
| --- | --- |
| `/` | Editorial homepage |
| `/reviews/` | All reviews, filterable by dealcoholized status |
| `/reviews/wine/` | Wine, including sparkling |
| `/reviews/beer/` | Beer |
| `/reviews/spirits/` | Spirits |
| `/reviews/cocktails/` | RTDs, spritzes, aperitifs used as cocktail ingredients |
| `/reviews/cider/` | Cider |
| `/reviews/{category}/{slug}/` | Permanent review URL — no dates |
| `/guides/` | Educational / buying guides |
| `/brands/` | Brand index generated from reviews |
| `/methods/` | Dealcoholization-method reference |
| `/about/` | Mission and scoring |
| `/feed.xml` | Atom feed |
| `/sitemap.xml` | URL list for this client site |
| `/catalog.json` | Structured review index |

## Sample reviews

- `/reviews/wine/leitz-eins-zwei-zero-riesling/`
- `/reviews/beer/guinness-0-0/`
- `/reviews/wine/noughty-sparkling-chardonnay/`
- `/reviews/spirits/spiritless-kentucky-74/`
- `/reviews/cocktails/lyres-italian-orange/`

## Design

Warm paper, black masthead, Fraunces / Source Serif 4 / DM Sans. No product photography in phase one (no stock mocktail images). Typography carries the pages.

## Parent platform

Served by `ClientSiteController` like keithhillmusic.com. Preview is noindex at the Laravel header. Do not add Dry Standard URLs to the main karlhill.com sitemap.

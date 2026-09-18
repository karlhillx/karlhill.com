# Site map

Public base: `/clients/the-dry-standard/`

| Path | Purpose |
| --- | --- |
| `/` | Editorial homepage |
| `/reviews/` | Searchable cellar: query, category, process, method, sort |
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
| `/catalog.json` | Structured review index plus facets |

## Sample reviews

- `/reviews/wine/leitz-eins-zwei-zero-riesling/`
- `/reviews/beer/guinness-0-0/`
- `/reviews/wine/noughty-sparkling-chardonnay/`
- `/reviews/spirits/spiritless-kentucky-74/`
- `/reviews/cocktails/lyres-italian-orange/`

## Design

Cool paper, black masthead, Cormorant Garamond / Source Serif 4 / IBM Plex Sans. Reviews render as a wine-list ledger, not cards. Header search goes to `/reviews/?q=`. Filters stay in the URL so the archive can grow without a database. Product stills live in `media/reviews/{slug}.jpg` and appear on the ledger, review hero, and Open Graph tags. Typography still carries the pages.

## Parent platform

Served by `ClientSiteController` like keithhillmusic.com. Preview is noindex at the Laravel header. Do not add Dry Standard URLs to the main karlhill.com sitemap.

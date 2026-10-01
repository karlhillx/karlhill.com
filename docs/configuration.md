# Configuration

Domain copy lives in `config/site/*.php` (hero, person, experience, projects, now, …). `config/site.php` is the aggregator: it loads those fragments and wires env-sensitive flags (analytics, booking, Turnstile, push, platform surfaces).

Hire bio is canonical in `config/site/person.php` (`bio`, third person). Shared scale and affiliation facts live in `config/site/facts.php`. Homepage spoken line is `hero.lede`. Next-role copy is **not** on the homepage — About, `llms.txt` (after the identity block), and the hire packet use `person.availability` (Principal-level technical leadership first, then Engineering Manager). The longer ask is `person.availability_long`. Current-focus data remains in `config/site/now.php` and renders on About. Music stays a short coda on `/about`; do not add `/music`.

## Portfolio architecture

- Primary navigation is Work, Writing, About, Resume, Contact from `lg` (1024px); below that a drawer carries Work, Writing, About, Resume, and email while Contact stays visible. The footer has five utility links: Writing, Resume, GitHub, LinkedIn, Privacy.
- `/kit` redirects permanently to `/about`; `/now` to `/#book`; `/delivery` and `/lead` directly to `/work/jacobs-mission-software#delivery-practices`. No redirect chains. Retired pages are absent from the sitemap, prefetch targets, and search destinations. Existing `now` and `kit` JSON keys remain compatible but point to the consolidated destinations.
- About combines career, current focus, working approach, and hiring information. Delivery practices live in the Jacobs narrative. Publication details remain linked from the Flood Mapping case study, not global navigation.
- Cards have one accessible case-study link in the title, with a stretched hit area and a visual CTA below. Independent repository links remain separately operable. Live systems and papers are linked within case studies.
- `/` introduces Karl, then presents three compact case-study teasers, a short delivery-practices link, the latest three notes, and contact. Home teasers omit large visuals, repeated roles, and stack lists. Work is the primary CTA; the five-card catalog lives on `/work`. Earth Observatory, Direct Readout Laboratory, and ESSCOR are omitted from that overview; their case-study pages remain linked from the NASA role on Resume and stay in the sitemap, search, and machine-readable catalog. The `/#system` bookmark remains valid.
- `/work` is a server-rendered collection hub: Mission Software, NASA Platforms, Developer Tooling / Open Source, and Independent Products. Its structured-data list describes only the five cards and two earlier-work links displayed there. Anchor navigation does not hide content or require JavaScript. Existing `/work#work`, `#chapters`, `#open-source`, and `#products` links remain valid; `#chapters` now targets the NASA card grid. Legacy tag URLs still redirect.
- `config/site/work.php` defines full collection titles, concise `nav_label` values, and anchors. The collection rail uses Mission, NASA, Tools, Products, Earlier; active items stay visible if the rail overflows. `config/site/projects.php` assigns `portfolio_group`, `featured_order`, and concise `summary` fields (problem, contribution, impact, qualification). Full narratives and roles remain in `resources/work/{slug}.md`.
- `ProjectCatalog::collections()` powers the hub and homepage index; `featured(3)` powers the home cards and their JSON-LD list; `featured(5)` is the editorial order used on `/work` and in tests. `work-card` is shared rather than duplicating product markup.
- `PortfolioContent` validates required project and narrative fields with source-specific exceptions. CI checks the entire catalog, unique slugs, and featured ordering. Case-study metrics may use `fact: repos_display` instead of `value:` to reference `site.facts`; changing facts invalidates the narrative cache.
- `CaseStudyPage` prepares gallery defaults, artifact links, scope, and the TOC. Case studies render problem → decisions → diagram/screenshots and evidence → outcome → supporting detail. The Jacobs diagram replaces the decorative logo panel. Existing section IDs remain stable.
- Writing filters use ordinary server navigation. Breadcrumbs, series, canonical metadata, focus, and browser history update together; there is no partial-page swap layer.
- `/work/developer-tooling` features only **bb-run, testrisk, and pipeguard**, in that order, from `config/site/github.php`. `/work/the-dry-standard` documents product ownership, the generated SQLite catalog, evidence rules, discovery, editorial operations, and production delivery. Both flow through the existing sitemap, command search, and machine-readable catalog. The resume keeps Independent Products separate from Open Source.
- Keep metrics attributable: Jacobs figures describe scope and adopted standards; the flood-map evaluation is collaborative research; Earth Observatory traffic describes historical platform scale. Do not invent product revenue, adoption, or speed claims.
- Portfolio styling and collection navigation live in `resources/css/portfolio.css`, using shared tokens. Body text uses a normal-width system font; Oswald supplies condensed headings/UI and Bebas Neue supplies display text. Cards expose their content without hover; screenshots use the responsive-image component, explicit dimensions, and lazy loading. Three first-paint font faces are preloaded.
- About supplies context, Resume owns chronology/credentials/PDF, and Kit is the forwarding document. Do not turn each into another homepage.

For new raster images, run `php artisan assets:webp`, then `php artisan og:generate <slug>` for a 1200×630 social card. The developer-tooling diagram source is `public/img/developer-tooling.svg`; its PNG is the raster input to the same asset pipeline.

## Name disambiguation

Google associates bare “Karl Hill” with a Scottish novelist (pen name of a lawyer in Eaglesham). The primary English Wikipedia article is a 19th-century German baritone. This site is a third person: Washington, DC software engineer, NASA Goddard 2017–2025, Jacobs, published as Karl M. Hill.

On-site: Person JSON-LD (`sameAs` from `config/site/social.php` — LinkedIn, GitHub, ORCID, ResearchGate, Scholar, Scilit, SciProfiles, Discogs, [Gravatar](https://gravatar.com/karlhillx), [Crunchbase](https://www.crunchbase.com/person/karl-hill-09bb), [about.me](https://about.me/karlhill/) — plus Wikidata `Q139902938`, ORCID / Wikidata / Google Scholar / Scilit / SciProfiles `identifier`, `memberOf` the bands, `disambiguatingDescription`). `llms.txt` also states this is not the Scottish novelist. Do **not** put `Karl Hill (musician)` on Person `sameAs` — that title redirects to the Government Issue **band** article. Karl was in that band (drums, 2014–2015); the link belongs on the MusicGroup (`memberOf`), which `sameAs` [Government Issue](https://en.wikipedia.org/wiki/Government_Issue). LinkedIn headline copy lives in `config/site/person.php` (`linkedin_headline`). Search Console URL-prefix verification: `GOOGLE_SITE_VERIFICATION` in `.env`.

Off-site: Wikidata item exists (`Q139902938`). Next: mark it **different from** the baritone (`Q112904`); set the website field on ORCID, Google Scholar, ResearchGate, GitHub, and LinkedIn; get the GeoHorizons author line to link here; request indexing of `/` and `/about` after deploy. A standalone Wikipedia biography is optional and must meet notability with independent sources.

## Shared catalog

`app/Support/SiteCatalog.php` is the shared read model for posts, case studies, series, person, and sitemap/feed URLs. Machine surfaces (`/api/site.json`, `/llms.txt`, `/api/commands.json`, the sitemap) project from it — do not duplicate lists in those formatters.

The portfolio renders the curated repository descriptions in `config/site/github.php` without a request to GitHub, so availability and ordering do not depend on its API. The existing `GitHubRepository` client remains available for live metadata consumers and caches responses for one hour. To raise its API rate limit:

```env
GITHUB_TOKEN=ghp_xxx
GITHUB_USERNAME=karlhillx
```

Analytics — **Plausible is the primary provider**. Enabling GA4 requires
turning Plausible off (no dual tracking):

```env
PLAUSIBLE_ENABLED=true
PLAUSIBLE_DOMAIN=karlhill.com

# Optional GA4 instead of Plausible:
# PLAUSIBLE_ENABLED=false
# GOOGLE_ANALYTICS_ENABLED=true
# GOOGLE_ANALYTICS_MEASUREMENT_ID=G-EZZNL8KY8P
```

Booking (Calendly or Cal.com) lives beside Contact on desktop and before the form on mobile. DOM and keyboard order follow the mobile layout: introduction, email/scheduling, form, profiles. A native
disclosure keeps the inline scheduler collapsed until requested; `/#book`
opens it directly, including without JavaScript. Footer and resume booking
links use that anchor. The primary navigation always says Contact:

```env
BOOKING_URL=https://calendly.com/karlhill
BOOKING_LABEL="Book a conversation"
```

Optional Cloudflare Turnstile for the contact form (skipped until both keys
are set). The widget script loads on form focus or when the footer is near
the viewport — not on every page's first paint.

```env
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

Production should always set:

```env
APP_URL=https://karlhill.com
APP_DEBUG=false
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="karlhillx@gmail.com"
```

`MAIL_FROM_ADDRESS` is the public address (`person.email`). The onboarding sender only delivers to the Resend account inbox.

## Motion

Motion is progressive and declarative. Entrance animations (`.hero-enter`, `.nav-enter`) and scroll-driven reveals (`view()` timelines, with an IntersectionObserver fallback in `reveal.js`) are the only ambient layers; there is no pointer-following chrome. `prefers-reduced-motion` and Save-Data (`html.save-data`, set in `resources/js/lib/prefs.js`) collapse transitions to their end state in CSS. Styles live in `resources/css/motion.css`.

### CSS layout and budget

`resources/css/app.css` imports tokens → base → layout → components → motion → portfolio. `components.css` is an index over `resources/css/components/*.css`. Prose owns reading styles, syntax owns code highlighting, article owns the TOC, and case-study/on-device own their respective responsive rules. The retired interactive homepage delivery map no longer ships; `config/site/system.php` retains the compatible machine-readable delivery vocabulary. CI runs `scripts/check-bundle-size.sh`, which enforces byte budgets and rejects unreferenced class selectors.

## Optional platform surfaces

Most kill switches default **off** so a fresh deploy stays conservative.
Early Hints, content credentials, compression dictionaries, reporting, and
the flood WebGPU field default **on** (with progressive fallbacks). Pest
enables the rest via `phpunit.xml`. `INTEGRITY_POLICY=auto` promotes to an
enforcing Integrity-Policy header once Vite SRI hashes exist and retained
integrity reports are clean. Turnstile stays off until both keys are set.

```env
WEBMENTION_ENABLED=false
REPORTING_ENABLED=true
INTEGRITY_POLICY=auto      # report-only | enforce | auto
COMPRESSION_DICTIONARY=true
CONTENT_CREDENTIALS=true
WEBGPU_FLOOD=true
EARLY_HINTS=true           # 103 only on FrankenPHP / EARLY_HINTS_FORCE
```

### On-device reader tools

The essay summarizer (Chrome Summarizer API) and "Ask this resume" (Prompt API) are reader tools, not page chrome: they render *after* the essay body / resume document, ship `hidden`, and are revealed by `summarizer.js` / `ask-page.js` only once the browser reports the model as available, downloading, or downloadable. The wrapping `.reader-tools` band collapses via `:has()` while nothing inside is revealed, so browsers without the APIs never see an empty section.

## Edge nginx (production)

The production host runs a single shared nginx container (`karl-nginx-1`) in
front of several sites; its config lives at `/home/karl/data/nginx/default.conf`
on the host, **not** in this repo. `docker/nginx/default.conf` mirrors the
karlhill.com server block (HTTP/2, gzip, immutable `/build/`, 30-day `/img/`)
so local dev behaves the same — when you change one, change the other. Apply on
the host with `docker exec karl-nginx-1 nginx -t && docker exec karl-nginx-1
nginx -s reload`; back up the file first, the directory is root-owned so you
can only overwrite in place.

## CDN (recommended)

Point DNS through **Cloudflare** (or similar) in front of the Docker host.
The app already emits `Cache-Control` + `ETag` on HTML/feeds — a CDN turns
those into cheap global 304s.

Suggested Cloudflare settings:

1. Proxy the apex (`karlhill.com`) orange-cloud.
2. SSL/TLS: Full (strict) with a valid origin cert.
3. Caching: respect origin `Cache-Control` and `CDN-Cache-Control` (do not override HTML to “cache everything”).
4. Optional Cache Rule: cache `/build/*`, `/img/*`, `/fonts/*` as static.
5. Bypass cache for `POST /contact` and `/csrf-token` (already `no-store`).

HTML documents send `public, max-age=300` to the browser and `s-maxage=600` plus `stale-while-revalidate` so a CDN can serve a fresh-enough copy while the origin revalidates on ETag. `CDN-Cache-Control` (RFC 9213) repeats the shared-cache policy for Cloudflare.

No app code changes are required for a basic CDN pass-through.

Web Push subscribe UI appears only when both VAPID keys are set (`php artisan push:vapid`).

## Resume source of truth

- **Canonical HTML:** `/resume` (from `config/site/experience.php` + related
  fragments). `/about` has its own shorter career narrative in
  `config/site/about.php`.
- **Downloadable PDF:** `public/files/Karl-Hill-Resume.pdf` — classic 2-page
  navy-sidebar layout, generated with Playwright (not browser Print).

Regenerate after content changes:

```bash
php artisan resume:pdf
# or: make resume-pdf
```

When experience changes, update these keys (then regenerate the PDF and
spot-check `/resume` + `/about`):

1. `config/site/experience.php` — roles, dates, bullets
2. `config/site/education.php` / `certifications.php` / `stack.php`
3. `config/site/resume.php` — phone, ZIP, tagline, impact, expertise
4. `config/site/person.php` — title, location, availability (short form is
   About, `llms.txt`, and the hire packet — not the homepage or the CV body).
   Music stays on `/about`; do not add `/music`.

## Client staging

Client previews live in `clients/{domain}/` (static `index.html`, or a
Laravel-rendered catalog with `data/config.yaml`) and are served at:

- `/clients` — staging index (noindex)
- `/clients/{domain}/` — the client site

Not linked from the main nav or sitemap. Add a new folder under `clients/` to
stage the next preview. The Dry Standard now lives at
https://drinkdrystandard.com/; `/clients/the-dry-standard/*` permanently
redirects there.

# karlhill.com

Personal site for Karl Hill — Staff Software Engineer. A Laravel 13 + Tailwind v4 portfolio and flat-file blog at [karlhill.com](https://karlhill.com).

## Stack

- **Backend:** Laravel 13 (PHP 8.4+)
- **Frontend:** Tailwind CSS v4, vanilla JS (no SPA framework)
- **Build:** Vite 8 with `laravel-vite-plugin`
- **Fonts:** IBM Plex Sans, Bebas Neue, JetBrains Mono (self-hosted via `@fontsource`)
- **Testing:** Pest 4, Laravel Pint

## Getting Started

Requires PHP 8.4+, Composer, Node 22+, and Python 3 with Pillow for OG/WebP generation.

```bash
composer setup
pip install -r scripts/requirements.txt   # optional locally; required on deploy
php artisan og:generate
php artisan assets:webp   # WebP + AVIF variants
```

This installs PHP and JS deps, copies `.env.example` to `.env`, generates an app key, and builds frontend assets. No database is required — the site uses file cache and flat-file blog posts.

### Local development

```bash
composer dev
```

The site is then available at `http://localhost:8000`.

### Production build

```bash
npm run build
composer test
./vendor/bin/pint --test
# with the app on :8000:
# A11Y_FIXTURES=true php artisan serve --host=127.0.0.1 --port=8000
npm run a11y            # uses system Chrome when available; else installs pa11y’s Chrome
npm run a11y:browsers   # optional: pre-install bundled Chrome for CI/Linux
npm run test:e2e
```

`npm run a11y` runs `scripts/run-pa11y.mjs`, which targets pa11y-ci’s nested Puppeteer (not the top-level package used for resume PDFs) and avoids broken sandbox browser caches.

## Configuration

Site content lives in `config/site.php` (hero, experience, projects, stack, SEO copy, etc.).

GitHub repos on the homepage are fetched server-side and cached for one hour. To raise the API rate limit:

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

Booking (Calendly or Cal.com). Shown as an **inline embed on `/now#book`**,
plus CTAs on the homepage, footer, and mobile menu:

```env
BOOKING_URL=https://calendly.com/karlhill
BOOKING_LABEL="Book a conversation"
```

Optional Cloudflare Turnstile for the contact form (skipped until both keys
are set):

```env
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

Production should always set:

```env
APP_URL=https://karlhill.com
APP_DEBUG=false
```

### CDN (recommended)

Point DNS through **Cloudflare** (or similar) in front of the Docker host.
The app already emits `Cache-Control` + `ETag` on HTML/feeds — a CDN turns
those into cheap global 304s.

Suggested Cloudflare settings:

1. Proxy the apex (`karlhill.com`) orange-cloud.
2. SSL/TLS: Full (strict) with a valid origin cert.
3. Caching: respect origin `Cache-Control` (do not override HTML to “cache everything”).
4. Optional Cache Rule: cache `/build/*`, `/img/*`, `/fonts/*` as static.
5. Bypass cache for `POST /contact` and `/csrf-token` (already `no-store`).

No app code changes are required for a basic CDN pass-through.
### Resume source of truth

- **Canonical HTML:** `/resume` (from `config/site/experience.php` + related
  fragments — same experience data as `/about`).
- **Downloadable PDF:** `public/files/Karl-Hill-Resume.pdf` — classic 2-page
  navy-sidebar layout, generated with Puppeteer (not browser Print).

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
4. `config/site/person.php` — title, location, availability (availability is
   shown on home + `/now`, not repeated on the CV body)

### Client staging

Static client previews live in `clients/{domain}/` (must include `index.html`)
and are served at:

- `/clients` — staging index (noindex)
- `/clients/{domain}/` — the client site

Not linked from the main nav or sitemap. Add a new folder under `clients/` to
stage the next preview.

**Octaves of Love** (sound baths) lives at
`/clients/keithhillmusic.com/octaves-of-love/` (production path:
`keithhillmusic.com/octaves-of-love/`). Point `octavesoflove.com` DNS forward
at that URL. Email alias setup: `clients/EMAIL-SETUP.md`.

## Project Layout

```
app/Http/Controllers/HomeController.php      # homepage
app/Http/Controllers/BlogController.php    # /blog index + /blog/{slug}
app/Http/Controllers/ClientSiteController.php # /clients staging previews
app/Http/Controllers/NowController.php       # /now (current focus)
app/Http/Controllers/ResumeController.php    # /resume (live HTML CV)
app/Http/Controllers/FeedController.php      # /feed.xml (Atom) + /feed.json
app/Http/Controllers/SitemapController.php  # /sitemap.xml
app/Http/Controllers/LlmsTxtController.php  # /llms.txt + /llms-full.txt
app/Console/Commands/GenerateOgImages.php   # php artisan og:generate
app/Console/Commands/GenerateWebpAssets.php   # php artisan assets:webp (WebP + AVIF + LQIP)
app/Console/Commands/SyndicatePost.php        # php artisan post:syndicate
app/Support/BlogPost.php                      # blog post value object
app/Support/BlogPostRepository.php            # markdown loader + cache
app/Support/BlogSeries.php                    # ordered essay series
app/Support/GitHubRepository.php              # server-side GitHub API client
app/Support/PageMeta.php                      # SEO meta for all pages
app/Support/HomeStructuredData.php            # homepage JSON-LD
clients/{domain}/                             # client staging sites (static HTML)
config/site.php                               # aggregator (env flags, sameAs)
config/site/*.php                             # content fragments (experience, projects, now, …)
resources/js/app.js                           # modular UI entry
resources/js/modules/*                        # view transitions, palette, contact, …
resources/posts/*.md                          # blog posts (YAML frontmatter)
resources/views/home/index.blade.php          # homepage shell
resources/views/home/partials/*               # homepage sections
resources/views/now/index.blade.php           # /now page
resources/views/components/site/*             # nav, footer, cards, series, images
resources/views/layouts/site.blade.php        # shared layout
resources/css/app.css                         # CSS entry (imports tokens/base/layout/…)
app/Console/Commands/PublishPost.php          # php artisan post:publish {slug}
public/sw.js                                  # offline service worker
public/offline.html                           # offline fallback
scripts/deploy.sh                             # production deploy entrypoint
scripts/generate-og-images.py                 # OG card generator
scripts/generate-webp.py                      # batch WebP / AVIF / LQIP
routes/web.php                                # all routes
```

## Writing

The blog at `/blog` is a flat-file Markdown system — no DB, no admin UI. To add a post:

1. Create `resources/posts/YYYY-MM-DD-{slug}.md` with YAML frontmatter:

   ```markdown
   ---
   title: "Your Post Title"
   slug: your-post-slug
   date: 2026-05-15
   updated: 2026-06-01   # optional; defaults to date
   excerpt: "One- or two-sentence summary used in the listing, OG description, and feed."
   tags: [engineering, leadership]
   hero_image: img/blog/your-post-slug.jpg
   ---

   Body in standard markdown. GFM tables, fenced code, blockquotes all supported.
   ```

2. Drop a hero image at `public/img/blog/{slug}.jpg`.

3. Run the one-shot publish pipeline (WebP/AVIF/LQIP + OG card):

   ```bash
   php artisan post:publish your-post-slug
   # or: make publish SLUG=your-post-slug
   # optional syndication: make publish SLUG=your-post-slug SYNDICATE=1
   ```

4. Done. The post is live at `/blog/{slug}`, listed on `/blog`, in `/feed.xml`, and in `/sitemap.xml`.

To add a post to an essay series, append its slug under `config/site.php` → `series`.

### Syndicating to dev.to

Posts are canonical on karlhill.com. Cross-post new essays so the EM craft series is discoverable:

```bash
php artisan post:publish staff-to-em-first-90-days --syndicate
# or syndicate alone:
php artisan post:syndicate staff-to-em-first-90-days --dry-run
php artisan post:syndicate staff-to-em-first-90-days
```

Set `DEVTO_API_KEY` in `.env` (generate at https://dev.to/settings/extensions).

## Deployment

CI runs on every push to `main` (tests, build, Pint, bundle budget, a11y). Deploy is triggered manually or automatically after a green CI run on `main`.

When you change the HTML shell, offline fallback, or the precache list in
`public/sw.js`, bump the `CACHE` version string (e.g. `karlhill-offline-v4` →
`v5`) so clients drop stale caches on activate.

On the server:

```bash
bash scripts/deploy.sh
```

Or use the GitHub Actions **Deploy** workflow. Add these **environment secrets** under Settings → Environments → production:

| Secret | Value |
|--------|-------|
| `DEPLOY_HOST` | `karlhill.com` |
| `DEPLOY_USER` | `karl` |
| `DEPLOY_SSH_KEY` | private SSH key with access to the server |
| `DEPLOY_CONTAINER` | `karl-karlhill-1` (optional) |

Deploy streams code into the Docker app container at `/var/www/html` (not the host path).

SSH shortcut:

```bash
make ssh   # uses SSH_USER and PRODUCTION from .env
```

## License

Site content is © Karl Hill. The Laravel framework is MIT-licensed.

# karlhill.com

Personal site for Karl Hill — Staff Software Engineer. A Laravel 13 + Tailwind v4 portfolio and flat-file blog at [karlhill.com](https://karlhill.com).

## Stack

- **Backend:** Laravel 13 (PHP 8.4+)
- **Frontend:** Tailwind CSS v4, vanilla JS (no SPA framework)
- **Build:** Vite 8 with `laravel-vite-plugin`
- **Fonts:** Inter Variable, Bebas Neue, JetBrains Mono (self-hosted via `@fontsource`)
- **Testing:** PHPUnit 12, Laravel Pint

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
```

## Configuration

Site content lives in `config/site.php` (hero, experience, projects, stack, SEO copy, etc.).

GitHub repos on the homepage are fetched server-side and cached for one hour. To raise the API rate limit:

```env
GITHUB_TOKEN=ghp_xxx
GITHUB_USERNAME=karlhillx
```

Analytics — **GA4 is the primary provider**. Enabling Plausible turns GA off
(no dual tracking):

```env
GOOGLE_ANALYTICS_ENABLED=true
GOOGLE_ANALYTICS_MEASUREMENT_ID=G-EZZNL8KY8P

# Set true to use Plausible instead of GA4
PLAUSIBLE_ENABLED=false
PLAUSIBLE_DOMAIN=karlhill.com
```

Booking CTA (Calendly). Shown on `/now`, homepage availability, footer, and
the mobile menu:

```env
BOOKING_URL=https://calendly.com/karlhill
BOOKING_LABEL="Book a conversation"
```

Production should always set:

```env
APP_URL=https://karlhill.com
APP_DEBUG=false
```

### Resume source of truth

- **Canonical:** `/resume` (HTML generated from `config/site/experience.php` +
  related fragments — same experience data as `/about`).
- **ATS download:** `public/files/karlhill-resume.pdf` linked from the footer
  and resume page. When PDF and HTML disagree, **HTML wins**.

When you update the PDF, sync these keys (then spot-check `/resume` + `/about`):

1. `config/site/experience.php` — roles, dates, bullets
2. `config/site/education.php` / `certifications.php` / `stack.php`
3. `config/site/person.php` — title, location, availability (availability is
   shown on home + `/now`, not repeated on the CV body)
4. Replace `public/files/karlhill-resume.pdf`

### Client staging

Static client previews live in `clients/{domain}/` (must include `index.html`)
and are served at:

- `/clients` — staging index (noindex)
- `/clients/{domain}/` — the client site

Not linked from the main nav or sitemap. Add a new folder under `clients/` to
stage the next preview.

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
resources/css/app.css                         # design tokens, animations
public/sw.js                                  # offline service worker
public/offline.html                           # offline fallback
scripts/deploy.sh                             # production deploy entrypoint
scripts/generate-og-images.py                 # OG card generator
scripts/generate-webp.py                      # batch WebP / AVIF / LQIP
public/files/karlhill-resume.pdf              # resume linked from footer
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

2. Drop a hero image at `public/img/blog/{slug}.jpg`. Run `php artisan assets:webp` to generate WebP, AVIF, and LQIP variants under `public/img/webp/`, `public/img/avif/`, and `public/img/lqip/`.

3. Generate a social card: `php artisan og:generate your-post-slug`

4. Done. The post is live at `/blog/{slug}`, listed on `/blog`, in `/feed.xml`, and in `/sitemap.xml`.

To add a post to an essay series, append its slug under `config/site.php` → `series`.

### Syndicating to dev.to

Posts are canonical on karlhill.com. Cross-post new essays so the EM craft series is discoverable:

```bash
php artisan post:syndicate staff-to-em-first-90-days --dry-run
php artisan post:syndicate staff-to-em-first-90-days
php artisan post:syndicate saying-no-roadmap-pressure
php artisan post:syndicate performance-feedback-without-politics
```

Set `DEVTO_API_KEY` in `.env` (generate at https://dev.to/settings/extensions).

## Deployment

CI runs on every push to `main` (tests, build, Pint). Deploy is triggered manually or automatically after a green CI run on `main`.

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

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

Optional analytics (pick one — needed to measure the EM funnel):

```env
# Privacy-friendly (recommended)
PLAUSIBLE_ENABLED=true
PLAUSIBLE_DOMAIN=karlhill.com

# Or GA4
GOOGLE_ANALYTICS_ENABLED=true
GOOGLE_ANALYTICS_MEASUREMENT_ID=G-XXXXXXXXXX
```

Optional booking CTA (Cal.com / Calendly). When set, CTAs appear on `/now`,
the homepage availability line, and the contact footer:

```env
BOOKING_URL=https://cal.com/your-username
BOOKING_LABEL="Book a conversation"
```

Production should always set:

```env
APP_URL=https://karlhill.com
APP_DEBUG=false
```

Keep `public/files/karlhill-resume.pdf` aligned with `/about` and `/now`
(Jacobs role + Engineering Manager trajectory).

## Project Layout

```
app/Http/Controllers/HomeController.php      # homepage
app/Http/Controllers/BlogController.php    # /blog index + /blog/{slug}
app/Http/Controllers/NowController.php       # /now (current focus)
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
config/site.php                               # site content, series, /now copy
resources/posts/*.md                          # blog posts (YAML frontmatter)
resources/views/home/index.blade.php          # homepage shell
resources/views/home/partials/*               # homepage sections
resources/views/now/index.blade.php           # /now page
resources/views/components/site/*             # nav, footer, cards, series, images
resources/views/layouts/site.blade.php        # shared layout
resources/css/app.css                         # design tokens, animations
resources/js/app.js                           # scroll-spy, command palette, SW register
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

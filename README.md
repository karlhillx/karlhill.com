# karlhill.com

Personal site for Karl Hill — Staff Software Engineer. A single-page Laravel + Tailwind v4 site that pulls live data from the GitHub API and lives at [karlhill.com](https://karlhill.com).

## Stack

- **Backend:** Laravel 13 (PHP 8.3+)
- **Frontend:** Tailwind CSS v4, vanilla JS (no SPA framework)
- **Build:** Vite 8 with `laravel-vite-plugin`
- **Fonts:** Inter, Bebas Neue, JetBrains Mono (self-hosted via `@fontsource`)
- **Testing:** PHPUnit 12

## Getting Started

Requires PHP 8.3+, Composer, and Node 20+.

```bash
composer setup
```

This installs PHP and JS deps, copies `.env.example` to `.env`, generates an app key, runs migrations, and builds frontend assets.

### Local development

Run the app, queue worker, log viewer, and Vite dev server concurrently:

```bash
composer dev
```

The site is then available at `http://localhost:8000`.

### Production build

```bash
npm run build
```

## Configuration

The GitHub repo widget on `/#open-source` calls `/api/github/repos`, which proxies `api.github.com`. To raise the rate limit, set a personal access token in `.env`:

```env
GITHUB_TOKEN=ghp_xxx
GITHUB_USERNAME=karlhillx
```

The endpoint falls back to anonymous requests if the token is missing or rejected, and the frontend falls back to a direct `api.github.com` call if the backend returns nothing.

## Project Layout

```
app/Http/Controllers/GitHubController.php   # /api/github/repos proxy
app/Http/Controllers/BlogController.php     # /blog index + /blog/{slug} post pages
app/Http/Controllers/FeedController.php     # /feed.xml (Atom)
app/Http/Controllers/SitemapController.php  # /sitemap.xml (dynamic, includes posts)
app/Console/Commands/SyndicatePost.php      # `php artisan post:syndicate` -> dev.to
app/Support/BlogPost.php                    # value object for a parsed post
app/Support/BlogPostRepository.php          # markdown loader + cache
resources/posts/*.md                        # blog posts (YAML frontmatter + body)
resources/views/welcome.blade.php           # single-page homepage
resources/views/layouts/site.blade.php      # shared layout (head/nav/footer) for blog
resources/views/blog/{index,show}.blade.php # blog listing + single-post views
resources/css/app.css                       # design tokens, animations, .prose-karl
resources/js/app.js                         # scroll-spy, command palette, share/copy
public/files/karlhill-resume.pdf            # resume linked from the site footer
public/img/blog/                            # post hero images (jpg + webp variants)
routes/web.php                              # all routes
```

## Writing

The blog at `/blog` is a flat-file Markdown system — no DB, no admin UI. To add a post:

1. Create `resources/posts/YYYY-MM-DD-{slug}.md` with YAML frontmatter:

   ```markdown
   ---
   title: "Your Post Title"
   slug: your-post-slug
   date: 2026-05-15
   excerpt: "One- or two-sentence summary used in the listing, OG description, and feed."
   tags: [engineering, leadership]
   hero_image: img/blog/your-post-slug.jpg
   ---

   Body in standard markdown. GFM tables, fenced code, blockquotes all supported.
   ```

2. Drop a hero image at `public/img/blog/{slug}.jpg`. A WebP variant at
   `public/img/webp/blog/{slug}.webp` is picked up automatically by the `<picture>` element
   if present (use `cwebp -q 78 in.jpg -o out.webp`).

3. Done. The post is live at `/blog/{slug}`, listed on `/blog`, in `/feed.xml`, and in
   `/sitemap.xml`. The post-list cache is keyed off the directory mtime so changes appear
   immediately in dev.

### Syndicating to dev.to

Posts are canonical on karlhill.com. To cross-post to dev.to with a `canonical_url`
pointing back here:

```bash
# 1. Set DEVTO_API_KEY in .env (generate at https://dev.to/settings/extensions)
# 2. Dry-run to inspect the payload
php artisan post:syndicate your-post-slug --dry-run

# 3. Publish
php artisan post:syndicate your-post-slug
```

The first run `POST`s to `https://dev.to/api/articles` and writes the returned `id` back
into the post's frontmatter as `dev_to_id: <n>`. Subsequent runs detect the id and
`PUT` updates instead of creating duplicates.

### LinkedIn

LinkedIn does not expose a personal-account article API. Each post page includes a
"Share on LinkedIn" button that opens LinkedIn's prefilled share dialog (no auth
required). For full Pulse-style article reach, paste manually into LinkedIn's article
editor with an "Originally published on karlhill.com" link at the top.

## Deployment

The included `Makefile` opens an SSH session to the host configured in `.env`:

```bash
make ssh   # uses SSH_USER and PRODUCTION from .env
```

Build assets locally with `npm run build` before deploying, or run the build on the server.

## License

The site content is © Karl Hill. The underlying Laravel framework is MIT-licensed.

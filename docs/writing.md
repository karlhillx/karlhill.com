# Writing and publishing

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

To add a post to an essay series, append its slug under `config/site/series.php`.

OG cards are static JPGs from `php artisan og:generate` (`public/img/og/blog/{slug}.jpg`, else `/img/og-home.jpg`) — there is no runtime PNG route. The command palette fetches `/api/commands.json` on idle / first open instead of inlining the index in every HTML page.

## Syndicating to dev.to

Posts are canonical on karlhill.com. Cross-post new essays so the EM craft series is discoverable:

```bash
php artisan post:publish staff-to-em-first-90-days --syndicate
# or syndicate alone:
php artisan post:syndicate staff-to-em-first-90-days --dry-run
php artisan post:syndicate staff-to-em-first-90-days
```

Set `DEVTO_API_KEY` in `.env` (generate at https://dev.to/settings/extensions).

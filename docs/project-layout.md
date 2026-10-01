# Project layout

```
app/Http/Controllers/HomeController.php       # homepage
app/Http/Controllers/BlogController.php       # /blog index + /blog/{slug}
app/Http/Controllers/ClientSiteController.php # /clients staging previews
app/Http/Controllers/AboutController.php      # /about (career, focus, opportunities)
app/Http/Controllers/ResumeController.php     # /resume (live HTML CV)
app/Http/Controllers/FeedController.php       # /feed.xml (Atom) + /feed.json
app/Http/Controllers/SitemapController.php    # /sitemap.xml
app/Http/Controllers/LlmsTxtController.php    # /llms.txt + /llms-full.txt
app/Http/Controllers/AgentPacketController.php # /api/site.json + /api/commands.json
app/Console/Commands/GenerateOgImages.php     # php artisan og:generate (static JPG cards)
app/Console/Commands/GenerateWebpAssets.php   # php artisan assets:webp (WebP + AVIF + LQIP)
app/Console/Commands/SyndicatePost.php        # php artisan post:syndicate
app/Support/SiteCatalog.php                   # shared catalog (posts, studies, person, URLs)
app/Support/SiteFeatures.php                  # optional platform kill switches
app/Support/PageFeatures.php                  # per-route JS chunks (pointer, reveal, …)
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
resources/js/lib/prefs.js                     # reduced-motion / Save-Data / pointer gates
resources/js/modules/*                        # view transitions, palette, contact, …
resources/posts/*.md                          # blog posts (YAML frontmatter)
resources/views/home/index.blade.php          # homepage shell
resources/views/home/partials/*               # homepage sections
resources/views/about/index.blade.php         # consolidated professional background
resources/views/components/site/*             # nav, footer, cards, series, images
resources/views/layouts/site.blade.php        # shared layout
resources/css/app.css                         # CSS entry (imports tokens/base/layout/…)
resources/css/components/*.css                # per-surface component styles
scripts/check-dead-css.py                     # CI: fail on unreferenced class selectors
resources/css/motion.css                      # entrance, scroll-driven, and idle motion
app/Console/Commands/PublishPost.php          # php artisan post:publish {slug}
public/img/og/                                # static OG cards (og:generate)
public/sw.js                                  # offline service worker
public/offline.html                           # offline fallback
scripts/deploy.sh                             # production deploy entrypoint
scripts/generate-og-images.py                 # OG card generator
scripts/generate-webp.py                      # batch WebP / AVIF / LQIP
routes/web.php                                # HTML and form routes
routes/machine.php                            # cacheable machine GETs (no session)
```

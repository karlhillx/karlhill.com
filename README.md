```text
╔═══════════════════════════════════════════════════════════════════════════╗
║                                                                           ║
║       ██╗  ██╗ █████╗ ██████╗ ██╗       ██╗  ██╗██╗██╗     ██╗            ║
║       ██║ ██╔╝██╔══██╗██╔══██╗██║       ██║  ██║██║██║     ██║            ║
║       █████╔╝ ███████║██████╔╝██║       ███████║██║██║     ██║            ║
║       ██╔═██╗ ██╔══██║██╔══██╗██║       ██╔══██║██║██║     ██║            ║
║       ██║  ██╗██║  ██║██║  ██║███████╗  ██║  ██║██║███████╗███████╗       ║
║       ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚══════╝  ╚═╝  ╚═╝╚═╝╚══════╝╚══════╝       ║
║                                                                           ║
║              Personal hire site · Aerospace mission software              ║
║                          Laravel · karlhill.com                           ║
║                                                                           ║
║       Staff Aerospace Software Engineer · Jacobs National Security        ║
║          NASA Goddard 2017–2025 · GeoHorizons co-author                   ║
║                                                                           ║
╚═══════════════════════════════════════════════════════════════════════════╝
```

# karlhill.com

Personal site for Karl Hill — Staff Aerospace Software Engineer (Washington, DC; NASA · Jacobs). A Laravel 13 + Tailwind v4 portfolio and flat-file blog at [karlhill.com](https://karlhill.com).

## Stack

- **Backend:** Laravel 13 (PHP 8.5)
- **Frontend:** Tailwind CSS v4, vanilla JS (no SPA framework), CSS scroll/view timelines + gated idle motion
- **Build:** Vite 8 with `laravel-vite-plugin`
- **Fonts:** Barlow Semi Condensed (UI/body), Bebas Neue (display), JetBrains Mono (self-hosted via `@fontsource`)
- **Testing:** Pest 4, Laravel Pint

## Getting Started

Requires PHP 8.5+, Composer, and Node 22+.

```bash
composer setup
php artisan og:generate     # needs Python 3 + Pillow
php artisan assets:webp     # WebP + AVIF variants; same
```

Pillow is optional for local `composer dev`. Deploy and `og:generate` / `assets:webp` need it (`pip install -r scripts/requirements.txt`, or `python3-pil` in Docker).

This installs PHP and JS deps, copies `.env.example` to `.env`, generates an app key, and builds frontend assets. No database is required — the site uses file cache and flat-file blog posts.

### Local development

```bash
composer dev
```

That runs Laravel’s `php artisan dev` (server, log tail, Vite — no queue). The site is then available at `http://localhost:8000`.

### Production build

```bash
npm run build
composer test
./vendor/bin/pint --test
# with the app on :8000:
# A11Y_FIXTURES=true php artisan serve --host=127.0.0.1 --port=8000
npm run a11y            # axe WCAG2 A/AA over .pa11yci.json URLs (Playwright)
npm run a11y:browsers   # optional: install Playwright Chromium for CI/Linux
npm run test:e2e
```

`npm run a11y` runs `scripts/run-a11y.mjs` (Playwright + axe). Resume PDFs use the same Playwright stack — Puppeteer/pa11y were removed to clear Dependabot’s unpatched `extract-zip` advisory.

## Documentation

Deeper material lives in [`docs/`](docs/):

- [Configuration](docs/configuration.md) — `config/site/*.php` fragments, portfolio architecture, shared catalog, motion gates, optional platform surfaces, nginx/CDN, resume source of truth, client staging.
- [Writing and publishing](docs/writing.md) — post frontmatter, series, OG cards, `post:publish`, dev.to syndication.
- [Deployment and monitoring](docs/deployment.md) — `scripts/deploy.sh`, uptime checks, reporting.
- [Project layout](docs/project-layout.md) — annotated tree of controllers, support classes, views, and scripts.

Content lives as Markdown with YAML frontmatter: blog posts in `resources/posts/`, case-study narratives in `resources/work/`. Card metadata for projects stays in `config/site/projects.php`.

## License

Site content is © Karl Hill. The Laravel framework is MIT-licensed.

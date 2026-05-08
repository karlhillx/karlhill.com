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
resources/views/welcome.blade.php           # the entire single-page site
resources/css/app.css                       # design tokens, animations, components
resources/js/app.js                         # scroll-spy, command palette, repo loader
public/files/karlhill-resume.pdf            # resume linked from the site footer
public/sitemap.xml, robots.txt              # SEO
routes/web.php                              # `/` + the GitHub proxy route
```

## Deployment

The included `Makefile` opens an SSH session to the host configured in `.env`:

```bash
make ssh   # uses SSH_USER and PRODUCTION from .env
```

Build assets locally with `npm run build` before deploying, or run the build on the server.

## License

The site content is © Karl Hill. The underlying Laravel framework is MIT-licensed.

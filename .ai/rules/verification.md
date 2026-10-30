---
paths:
  - tests/**/*
  - .github/workflows/**/*.yml
  - package.json
  - composer.json
  - playwright.config.js
---

# Verification

Report only commands you ran and the result they printed. Do not invent pass counts.

| Check | Command |
| --- | --- |
| PHP tests | `php artisan test` or a path filter |
| PHP style | `./vendor/bin/pint --test` |
| JS lint | `npm run lint` |
| CSS and JS format | `npm run format:check` |
| Production CSS | `npm run build` |
| Bundle budget | `bash scripts/check-bundle-size.sh` |
| Dead CSS selectors | `python3 scripts/check-dead-css.py` |
| Accessibility | `npm run a11y` with the app on port 8000 |
| Browser | `npm run test:e2e` |

Playwright screenshot checks compare captures inside one run. Do not commit golden images.

CI on a push to `main` is syntax-only and then deploys. The Pest, Pint, build, and browser jobs run when CI is dispatched by hand. A green local test run is not a production deploy.

Agents do not dispatch Deploy, push, or merge unless the human asks in the current request.

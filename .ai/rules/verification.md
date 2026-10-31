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

CI on a push or pull request to `main` runs syntax, then Pest, Pint, build, a11y, and Playwright. A successful push-triggered CI run can start production deploy for that commit. Dispatching the Deploy workflow by hand does not re-run those checks. A green local test run is not a production deploy. YAML does not create GitHub branch-protection rules.

Agents do not dispatch Deploy, push, or merge unless the human asks in the current request.

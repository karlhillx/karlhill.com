---
paths:
  - app/**/*.php
  - routes/**/*.php
  - config/**/*.php
  - resources/views/**/*.blade.php
  - resources/posts/**/*.md
  - resources/work/**/*.md
---

# Application architecture

Laravel 13, PHP 8.5, Blade, Tailwind v4, Vite 8. No Inertia, Livewire, or SPA. File cache and flat-file content. Do not add a database for the public site.

- Controllers stay thin. Shared reads go through `App\Support` (`ProjectCatalog`, `SiteCatalog`, `PageMeta`, `PortfolioContent`).
- Page copy that is a fact lives in `config/site/`. Case-study prose lives in `resources/work/*.md`. Essays live in `resources/posts/*.md`.
- Do not duplicate a project summary in a new wording on another page. Link to the case study.
- Routes stay in `routes/web.php` and `routes/machine.php`. Keep existing redirects.
- CSS is imported from `resources/css/app.css`. Do not add a new stylesheet unless an existing file is the wrong owner.
- JavaScript is progressive enhancement in `resources/js/modules/`. The site must work with JS disabled for reading and navigation.
- Contact is email. Do not restore the contact form or Calendly.
- Oswald and Lato are for the resume PDF only.

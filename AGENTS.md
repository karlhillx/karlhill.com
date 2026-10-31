# Agent contract

karlhill.com is a Laravel 13 portfolio. Blade, Tailwind v4, and Vite. No SPA framework. No database.

Read this file first. Then open [`.ai/rules/index.md`](.ai/rules/index.md) and the rule whose paths match the files you will edit. Skills in [`.ai/skills/`](.ai/skills/) are workflows. Read a skill only when its description matches the task. Files under `.ai/` are not loaded automatically.

## Do not

- Push, merge to `main`, or run the Deploy workflow unless the human explicitly asks. A push to `main` starts syntax CI and then production deploy.
- Invent clearance, unpublished program names, internal topology, audience numbers, or test results.
- Link `github.com/karlhillx/drinkdrystandard.com`. The live site is https://drinkdrystandard.com.
- Add Cursor commit trailers (`Co-authored-by: Cursor`, `Made-with: Cursor`).
- Commit unless the human asks. Do not commit `.env` or credentials.

## Change shape

- Small, reviewable diffs. Match the surrounding code.
- Public numbers come from `config/site/facts.php` and the project file that already states them. Keep the qualifier that is already next to the number.
- Scale in prose is the facts file: roughly 12 repositories, four environments, about 6 engineers.
- Dark is the default theme. Pathway Extreme at 83% width is the UI face: Light (300) for the name and page titles, Regular (400) for text, Medium (500) for navigation and section headings. JetBrains Mono is for code.

## Where things live

| Concern | Location |
| --- | --- |
| Rules index | [`.ai/rules/index.md`](.ai/rules/index.md) |
| Why this exists | [`.ai/README.md`](.ai/README.md) |
| Public explanation | [`docs/ai-assisted-development.md`](docs/ai-assisted-development.md) |
| Cursor entry | [`.cursor/rules/project.mdc`](.cursor/rules/project.mdc) |
| Content | `config/site/*.php`, `resources/posts/`, `resources/work/` |
| CSS | `resources/css/app.css` import order |

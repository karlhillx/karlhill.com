---
paths:
  - resources/css/**/*.css
  - resources/views/**/*.blade.php
  - resources/js/**/*.js
---

# Design system

One voice. Pathway Extreme Variable for UI and headings, with `font-stretch: 83%` on `html`. Light (300) is for the name, the homepage statement, and page titles. Regular (400) is for body text. Medium (500) is for navigation, buttons, and section headings. Do not set Light on paragraphs. JetBrains Mono is for code and short technical labels. Both are self-hosted from Fontsource. Do not add another UI typeface. Do not use negative tracking; it was tuned for Albert Sans and pinches this width.

## Tokens

`resources/css/tokens.css` owns color, type size, line-height, tracking, and spacing. `html { font-size: 106.25%; }` is the 17px root those tokens were set against. Do not reset it without retuning rem spacing.

Prefer the tokens that already exist in `tokens.css`:

- `--tracking-sans` on body. Display sizes may use `--tracking-display` or `--tracking-display-wide`. Do not put display tracking on paragraphs.
- `--leading-body` and `--leading-prose` for reading text. `--leading-display` for large headings.
- `--tracking-mono` for short uppercase labels. Do not open them past that token without a measured reason.
- `--space-*` for section and card padding. One-off rem values need a comment.

`font-kerning: normal` stays on. Do not set `font-kerning: none`.

## Cascade

`app.css` imports tokens, base, layout, components, motion, portfolio, then `mission.css`. `mission.css` is unlayered and last, so it beats earlier rules and Tailwind layers. Use it for page treatments (hero, footer, study figures). Put shared type in `base.css` and tokens, not a new override file.

## Color

Semantic tokens (`--bg`, `--text`, `--muted`, `--border`, `--accent`) are the color API. `@theme` maps several Tailwind `neutral-*` utilities onto those tokens because older templates used them as roles. New CSS should use the semantic variables. Do not introduce a second palette in a single change.

Dark is the default (`color-scheme` and the pre-paint script). Check light mode when a surface color changes.

## UI

- Nav, buttons, and footer stay compact. Do not add a booking widget.
- Screenshots use real images and a short caption. Do not fake terminal output.
- Homepage order is work, then independent tools, then how I work, then notes, then contact.

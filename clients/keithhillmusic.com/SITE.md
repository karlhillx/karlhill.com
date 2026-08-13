# KeithHillMusic.com — site map

Static multi-page bio/info site staged at `/clients/keithhillmusic.com/`.

## Pages (nav order)

| Path | Purpose |
|------|---------|
| `/` | About Keith (home) |
| `/live-music/` | Maha messaging + 2026 shows + performance media |
| `/soundbaths/` | Octaves of Love messaging + flute featured video + reviews |
| `/jeep-tours/` | Sedona tours copy + reviews + gallery |
| `/hospitality-retreats/` | Partnership / résumé-style narrative |
| `/contact/` | Phone + email only (no form) |
| `/octaves-of-love/` | Separate Octaves of Love landing (kept as its own product page) |

## Source of truth for copy

Editable markdown lives in `content/` (copied from the Keith Website Folder pack). HTML pages are the published surface — update both when Keith revises copy.

## Media layout

```
assets/
  about/           about-keith-01.jpg
  live-music/      photos
  soundbath/       photos
  jeep/            photos
  hospitality/     photos
  videos/          web MP4 + poster JPG (never serve raw camera .mov)
```

## Shows

Edit `shows.js` — `window.KEITH_SHOWS` array. Past/Upcoming split is automatic by date.

## Contact (site-wide)

- Phone: `830-308-8444` (`tel:+18303088444`)
- Email: `khillcorp@gmail.com`
- Do **not** use `615-480-2475` or domain-based booking addresses until domains work.

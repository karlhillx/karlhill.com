# Cursor Instructions — Keith's Website

This file is the entry point for continuing to build Keith's site in Cursor. Read this first, then pull content from the `Content/` folder and media from `Images/` / `Videos/`.

## What this site is

A bio/info site for Keith — "everything Keith" — covering his offerings (Live Music, Sound Baths, Jeep Tours, Hospitality & Retreat Partnerships) plus an About page that doubles as his resume/bio. It is **not** a booking or e-commerce site. Every page should ultimately funnel visitors to contact Keith directly by email or phone for availability, pricing, and details — see `Content/06-contact.md`.

## Folder structure (reorganized from the original email dump)

```
Keith Website Folder/
├── Images/
│   ├── About-Keith/
│   ├── Live-Music/
│   ├── SoundBath/
│   ├── Jeep-Tours/
│   └── Hospitality-Retreats/
├── Videos/
│   ├── Live-Music/
│   └── SoundBath/
├── Content/                  ← page copy, ready to drop into the site, one .md file per page
│   ├── 01-about-keith.md
│   ├── 02-live-music.md
│   ├── 03-soundbaths.md
│   ├── 04-jeep-tours.md
│   ├── 05-hospitality-retreats.md
│   └── 06-contact.md
└── Original-Source-Files/    ← untouched originals (.docx/.pages/.heic) + their converted-to-markdown versions, kept as backup/reference only. Not meant to be pulled into the site directly — use Content/ instead.
```

Each `Content/*.md` file has YAML front matter (`page`, `nav_order`, `slug`, `images`, `videos`) telling you the intended nav position and where its media lives. Each file also ends with a "Media" list (exact filenames to use) and a "Notes for Cursor" section with page-specific instructions — read those, they matter.

## Page order (per Keith)

1. About Keith
2. Live Music
3. SoundBath
4. Jeep Tours
5. Hospitality / Retreats
6. Contact (footer + dedicated page/section)

## Global changes to make across the site

1. **Remove dead functionality.** Any existing nav links, buttons, or features on the current site that don't go anywhere (placeholder booking flows, broken links, etc.) should be removed rather than left as dead ends.
2. **Simplify contact everywhere to just email + phone.** Keith's domains aren't functional right now, so drop any contact form, scheduling widget, or third-party booking integration. Use only:
   - Phone (Google Voice): **830-308-8444**
   - Email: **khillcorp@gmail.com**
   One of the original source docs (Hospitality page) had an old phone number (615-480-2475) baked into its "Let's Connect" section — that number has been removed from `Content/05-hospitality-retreats.md` and should not appear anywhere on the site. See `Content/06-contact.md` for full detail.
3. **Live Music and SoundBath pages already have messaging Keith worked on previously with Maha.** Keep that existing copy — the `02-live-music.md` and `03-soundbaths.md` files contain *additional* content to merge in (real show dates, reviews, the main video), not full replacements.
4. **SoundBath page:** set `Videos/SoundBath/soundbath-flute-main.mov` (the flute video) as the **main/featured video** on that page.
5. **Live Music page:** replace any placeholder/sample show dates with the real 2026 schedule in `Content/02-live-music.md` (organize as a "2026" section split into Past / Upcoming; build it so Keith can easily add future dates).

## Media conversion notes

- **HEIC → JPG:** The original `Screenshot 2026-08-11 at 11.04.16 AM.heic` (Live Music folder) has been converted to `Images/Live-Music/live-music-screenshot-01.jpg`. HEIC isn't reliably readable by browsers or by Cursor's tooling, so this JPG is the one to use.
- **.docx / .pages → Markdown:** All Word (.docx) and Apple Pages (.pages) documents have been converted to clean Markdown in `Content/`. `.pages` files are a proprietary Apple bundle format that isn't readable in a standard dev environment — the originals are preserved in `Original-Source-Files/` along with a straight text-conversion of each (`*-converted.md`) in case you need to double check anything against the source.
- **Video files are large, unprocessed camera-roll exports** (some 300–560MB). Before publishing, compress/transcode them to web-friendly MP4 (H.264, ~1080p, reasonable bitrate) and generate poster/thumbnail frames — don't serve the raw files directly on the live site. Flag to Keith if compression visibly reduces quality on the featured flute video, since he mentioned quality was a concern.

## File naming reference (original → renamed)

**About Keith:** `715360731_..._n.jpg` → `about-keith-01.jpg`

**Jeep Tours:** `699559086_....jpg` → `jeep-01.jpg`, `749354983_....jpg` → `jeep-02.jpg`, `IMG_4018.JPG` → `jeep-03.jpg`, `IMG_6077 2.JPG` → `jeep-04.jpg`

**Hospitality/Retreats:** `IMG_4965 2.JPG` → `hospitality-retreats-01.jpg`

**Live Music:** `Keith Music Photo.jpg` → `live-music-photo-01.jpg`, `Facetune_....jpg` → `live-music-facetune-01.jpg`, `Screenshot....heic` → `live-music-screenshot-01.jpg`, `Bring it on Home.mp4` → `live-music-bring-it-on-home.mp4`, `Keith Music Promo.mov` → `live-music-promo.mov`, `Saloon perf.mov` → `live-music-saloon-performance.mov`, `Videoshop_....MOV` → `live-music-videoshop-clip.mov`

**SoundBath:** `IMG_2107.jpeg` → `soundbath-01.jpeg`, `IMG_3039.JPG` → `soundbath-02.jpg`, `IMG_3043.JPG` → `soundbath-03.jpg`, `IMG_4373.JPG` → `soundbath-04.jpg`, `IMG_6101.jpg` → `soundbath-05.jpg`, `IMG_6104.JPG` → `soundbath-06.jpg`, `Soundbath Intro.MOV` → `soundbath-flute-main.mov` **(main video)**

## Open questions for Keith (not decided here)

- Live Music page: no single video is designated as "main" — worth asking which of the four clips should be featured first.
- Whether the 2026 shows list should support past events beyond 2026 in the same UI pattern, since Keith will keep adding dates.

#!/usr/bin/env python3
"""Generate 1200×630 Open Graph images for karlhill.com.

Usage:
    python3 scripts/generate-og-images.py              # homepage only
    python3 scripts/generate-og-images.py --blog SLUG TITLE HERO_PATH

Requires: Pillow (pip install Pillow)
"""

from __future__ import annotations

import argparse
import os
import sys
from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter, ImageFont

ROOT = Path(__file__).resolve().parents[1]
PUBLIC = ROOT / "public" / "img"
BUNDLED_FONTS = ROOT / "scripts" / "fonts"
W, H = 1200, 630

BG = (8, 8, 8)
ORANGE = (249, 115, 22)
WHITE = (245, 245, 245)
GRAY = (163, 163, 163)
DARK_GRAY = (82, 82, 82)


def font_paths(bold: bool) -> list[str]:
    dejavu = "/usr/share/fonts/truetype/dejavu"
    paths = [
        str(BUNDLED_FONTS / ("DejaVuSans-Bold.ttf" if bold else "DejaVuSans.ttf")),
        f"{dejavu}/DejaVuSans-Bold.ttf" if bold else f"{dejavu}/DejaVuSans.ttf",
    ]
    if bold:
        paths.extend([
            "/System/Library/Fonts/Supplemental/Arial Bold.ttf",
            "/Library/Fonts/Arial Bold.ttf",
        ])
    else:
        paths.extend([
            "/System/Library/Fonts/Supplemental/Arial.ttf",
            "/Library/Fonts/Arial.ttf",
        ])
    return paths


def font(size: int, bold: bool = False) -> ImageFont.FreeTypeFont:
    for path in font_paths(bold):
        if os.path.exists(path):
            return ImageFont.truetype(path, size)
    raise FileNotFoundError(
        "No TrueType font found. On Linux install fonts-dejavu-core."
    )


def generate_home() -> Path:
    profile_path = PUBLIC / "webp" / "profile.webp"
    out_path = PUBLIC / "og-home.jpg"

    img = Image.new("RGB", (W, H), BG)
    draw = ImageDraw.Draw(img)

    glow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    gdraw = ImageDraw.Draw(glow)
    gdraw.ellipse((700, -120, 1300, 480), fill=(*ORANGE, 35))
    glow = glow.filter(ImageFilter.GaussianBlur(80))
    img.paste(glow, (0, 0), glow)

    f_title = font(96, bold=True)
    f_sub = font(34)
    f_meta = font(24)
    f_cta = font(28, bold=True)
    f_site = font(20)

    size = 340
    profile = Image.open(profile_path).convert("RGBA").resize((size, size), Image.LANCZOS)
    mask = Image.new("L", (size, size), 0)
    ImageDraw.Draw(mask).ellipse((0, 0, size, size), fill=255)
    profile.putalpha(mask)

    ring = Image.new("RGBA", (size + 12, size + 12), (0, 0, 0, 0))
    rd = ImageDraw.Draw(ring)
    rd.ellipse((0, 0, size + 11, size + 11), outline=ORANGE + (255,), width=4)
    ring.paste(profile, (6, 6), profile)
    img.paste(ring, (W - size - 90, (H - size - 12) // 2), ring)

    x, y = 80, 130
    draw.text((x, y), "KARL HILL", fill=WHITE, font=f_title)
    y += 110
    draw.text((x, y), "Staff Software Engineer", fill=GRAY, font=f_sub)
    y += 52
    draw.text((x, y), "25+ years  ·  NASA  ·  Aerospace  ·  Mission Software", fill=DARK_GRAY, font=f_meta)
    y += 70
    draw.rectangle((x, y, x + 80, y + 4), fill=ORANGE)
    y += 50
    draw.text((x, y), "karlhill.com", fill=ORANGE, font=f_cta)
    tw = draw.textlength("karlhill.com", font=f_cta)
    draw.text((x + tw + 12, y + 2), "->", fill=ORANGE, font=f_cta)
    draw.text((x, H - 60), "KARL HILL", fill=DARK_GRAY, font=f_site)

    img.save(out_path, "JPEG", quality=88, optimize=True)
    return out_path


def generate_blog(slug: str, title: str, hero_rel: str) -> Path:
    hero_path = ROOT / "public" / hero_rel.lstrip("/")
    out_dir = PUBLIC / "og" / "blog"
    out_dir.mkdir(parents=True, exist_ok=True)
    out_path = out_dir / f"{slug}.jpg"

    hero = Image.open(hero_path).convert("RGB")
    target_ratio = W / H
    w, h = hero.size
    current = w / h
    if current > target_ratio:
        new_w = int(h * target_ratio)
        left = (w - new_w) // 2
        hero = hero.crop((left, 0, left + new_w, h))
    else:
        new_h = int(w / target_ratio)
        top = (h - new_h) // 2
        hero = hero.crop((0, top, w, top + new_h))
    hero = hero.resize((W, H), Image.LANCZOS)

    overlay = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    od = ImageDraw.Draw(overlay)
    for i in range(H // 2, H):
        alpha = int(180 * ((i - H // 2) / (H // 2)) ** 1.2)
        od.line([(0, i), (W, i)], fill=(*BG, alpha))
    hero = Image.alpha_composite(hero.convert("RGBA"), overlay).convert("RGB")
    draw = ImageDraw.Draw(hero)

    f_site = font(22, bold=True)
    f_title = font(52, bold=True)
    f_cta = font(24, bold=True)

    draw.text((60, H - 200), "KARL HILL", fill=ORANGE, font=f_site)

    words = title.split()
    lines: list[str] = []
    line: list[str] = []
    max_w = W - 120
    for word in words:
        test = " ".join(line + [word])
        if draw.textlength(test, font=f_title) <= max_w:
            line.append(word)
        else:
            if line:
                lines.append(" ".join(line))
            line = [word]
    if line:
        lines.append(" ".join(line))

    y = H - 160
    for ln in lines[:3]:
        draw.text((60, y), ln, fill=WHITE, font=f_title)
        y += 58

    draw.text((60, H - 55), "Read on karlhill.com ->", fill=GRAY, font=f_cta)
    hero.save(out_path, "JPEG", quality=88, optimize=True)
    return out_path


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--blog", nargs=3, metavar=("SLUG", "TITLE", "HERO"), help="Generate a blog post OG card")
    args = parser.parse_args()

    try:
        if args.blog:
            slug, title, hero = args.blog
            path = generate_blog(slug, title, hero)
        else:
            path = generate_home()
    except OSError as exc:
        print(f"error: {exc}", file=sys.stderr)
        return 1

    print(f"wrote {path}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

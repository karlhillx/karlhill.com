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


def cover_crop(src: Image.Image, tw: int, th: int) -> Image.Image:
    """Scale+crop to exactly tw×th (object-fit: cover), bias slightly toward the face."""
    w, h = src.size
    scale = max(tw / w, th / h)
    nw, nh = int(w * scale + 0.5), int(h * scale + 0.5)
    resized = src.resize((nw, nh), Image.LANCZOS)
    left = max(0, (nw - tw) // 2)
    # Bias up so eyes stay in frame when the source is a tight headshot.
    top = max(0, (nh - th) // 2 - th // 20)
    top = min(top, nh - th)
    return resized.crop((left, top, left + tw, top + th))


def generate_home() -> Path:
    """Homepage OG card optimized for Google’s square SERP thumbnail.

    Layout: full-bleed portrait on the left 630×630 (what Google crops),
    hire copy on the right. The old design put a small circle on the right
    of a black field — center/right crops showed a letter fragment and a
    tiny face.
    """
    jpg = PUBLIC / "profile.jpg"
    webp = PUBLIC / "webp" / "profile.webp"
    profile_path = jpg if jpg.exists() else webp
    out_path = PUBLIC / "og-home.jpg"

    face_w = H  # 630 — square SERP thumb is the left panel
    text_x = face_w + 48

    img = Image.new("RGB", (W, H), BG)
    face = cover_crop(Image.open(profile_path).convert("RGB"), face_w, H)
    img.paste(face, (0, 0))

    # Soft seam: photo → dark panel so LinkedIn/Twitter still read as one card.
    seam = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    sdraw = ImageDraw.Draw(seam)
    blend = 160
    for i in range(blend):
        alpha = int(255 * (i / blend) ** 1.35)
        x = face_w - blend + i
        sdraw.line([(x, 0), (x, H)], fill=(*BG, alpha))
    sdraw.rectangle((face_w, 0, W, H), fill=(*BG, 255))
    img = Image.alpha_composite(img.convert("RGBA"), seam).convert("RGB")
    draw = ImageDraw.Draw(img)

    # Quiet brand glow behind the copy (not over the face).
    glow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    gdraw = ImageDraw.Draw(glow)
    gdraw.ellipse((820, -80, 1380, 420), fill=(*ORANGE, 28))
    glow = glow.filter(ImageFilter.GaussianBlur(90))
    img = Image.alpha_composite(img.convert("RGBA"), glow).convert("RGB")
    draw = ImageDraw.Draw(img)

    f_name = font(72, bold=True)
    f_role = font(28)
    f_meta = font(22)
    f_ask = font(24, bold=True)
    f_cta = font(26, bold=True)

    x, y = text_x, 118
    draw.text((x, y), "KARL HILL", fill=WHITE, font=f_name)
    y += 86
    draw.text((x, y), "Staff Aerospace Software Engineer", fill=GRAY, font=f_role)
    y += 44
    draw.text((x, y), "Jacobs National Security  ·  NASA Goddard", fill=DARK_GRAY, font=f_meta)
    y += 56
    draw.rectangle((x, y, x + 72, y + 4), fill=ORANGE)
    y += 36
    draw.text((x, y), "Seeking Engineering Manager", fill=WHITE, font=f_ask)
    y += 48
    draw.text((x, y), "karlhill.com", fill=ORANGE, font=f_cta)
    tw = draw.textlength("karlhill.com", font=f_cta)
    draw.text((x + tw + 10, y + 1), "→", fill=ORANGE, font=f_cta)

    img.save(out_path, "JPEG", quality=90, optimize=True)
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


def generate_project(slug: str, title: str, hero_rel: str) -> Path:
    hero_path = ROOT / "public" / hero_rel.lstrip("/")
    out_dir = PUBLIC / "og" / "work"
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

    draw.text((60, H - 55), "Case study on karlhill.com ->", fill=GRAY, font=f_cta)
    hero.save(out_path, "JPEG", quality=88, optimize=True)
    return out_path


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--blog", nargs=3, metavar=("SLUG", "TITLE", "HERO"), help="Generate a blog post OG card")
    parser.add_argument("--project", nargs=3, metavar=("SLUG", "TITLE", "HERO"), help="Generate a project case study OG card")
    args = parser.parse_args()

    try:
        if args.blog:
            slug, title, hero = args.blog
            path = generate_blog(slug, title, hero)
        elif args.project:
            slug, title, hero = args.project
            path = generate_project(slug, title, hero)
        else:
            path = generate_home()
    except OSError as exc:
        print(f"error: {exc}", file=sys.stderr)
        return 1

    print(f"wrote {path}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

#!/usr/bin/env python3
"""Generate distinct dark/orange abstract hero images for blog posts.

Usage:
    python3 scripts/generate-blog-heroes.py
"""

from __future__ import annotations

import random
from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "public" / "img" / "blog"
W, H = 1600, 900
BG = (8, 8, 8)
ORANGE = (249, 115, 22)


def base(seed: int) -> tuple[Image.Image, ImageDraw.ImageDraw, random.Random]:
    rng = random.Random(seed)
    img = Image.new("RGB", (W, H), BG)
    draw = ImageDraw.Draw(img, "RGBA")

    # Subtle grain field
    grain = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    gdraw = ImageDraw.Draw(grain)
    for _ in range(1800):
        x, y = rng.randint(0, W - 1), rng.randint(0, H - 1)
        a = rng.randint(8, 28)
        gdraw.point((x, y), fill=(255, 255, 255, a))
    img = Image.alpha_composite(img.convert("RGBA"), grain).convert("RGB")
    draw = ImageDraw.Draw(img, "RGBA")
    return img, draw, rng


def glow(img: Image.Image, box: tuple[int, int, int, int], alpha: int = 40) -> Image.Image:
    layer = Image.new("RGBA", img.size, (0, 0, 0, 0))
    d = ImageDraw.Draw(layer)
    d.ellipse(box, fill=(*ORANGE, alpha))
    layer = layer.filter(ImageFilter.GaussianBlur(90))
    return Image.alpha_composite(img.convert("RGBA"), layer).convert("RGB")


def save(img: Image.Image, slug: str) -> Path:
    path = OUT / f"{slug}.jpg"
    img = img.convert("RGB")
    img.save(path, "JPEG", quality=90, optimize=True)
    print(f"wrote {path}")
    return path


def staff_to_em() -> None:
    """90-day arc: three ascending panels / milestones."""
    img, draw, rng = base(90)
    img = glow(img, (200, 100, 900, 700), 24)
    img = glow(img, (900, 400, 1700, 1100), 38)
    draw = ImageDraw.Draw(img, "RGBA")

    # Three milestone blocks ascending left → right (lighter so they read on black)
    panels = [
        (180, 500, 460, 740),
        (560, 340, 920, 740),
        (1020, 160, 1440, 740),
    ]
    for i, box in enumerate(panels):
        shade = 48 + i * 14
        draw.rounded_rectangle(box, radius=14, fill=(shade, shade, shade, 255))
        draw.rounded_rectangle(box, radius=14, outline=(90, 90, 90, 255), width=2)
        # Inner accent edge on the tallest panel
        if i == 2:
            x0, y0, x1, y1 = box
            draw.rectangle((x0, y0, x0 + 6, y1), fill=(*ORANGE, 220))
        # Day markers as thin orange ticks on top edge
        x0, y0, x1, _ = box
        tick_y = y0 - 28
        draw.line((x0 + 28, tick_y, x1 - 28, tick_y), fill=(*ORANGE, 200), width=4)
        draw.ellipse((x0 + 20, tick_y - 7, x0 + 36, tick_y + 9), fill=(*ORANGE, 255))

    # Connecting path through panel tops
    draw.line(
        [(320, 500), (740, 340), (1230, 160)],
        fill=(*ORANGE, 200),
        width=5,
    )
    save(img, "staff-to-em-first-90-days")


def roadmap_pressure() -> None:
    """Tradeoff fork: one path accepted, one deferred."""
    img, draw, rng = base(18)
    img = glow(img, (-200, -100, 700, 500), 28)
    img = glow(img, (1000, 500, 1800, 1200), 22)
    draw = ImageDraw.Draw(img, "RGBA")

    # Spine
    cx, cy = 520, 450
    draw.line((180, cy, cx, cy), fill=(70, 70, 70, 255), width=10)
    # Accepted branch (orange)
    draw.line((cx, cy, 1280, 220), fill=(*ORANGE, 230), width=10)
    # Deferred branch (muted)
    draw.line((cx, cy, 1280, 700), fill=(55, 55, 55, 255), width=10)

    # Node
    draw.ellipse((cx - 18, cy - 18, cx + 18, cy + 18), fill=(*ORANGE, 255))

    # Capsules representing scoped work
    for i, (x, y, w, on) in enumerate([
        (980, 160, 220, True),
        (1180, 280, 180, True),
        (1000, 620, 200, False),
        (1200, 740, 160, False),
    ]):
        fill = (*ORANGE, 40) if on else (40, 40, 40, 220)
        outline = (*ORANGE, 160) if on else (70, 70, 70, 255)
        draw.rounded_rectangle((x, y, x + w, y + 48), radius=24, fill=fill, outline=outline, width=2)

    # Soft veto mark on deferred branch
    draw.line((1120, 650, 1180, 710), fill=(120, 120, 120, 180), width=4)
    draw.line((1180, 650, 1120, 710), fill=(120, 120, 120, 180), width=4)
    save(img, "saying-no-roadmap-pressure")


def performance_feedback() -> None:
    """Signal over noise: clear bars + one accent insight."""
    img, draw, rng = base(25)
    img = glow(img, (600, -80, 1400, 420), 26)
    draw = ImageDraw.Draw(img, "RGBA")

    # Horizontal signal bars (most muted, one clear)
    bars = [
        (220, 220, 980, 0.35),
        (220, 320, 1180, 0.55),
        (220, 420, 860, 0.4),
        (220, 520, 1320, 1.0),  # clear signal
        (220, 620, 740, 0.3),
        (220, 720, 1040, 0.45),
    ]
    for x0, y, x1, strength in bars:
        if strength >= 1.0:
            draw.rounded_rectangle((x0, y, x1, y + 36), radius=8, fill=(*ORANGE, 210))
            # Annotation tick
            draw.polygon([(x1 + 20, y + 18), (x1 + 48, y + 4), (x1 + 48, y + 32)], fill=(*ORANGE, 220))
        else:
            a = int(50 + strength * 80)
            gray = int(36 + strength * 40)
            draw.rounded_rectangle((x0, y, x1, y + 36), radius=8, fill=(gray, gray, gray, a + 120))

    # Vertical guide (1:1 / review cadence)
    draw.line((180, 160, 180, 780), fill=(50, 50, 50, 255), width=2)
    for y in range(220, 760, 100):
        draw.ellipse((172, y + 10, 188, y + 26), fill=(70, 70, 70, 255))
    draw.ellipse((170, 528, 190, 548), fill=(*ORANGE, 255))
    save(img, "performance-feedback-without-politics")


def main() -> int:
    OUT.mkdir(parents=True, exist_ok=True)
    staff_to_em()
    roadmap_pressure()
    performance_feedback()
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

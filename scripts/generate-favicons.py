#!/usr/bin/env python3
"""Build PNG/ICO/maskable icons from the rasterized favicon master PNG."""

from __future__ import annotations

import subprocess
import sys
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
IMG = ROOT / "public" / "img"
MASTER = IMG / ".favicon-master.png"
NAVY = (2, 34, 75, 255)  # #02224b — darkest fill in favicon.svg
RASTERIZE = ROOT / "scripts" / "rasterize-favicon.mjs"

SIZES = {
    "favicon-16x16.png": 16,
    "favicon-32x32.png": 32,
    "favicon-48x48.png": 48,
    "favicon-96x96.png": 96,
    "android-chrome-192x192.png": 192,
    "android-chrome-512x512.png": 512,
}


def python_ok() -> None:
    if not hasattr(Image, "Resampling"):
        raise SystemExit("Pillow 10+ is required")


def rasterize() -> None:
    cmd = ["node", str(RASTERIZE), str(MASTER)]
    result = subprocess.run(cmd, cwd=ROOT, capture_output=True, text=True)
    if result.returncode != 0:
        sys.stderr.write(result.stdout)
        sys.stderr.write(result.stderr)
        raise SystemExit("favicon rasterize failed (Playwright Chromium required)")
    if result.stdout:
        print(result.stdout.strip())


def fit(source: Image.Image, size: int) -> Image.Image:
    return source.resize((size, size), Image.Resampling.LANCZOS)


def opaque(source: Image.Image, size: int, fill: tuple[int, int, int, int] = NAVY) -> Image.Image:
    canvas = Image.new("RGBA", (size, size), fill)
    icon = fit(source, size)
    canvas.alpha_composite(icon)
    return canvas


def maskable(source: Image.Image, size: int) -> Image.Image:
    canvas = Image.new("RGBA", (size, size), NAVY)
    inner = int(round(size * 0.8))
    icon = fit(source, inner)
    offset = (size - inner) // 2
    canvas.alpha_composite(icon, (offset, offset))
    return canvas


def save_png(image: Image.Image, dest: Path) -> None:
    dest.parent.mkdir(parents=True, exist_ok=True)
    image.save(dest, "PNG", optimize=True)


def main() -> int:
    python_ok()
    rasterize()
    try:
        source = Image.open(MASTER).convert("RGBA")

        for name, size in SIZES.items():
            save_png(fit(source, size), IMG / name)

        save_png(opaque(source, 180), IMG / "apple-touch-icon.png")
        save_png(maskable(source, 192), IMG / "maskable-192x192.png")
        save_png(maskable(source, 512), IMG / "maskable-512x512.png")

        ico_source = fit(source, 256)
        ico_source.save(
            IMG / "favicon.ico",
            format="ICO",
            sizes=[(16, 16), (32, 32), (48, 48)],
        )
        public_ico = ROOT / "public" / "favicon.ico"
        public_ico.write_bytes((IMG / "favicon.ico").read_bytes())
    finally:
        MASTER.unlink(missing_ok=True)

    print("wrote favicon png/ico/maskable set from favicon.svg")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

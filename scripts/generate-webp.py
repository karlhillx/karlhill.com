#!/usr/bin/env python3
"""Generate WebP variants for JPG/PNG assets under public/img."""

from __future__ import annotations

from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
IMG = ROOT / "public" / "img"
QUALITY = 78
SKIP_DIRS = {"webp", "og", "icons"}

# Card/hero imagery also gets fixed-width variants for `srcset`
# (consumed via App\Support\Images::srcset()).
RESIZE_WIDTHS = (800, 1600)


def should_convert(path: Path) -> bool:
    if path.suffix.lower() not in {".jpg", ".jpeg", ".png"}:
        return False
    parts = set(path.relative_to(IMG).parts)
    return not parts.intersection(SKIP_DIRS)


def wants_variants(path: Path) -> bool:
    rel = path.relative_to(IMG)
    return rel.parts[0] == "blog" or rel.name.startswith(("ss-", "small-"))


def target_path(source: Path) -> Path:
    rel = source.relative_to(IMG)
    return IMG / "webp" / rel.with_suffix(".webp")


def main() -> int:
    converted = 0
    for source in sorted(IMG.rglob("*")):
        if not source.is_file() or not should_convert(source):
            continue

        dest = target_path(source)
        dest.parent.mkdir(parents=True, exist_ok=True)

        with Image.open(source) as img:
            img.save(dest, "WEBP", quality=QUALITY, method=6)

            if wants_variants(source):
                for width in RESIZE_WIDTHS:
                    if img.width <= width:
                        continue
                    height = round(img.height * width / img.width)
                    variant = dest.with_name(f"{dest.stem}-{width}.webp")
                    img.resize((width, height), Image.LANCZOS).save(
                        variant, "WEBP", quality=QUALITY, method=6
                    )
                    print(f"wrote {variant.relative_to(ROOT)}")
                    converted += 1

        print(f"wrote {dest.relative_to(ROOT)}")
        converted += 1

    print(f"done ({converted} files)")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

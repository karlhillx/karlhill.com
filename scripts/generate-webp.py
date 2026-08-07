#!/usr/bin/env python3
"""Generate WebP + AVIF + LQIP variants for JPG/PNG assets under public/img."""

from __future__ import annotations

from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
IMG = ROOT / "public" / "img"
QUALITY = 78
AVIF_QUALITY = 55
LQIP_WIDTH = 32
LQIP_QUALITY = 40
SKIP_DIRS = {"webp", "avif", "lqip", "og", "icons"}

# Card/hero imagery also gets fixed-width variants for `srcset`
# (consumed via App\Support\Images::srcset()).
RESIZE_WIDTHS = (400, 800, 1200, 1600)


def should_convert(path: Path) -> bool:
    if path.suffix.lower() not in {".jpg", ".jpeg", ".png"}:
        return False
    parts = set(path.relative_to(IMG).parts)
    return not parts.intersection(SKIP_DIRS)


def wants_variants(path: Path) -> bool:
    rel = path.relative_to(IMG)
    return rel.parts[0] == "blog" or rel.name.startswith(("ss-", "small-"))


def wants_lqip(path: Path) -> bool:
    rel = path.relative_to(IMG)
    return wants_variants(path) or rel.name.startswith("profile")


def webp_path(source: Path) -> Path:
    rel = source.relative_to(IMG)
    return IMG / "webp" / rel.with_suffix(".webp")


def avif_path(source: Path) -> Path:
    rel = source.relative_to(IMG)
    return IMG / "avif" / rel.with_suffix(".avif")


def lqip_path(source: Path) -> Path:
    rel = source.relative_to(IMG)
    return IMG / "lqip" / rel.with_suffix(".webp")


def supports_avif() -> bool:
    try:
        with Image.new("RGB", (8, 8), (0, 0, 0)) as probe:
            from io import BytesIO

            buf = BytesIO()
            probe.save(buf, "AVIF", quality=40)
        return True
    except Exception:
        return False


def save_webp(img: Image.Image, dest: Path) -> None:
    dest.parent.mkdir(parents=True, exist_ok=True)
    img.save(dest, "WEBP", quality=QUALITY, method=6)


def save_avif(img: Image.Image, dest: Path) -> bool:
    dest.parent.mkdir(parents=True, exist_ok=True)
    try:
        img.save(dest, "AVIF", quality=AVIF_QUALITY)
        return True
    except Exception as exc:
        print(f"skip avif {dest.relative_to(ROOT)} ({exc})")
        return False


def save_lqip(img: Image.Image, dest: Path) -> None:
    dest.parent.mkdir(parents=True, exist_ok=True)
    width = min(LQIP_WIDTH, img.width)
    height = max(1, round(img.height * width / img.width))
    tiny = img.resize((width, height), Image.LANCZOS)
    tiny.save(dest, "WEBP", quality=LQIP_QUALITY, method=6)


def write_variants(img: Image.Image, dest: Path, writer) -> int:
    written = 0
    for width in RESIZE_WIDTHS:
        if img.width <= width:
            continue
        height = round(img.height * width / img.width)
        variant = dest.with_name(f"{dest.stem}-{width}{dest.suffix}")
        writer(img.resize((width, height), Image.LANCZOS), variant)
        print(f"wrote {variant.relative_to(ROOT)}")
        written += 1
    return written


def main() -> int:
    avif_ok = supports_avif()
    if not avif_ok:
        print("note: AVIF encode unavailable in this Pillow build; writing WebP only")

    converted = 0
    for source in sorted(IMG.rglob("*")):
        if not source.is_file() or not should_convert(source):
            continue

        with Image.open(source) as img:
            rgb = img.convert("RGBA") if img.mode in {"P", "RGBA"} else img.convert("RGB")

            webp_dest = webp_path(source)
            save_webp(rgb, webp_dest)
            print(f"wrote {webp_dest.relative_to(ROOT)}")
            converted += 1

            if wants_variants(source):
                converted += write_variants(rgb, webp_dest, save_webp)

            if wants_lqip(source):
                dest = lqip_path(source)
                save_lqip(rgb, dest)
                print(f"wrote {dest.relative_to(ROOT)}")
                converted += 1

            if avif_ok:
                avif_dest = avif_path(source)
                if save_avif(rgb, avif_dest):
                    print(f"wrote {avif_dest.relative_to(ROOT)}")
                    converted += 1
                    if wants_variants(source):
                        converted += write_variants(rgb, avif_dest, save_avif)

    print(f"done ({converted} files)")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

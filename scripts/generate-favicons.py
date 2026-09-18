#!/usr/bin/env python3
"""Build PNG/ICO/maskable icons from favicon.svg (the rocket site mark).

Google Search listing icons come from <link rel="icon">. Person JSON-LD
image is the portrait and must not be used as the favicon.
"""

from __future__ import annotations

import struct
import subprocess
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
IMG = ROOT / "public" / "img"
MASTER = IMG / ".favicon-master.png"
RASTERIZE = ROOT / "scripts" / "rasterize-favicon.mjs"
NAVY = "0x02224b"

SIZES = {
    "favicon-16x16.png": 16,
    "favicon-32x32.png": 32,
    "favicon-48x48.png": 48,
    "favicon-96x96.png": 96,
    "android-chrome-192x192.png": 192,
    "android-chrome-512x512.png": 512,
}


def run(cmd: list[str]) -> None:
    result = subprocess.run(cmd, cwd=ROOT, capture_output=True, text=True)
    if result.returncode != 0:
        sys.stderr.write(result.stdout)
        sys.stderr.write(result.stderr)
        raise SystemExit(f"command failed: {' '.join(cmd[:3])}")
    if result.stdout.strip():
        print(result.stdout.strip())


def ffmpeg(*args: str) -> None:
    run(["ffmpeg", "-hide_banner", "-loglevel", "error", *args])


def rasterize() -> None:
    run(["node", str(RASTERIZE), str(MASTER)])


def scale_png(size: int, dest: Path) -> None:
    ffmpeg(
        "-y",
        "-i",
        str(MASTER),
        "-vf",
        f"scale={size}:{size}:flags=lanczos+accurate_rnd,format=rgba",
        "-update",
        "1",
        "-frames:v",
        "1",
        str(dest),
    )


def composite_on_navy(size: int, dest: Path, inner: int | None = None) -> None:
    icon_size = inner if inner is not None else size
    offset = (size - icon_size) // 2
    ffmpeg(
        "-y",
        "-f",
        "lavfi",
        "-i",
        f"color=c={NAVY}:s={size}x{size}:d=1,format=rgba",
        "-i",
        str(MASTER),
        "-filter_complex",
        f"[1:v]scale={icon_size}:{icon_size}:flags=lanczos+accurate_rnd,format=rgba[icon];"
        f"[0:v][icon]overlay={offset}:{offset}:format=auto",
        "-update",
        "1",
        "-frames:v",
        "1",
        str(dest),
    )


def png_size(blob: bytes) -> tuple[int, int]:
    if blob[:8] != b"\x89PNG\r\n\x1a\n":
        raise SystemExit(f"not a PNG ({len(blob)} bytes)")
    width, height = struct.unpack(">II", blob[16:24])
    return width, height


def write_ico(png_paths: list[Path], dest: Path) -> None:
    blobs = [path.read_bytes() for path in png_paths]
    count = len(blobs)
    offset = 6 + 16 * count
    header = struct.pack("<HHH", 0, 1, count)
    entries = bytearray()
    payload = bytearray()

    for blob in blobs:
        width, height = png_size(blob)
        entries += struct.pack(
            "<BBBBHHII",
            0 if width >= 256 else width,
            0 if height >= 256 else height,
            0,
            0,
            1,
            32,
            len(blob),
            offset,
        )
        payload += blob
        offset += len(blob)

    dest.write_bytes(header + bytes(entries) + bytes(payload))


def main() -> int:
    rasterize()
    try:
        for name, size in SIZES.items():
            scale_png(size, IMG / name)

        composite_on_navy(180, IMG / "apple-touch-icon.png")
        composite_on_navy(192, IMG / "maskable-192x192.png", inner=round(192 * 0.8))
        composite_on_navy(512, IMG / "maskable-512x512.png", inner=round(512 * 0.8))

        ico = IMG / "favicon.ico"
        write_ico(
            [
                IMG / "favicon-16x16.png",
                IMG / "favicon-32x32.png",
                IMG / "favicon-48x48.png",
            ],
            ico,
        )
        (ROOT / "public" / "favicon.ico").write_bytes(ico.read_bytes())
    finally:
        MASTER.unlink(missing_ok=True)

    print("wrote favicon png/ico/maskable set from favicon.svg")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

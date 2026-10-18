"""PNG and SVG rendering of a :class:`~linkpress_qr.qr.QRMatrix`."""

from __future__ import annotations

import io
import math
from collections.abc import Iterator
from xml.sax.saxutils import escape

from PIL import Image, ImageDraw

from .layout import (
    PNG_SIZES,
    PPI_OPTIONS,
    CardBorder,
    Layout,
    OuterBackground,
    Preset,
    make_layout,
    place_on_canvas,
)
from .qr import QRMatrix

WHITE = (255, 255, 255)
BLACK = (0, 0, 0)
_CORNER_SUPERSAMPLE = 8
SVG_PIXELS_PER_MODULE = 10


def iter_runs(matrix: QRMatrix) -> Iterator[tuple[int, int, int]]:
    """Yield ``(x, y, length)`` for each horizontal run of dark modules."""
    for y, row in enumerate(matrix.rows):
        x = 0
        n = len(row)
        while x < n:
            if row[x]:
                start = x
                while x < n and row[x]:
                    x += 1
                yield start, y, x - start
            else:
                x += 1


# --------------------------------------------------------------------------- PNG


def _corner_tile(radius_px: float) -> Image.Image:
    """Anti-aliased top-left corner alpha mask (only the card edge is smoothed)."""
    tile = math.ceil(radius_px) + 1
    ss = _CORNER_SUPERSAMPLE
    big = Image.new("L", (tile * ss, tile * ss), 0)
    ImageDraw.Draw(big).rounded_rectangle(
        (0, 0, tile * ss * 2, tile * ss * 2), radius=radius_px * ss, fill=255
    )
    return big.resize((tile, tile), Image.Resampling.BOX)


def _card_mask(size_px: int, radius_px: float) -> Image.Image:
    mask = Image.new("L", (size_px, size_px), 255)
    if radius_px <= 0:
        return mask
    tl = _corner_tile(radius_px)
    t = tl.width
    mask.paste(tl, (0, 0))
    mask.paste(tl.transpose(Image.Transpose.FLIP_LEFT_RIGHT), (size_px - t, 0))
    mask.paste(tl.transpose(Image.Transpose.FLIP_TOP_BOTTOM), (0, size_px - t))
    mask.paste(tl.transpose(Image.Transpose.ROTATE_180), (size_px - t, size_px - t))
    return mask


def render_png_image(
    matrix: QRMatrix,
    *,
    preset: Preset = Preset.ROUNDED,
    outer_background: OuterBackground = OuterBackground.TRANSPARENT,
    card_border: CardBorder = CardBorder.NONE,
    size: int = 1024,
) -> Image.Image:
    if size not in PNG_SIZES:
        raise ValueError(f"PNG size must be one of {PNG_SIZES}")
    layout = make_layout(matrix.size, preset, outer_background, card_border)
    place = place_on_canvas(layout, size)

    if layout.card is None:
        image = Image.new("RGB", (size, size), WHITE)
    elif outer_background is OuterBackground.TRANSPARENT:
        # Transparent *white* so anti-aliased card edges have no dark fringe.
        image = Image.new("RGBA", (size, size), (255, 255, 255, 0))
    else:
        image = Image.new("RGB", (size, size), BLACK)
    draw = ImageDraw.Draw(image)

    if layout.card is not None:
        x0 = place.px(layout.card.x)
        card_px = place.px(layout.card.x + layout.card.size) - x0
        mask = _card_mask(card_px, layout.card_radius * place.scale)
        image.paste(Image.new(image.mode, (card_px, card_px), WHITE), (x0, x0), mask)
        if card_border is CardBorder.BLACK:
            draw = ImageDraw.Draw(image)
            draw.rounded_rectangle(
                (x0, x0, x0 + card_px - 1, x0 + card_px - 1),
                radius=round(layout.card_radius * place.scale),
                outline=BLACK,
                width=place.scale,
            )

    # Protected area (symbol + quiet zone) is drawn fully opaque white explicitly.
    p0 = place.px(layout.protected.x)
    p1 = place.px(layout.protected.x + layout.protected.size)
    draw.rectangle((p0, p0, p1 - 1, p1 - 1), fill=WHITE)

    s = place.scale
    origin = place.px(layout.symbol.x)
    for x, y, length in iter_runs(matrix):
        left = origin + x * s
        top = origin + y * s
        draw.rectangle((left, top, left + length * s - 1, top + s - 1), fill=BLACK)
    return image


def render_png(
    matrix: QRMatrix,
    *,
    preset: Preset = Preset.ROUNDED,
    outer_background: OuterBackground = OuterBackground.TRANSPARENT,
    card_border: CardBorder = CardBorder.NONE,
    size: int = 1024,
    ppi: int | None = None,
) -> bytes:
    """Render a PNG. ``ppi`` (300 or 600) is stored as pixel-density metadata."""
    if ppi is not None and ppi not in PPI_OPTIONS:
        raise ValueError(f"PPI must be one of {PPI_OPTIONS}")
    image = render_png_image(
        matrix, preset=preset, outer_background=outer_background, card_border=card_border, size=size
    )
    buf = io.BytesIO()
    params: dict[str, object] = {}
    if ppi is not None:
        params["dpi"] = (ppi, ppi)
    image.save(buf, format="PNG", **params)
    return buf.getvalue()


# --------------------------------------------------------------------------- SVG


def _n(value: float) -> str:
    text = f"{value:.3f}".rstrip("0").rstrip(".")
    return text or "0"


def _modules_path(matrix: QRMatrix, layout: Layout) -> str:
    ox = int(layout.symbol.x)
    oy = int(layout.symbol.y)
    return "".join(
        f"M{ox + x} {oy + y}h{length}v1h-{length}z" for x, y, length in iter_runs(matrix)
    )


def render_svg(
    matrix: QRMatrix,
    *,
    preset: Preset = Preset.ROUNDED,
    outer_background: OuterBackground = OuterBackground.TRANSPARENT,
    card_border: CardBorder = CardBorder.NONE,
) -> str:
    """Render a self-contained vector SVG (one ``viewBox`` unit = one module)."""
    layout = make_layout(matrix.size, preset, outer_background, card_border)
    t = layout.total
    px = t * SVG_PIXELS_PER_MODULE
    pr = layout.protected
    lines = [
        '<?xml version="1.0" encoding="UTF-8"?>',
        f'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="{px}" height="{px}" '
        f'viewBox="0 0 {t} {t}">',
        "<title>QR code</title>",
        f"<desc>Linkpress QR · version {matrix.version} · error correction "
        f"{matrix.error_correction} · {escape(matrix.data)}</desc>",
    ]
    if layout.card is not None:
        if outer_background is OuterBackground.BLACK:
            lines.append(
                f'<g id="outer-background"><rect width="{t}" height="{t}" fill="#000000"/></g>'
            )
        c = layout.card
        r = _n(layout.card_radius)
        border = "" if card_border is CardBorder.NONE else ' stroke="#000000" stroke-width="1"'
        lines.append(
            f'<g id="card"><rect x="{_n(c.x)}" y="{_n(c.y)}" width="{_n(c.size)}" '
            f'height="{_n(c.size)}" rx="{r}" ry="{r}" fill="#ffffff"{border}/></g>'
        )
    lines += [
        '<g id="qr-code">',
        f'<rect id="qr-background" x="{_n(pr.x)}" y="{_n(pr.y)}" width="{_n(pr.size)}" '
        f'height="{_n(pr.size)}" fill="#ffffff"/>',
        f'<path id="qr-modules" fill="#000000" shape-rendering="crispEdges" '
        f'd="{_modules_path(matrix, layout)}"/>',
        "</g>",
        "</svg>",
    ]
    return "\n".join(lines) + "\n"

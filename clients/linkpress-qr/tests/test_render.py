from __future__ import annotations

import io
import math
import re
import xml.etree.ElementTree as ET

import pytest
from PIL import Image

from linkpress_qr.layout import (
    PNG_SIZES,
    QUIET_ZONE,
    CardBorder,
    OuterBackground,
    Preset,
    make_layout,
    place_on_canvas,
)
from linkpress_qr.qr import QRMatrix, build
from linkpress_qr.render import render_png, render_png_image, render_svg

from .conftest import TRICKY_URLS, decode_png

SVG_NS = "{http://www.w3.org/2000/svg}"
VARIANTS = [
    (Preset.ROUNDED, OuterBackground.TRANSPARENT),
    (Preset.ROUNDED, OuterBackground.BLACK),
    (Preset.SQUARE, OuterBackground.TRANSPARENT),
]


def _white(px: tuple[int, ...] | int | float) -> bool:
    assert isinstance(px, tuple)
    return px[:3] == (255, 255, 255) and (len(px) == 3 or px[3] == 255)


def _assert_symbol_and_quiet_zone(image: Image.Image, matrix: QRMatrix, preset: Preset) -> None:
    layout = make_layout(matrix.size, preset)
    place = place_on_canvas(layout, image.width)
    s = place.scale
    p0 = place.px(layout.protected.x)
    p1 = place.px(layout.protected.x + layout.protected.size)
    sym0 = place.px(layout.symbol.x)
    sym1 = sym0 + matrix.size * s
    assert sym0 - p0 == QUIET_ZONE * s
    assert p1 - sym1 == QUIET_ZONE * s
    px = image.load()
    assert px is not None
    # Quiet zone: every pixel opaque white.
    for y in range(p0, p1):
        for x in range(p0, p1):
            if sym0 <= x < sym1 and sym0 <= y < sym1:
                continue
            assert _white(px[x, y]), (x, y, px[x, y])
    # Symbol: every module is an exact s x s block of pure black or pure white.
    for my, row in enumerate(matrix.rows):
        for mx, dark in enumerate(row):
            for y in range(sym0 + my * s, sym0 + (my + 1) * s):
                for x in range(sym0 + mx * s, sym0 + (mx + 1) * s):
                    value = px[x, y]
                    assert isinstance(value, tuple)
                    assert value[:3] == ((0, 0, 0) if dark else (255, 255, 255))


@pytest.mark.parametrize(("preset", "outer"), VARIANTS)
@pytest.mark.parametrize("size", PNG_SIZES)
def test_png_dimensions_and_decoding(preset: Preset, outer: OuterBackground, size: int) -> None:
    url = TRICKY_URLS[1]
    data = render_png(build(url), preset=preset, outer_background=outer, size=size)
    image = Image.open(io.BytesIO(data))
    assert image.format == "PNG"
    assert image.size == (size, size)
    assert decode_png(data) == [url]


@pytest.mark.parametrize("url", TRICKY_URLS)
@pytest.mark.parametrize("preset", list(Preset))
def test_smallest_canvas_decodes_exactly(url: str, preset: Preset) -> None:
    data = render_png(build(url), preset=preset, size=512)
    assert decode_png(data) == [url]


@pytest.mark.parametrize(("preset", "outer"), VARIANTS)
def test_png_quiet_zone_and_whole_pixel_modules(preset: Preset, outer: OuterBackground) -> None:
    matrix = build(TRICKY_URLS[2])
    image = render_png_image(matrix, preset=preset, outer_background=outer, size=512)
    _assert_symbol_and_quiet_zone(image, matrix, preset)


def test_large_version_fits_smallest_canvas() -> None:
    matrix = build("https://example.com/" + "x" * 2200)
    assert matrix.version == 40
    for preset in Preset:
        image = render_png_image(matrix, preset=preset, size=512)
        assert image.size == (512, 512)
        _assert_symbol_and_quiet_zone(image, matrix, preset)


def test_rounded_transparent_only_outside_card() -> None:
    matrix = build(TRICKY_URLS[0])
    image = render_png_image(matrix, outer_background=OuterBackground.TRANSPARENT, size=1024)
    assert image.mode == "RGBA"
    layout = make_layout(matrix.size)
    place = place_on_canvas(layout, 1024)
    assert layout.card is not None
    c0 = place.px(layout.card.x)
    c1 = place.px(layout.card.x + layout.card.size)
    alpha = image.getchannel("A")
    assert alpha.getpixel((0, 0)) == 0  # outer canvas
    corner = alpha.getpixel((c0, c0))
    assert isinstance(corner, int)
    assert corner < 255  # rounded corner, partially/fully clear
    assert alpha.getpixel((c0, (c0 + c1) // 2)) == 255  # card edge midpoint
    # Everything inside the card except the four corner regions is opaque.
    r = math.ceil(layout.card_radius * place.scale) + 1
    inner = alpha.crop((c0 + r, c0, c1 - r, c1))
    assert inner.getextrema() == (255, 255)
    _assert_symbol_and_quiet_zone(image, matrix, Preset.ROUNDED)


def test_rounded_black_and_square_are_opaque() -> None:
    matrix = build(TRICKY_URLS[0])
    black = render_png_image(matrix, outer_background=OuterBackground.BLACK, size=512)
    assert black.mode == "RGB"
    assert black.getpixel((0, 0)) == (0, 0, 0)
    square = render_png_image(matrix, preset=Preset.SQUARE, size=512)
    assert square.mode == "RGB"
    assert square.getpixel((0, 0)) == (255, 255, 255)


def test_black_card_border_is_rendered_in_png_and_svg() -> None:
    matrix = build(TRICKY_URLS[0])
    layout = make_layout(matrix.size, card_border=CardBorder.BLACK)
    assert layout.card is not None

    image = render_png_image(matrix, card_border=CardBorder.BLACK, size=512)
    place = place_on_canvas(layout, 512)
    card_edge = place.px(layout.card.x)
    assert image.getpixel((card_edge, image.height // 2))[:3] == (0, 0, 0)

    svg = render_svg(matrix, card_border=CardBorder.BLACK)
    root = ET.fromstring(svg)
    card = root.find(f".//{SVG_NS}g[@id='card']/{SVG_NS}rect")
    assert card is not None
    assert card.attrib["stroke"] == "#000000"
    assert card.attrib["stroke-width"] == "1"


def test_card_corner_radius_stays_outside_protected_area() -> None:
    for version_size in range(21, 178, 4):
        layout = make_layout(version_size)
        assert layout.card is not None
        pad = layout.protected.x - layout.card.x
        r = layout.card_radius
        # The protected corner must lie inside the arc: within r of the arc centre.
        assert pad >= r or math.hypot(r - pad, r - pad) <= r


@pytest.mark.parametrize("ppi", [300, 600])
def test_png_ppi_metadata(ppi: int) -> None:
    data = render_png(build(TRICKY_URLS[0]), size=1024, ppi=ppi)
    image = Image.open(io.BytesIO(data))
    dpi = image.info["dpi"]
    assert round(dpi[0]) == ppi
    assert round(dpi[1]) == ppi


def test_png_without_ppi_has_no_density() -> None:
    image = Image.open(io.BytesIO(render_png(build(TRICKY_URLS[0]), size=512)))
    assert "dpi" not in image.info


def test_png_rejects_unsupported_options() -> None:
    matrix = build(TRICKY_URLS[0])
    with pytest.raises(ValueError, match="PNG size"):
        render_png(matrix, size=4096)
    with pytest.raises(ValueError, match="PPI"):
        render_png(matrix, ppi=72)


# --------------------------------------------------------------------------- SVG


def _svg_matrix(root: ET.Element, size: int, origin: int) -> list[list[bool]]:
    path = root.find(f".//{SVG_NS}path[@id='qr-modules']")
    assert path is not None
    grid = [[False] * size for _ in range(size)]
    for x, y, w in re.findall(r"M(\d+) (\d+)h(\d+)v1h-\3z", path.attrib["d"]):
        for i in range(int(w)):
            grid[int(y) - origin][int(x) - origin + i] = True
    return grid


@pytest.mark.parametrize(("preset", "outer"), VARIANTS)
def test_svg_is_vector_with_explicit_background(preset: Preset, outer: OuterBackground) -> None:
    matrix = build(TRICKY_URLS[2])
    svg = render_svg(matrix, preset=preset, outer_background=outer)
    root = ET.fromstring(svg)
    layout = make_layout(matrix.size, preset, outer)
    assert root.attrib["viewBox"] == f"0 0 {layout.total} {layout.total}"

    bg = root.find(f".//{SVG_NS}rect[@id='qr-background']")
    assert bg is not None
    assert bg.attrib["fill"] == "#ffffff"
    assert float(bg.attrib["width"]) == matrix.size + 2 * QUIET_ZONE

    group_ids = {g.attrib.get("id") for g in root.iter(f"{SVG_NS}g")}
    assert "qr-code" in group_ids
    if preset is Preset.ROUNDED:
        card = root.find(f".//{SVG_NS}g[@id='card']/{SVG_NS}rect")
        assert card is not None
        assert float(card.attrib["rx"]) > 0
        assert card.attrib["fill"] == "#ffffff"
        assert ("outer-background" in group_ids) == (outer is OuterBackground.BLACK)
    else:
        assert "card" not in group_ids

    # Self-contained: no raster images, scripts, or external references.
    tags = {el.tag.removeprefix(SVG_NS) for el in root.iter()}
    assert tags <= {"svg", "title", "desc", "g", "rect", "path"}
    assert "href" not in svg
    assert "<image" not in svg
    assert "<script" not in svg

    origin = int(layout.symbol.x)
    assert _svg_matrix(root, matrix.size, origin) == [list(r) for r in matrix.rows]


def test_svg_escapes_destination_in_metadata() -> None:
    url = 'https://example.com/?a=1&b=<x>"'
    svg = render_svg(build(url))
    ET.fromstring(svg)  # well-formed
    assert "&amp;b=&lt;x&gt;" in svg

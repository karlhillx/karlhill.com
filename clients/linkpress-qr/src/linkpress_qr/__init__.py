"""Linkpress QR — Clean QR codes. Ready for print.

Reusable core API::

    from linkpress_qr import build, render_png, render_svg, Preset

    matrix = build("https://example.com/tickets")
    png_bytes = render_png(matrix, preset=Preset.ROUNDED, size=1024)
    svg_text = render_svg(matrix, preset=Preset.SQUARE)
"""

__version__ = "0.1.0"

from .layout import PNG_SIZES, PPI_OPTIONS, CardBorder, OuterBackground, Preset, make_layout
from .qr import ERROR_CORRECTION, QRCapacityError, QRMatrix, build, encode
from .render import render_png, render_png_image, render_svg
from .urls import InvalidURLError, validate_url

__all__ = [
    "ERROR_CORRECTION",
    "PNG_SIZES",
    "PPI_OPTIONS",
    "CardBorder",
    "InvalidURLError",
    "OuterBackground",
    "Preset",
    "QRCapacityError",
    "QRMatrix",
    "__version__",
    "build",
    "encode",
    "make_layout",
    "render_png",
    "render_png_image",
    "render_svg",
    "validate_url",
]

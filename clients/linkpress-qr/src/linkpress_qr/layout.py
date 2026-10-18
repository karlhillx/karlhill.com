"""Presentation layout shared by the preview, PNG export and SVG export.

All geometry is defined in *module units*. SVG uses those units directly as its
``viewBox``; PNG multiplies them by a whole-number pixel scale and centres the
result on the requested canvas, so no QR module is ever fractional or resampled.

Protected area: the symbol plus a 4-module quiet zone. It is always opaque
white. Rounded corners and the outer canvas sit strictly outside it.
"""

from __future__ import annotations

from dataclasses import dataclass
from enum import StrEnum

QUIET_ZONE = 4
CARD_PADDING = 2
"""White card margin beyond the quiet zone, in modules (rounded card only)."""
OUTER_MARGIN = 3
"""Outer canvas margin around the card, in modules (rounded card only)."""
CARD_RADIUS_RATIO = 0.07
CARD_RADIUS_MAX = 3.0 * CARD_PADDING
"""A corner arc of radius r stays outside the protected square while
r <= padding / (1 - 1/sqrt(2)) ~= 3.41 * padding; 3.0x leaves a safety margin."""

PNG_SIZES: tuple[int, ...] = (512, 1024, 2048)
PPI_OPTIONS: tuple[int, ...] = (300, 600)


class Preset(StrEnum):
    ROUNDED = "rounded"
    SQUARE = "square"


class OuterBackground(StrEnum):
    TRANSPARENT = "transparent"
    BLACK = "black"


class CardBorder(StrEnum):
    NONE = "none"
    BLACK = "black"


@dataclass(frozen=True, slots=True)
class Rect:
    x: float
    y: float
    size: float


@dataclass(frozen=True, slots=True)
class Layout:
    """Geometry in module units for a symbol of ``symbol_size`` modules."""

    preset: Preset
    outer_background: OuterBackground
    card_border: CardBorder
    symbol_size: int
    total: int
    card: Rect | None
    card_radius: float
    protected: Rect
    """Symbol + quiet zone (opaque white)."""
    symbol: Rect

    @property
    def has_outer_canvas(self) -> bool:
        return self.card is not None


def make_layout(
    symbol_size: int,
    preset: Preset = Preset.ROUNDED,
    outer_background: OuterBackground = OuterBackground.TRANSPARENT,
    card_border: CardBorder = CardBorder.NONE,
) -> Layout:
    protected_size = symbol_size + 2 * QUIET_ZONE
    if preset is Preset.SQUARE:
        return Layout(
            preset=preset,
            outer_background=outer_background,
            card_border=card_border,
            symbol_size=symbol_size,
            total=protected_size,
            card=None,
            card_radius=0.0,
            protected=Rect(0, 0, protected_size),
            symbol=Rect(QUIET_ZONE, QUIET_ZONE, symbol_size),
        )
    card_size = protected_size + 2 * CARD_PADDING
    total = card_size + 2 * OUTER_MARGIN
    radius = round(min(card_size * CARD_RADIUS_RATIO, CARD_RADIUS_MAX), 3)
    card_origin = OUTER_MARGIN
    protected_origin = card_origin + CARD_PADDING
    return Layout(
        preset=preset,
        outer_background=outer_background,
        card_border=card_border,
        symbol_size=symbol_size,
        total=total,
        card=Rect(card_origin, card_origin, card_size),
        card_radius=radius,
        protected=Rect(protected_origin, protected_origin, protected_size),
        symbol=Rect(protected_origin + QUIET_ZONE, protected_origin + QUIET_ZONE, symbol_size),
    )


@dataclass(frozen=True, slots=True)
class PixelPlacement:
    """Integer pixel placement of a :class:`Layout` on a square canvas."""

    canvas: int
    scale: int
    offset: int

    def px(self, units: float) -> int:
        return self.offset + round(units * self.scale)


def place_on_canvas(layout: Layout, canvas: int) -> PixelPlacement:
    """Pick the largest whole-number module scale that fits ``canvas``.

    Leftover pixels become extra padding (white for the classic square, outer
    canvas for the rounded card), split evenly around the design.
    """
    scale = canvas // layout.total
    if scale < 1:
        raise ValueError(f"A {canvas}px canvas is too small for this QR code.")
    offset = (canvas - scale * layout.total) // 2
    return PixelPlacement(canvas=canvas, scale=scale, offset=offset)


def print_size_inches(pixels: int, ppi: int) -> float:
    return pixels / ppi

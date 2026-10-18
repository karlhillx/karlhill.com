"""QR encoding with Segno: standard QR only, explicit error-correction level M."""

from __future__ import annotations

from dataclasses import dataclass

import segno
from segno import DataOverflowError

from .urls import validate_url

ERROR_CORRECTION = "M"


class QRCapacityError(ValueError):
    """Raised when the destination exceeds the capacity of a standard QR code."""


@dataclass(frozen=True, slots=True)
class QRMatrix:
    """An encoded QR symbol without quiet zone. ``rows[y][x]`` is ``True`` for dark."""

    data: str
    version: int
    error_correction: str
    mask: int
    rows: tuple[tuple[bool, ...], ...]

    @property
    def size(self) -> int:
        """Symbol width/height in modules (excluding the quiet zone)."""
        return len(self.rows)


def encode(url: str) -> QRMatrix:
    """Encode an already-validated destination exactly as given.

    Segno chooses the smallest version and the best mask; error correction is
    fixed at M (``boost_error`` disabled) and Micro QR is never produced.
    """
    try:
        qr = segno.make_qr(url, error=ERROR_CORRECTION, boost_error=False)
    except DataOverflowError:
        raise QRCapacityError(
            f"This link is too long for a QR code ({len(url.encode('utf-8')):,} bytes). "
            "At error correction level M a QR code holds about 2,300 bytes; "
            "use a shorter link."
        ) from None
    if qr.is_micro:  # pragma: no cover - make_qr never returns Micro QR
        raise AssertionError("Micro QR must not be generated")
    rows = tuple(tuple(bool(cell) for cell in row) for row in qr.matrix)
    version = qr.version
    assert isinstance(version, int)
    return QRMatrix(
        data=url,
        version=version,
        error_correction=str(qr.error),
        mask=int(qr.mask),
        rows=rows,
    )


def build(raw_url: str) -> QRMatrix:
    """Validate and encode user input in one step."""
    return encode(validate_url(raw_url))

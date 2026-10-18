from __future__ import annotations

import pytest
import segno

from linkpress_qr.qr import ERROR_CORRECTION, QRCapacityError, build, encode
from linkpress_qr.urls import InvalidURLError

from .conftest import TRICKY_URLS


@pytest.mark.parametrize("url", TRICKY_URLS)
def test_encodes_exact_destination(url: str) -> None:
    matrix = build(url)
    assert matrix.data == url
    reference = segno.make_qr(url, error="M", boost_error=False)
    assert matrix.rows == tuple(tuple(bool(c) for c in row) for row in reference.matrix)


def test_standard_qr_not_micro_even_for_tiny_input() -> None:
    # "http://a.b" fits in Micro QR M4; it must still be a standard QR symbol.
    assert segno.make("http://a.b", micro=True).is_micro
    matrix = build("http://a.b")
    assert matrix.version >= 1
    assert matrix.size == 17 + 4 * matrix.version
    assert matrix.size >= 21


def test_error_correction_is_explicitly_medium() -> None:
    assert ERROR_CORRECTION == "M"
    # Short data where segno would otherwise boost the level.
    assert build("https://a.co").error_correction == "M"
    assert build("https://example.com/" + "x" * 900).error_correction == "M"


def test_smallest_version_is_chosen() -> None:
    assert build("https://a.co").version == 1
    assert build("https://example.com/" + "x" * 300).version > 5


def test_capacity_error_is_clear() -> None:
    url = "https://example.com/" + "x" * 2400
    with pytest.raises(QRCapacityError, match="too long for a QR code"):
        encode(url)


def test_build_validates_first() -> None:
    with pytest.raises(InvalidURLError):
        build("example.com")

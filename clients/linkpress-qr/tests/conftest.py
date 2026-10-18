from __future__ import annotations

import io
from collections.abc import Iterator

import pytest
import zxingcpp
from fastapi.testclient import TestClient
from PIL import Image

from linkpress_qr.app import create_app

TRICKY_URLS = [
    "https://example.com/tickets",
    "https://Example.COM/Path/To/Page?b=2&a=1&B=3#Section-2",
    "http://example.com/caf%C3%A9/menu?q=hello%20world&x=%2F%3F#frag%20ment",
    "https://shop.example.org:8443/Items?id=007&utm_source=print&empty=&flag#top",
]


@pytest.fixture
def client() -> Iterator[TestClient]:
    with TestClient(create_app()) as c:
        yield c


def decode_png(data: bytes) -> list[str]:
    image = Image.open(io.BytesIO(data))
    image.load()
    return [r.text for r in zxingcpp.read_barcodes(image)]


def decode_image(image: Image.Image) -> list[str]:
    return [r.text for r in zxingcpp.read_barcodes(image)]

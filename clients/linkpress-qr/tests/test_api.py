from __future__ import annotations

import io

import pytest
from fastapi.testclient import TestClient
from PIL import Image

from linkpress_qr.app import MAX_BODY_BYTES
from linkpress_qr.layout import CardBorder
from linkpress_qr.qr import build
from linkpress_qr.render import render_svg

from .conftest import TRICKY_URLS, decode_png


def test_index_page(client: TestClient) -> None:
    r = client.get("/")
    assert r.status_code == 200
    assert r.headers["content-type"].startswith("text/html")
    html = r.text
    assert "Generate QR code" in html
    assert 'placeholder="https://example.com/tickets"' in html
    assert "Download PNG" in html and "Download SVG" in html
    assert "/static/app.css" in html and "/static/app.js" in html
    assert "https://" not in html.split("<body>")[0].replace("https://example.com", "")
    assert "default-src 'self'" in r.headers["content-security-policy"]


@pytest.mark.parametrize("asset", ["app.css", "app.js", "favicon.svg"])
def test_static_assets_served(client: TestClient, asset: str) -> None:
    assert client.get(f"/static/{asset}").status_code == 200


@pytest.mark.parametrize("url", TRICKY_URLS)
def test_generate_returns_exact_destination(client: TestClient, url: str) -> None:
    r = client.post("/api/generate", json={"url": f"  {url}\n"})
    assert r.status_code == 200
    body = r.json()
    assert body["destination"] == url
    assert body["error_correction"] == "M"
    assert body["modules"] == 17 + 4 * body["version"]
    assert body["svg"] == render_svg(build(url))


def test_generate_and_export_support_black_card_border(client: TestClient) -> None:
    settings = {
        "url": TRICKY_URLS[0],
        "preset": "rounded",
        "card_border": CardBorder.BLACK,
    }
    generated = client.post("/api/generate", json=settings).json()
    assert 'stroke="#000000" stroke-width="1"' in generated["svg"]
    assert generated["card_border"] == "black"

    exported = client.post("/api/export/svg", json=settings)
    assert exported.status_code == 200
    assert exported.headers["content-disposition"].endswith(
        '-rounded-transparent-black-border.svg"'
    )
    assert exported.text == generated["svg"]


def test_generate_invalid_url(client: TestClient) -> None:
    r = client.post("/api/generate", json={"url": "example.com/tickets"})
    assert r.status_code == 422
    err = r.json()["error"]
    assert err["field"] == "url"
    assert "https://example.com/tickets" in err["message"]


def test_generate_capacity_error(client: TestClient) -> None:
    r = client.post("/api/generate", json={"url": "https://example.com/" + "x" * 3000})
    assert r.status_code == 422
    err = r.json()["error"]
    assert err == {
        "code": "too_long",
        "message": err["message"],
        "field": "url",
    }
    assert "too long for a QR code" in err["message"]


def test_generate_rejects_unknown_preset(client: TestClient) -> None:
    r = client.post("/api/generate", json={"url": TRICKY_URLS[0], "preset": "fancy"})
    assert r.status_code == 422
    assert r.json()["error"]["field"] == "preset"


def test_missing_url_field(client: TestClient) -> None:
    r = client.post("/api/generate", json={})
    assert r.status_code == 422
    assert r.json()["error"]["field"] == "url"


def test_request_body_limit(client: TestClient) -> None:
    r = client.post(
        "/api/generate",
        content=b'{"url": "' + b"a" * (MAX_BODY_BYTES + 10) + b'"}',
        headers={"content-type": "application/json"},
    )
    assert r.status_code == 413


@pytest.mark.parametrize(
    ("preset", "outer", "variant"),
    [
        ("rounded", "transparent", "rounded-transparent"),
        ("rounded", "black", "rounded-black"),
        ("square", "transparent", "square"),
    ],
)
@pytest.mark.parametrize("size", [512, 1024, 2048])
def test_png_download(client: TestClient, preset: str, outer: str, variant: str, size: int) -> None:
    url = TRICKY_URLS[3]
    r = client.post(
        "/api/export/png",
        json={"url": url, "preset": preset, "outer_background": outer, "size": size},
    )
    assert r.status_code == 200
    assert r.headers["content-type"] == "image/png"
    assert r.headers["content-disposition"] == (
        f'attachment; filename="linkpress-qr-shop-example-org-{variant}-{size}px.png"'
    )
    image = Image.open(io.BytesIO(r.content))
    assert image.size == (size, size)
    assert image.mode == ("RGBA" if variant == "rounded-transparent" else "RGB")
    assert decode_png(r.content) == [url]


def test_png_download_with_ppi(client: TestClient) -> None:
    r = client.post("/api/export/png", json={"url": TRICKY_URLS[0], "size": 2048, "ppi": 600})
    assert r.status_code == 200
    assert r.headers["content-disposition"].endswith('-2048px-600ppi.png"')
    image = Image.open(io.BytesIO(r.content))
    assert round(image.info["dpi"][0]) == 600


@pytest.mark.parametrize(("field", "value"), [("size", 4096), ("size", 100), ("ppi", 72)])
def test_png_download_limits(client: TestClient, field: str, value: int) -> None:
    r = client.post("/api/export/png", json={"url": TRICKY_URLS[0], field: value})
    assert r.status_code == 422
    assert r.json()["error"]["field"] == field


def test_svg_download(client: TestClient) -> None:
    url = TRICKY_URLS[1]
    r = client.post(
        "/api/export/svg", json={"url": url, "preset": "rounded", "outer_background": "black"}
    )
    assert r.status_code == 200
    assert r.headers["content-type"].startswith("image/svg+xml")
    assert r.headers["content-disposition"] == (
        'attachment; filename="linkpress-qr-example-com-rounded-black.svg"'
    )
    assert r.text.startswith('<?xml version="1.0" encoding="UTF-8"?>')
    assert 'id="qr-modules"' in r.text


def test_preview_and_downloads_are_consistent(client: TestClient) -> None:
    """The preview SVG, SVG download and PNG download all describe the same symbol."""
    url = TRICKY_URLS[2]
    settings = {"url": url, "preset": "square", "outer_background": "transparent"}
    gen = client.post("/api/generate", json=settings).json()
    svg = client.post("/api/export/svg", json=settings)
    png = client.post("/api/export/png", json={**settings, "size": 512})
    assert svg.text == gen["svg"]
    assert svg.headers["x-qr-version"] == png.headers["x-qr-version"] == str(gen["version"])
    assert png.headers["x-qr-error-correction"] == "M"
    assert decode_png(png.content) == [gen["destination"]] == [url]


def test_export_errors_are_json(client: TestClient) -> None:
    r = client.post("/api/export/svg", json={"url": "ftp://example.com"})
    assert r.status_code == 422
    assert r.json()["error"]["field"] == "url"

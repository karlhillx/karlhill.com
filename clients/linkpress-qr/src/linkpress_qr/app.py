"""FastAPI application: HTML interface plus stateless generate/export endpoints.

Destinations are only ever sent in POST bodies (never in query strings) so they
do not appear in access logs, and the application itself never logs them.
"""

from __future__ import annotations

from pathlib import Path
from typing import Annotated, Any

from fastapi import FastAPI, Request
from fastapi.exceptions import RequestValidationError
from fastapi.responses import HTMLResponse, JSONResponse, Response
from fastapi.staticfiles import StaticFiles
from fastapi.templating import Jinja2Templates
from pydantic import BaseModel, ConfigDict, Field
from starlette.types import ASGIApp, Message, Receive, Scope, Send

from . import __version__
from .layout import PNG_SIZES, PPI_OPTIONS, CardBorder, OuterBackground, Preset
from .qr import QRCapacityError, QRMatrix, build
from .render import render_png, render_svg
from .urls import InvalidURLError, filename_stem

PACKAGE_DIR = Path(__file__).resolve().parent
TEMPLATES_DIR = PACKAGE_DIR / "templates"
STATIC_DIR = PACKAGE_DIR / "static"

MAX_BODY_BYTES = 32 * 1024
MAX_URL_FIELD_CHARS = 10_000

CSP = (
    "default-src 'self'; img-src 'self' blob: data:; style-src 'self'; script-src 'self'; "
    "connect-src 'self'; object-src 'none'; base-uri 'none'; form-action 'self'; "
    "frame-ancestors 'none'"
)


class BodyLimitMiddleware:
    """Reject request bodies larger than ``limit`` bytes with HTTP 413."""

    def __init__(self, app: ASGIApp, limit: int = MAX_BODY_BYTES) -> None:
        self.app = app
        self.limit = limit

    async def __call__(self, scope: Scope, receive: Receive, send: Send) -> None:
        if scope["type"] != "http" or scope["method"] in ("GET", "HEAD", "OPTIONS"):
            await self.app(scope, receive, send)
            return
        for name, value in scope["headers"]:
            if name == b"content-length" and value.isdigit() and int(value) > self.limit:
                await self._reject(scope, receive, send)
                return
        body = bytearray()
        while True:
            message = await receive()
            if message["type"] == "http.disconnect":
                return
            body += message.get("body", b"")
            if len(body) > self.limit:
                await self._reject(scope, receive, send)
                return
            if not message.get("more_body", False):
                break

        replayed = False

        async def replay() -> Message:
            nonlocal replayed
            if not replayed:
                replayed = True
                return {"type": "http.request", "body": bytes(body), "more_body": False}
            return await receive()

        await self.app(scope, replay, send)

    @staticmethod
    async def _reject(scope: Scope, receive: Receive, send: Send) -> None:
        response = _error(413, "request_too_large", "The request is too large.", None)
        await response(scope, receive, send)


class GenerateRequest(BaseModel):
    model_config = ConfigDict(extra="forbid")

    url: Annotated[str, Field(max_length=MAX_URL_FIELD_CHARS)]
    preset: Preset = Preset.ROUNDED
    outer_background: OuterBackground = OuterBackground.TRANSPARENT
    card_border: CardBorder = CardBorder.NONE


class PngExportRequest(GenerateRequest):
    size: int = 1024
    ppi: int | None = None


class GenerateResponse(BaseModel):
    destination: str
    version: int
    error_correction: str
    mask: int
    modules: int
    preset: Preset
    outer_background: OuterBackground
    card_border: CardBorder
    svg: str


def _error(status: int, code: str, message: str, field: str | None) -> JSONResponse:
    return JSONResponse(
        status_code=status,
        content={"error": {"code": code, "message": message, "field": field}},
        headers={"Cache-Control": "no-store"},
    )


def _encode_or_error(raw_url: str) -> QRMatrix | JSONResponse:
    try:
        return build(raw_url)
    except InvalidURLError as exc:
        return _error(422, "invalid_url", str(exc), "url")
    except QRCapacityError as exc:
        return _error(422, "too_long", str(exc), "url")


def _download(content: bytes, media_type: str, filename: str, matrix: QRMatrix) -> Response:
    return Response(
        content=content,
        media_type=media_type,
        headers={
            "Content-Disposition": f'attachment; filename="{filename}"',
            "Cache-Control": "no-store",
            "X-QR-Version": str(matrix.version),
            "X-QR-Error-Correction": matrix.error_correction,
        },
    )


def _variant(preset: Preset, outer: OuterBackground, border: CardBorder) -> str:
    variant = "square" if preset is Preset.SQUARE else f"rounded-{outer.value}"
    if preset is Preset.SQUARE or border is CardBorder.NONE:
        return variant
    return f"{variant}-black-border"


def create_app() -> FastAPI:
    app = FastAPI(
        title="Linkpress QR",
        version=__version__,
        docs_url=None,
        redoc_url=None,
        openapi_url=None,
    )
    templates = Jinja2Templates(directory=str(TEMPLATES_DIR))
    app.mount("/static", StaticFiles(directory=str(STATIC_DIR)), name="static")

    @app.middleware("http")
    async def security_headers(request: Request, call_next: Any) -> Response:
        response: Response = await call_next(request)
        response.headers.setdefault("Content-Security-Policy", CSP)
        response.headers.setdefault("X-Content-Type-Options", "nosniff")
        response.headers.setdefault("Referrer-Policy", "no-referrer")
        return response

    @app.exception_handler(RequestValidationError)
    async def validation_handler(_: Request, exc: RequestValidationError) -> JSONResponse:
        errors = exc.errors()
        first = errors[0] if errors else {}
        loc = [str(p) for p in first.get("loc", ()) if p != "body"]
        field = loc[0] if loc else None
        if field == "url" and first.get("type") == "string_too_long":
            message = "That link is far too long to fit in a QR code."
        elif field == "url":
            message = "Paste a link to generate a QR code."
        elif field in ("preset", "outer_background", "card_border"):
            message = "Choose a presentation preset from the options shown."
        elif field in ("size", "ppi"):
            message = "Choose an export option from the options shown."
        else:
            message = "The request couldn't be processed."
        return _error(422, "invalid_request", message, field)

    @app.get("/", response_class=HTMLResponse)
    async def index(request: Request) -> HTMLResponse:
        return templates.TemplateResponse(
            request,
            "index.html",
            {
                "version": __version__,
                "png_sizes": PNG_SIZES,
                "ppi_options": PPI_OPTIONS,
                "default_png_size": 1024,
            },
        )

    @app.get("/healthz")
    async def healthz() -> dict[str, str]:
        return {"status": "ok"}

    @app.post("/api/generate", response_model=GenerateResponse)
    async def generate(req: GenerateRequest) -> GenerateResponse | JSONResponse:
        result = _encode_or_error(req.url)
        if isinstance(result, JSONResponse):
            return result
        return GenerateResponse(
            destination=result.data,
            version=result.version,
            error_correction=result.error_correction,
            mask=result.mask,
            modules=result.size,
            preset=req.preset,
            outer_background=req.outer_background,
            card_border=req.card_border,
            svg=render_svg(
                result,
                preset=req.preset,
                outer_background=req.outer_background,
                card_border=req.card_border,
            ),
        )

    @app.post("/api/export/png")
    async def export_png(req: PngExportRequest) -> Response:
        if req.size not in PNG_SIZES:
            sizes = ", ".join(f"{s}" for s in PNG_SIZES)
            return _error(422, "invalid_size", f"PNG size must be one of {sizes} px.", "size")
        if req.ppi is not None and req.ppi not in PPI_OPTIONS:
            return _error(422, "invalid_ppi", "PPI must be 300 or 600.", "ppi")
        result = _encode_or_error(req.url)
        if isinstance(result, JSONResponse):
            return result
        png = render_png(
            result,
            preset=req.preset,
            outer_background=req.outer_background,
            card_border=req.card_border,
            size=req.size,
            ppi=req.ppi,
        )
        ppi_part = f"-{req.ppi}ppi" if req.ppi else ""
        name = (
            f"linkpress-qr-{filename_stem(result.data)}-"
            f"{_variant(req.preset, req.outer_background, req.card_border)}-"
            f"{req.size}px{ppi_part}.png"
        )
        return _download(png, "image/png", name, result)

    @app.post("/api/export/svg")
    async def export_svg(req: GenerateRequest) -> Response:
        result = _encode_or_error(req.url)
        if isinstance(result, JSONResponse):
            return result
        svg = render_svg(
            result,
            preset=req.preset,
            outer_background=req.outer_background,
            card_border=req.card_border,
        )
        name = (
            f"linkpress-qr-{filename_stem(result.data)}-"
            f"{_variant(req.preset, req.outer_background, req.card_border)}.svg"
        )
        return _download(svg.encode("utf-8"), "image/svg+xml", name, result)

    app.add_middleware(BodyLimitMiddleware, limit=MAX_BODY_BYTES)
    return app


app = create_app()

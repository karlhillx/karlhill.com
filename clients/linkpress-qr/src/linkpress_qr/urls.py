"""Destination URL validation.

Validation never rewrites the destination: the only change made is trimming
surrounding whitespace. Everything else (case, path, query order, percent
encoding, fragment) is encoded exactly as submitted.
"""

from __future__ import annotations

import re
from urllib.parse import urlsplit

MAX_URL_LENGTH = 4096
"""Hard input limit. Well above QR capacity (~2.3 KB at level M), so longer
input fails with a clear message before any encoding work is attempted."""

_ALLOWED_SCHEMES = frozenset({"http", "https"})
_SCHEME_RE = re.compile(r"^[A-Za-z][A-Za-z0-9+.\-]*:")
_FORBIDDEN_CHARS_RE = re.compile(r"[\s\x00-\x1f\x7f]")
_HOST_LIKE_RE = re.compile(r"^[^\s/:?#]+\.[^\s/:?#]+")


class InvalidURLError(ValueError):
    """Raised when a destination cannot be accepted. ``str(exc)`` is user-facing."""


def validate_url(raw: str) -> str:
    """Return the destination to encode, or raise :class:`InvalidURLError`.

    The returned string is ``raw`` with only leading/trailing whitespace removed.
    """
    url = raw.strip()
    if not url:
        raise InvalidURLError("Paste a link to generate a QR code.")
    if len(url) > MAX_URL_LENGTH:
        raise InvalidURLError(
            f"That link is {len(url):,} characters long. Links longer than "
            f"{MAX_URL_LENGTH:,} characters can't be encoded."
        )
    if _FORBIDDEN_CHARS_RE.search(url):
        raise InvalidURLError("Links can't contain spaces or line breaks. Replace spaces with %20.")

    scheme_match = _SCHEME_RE.match(url)
    # "example.com:8080/x" parses as scheme "example.com"; treat dotted
    # schemes followed by digits as a missing scheme instead.
    looks_like_host_port = bool(re.match(r"^[^/:]+\.[^/:]+:\d", url))
    if scheme_match is None or looks_like_host_port:
        if url.startswith("//") or _HOST_LIKE_RE.match(url) or looks_like_host_port:
            suggestion = "https://" + url.removeprefix("//")
            raise InvalidURLError(
                f"Add https:// (or http://) to the start of the link, e.g. {suggestion}"
            )
        raise InvalidURLError(
            "Enter a complete link that starts with https:// or http://, "
            "e.g. https://example.com/tickets"
        )

    scheme = scheme_match.group(0)[:-1].lower()
    if scheme not in _ALLOWED_SCHEMES:
        raise InvalidURLError(
            f"Only http:// and https:// links are supported (this link uses {scheme}:)."
        )
    if not url[len(scheme) + 1 :].startswith("//"):
        raise InvalidURLError(
            f"The link is missing // after {scheme}:. It should look like {scheme}://example.com"
        )

    try:
        parts = urlsplit(url)
        hostname = parts.hostname
    except ValueError:
        raise InvalidURLError("The hostname in the link isn't valid.") from None
    if not hostname:
        raise InvalidURLError(
            f"The link is missing a hostname. Add a domain such as example.com after {scheme}://"
        )
    if hostname.startswith(".") or ".." in hostname:
        raise InvalidURLError(f"“{hostname}” isn't a valid hostname.")
    try:
        _ = parts.port
    except ValueError:
        raise InvalidURLError("The port number in the link isn't valid.") from None
    return url


def filename_stem(url: str) -> str:
    """A filesystem-safe, ASCII-only name fragment derived from the hostname."""
    try:
        host = urlsplit(url).hostname or ""
    except ValueError:
        host = ""
    host = host.removeprefix("www.")
    slug = re.sub(r"[^a-z0-9]+", "-", host.lower()).strip("-")[:48].strip("-")
    return slug or "link"

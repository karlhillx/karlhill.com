from __future__ import annotations

import pytest

from linkpress_qr.urls import MAX_URL_LENGTH, InvalidURLError, filename_stem, validate_url

from .conftest import TRICKY_URLS


@pytest.mark.parametrize("url", TRICKY_URLS)
def test_valid_urls_are_returned_unchanged(url: str) -> None:
    assert validate_url(url) == url


def test_only_surrounding_whitespace_is_trimmed() -> None:
    assert validate_url("  \thttps://example.com/A?b=1#C \n") == "https://example.com/A?b=1#C"


@pytest.mark.parametrize(
    "url",
    [
        "HTTPS://EXAMPLE.COM",
        "http://localhost:8000/x",
        "https://192.168.1.10/admin",
        "https://[2001:db8::1]/v6",
        "https://例え.jp/パス",
        "https://user@example.com/",
    ],
)
def test_other_valid_forms(url: str) -> None:
    assert validate_url(url) == url


@pytest.mark.parametrize(
    ("raw", "fragment"),
    [
        ("", "Paste a link"),
        ("   ", "Paste a link"),
        ("example.com/tickets", "Add https://"),
        ("www.example.com", "Add https://"),
        ("example.com:8080/x", "Add https://"),
        ("//example.com/x", "Add https://"),
        ("tickets", "starts with https://"),
        ("ftp://example.com/file", "Only http:// and https://"),
        ("javascript:alert(1)", "Only http:// and https://"),
        ("mailto:someone@example.com", "Only http:// and https://"),
        ("https:example.com", "missing //"),
        ("https://", "missing a hostname"),
        ("https:///path", "missing a hostname"),
        ("https://?q=1", "missing a hostname"),
        ("https://exa mple.com", "spaces"),
        ("https://example.com/a\nb", "spaces"),
        ("https://example.com:99999/", "port"),
        ("https://example..com/", "valid hostname"),
        ("https://[::1/", "hostname"),
    ],
)
def test_invalid_urls_give_actionable_errors(raw: str, fragment: str) -> None:
    with pytest.raises(InvalidURLError) as exc:
        validate_url(raw)
    assert fragment in str(exc.value)


def test_suggestion_preserves_the_rest_of_the_input() -> None:
    with pytest.raises(InvalidURLError, match=r"https://example\.com/Tix\?a=1"):
        validate_url("example.com/Tix?a=1")


def test_overlong_input_is_rejected() -> None:
    with pytest.raises(InvalidURLError, match="can't be encoded"):
        validate_url("https://example.com/" + "a" * MAX_URL_LENGTH)


@pytest.mark.parametrize(
    ("url", "stem"),
    [
        ("https://www.Example.com/x", "example-com"),
        ("https://例え.jp/", "jp"),
        ("http://[::1]/", "1"),
        ("https://" + "a" * 80 + ".com", "a" * 48),
    ],
)
def test_filename_stem_is_ascii_safe(url: str, stem: str) -> None:
    assert filename_stem(url) == stem

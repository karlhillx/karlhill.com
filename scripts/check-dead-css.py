#!/usr/bin/env python3
"""Fail when resources/css defines a class selector nothing in the app references.

Class tokens are collected from every author stylesheet and looked up as plain
substrings across views, JS, PHP, and the static public shell. Classes that
only exist at runtime (highlighter output, modifier suffixes composed in Blade)
are allowlisted below by prefix or exact name.

Usage: python3 scripts/check-dead-css.py [--list]
"""

from __future__ import annotations

import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
CSS_DIRS = [ROOT / "resources/css"]
SEARCH_DIRS = [
    ROOT / "resources/views",
    ROOT / "resources/js",
    ROOT / "app",
    ROOT / "config",
    ROOT / "public/offline.html",
    ROOT / "public/sw.js",
]
SEARCH_SUFFIXES = {".php", ".js", ".mjs", ".html"}

# Runtime-generated classes: Tempest highlighter tokens, delivery-map variants
# composed from config ids, and portfolio group modifiers from project data.
ALLOW_PREFIXES = ("hl-", "delivery-map__node--", "delivery-map__sat--", "portfolio-card--")
ALLOW_EXACT: set[str] = set()

CLASS_RE = re.compile(r"\.(-?[_a-zA-Z][_a-zA-Z0-9-]*)")
# Skip numeric-looking tokens inside values (e.g. `.5rem` never matches the
# identifier start, but `.sm\:mb-12` style escaped utilities do).
ESCAPED_RE = re.compile(r"\\:")


def css_files() -> list[Path]:
    files: list[Path] = []
    for d in CSS_DIRS:
        files.extend(sorted(d.rglob("*.css")))
    return files


def strip_comments_and_values(css: str) -> str:
    css = re.sub(r"/\*.*?\*/", "", css, flags=re.S)
    # Drop declaration blocks so `url(./a.b)` or `0.5rem` never look like classes.
    out, depth, buf = [], 0, []
    for ch in css:
        if ch == "{":
            depth += 1
            if depth == 1:
                out.append("".join(buf))
                buf = []
            continue
        if ch == "}":
            depth -= 1
            continue
        if depth == 0:
            buf.append(ch)
        elif depth >= 1 and ch in "{}":
            pass
        else:
            # nested rules inside @media keep their selectors
            if depth == 1:
                buf.append(ch)
    out.append("".join(buf))
    return "\n".join(out)


def defined_classes() -> set[str]:
    classes: set[str] = set()
    for path in css_files():
        text = strip_comments_and_values(path.read_text())
        for match in CLASS_RE.finditer(text):
            name = match.group(1)
            if ESCAPED_RE.search(name):
                continue
            classes.add(name)
    return classes


def haystack() -> str:
    chunks: list[str] = []
    for target in SEARCH_DIRS:
        if target.is_file():
            chunks.append(target.read_text(errors="ignore"))
            continue
        for path in target.rglob("*"):
            if path.suffix in SEARCH_SUFFIXES and path.is_file():
                chunks.append(path.read_text(errors="ignore"))
    return "\n".join(chunks)


def allowed(name: str) -> bool:
    return name in ALLOW_EXACT or name.startswith(ALLOW_PREFIXES)


def main() -> int:
    text = haystack()
    dead = sorted(c for c in defined_classes() if not allowed(c) and c not in text)
    if "--list" in sys.argv or dead:
        for name in dead:
            print(name)
    if dead:
        print(
            f"error: {len(dead)} CSS class selector(s) are never referenced "
            "(delete the rules or allowlist runtime-only classes in scripts/check-dead-css.py)",
            file=sys.stderr,
        )
        return 1
    print("Dead CSS check: no unreferenced class selectors.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

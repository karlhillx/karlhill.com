"""Wheel-install smoke check.

Builds the wheel, installs it into a fresh virtual environment in a temporary
directory (outside the source tree), launches ``linkpress-qr --no-browser`` from
that directory, and exercises the page, static assets and export endpoints.

Run with:  uv run python scripts/smoke_wheel.py
"""

from __future__ import annotations

import json
import os
import shutil
import socket
import subprocess
import sys
import tempfile
import time
import urllib.error
import urllib.request
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
URL = "https://Example.com/Smoke%20Test?b=2&a=1#Frag"


def free_port() -> int:
    with socket.socket() as s:
        s.bind(("127.0.0.1", 0))
        return int(s.getsockname()[1])


def request(
    base: str, path: str, payload: dict[str, object] | None = None
) -> tuple[int, dict[str, str], bytes]:
    data = json.dumps(payload).encode() if payload is not None else None
    req = urllib.request.Request(base + path, data=data, method="POST" if data else "GET")
    if data:
        req.add_header("Content-Type", "application/json")
    with urllib.request.urlopen(req, timeout=10) as resp:
        return resp.status, dict(resp.headers), resp.read()


def check(condition: bool, label: str) -> None:
    print(("  ok   " if condition else "  FAIL ") + label)
    if not condition:
        raise SystemExit(1)


def main() -> int:
    uv = shutil.which("uv")
    if uv is None:
        raise SystemExit("uv is required")
    with tempfile.TemporaryDirectory(prefix="linkpress-smoke-") as tmp_str:
        tmp = Path(tmp_str)
        print(f"Building wheel into {tmp}")
        subprocess.run(
            [uv, "build", "--wheel", "--out-dir", str(tmp / "dist")], cwd=ROOT, check=True
        )
        wheel = next((tmp / "dist").glob("linkpress_qr-*.whl"))
        venv = tmp / "venv"
        subprocess.run([uv, "venv", "--python", "3.12", str(venv)], cwd=tmp, check=True)
        subprocess.run(
            [uv, "pip", "install", "--python", str(venv / "bin" / "python"), str(wheel)],
            cwd=tmp,
            check=True,
        )

        python = venv / "bin" / "python"
        location = subprocess.run(
            [str(python), "-c", "import linkpress_qr, sys; print(linkpress_qr.__file__)"],
            cwd=tmp,
            check=True,
            capture_output=True,
            text=True,
        ).stdout.strip()
        check(str(ROOT) not in location, f"imported from installed wheel ({location})")

        port = free_port()
        base = f"http://127.0.0.1:{port}"
        env = {k: v for k, v in os.environ.items() if k not in ("VIRTUAL_ENV", "PYTHONPATH")}
        proc = subprocess.Popen(
            [str(venv / "bin" / "linkpress-qr"), "--no-browser", "--port", str(port)],
            cwd=tmp,
            env=env,
            stdout=subprocess.PIPE,
            stderr=subprocess.STDOUT,
            text=True,
        )
        try:
            for _ in range(100):
                try:
                    request(base, "/healthz")
                    break
                except (urllib.error.URLError, ConnectionError):
                    time.sleep(0.1)
            else:
                raise SystemExit("server did not start")

            status, _, body = request(base, "/")
            check(status == 200 and b"Generate QR code" in body, "index template renders")
            for asset in ("app.css", "app.js", "favicon.svg"):
                status, _, body = request(base, f"/static/{asset}")
                check(status == 200 and len(body) > 100, f"static/{asset} served")

            status, _, body = request(base, "/api/generate", {"url": URL})
            check(
                status == 200 and json.loads(body)["destination"] == URL, "generate preserves URL"
            )

            status, headers, body = request(base, "/api/export/png", {"url": URL, "size": 512})
            check(
                status == 200
                and headers["content-type"] == "image/png"
                and body.startswith(b"\x89PNG\r\n\x1a\n"),
                "PNG export",
            )
            status, headers, body = request(base, "/api/export/svg", {"url": URL})
            check(status == 200 and b'id="qr-modules"' in body, "SVG export")
        finally:
            proc.terminate()
            out, _ = proc.communicate(timeout=10)
        check(f"http://127.0.0.1:{port}/" in out, "CLI printed local URL")
        check(URL not in out and "Smoke" not in out, "destination URL not logged")
    print("Wheel smoke check passed.")
    return 0


if __name__ == "__main__":
    sys.exit(main())

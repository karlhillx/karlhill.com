"""``linkpress-qr`` command: start the local server and open the browser."""

from __future__ import annotations

import argparse
import errno
import socket
import sys
import threading
import time
import webbrowser
from collections.abc import Sequence

import uvicorn

from . import __version__

DEFAULT_HOST = "127.0.0.1"
DEFAULT_PORT = 8000


def _port(value: str) -> int:
    try:
        port = int(value)
    except ValueError:
        raise argparse.ArgumentTypeError("port must be a number") from None
    if not 1 <= port <= 65535:
        raise argparse.ArgumentTypeError("port must be between 1 and 65535")
    return port


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(
        prog="linkpress-qr",
        description="Linkpress QR — Clean QR codes. Ready for print. "
        "Starts a local web app for generating QR codes.",
    )
    parser.add_argument(
        "--port",
        type=_port,
        default=DEFAULT_PORT,
        help=f"port to listen on (default {DEFAULT_PORT})",
    )
    parser.add_argument(
        "--no-browser", action="store_true", help="don't open a browser window automatically"
    )
    parser.add_argument("--version", action="version", version=f"%(prog)s {__version__}")
    return parser


def _port_available(host: str, port: int) -> bool:
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as sock:
        try:
            sock.bind((host, port))
        except OSError as exc:
            if exc.errno in (errno.EADDRINUSE, errno.EACCES):
                return False
            raise
    return True


def _open_when_ready(server: uvicorn.Server, url: str) -> None:
    deadline = time.monotonic() + 15
    while not server.started and not server.should_exit and time.monotonic() < deadline:
        time.sleep(0.05)
    if server.started:
        webbrowser.open(url)


def main(argv: Sequence[str] | None = None) -> int:
    args = build_parser().parse_args(argv)
    host = DEFAULT_HOST
    url = f"http://{host}:{args.port}/"

    if not _port_available(host, args.port):
        print(
            f"Port {args.port} is already in use. Try: linkpress-qr --port {args.port + 1}",
            file=sys.stderr,
        )
        return 1

    config = uvicorn.Config(
        "linkpress_qr.app:app",
        host=host,
        port=args.port,
        log_level="warning",
        access_log=False,
    )
    server = uvicorn.Server(config)

    print(f"Linkpress QR {__version__} — Clean QR codes. Ready for print.")
    print(f"Running at {url}  (press Ctrl+C to stop)", flush=True)

    if not args.no_browser:
        threading.Thread(target=_open_when_ready, args=(server, url), daemon=True).start()

    server.run()
    return 0


if __name__ == "__main__":  # pragma: no cover
    raise SystemExit(main())

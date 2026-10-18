from __future__ import annotations

import socket
from unittest import mock

import pytest

from linkpress_qr import cli
from linkpress_qr.app import STATIC_DIR, TEMPLATES_DIR


def test_defaults() -> None:
    args = cli.build_parser().parse_args([])
    assert args.port == 8000
    assert args.no_browser is False


def test_options() -> None:
    args = cli.build_parser().parse_args(["--port", "9123", "--no-browser"])
    assert args.port == 9123
    assert args.no_browser is True


@pytest.mark.parametrize("port", ["0", "70000", "abc"])
def test_invalid_port(port: str) -> None:
    with pytest.raises(SystemExit):
        cli.build_parser().parse_args(["--port", port])


def test_main_starts_local_server(capsys: pytest.CaptureFixture[str]) -> None:
    with (
        mock.patch.object(cli, "_port_available", return_value=True),
        mock.patch.object(cli.uvicorn.Server, "run") as run,
        mock.patch.object(cli.webbrowser, "open") as browser,
    ):
        assert cli.main(["--port", "8123", "--no-browser"]) == 0
    run.assert_called_once()
    browser.assert_not_called()
    assert "http://127.0.0.1:8123/" in capsys.readouterr().out


def test_port_in_use_message(capsys: pytest.CaptureFixture[str]) -> None:
    with socket.socket() as sock:
        sock.bind(("127.0.0.1", 0))
        sock.listen()
        port = sock.getsockname()[1]
        assert cli.main(["--port", str(port), "--no-browser"]) == 1
    assert "already in use" in capsys.readouterr().err


def test_assets_resolved_from_package_not_cwd() -> None:
    assert TEMPLATES_DIR.is_absolute()
    assert (TEMPLATES_DIR / "index.html").is_file()
    assert (STATIC_DIR / "app.js").is_file()

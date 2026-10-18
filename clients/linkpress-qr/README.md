# Linkpress QR

**Clean QR codes. Ready for print.**

Linkpress QR is a small local web app: paste a link, click **Generate QR code**, check the
exact destination, and download a print-ready PNG or SVG. It runs entirely on your computer.
Links are encoded directly and are never fetched, shortened, logged, or sent anywhere. After
installation, it works offline.

- Standard QR codes (never Micro QR), error correction level **M**, smallest suitable version.
- Two presets:
  - **Rounded card**: a white card with subtly rounded corners on a transparent or black canvas.
  - **Classic square**: a plain QR code with its white margin.
- The rounded card can have a black rounded outline or no outline.
- Square modules and finder patterns. A solid white quiet zone of at least 4 modules always surrounds the code.
- PNG at 512, 1024 or 2048 px, with optional 300 / 600 PPI metadata.
- True-vector SVG with named, editable layers.

## Install and run

Requires [uv](https://docs.astral.sh/uv/) and Python 3.12+.

```sh
uv sync --locked
uv run linkpress-qr
```

The server starts on <http://127.0.0.1:8000/>, prints the address, and opens your browser.
Press Ctrl+C to stop it.

```sh
uv run linkpress-qr --port 8080      # another port
uv run linkpress-qr --no-browser     # don't open a browser window
```

To install it as a standalone command:
`uv tool install .` (then run `linkpress-qr` from anywhere).

## Usage

1. Paste a complete `http://` or `https://` link (e.g. `https://example.com/tickets`).
2. Optionally choose **Rounded card** or **Classic square**. For the rounded card, choose a
   transparent or black area outside the card.
3. Press **Generate QR code** (or Enter).
4. Check the destination shown under **Opens**. This is exactly what the code contains.
5. Pick a PNG size and click **Download PNG** or **Download SVG**.

The link is encoded byte-for-byte as entered, apart from trimming surrounding whitespace. Case,
path, query-parameter order, percent-encoding and `#fragment` are preserved.

If you change the link or style after generating, the preview is marked out of date and
downloads are disabled until you generate again. The preview and the downloads always show
the same code.

## PNG or SVG?

| Use | Choose |
| --- | --- |
| Web pages, slides, documents, messaging | **PNG**, 1024 px |
| Print from a word processor or print shop that wants pixels | **PNG**, 2048 px with 300 PPI |
| Design tools (Affinity Designer, Illustrator, Inkscape), signage, any size | **SVG** |

SVG files contain pure vector shapes and no embedded images or scripts. One `viewBox` unit
equals one QR module. The layers are named for easy editing:

- `outer-background`: black canvas, rounded card with black outside only.
- `card`: the rounded white card.
- `qr-code`, containing:
  - `qr-background`: the white quiet zone and background.
  - `qr-modules`: the black modules.

If you recolour or restyle a code, keep dark modules on a light background and leave the white
quiet zone intact.

PNG modules are drawn at a whole number of pixels with no smoothing. Any leftover space
becomes extra padding, so the code stays perfectly crisp. Only the card's rounded edge is
anti-aliased.

## PPI, pixels and print size

Print size = pixels ÷ PPI (pixels per inch).

| PNG | at 300 PPI | at 600 PPI |
| --- | --- | --- |
| 512 px | 1.71 in (43.3 mm) | 0.85 in (21.7 mm) |
| 1024 px | 3.41 in (86.7 mm) | 1.71 in (43.3 mm) |
| 2048 px | 6.83 in (173.4 mm) | 3.41 in (86.7 mm) |

The PPI setting (under **Advanced export settings**) only writes resolution metadata into the
PNG. The pixels are identical, so layout software can choose a default print size. For printing,
300 PPI is a good default. Use 600 PPI if you want a small, very sharp code. For flexible
sizing, use SVG. As a rule of thumb, print a QR code at least 2 cm (0.8 in) wide, larger for
long links or for scanning at a distance.

## Manual scan check (recommended before printing)

1. **Digital:** open the downloaded PNG full screen. Scan it with a phone's camera app (iOS and
   Android) and confirm the link preview matches the **Opens** text exactly.
2. **Printed:** print the PNG or SVG at the intended size on the intended paper. Scan it from
   about 20–30 cm, in normal lighting, with at least two different phones. Check the destination
   again before opening it.
3. For the transparent rounded card, also check the code on the real background colour it will
   be placed on.

## Use as a library

```python
from linkpress_qr import CardBorder, Preset, OuterBackground, build, render_png, render_svg

matrix = build("https://example.com/tickets")  # validates, then encodes (level M)
png = render_png(
    matrix,
    preset=Preset.ROUNDED,
    outer_background=OuterBackground.BLACK,
    card_border=CardBorder.BLACK,
    size=2048,
    ppi=300,
)
svg = render_svg(matrix, preset=Preset.SQUARE)
```

## Development

```sh
uv sync --locked                       # install app + dev tools
uv run ruff format .                   # format
uv run ruff check .                    # lint
uv run ty check                        # type-check
uv run pytest                          # tests (PNG exports are decoded with zxing-cpp)
uv run python scripts/smoke_wheel.py   # build wheel, install in a temp venv, exercise server
```

Project layout: `src/linkpress_qr/` contains:

- `urls.py`: validation.
- `qr.py`: Segno encoding.
- `layout.py`: shared geometry.
- `render.py`: PNG/SVG rendering.
- `app.py`: FastAPI routes.
- `cli.py`: the CLI.
- `templates/` and `static/`: the interface.

## Limitations

- Only `http://` and `https://` links. There's no Wi-Fi, vCard or plain-text payloads.
- Capacity is roughly 2,300 bytes at level M. Very long links produce dense codes that are
  harder to scan, so shorter links scan best.
- Links with non-ASCII characters are encoded as UTF-8 without an ECI marker. Modern phone
  scanners handle this, but some older readers may not. Percent-encoded links are safest.
- No accounts, history, logo overlays, colours, or batch generation in this version.
- The server is intended for local use on `127.0.0.1` and has no authentication.

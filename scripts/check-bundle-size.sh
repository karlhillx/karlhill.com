#!/usr/bin/env bash
# Fail CI if the main Vite CSS/JS bundles grow past budgeted ceilings.
# Thresholds ≈ current build + ~15% headroom (update when intentional growth lands).
# Client entrypoints (e.g. Dry Standard site.js) share public/build but are not
# part of the karlhill.com JS budget — count only resources/js/* from the manifest.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
CSS_MAX=176000   # bytes (screen critical CSS: motion gates + case study deep dives; print decoupled)
PRINT_CSS_MAX=15000 # bytes (decoupled print stylesheet)
JS_MAX=18500     # bytes — core app.js after route-level splitting
JS_TOTAL_MAX=54000  # core + lazy chunks (analytics transport, summarizer, WebGPU)

css="$(find "$ROOT/public/build/assets" -maxdepth 1 -name 'app-*.css' -print -quit)"
print_css="$(find "$ROOT/public/build/assets" -maxdepth 1 -name 'print-*.css' -print -quit)"
js="$(find "$ROOT/public/build/assets" -maxdepth 1 -name 'app-*.js' -print -quit)"
manifest="$ROOT/public/build/manifest.json"

if [[ -z "${css}" || -z "${js}" ]]; then
    echo "error: built app-*.css / app-*.js not found under public/build/assets (run npm run build first)" >&2
    exit 1
fi

if [[ ! -f "$manifest" ]]; then
    echo "error: missing $manifest (run npm run build first)" >&2
    exit 1
fi

css_size="$(wc -c < "$css" | tr -d ' ')"
js_size="$(wc -c < "$js" | tr -d ' ')"

# Sum only JS emitted from resources/js/* (exclude client entrypoints in the same build).
js_total="$(
    python3 - "$manifest" "$ROOT/public/build" <<'PY'
import json, sys
from pathlib import Path

manifest_path, build_root = Path(sys.argv[1]), Path(sys.argv[2])
manifest = json.loads(manifest_path.read_text())
seen = set()
total = 0
for key, entry in manifest.items():
    if not key.startswith("resources/js/"):
        continue
    if not isinstance(entry, dict):
        continue
    rel = entry.get("file")
    if not rel or not rel.endswith(".js"):
        continue
    path = build_root / rel
    if path in seen or not path.is_file():
        continue
    seen.add(path)
    total += path.stat().st_size
print(total)
PY
)"

echo "Bundle sizes: CSS ${css_size}B ($(basename "$css")), JS core ${js_size}B ($(basename "$js")), JS total ${js_total}B (resources/js only)"
echo "Budgets:      CSS ≤ ${CSS_MAX}B, JS core ≤ ${JS_MAX}B, JS total ≤ ${JS_TOTAL_MAX}B"

fail=0
if (( css_size > CSS_MAX )); then
    echo "error: CSS bundle ${css_size}B exceeds budget ${CSS_MAX}B" >&2
    fail=1
fi
if [[ -n "${print_css}" ]]; then
    print_css_size="$(wc -c < "$print_css" | tr -d ' ')"
    echo "              Print CSS ${print_css_size}B ($(basename "$print_css")) [budget ≤ ${PRINT_CSS_MAX}B]"
    if (( print_css_size > PRINT_CSS_MAX )); then
        echo "error: Print CSS bundle ${print_css_size}B exceeds budget ${PRINT_CSS_MAX}B" >&2
        fail=1
    fi
fi
if (( js_size > JS_MAX )); then
    echo "error: JS core ${js_size}B exceeds budget ${JS_MAX}B" >&2
    fail=1
fi
if (( js_total > JS_TOTAL_MAX )); then
    echo "error: JS total ${js_total}B exceeds budget ${JS_TOTAL_MAX}B" >&2
    fail=1
fi

exit "$fail"

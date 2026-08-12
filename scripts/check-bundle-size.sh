#!/usr/bin/env bash
# Fail CI if the main Vite CSS/JS bundles grow past budgeted ceilings.
# Thresholds ≈ current build + ~15% headroom (update when intentional growth lands).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
CSS_MAX=160000   # bytes (print/kit + motion gates)
JS_MAX=18000     # bytes — core app.js after route-level splitting
JS_TOTAL_MAX=45000  # core + lazy chunks

css="$(find "$ROOT/public/build/assets" -maxdepth 1 -name 'app-*.css' -print -quit)"
js="$(find "$ROOT/public/build/assets" -maxdepth 1 -name 'app-*.js' -print -quit)"

if [[ -z "${css}" || -z "${js}" ]]; then
    echo "error: built app-*.css / app-*.js not found under public/build/assets (run npm run build first)" >&2
    exit 1
fi

css_size="$(wc -c < "$css" | tr -d ' ')"
js_size="$(wc -c < "$js" | tr -d ' ')"
js_total=0
while IFS= read -r -d '' f; do
    js_total=$((js_total + $(wc -c < "$f" | tr -d ' ')))
done < <(find "$ROOT/public/build/assets" -maxdepth 1 -name '*.js' -print0)

echo "Bundle sizes: CSS ${css_size}B ($(basename "$css")), JS core ${js_size}B ($(basename "$js")), JS total ${js_total}B"
echo "Budgets:      CSS ≤ ${CSS_MAX}B, JS core ≤ ${JS_MAX}B, JS total ≤ ${JS_TOTAL_MAX}B"

fail=0
if (( css_size > CSS_MAX )); then
    echo "error: CSS bundle ${css_size}B exceeds budget ${CSS_MAX}B" >&2
    fail=1
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

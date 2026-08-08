#!/usr/bin/env bash
# Fail CI if the main Vite CSS/JS bundles grow past budgeted ceilings.
# Thresholds ≈ current build + ~15% headroom (update when intentional growth lands).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
CSS_MAX=150000   # bytes (~125KB current)
JS_MAX=25000     # bytes (~17KB current)

css="$(find "$ROOT/public/build/assets" -maxdepth 1 -name 'app-*.css' -print -quit)"
js="$(find "$ROOT/public/build/assets" -maxdepth 1 -name 'app-*.js' -print -quit)"

if [[ -z "${css}" || -z "${js}" ]]; then
    echo "error: built app-*.css / app-*.js not found under public/build/assets (run npm run build first)" >&2
    exit 1
fi

css_size="$(wc -c < "$css" | tr -d ' ')"
js_size="$(wc -c < "$js" | tr -d ' ')"

echo "Bundle sizes: CSS ${css_size}B ($(basename "$css")), JS ${js_size}B ($(basename "$js"))"
echo "Budgets:      CSS ≤ ${CSS_MAX}B, JS ≤ ${JS_MAX}B"

fail=0
if (( css_size > CSS_MAX )); then
    echo "error: CSS bundle ${css_size}B exceeds budget ${CSS_MAX}B" >&2
    fail=1
fi
if (( js_size > JS_MAX )); then
    echo "error: JS bundle ${js_size}B exceeds budget ${JS_MAX}B" >&2
    fail=1
fi

exit "$fail"

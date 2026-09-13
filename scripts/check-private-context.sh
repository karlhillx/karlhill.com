#!/usr/bin/env bash
set -euo pipefail

protected_paths=(
  "docs/context-private"
  ".cursor/private"
)

tracked="$(git ls-files -- "${protected_paths[@]}")"

if [[ -n "$tracked" ]]; then
  echo "ERROR: Private Cursor context is tracked by Git:" >&2
  echo "$tracked" >&2
  echo "Remove it from the index with:" >&2
  echo "git rm --cached -r --ignore-unmatch docs/context-private .cursor/private" >&2
  exit 1
fi

staged="$(git diff --cached --name-only --diff-filter=ACMR -- "${protected_paths[@]}")"

if [[ -n "$staged" ]]; then
  echo "ERROR: Private Cursor context is staged:" >&2
  echo "$staged" >&2
  exit 1
fi

echo "Private Cursor context is not tracked or staged."

#!/usr/bin/env bash
# Production deploy for karlhill.com. Run on the server from the app root.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "→ Pulling latest code"
if ! git pull --ff-only origin main 2>/dev/null && ! git pull --ff-only; then
  echo "error: git pull failed." >&2
  if find .git -user root -print -quit 2>/dev/null | grep -q .; then
    echo "The repo has root-owned git files. Fix once via SSH:" >&2
    echo "  sudo chown -R $(whoami):$(whoami) $ROOT" >&2
    echo "Or use the GitHub Actions deploy workflow, which rsyncs code instead." >&2
  fi
  exit 1
fi

bash "$ROOT/scripts/deploy-remote.sh"

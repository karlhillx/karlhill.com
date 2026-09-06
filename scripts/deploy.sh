#!/usr/bin/env bash
# EMERGENCY FALLBACK ONLY. The GitHub Actions "Deploy" workflow is the
# supported path — it tars the repo over SSH straight into the app
# container and never touches host git state. This script instead runs
# `git pull` on the HOST, which requires the host user to own every
# tracked file; the app container writes some paths as its own runtime
# user, so this can start failing with "Permission denied" on unlink/
# create with no easy recovery if you don't have root on the box. Only
# reach for this if the Actions workflow is itself unavailable, and fix
# ownership from inside the container first (`docker exec -u root ...
# chown`) rather than `sudo chown`-ing the host tree.
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

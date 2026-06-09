#!/usr/bin/env bash
# Build and optimize on the server after code is synced.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "→ Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

echo "→ Installing JS dependencies and building assets"
npm ci
npm run build

ensure_python_image_tools() {
  if python3 -c "from PIL import Image" >/dev/null 2>&1; then
    return 0
  fi

  echo "→ Ensuring Python image tooling"

  if command -v apt-get >/dev/null 2>&1; then
    apt-get update -qq
    DEBIAN_FRONTEND=noninteractive apt-get install -y -qq python3-pil python3-pip fonts-dejavu-core
  fi

  if ! python3 -c "from PIL import Image" >/dev/null 2>&1; then
    if python3 -m pip --version >/dev/null 2>&1; then
      python3 -m pip install -q -r scripts/requirements.txt
    elif python3 -m ensurepip --upgrade >/dev/null 2>&1; then
      python3 -m pip install -q -r scripts/requirements.txt
    fi
  fi

  if ! python3 -c "from PIL import Image" >/dev/null 2>&1; then
    echo "error: Pillow is required for OG and WebP generation" >&2
    exit 1
  fi
}

ensure_python_image_tools

echo "→ Generating OG images and WebP variants"
php artisan og:generate --quiet
php artisan assets:webp --quiet

echo "→ Optimizing Laravel"
php artisan optimize

echo "✓ Deploy complete"

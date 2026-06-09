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

if command -v python3 >/dev/null 2>&1; then
  echo "→ Ensuring Python image tooling"
  python3 -m pip install --user -q -r scripts/requirements.txt 2>/dev/null \
    || python3 -m pip install -q -r scripts/requirements.txt
else
  echo "error: python3 is required for OG and WebP generation" >&2
  exit 1
fi

echo "→ Generating OG images and WebP variants"
php artisan og:generate --quiet
php artisan assets:webp --quiet

echo "→ Optimizing Laravel"
php artisan optimize

echo "→ Reloading PHP (if docker compose is available)"
if command -v docker >/dev/null 2>&1 && [ -f docker-compose.yml ]; then
  docker compose exec -T app php artisan optimize 2>/dev/null || true
  docker compose exec -T nginx nginx -s reload 2>/dev/null || true
fi

echo "✓ Deploy complete"

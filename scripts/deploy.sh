#!/usr/bin/env bash
# Production deploy for karlhill.com. Run on the server from the app root.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "→ Pulling latest code"
git pull --ff-only origin main 2>/dev/null || git pull --ff-only

echo "→ Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

echo "→ Installing JS dependencies and building assets"
npm ci
npm run build

echo "→ Generating OG images and WebP variants"
php artisan og:generate --quiet || true
php artisan assets:webp --quiet || true

echo "→ Optimizing Laravel"
php artisan optimize

echo "→ Reloading PHP (if docker compose is available)"
if command -v docker >/dev/null 2>&1 && [ -f docker-compose.yml ]; then
  docker compose exec -T app php artisan optimize 2>/dev/null || true
  docker compose exec -T nginx nginx -s reload 2>/dev/null || true
fi

echo "✓ Deploy complete"

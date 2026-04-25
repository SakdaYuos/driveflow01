#!/bin/bash

echo "==> Setting up DriveFlow on Railway..."

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
  echo "==> Generating application key..."
  php artisan key:generate --force
fi

# Create storage link
echo "==> Creating storage link..."
php artisan storage:link || true

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force || true

# Seed database (errors are safe to ignore on restarts)
echo "==> Seeding database..."
php artisan db:seed --force || true

# Clear stale caches then re-cache for production
echo "==> Caching config..."
php artisan config:clear || true
php artisan route:clear  || true
php artisan view:clear   || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting PHP server on port ${PORT:-8080}..."
exec php -S "0.0.0.0:${PORT:-8080}" -t public public/router.php

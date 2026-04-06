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

# Seed database (only if fresh)
echo "==> Seeding database..."
php artisan db:seed --force || true

# Clear and cache config for production
echo "==> Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting PHP server on port ${PORT:-8080}..."
exec php -S "0.0.0.0:${PORT:-8080}" -t public public/index.php
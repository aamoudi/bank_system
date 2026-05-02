#!/bin/bash

set -e

echo "Starting MASAREEFI Laravel Application..."

cd /var/www/html

# Clear and cache config for production
echo "Caching configuration..."
php artisan config:clear
php artisan config:cache

# Cache routes
echo "Caching routes..."
php artisan route:clear
php artisan route:cache

# Cache views
echo "Caching views..."
php artisan view:clear
php artisan view:cache

# Run migrations (safe - only runs new ones)
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Clear permission cache
echo "Clearing permission cache..."
php artisan permission:cache-reset || true

# Set storage permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Create storage symlink
php artisan storage:link || true

echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
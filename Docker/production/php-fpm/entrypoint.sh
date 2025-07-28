#!/bin/sh
set -e

# Initialize storage directory if empty
# -----------------------------------------------------------
if [ ! "$(ls -A /var/www/laravel/storage)" ]; then
  echo "Initializing storage directory..."
  cp -R /var/www/laravel/storage-init/. /var/www/laravel/storage
  chown -R www-data:www-data /var/www/laravel/storage
fi

# Remove storage-init directory
rm -rf /var/www/laravel/storage-init

# Run Laravel migrations
# -----------------------------------------------------------
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache configurations
# -----------------------------------------------------------
echo "Caching Laravel configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure correct ownership (optional safety net)
chown -R www-data:www-data /var/www/laravel

# Start Supervisor to run PHP-FPM and Laravel Horizon
# -----------------------------------------------------------
echo "Starting Supervisor..."
exec supervisord -n -c /etc/supervisor/supervisord.conf

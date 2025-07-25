#!/bin/sh
set -e

# Initialize storage directory if empty
# -----------------------------------------------------------
# If the storage directory is empty, copy the initial contents
# and set the correct permissions.
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
# Ensure the database schema is up to date.
# -----------------------------------------------------------
php artisan migrate --force

# Clear and cache configurations
# -----------------------------------------------------------
# Improves performance by caching config and routes.
# -----------------------------------------------------------
php artisan config:cache
php artisan route:cache

# Run the default command
exec "$@"

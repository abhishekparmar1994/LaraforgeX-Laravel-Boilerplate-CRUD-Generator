#!/bin/sh
set -e

# ==============================================================================
# Container Entrypoint Script for LaraforgeX on Render
# Auto-prepares Laravel application state on container startup
# ==============================================================================

echo "🚀 Bootstrapping LaraforgeX Container for Render..."

# Ensure required framework directories exist
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# Fix permissions for web server user
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# MySQL / Database Connection Logging
if [ "$DB_CONNECTION" = "mysql" ]; then
    echo "🗄️ Database Connection: MySQL ($DB_HOST:$DB_PORT / DB: $DB_DATABASE)"
fi

# Clear and rebuild application caches
echo "⚡ Caching configurations, routes, and views..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations automatically if configured
if [ "$RUN_MIGRATIONS" = "true" ] || [ -n "$DB_HOST" ] || [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "🗄️ Running database migrations..."
    php artisan migrate --force || echo "⚠️ Migration warning: proceeding with startup..."
fi

echo "✅ Container initialization complete. Starting Apache..."
exec apache2-foreground

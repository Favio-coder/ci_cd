#!/bin/sh
set -e

# Copy env example if .env doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Ensure storage and bootstrap/cache permissions are correct
echo "Setting correct folder permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create sqlite database file if configured and not exists
DB_CONNECTION=$(grep "^DB_CONNECTION=" .env | cut -d '=' -f2)
if [ "$DB_CONNECTION" = "sqlite" ]; then
    DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
    if [ -z "$DB_DATABASE" ] || [ "$DB_DATABASE" = "laravel" ] || [ "$DB_DATABASE" = "../database/database.sqlite" ]; then
        # Default fallback sqlite path inside docker
        DB_DATABASE="/var/www/html/database/database.sqlite"
    fi
    if [ ! -f "$DB_DATABASE" ]; then
        echo "Creating SQLite database at $DB_DATABASE..."
        mkdir -p "$(dirname "$DB_DATABASE")"
        touch "$DB_DATABASE"
        chown www-data:www-data "$DB_DATABASE"
        chmod 664 "$DB_DATABASE"
    fi
elif [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "Waiting for PostgreSQL database connection..."
    DB_HOST=$(grep "^DB_HOST=" .env | cut -d '=' -f2)
    DB_PORT=$(grep "^DB_PORT=" .env | cut -d '=' -f2 | tr -d '\r')
    DB_PORT=${DB_PORT:-5432}
    
    # Simple netcat/bash loop to wait for postgres
    until nc -z -v -w3 "$DB_HOST" "$DB_PORT"; do
        echo "PostgreSQL is unavailable - sleeping"
        sleep 1
    done
    echo "PostgreSQL is up and running!"
fi

# Run migrations if database is ready
echo "Running database migrations..."
php artisan migrate --force

# Start php-fpm in background and nginx in foreground
echo "Starting services..."
php-fpm -D
nginx -g "daemon off;"

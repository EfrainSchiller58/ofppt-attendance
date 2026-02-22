#!/bin/bash
set -e

cd /var/www/html

# ALWAYS create .env from environment variables first
echo "Creating .env from environment variables..."
cat > .env <<EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL=stack
LOG_LEVEL="${LOG_LEVEL:-debug}"

DB_CONNECTION="${DB_CONNECTION:-sqlite}"
DB_HOST="${DB_HOST:-}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-database/database.sqlite}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"
DATABASE_URL="${DATABASE_URL:-}"

SESSION_DRIVER="${SESSION_DRIVER:-cookie}"
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

CACHE_STORE="${CACHE_STORE:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

SANCTUM_STATEFUL_DOMAINS="${SANCTUM_STATEFUL_DOMAINS:-}"
FRONTEND_URL="${FRONTEND_URL:-}"

MAIL_MAILER="${MAIL_MAILER:-resend}"
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-onboarding@resend.dev}"
MAIL_FROM_NAME="${MAIL_FROM_NAME:-OFPPT Attendance}"
RESEND_API_KEY="${RESEND_API_KEY:-}"
EOF

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Handle SQLite database creation if using sqlite
DB_CONN="${DB_CONNECTION:-sqlite}"
if [ "$DB_CONN" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-database/database.sqlite}"
    NEED_SEED=false
    if [ ! -f "$DB_PATH" ]; then
        echo "Creating SQLite database at $DB_PATH..."
        touch "$DB_PATH"
        chmod 775 "$DB_PATH"
        NEED_SEED=true
    fi
fi

# Clear any cached config first
php artisan config:clear || true

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || true

# Seed database if it's a fresh database
if [ "$NEED_SEED" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force || true
else
    # Also seed if users table is empty
    echo "Checking if seed is needed..."
    php artisan db:seed --force || true
fi

# Cache configuration for production
echo "Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Link storage
php artisan storage:link || true

echo "Starting Laravel server on port ${PORT:-10000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
php artisan storage:link || true

echo "Starting Laravel server on port ${PORT:-10000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"

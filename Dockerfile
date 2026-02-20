FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (MySQL + PostgreSQL + SQLite support)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better Docker layer caching
COPY composer.json composer.lock* ./

# Install Laravel dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy the rest of the project files
COPY . .

# Re-run composer scripts (post-autoload-dump, etc.)
RUN composer dump-autoload --optimize

# Create required directories & set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p storage/app/public \
    && mkdir -p bootstrap/cache \
    && mkdir -p database \
    && chmod -R 775 storage bootstrap/cache database

# Copy startup script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Render uses PORT env variable (default 10000 on free tier)
ENV PORT=10000

EXPOSE ${PORT}

ENTRYPOINT ["docker-entrypoint.sh"]

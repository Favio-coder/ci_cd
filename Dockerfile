# ==========================================
# Stage 1: Build frontend assets
# ==========================================
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources/ ./resources/
RUN npm run build

# ==========================================
# Stage 2: Install PHP Composer dependencies
# ==========================================
FROM composer:2.7 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./
# Copy only the files needed for autoload generation
COPY app/ ./app/
COPY bootstrap/ ./bootstrap/
COPY database/ ./database/

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# ==========================================
# Stage 3: Final runtime environment
# ==========================================
FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and Nginx
RUN apk add --no-cache \
    nginx \
    shadow \
    bash \
    sqlite \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev

# Use docker-php-extension-installer helper to install required PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd zip bcmath pdo_mysql pdo_pgsql pdo_sqlite opcache intl

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy application files
COPY --chown=www-data:www-data . .

# Copy built vendor from Stage 2
COPY --from=composer-builder --chown=www-data:www-data /app/vendor ./vendor

# Copy built frontend assets from Stage 1
COPY --from=node-builder --chown=www-data:www-data /app/public/build ./public/build

# Set permissions for storage & bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for Nginx
EXPOSE 80

# Configure entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

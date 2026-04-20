# =========================================================
# Stage 1: Composer — install PHP dependencies
# =========================================================
FROM php:8.3-cli-alpine AS vendor

WORKDIR /app

RUN apk add --no-cache git unzip curl

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# =========================================================
# Stage 2: Node — build frontend assets (Laravel Mix)
# =========================================================
FROM node:18-alpine AS assets

WORKDIR /app

COPY package.json webpack.mix.js ./
COPY resources/ resources/

RUN npm install \
    && npm run prod

# =========================================================
# Stage 3: Production image
# =========================================================
FROM php:8.3-fpm-alpine

LABEL maintainer="onboarding-app"

# ---- System dependencies & PHP extensions ----
RUN apk add --no-cache \
        nginx \
        supervisor \
        curl \
        zip \
        unzip \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# ---- PHP configuration ----
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# ---- Nginx configuration ----
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# ---- Supervisor configuration ----
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ---- Application ----
WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

# Copy vendor from stage 1
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor

# Copy compiled assets from stage 2
COPY --from=assets --chown=www-data:www-data /app/public/js  ./public/js
COPY --from=assets --chown=www-data:www-data /app/public/css ./public/css
COPY --from=assets --chown=www-data:www-data /app/mix-manifest.json ./mix-manifest.json

# ---- Storage & cache permissions ----
RUN mkdir -p storage/framework/{sessions,views,cache} \
             storage/logs \
             bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

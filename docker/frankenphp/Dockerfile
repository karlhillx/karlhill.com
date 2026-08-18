# FrankenPHP (optional origin)
#
# Link preload headers already emit; this worker SAPI can flush them as 103
# Early Hints. Default production remains php-fpm + nginx.
#
#   docker compose -f docker-compose.frankenphp.yml up --build
#
# Set EARLY_HINTS=true in .env when running this stack.

FROM dunglas/frankenphp:1-php8.4

RUN install-php-extensions \
    pcntl \
    mbstring \
    bcmath \
    gd \
    opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

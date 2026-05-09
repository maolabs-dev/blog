# --- Stage 1: Dependências (Composer) ---
FROM composer:2 AS deps
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# --- Stage 2: Runtime (Chainguard Laravel) ---
FROM cgr.dev/chainguard/laravel:latest

WORKDIR /app

# Copiar código da aplicação (ignorado pelo .dockerignore)
COPY --chown=php:php . .

# Vendor otimizado do stage de build
COPY --from=deps --chown=php:php /app/vendor ./vendor

# Script de entrypoint
COPY --chown=php:php docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Garante permissões das pastas do Laravel
RUN mkdir -p storage/framework/{cache,sessions,views} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Usamos o entrypoint para caches em runtime
ENTRYPOINT ["docker-entrypoint.sh"]

# O processo principal é o PHP-FPM (Chainguard já configura o server ou FPM adequadamente)
CMD ["php-fpm"]

# --- Build stage ---
FROM composer:2 AS deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# --- Runtime stage ---
FROM php:8.3-alpine

# Extensões mínimas para Laravel
RUN apk add --no-cache \
        libxml2-dev \
        oniguruma-dev \
    && docker-php-ext-install \
        bcmath \
        mbstring \
        xml \
        dom \
    && apk del libxml2-dev oniguruma-dev \
    && rm -rf /var/cache/apk/*

WORKDIR /app

# Copiar dependências do stage anterior
COPY --from=deps /app/vendor ./vendor

# Copiar código da aplicação
COPY . .

# Gerar chave e cache de config
RUN cp .env.example .env \
    && php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Permissões para storage e cache
RUN mkdir -p storage/framework/{sessions,views,cache/data} \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Usar o servidor embutido do PHP (adequado para blog leve)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

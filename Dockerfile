# --- Build stage (Composer) ---
FROM composer:2 AS deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# --- Runtime stage (Chainguard Laravel) ---
FROM cgr.dev/chainguard/laravel:latest

WORKDIR /app

# Copiar dependências e código
COPY --from=deps --chown=php:php /app/vendor ./vendor
COPY --chown=php:php . .

# Otimização para produção
# Nota: APP_KEY será injetado via K8s, mas precisamos dele para o cache de config no build
RUN cp .env.example .env \
    && php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 8000

# O entrypoint já está configurado na imagem Chainguard para rodar o PHP-FPM ou servidor
# Mas para manter a consistência com o setup anterior:
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

# --- Stage 1: Dependências (Composer) ---
FROM composer:2 AS deps
WORKDIR /app
COPY composer.json composer.lock ./
# Força o Composer a resolver as dependências para o PHP 8.3, evitando erros de platform_check no FrankenPHP
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# --- Stage 2: Runtime (FrankenPHP Alpine) ---
FROM dunglas/frankenphp:1-php8.3-alpine

# O FrankenPHP gerencia o próprio SSL, mas no K8s o Ingress faz isso.
# Esta variável diz ao FrankenPHP para servir apenas HTTP localmente.
ENV SERVER_NAME="http://"

# Instala extensões (FrankenPHP já traz várias, adicionamos oniguruma/libxml2 por precaução)
RUN apk add --no-cache libxml2-dev oniguruma-dev \
    && install-php-extensions bcmath mbstring xml dom \
    && apk del libxml2-dev oniguruma-dev \
    && rm -rf /var/cache/apk/*

WORKDIR /app

# Copiar código e dependências
COPY --from=deps /app/vendor ./vendor
COPY . .
COPY Caddyfile /etc/caddy/Caddyfile

# Script de entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Ajuste de permissões
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

#!/bin/sh
set -e

# Caches em runtime, depois que os Secrets já foram injetados pelo K8s
echo "Gerando caches do Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Como é um blog sem DB, não precisamos de migrations.
# Mas deixamos o espaço caso mude no futuro.
# php artisan migrate --force

echo "Iniciando processo principal..."
exec "$@"

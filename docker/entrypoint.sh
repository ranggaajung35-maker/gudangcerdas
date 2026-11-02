#!/bin/sh
set -eu

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY belum diatur. Jalankan php artisan key:generate lalu mulai ulang container."
    exit 1
fi

if [ "${DB_CONNECTION:-mysql}" = "mysql" ]; then
    echo "Menunggu MySQL tersedia..."
    attempt=0
    until php -r '
        try {
            new PDO(
                "mysql:host=" . getenv("DB_HOST") . ";port=" . (getenv("DB_PORT") ?: "3306") . ";dbname=" . getenv("DB_DATABASE"),
                getenv("DB_USERNAME"),
                getenv("DB_PASSWORD"),
                [PDO::ATTR_TIMEOUT => 2]
            );
            exit(0);
        } catch (Throwable $e) { exit(1); }
    '; do
        attempt=$((attempt + 1))
        if [ "$attempt" -ge 40 ]; then
            echo "Database tidak dapat dihubungi. Periksa konfigurasi DB_* di .env."
            exit 1
        fi
        sleep 2
    done
fi

php artisan migrate --force
exec "$@"

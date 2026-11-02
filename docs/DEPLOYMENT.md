# Panduan deployment GudangCerdas

Aplikasi membutuhkan PHP 8.3+, Composer 2, ekstensi PHP Laravel, dan MySQL 8.0+. Pilih VPS/hosting yang memberikan document root ke direktori `public/` Laravel. Jangan arahkan web root ke root proyek karena file `.env`, `composer.json`, dan kode aplikasi tidak boleh dapat diunduh publik.

## Checklist sebelum deploy

- [ ] Ubah semua kata sandi demo dan jangan gunakan akun demo pada production.
- [ ] Buat database dan user MySQL production terpisah dari akun lokal.
- [ ] Siapkan `APP_KEY` unik menggunakan `php artisan key:generate` dan simpan secara rahasia.
- [ ] Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-anda`, `SESSION_SECURE_COOKIE=true`.
- [ ] Pastikan `storage/` dan `bootstrap/cache/` writable oleh proses PHP.
- [ ] Pastikan document root mengarah ke folder `public/`.
- [ ] Aktifkan TLS/HTTPS dan firewall/aturan akses database.

## Deployment pada VPS (Nginx + PHP-FPM)

1. Install PHP 8.3 FPM beserta `pdo_mysql`, `mbstring`, `xml`, `curl`, `fileinfo`, `openssl`, dan Composer 2.
2. Install MySQL 8.0, buat database `gudang_cerdas` dan user khusus dengan hak minimal.
3. Clone repository ke lokasi non-public, misalnya `/var/www/gudang-cerdas`.
4. Jalankan `composer install --no-dev --optimize-autoloader --no-interaction`.
5. Buat file `.env` dari `.env.example`, kemudian isi `APP_KEY`, URL HTTPS, dan kredensial DB production.
6. Jalankan `php artisan migrate --force`.
7. Jalankan `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
8. Atur ownership/permission `storage` dan `bootstrap/cache`.
9. Konfigurasikan Nginx `root /var/www/gudang-cerdas/public;` dan PHP-FPM front controller ke `public/index.php`.
10. Pasang sertifikat TLS, lalu uji login, role staff/admin, CRUD, mutasi stok, dan CSV export.

**Jangan jalankan `php artisan db:seed` di production** sebelum meninjau dan mengubah `DatabaseSeeder`; seeder menyediakan kredensial demo.

## Deployment via container

Proyek menyertakan `Dockerfile` dan `docker-compose.yml` sebagai lingkungan pengembangan/pengujian. Sebelum menjalankan:

1. Jalankan `composer install` pada komputer lokal agar Artisan tersedia.
2. Buat `.env` dari `.env.example`, lalu jalankan `php artisan key:generate`.
3. Pastikan `DB_HOST=db`, `DB_DATABASE=gudang_cerdas`, `DB_USERNAME=gudang_user`, dan password database disesuaikan di environment Compose.
4. Jalankan `docker compose up --build -d`.
5. Buka `http://localhost:8080`, kemudian uji aplikasi.

Container Compose bukan bukti deployment publik. Untuk deliverable live URL, deploy ke host publik, aktifkan HTTPS, uji endpoint melalui internet, dan dokumentasikan URL tersebut.

# Perbaikan deployment GudangCerdas di EthioDeploy

Perubahan ini ditujukan untuk error:

`/app/storage/logs/laravel.log could not be opened in append mode: Permission denied`

## Apa yang sudah diperbaiki di project

- Logging default Laravel diarahkan ke `stderr`, bukan file `storage/logs/laravel.log`.
- Emergency logger juga diarahkan ke `stderr`.
- Ditambahkan `deploy/ethiodeploy-start.sh` untuk membuat folder Laravel, memperbaiki ownership/permission untuk user PHP-FPM (`www-data`), menjalankan migrasi, lalu menjalankan startup standar EthioDeploy.
- Konfigurasi MySQL sekarang membaca `DB_URL`, dengan fallback ke `DATABASE_URL` dari addon EthioDeploy.
- Folder log runtime diabaikan Git agar `laravel.log` lokal tidak ikut di-commit.
- Proxy HTTPS EthioDeploy dipercaya Laravel agar URL dan cookie secure terdeteksi dengan benar.

## Langkah setelah project diperbarui di GitHub

1. Pastikan perubahan project sudah masuk ke branch `main`. Hapus `Procfile` lama di root project jika masih berisi perintah `heroku-php-apache2`; EthioDeploy tidak memakai Procfile untuk Laravel.
2. Buka EthioDeploy → project **GudangCerdas** → **Settings**.
3. Pada Environment Variables, pastikan nilai berikut tersedia:

   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://gudangcerdas.ethiodeploy.app` (sesuaikan dengan domain project jika berbeda)
   - `LOG_CHANNEL=stderr`
   - `DB_CONNECTION=mysql`

4. Pastikan addon MySQL sudah ditambahkan dan variabel `DATABASE_URL` tersedia dari addon tersebut.
5. Buka **Settings → Build → Start command** dan isi persis:

   `sh deploy/ethiodeploy-start.sh`

6. Simpan pengaturan lalu lakukan redeploy.
7. Jika deployment berhasil, buka `/login`.

## Penting

EthioDeploy mendeteksi Laravel dari `artisan` dan `composer.json`, lalu menyiapkan PHP-FPM + Nginx sendiri. Untuk jenis deployment ini, `Dockerfile` dan `Procfile` di repository tidak dipakai. Karena itu, perintah startup harus diatur di **Settings → Build → Start command**, bukan di Procfile.

Jangan menghapus atau mengganti `APP_KEY` yang sudah dipakai aplikasi karena itu dapat membuat data terenkripsi atau sesi lama tidak dapat dibaca.

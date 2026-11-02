# GudangCerdas — Sistem Manajemen Inventaris Gudang

Aplikasi web full-stack berbasis **Laravel 13, PHP 8.3+, dan MySQL** untuk membantu operasional inventaris: katalog produk, kategori, pemasok, transaksi stok masuk/keluar, ringkasan dashboard, laporan CSV, autentikasi, dan pembagian hak akses Admin/Staff.

> Proyek ini disusun sebagai fondasi pengerjaan UAS Web Application Development. Silakan ganti identitas mahasiswa dan sesuaikan isi/demonstrasi dengan pemahaman serta hasil pengujian sendiri sebelum dikumpulkan. Aplikasi belum di-deploy ke hosting publik; ikuti panduan deployment di bagian bawah untuk memperoleh URL live.

## 1. Fitur aplikasi

- **Dashboard:** jumlah produk, kategori, pemasok, estimasi nilai persediaan, produk stok menipis, ringkasan stok bulanan, dan aktivitas terbaru.
- **Produk:** tambah, lihat, ubah, cari, filter, status aktif/nonaktif, SKU unik, kategori, pemasok, harga beli/jual, satuan, dan lokasi rak.
- **Kategori dan pemasok:** CRUD lengkap, pencarian, relasi produk, dan validasi.
- **Transaksi stok:** stok masuk/keluar, referensi, catatan, tanggal transaksi, edit/hapus transaksi, dan histori per produk. Saldo berubah dalam database transaction dengan row lock; stok keluar yang melebihi saldo ditolak.
- **Laporan:** filter rentang tanggal, total kuantitas masuk/keluar, daftar stok minimum, dan ekspor CSV.
- **Pengguna:** registrasi publik sebagai Staff, login, logout, manajemen akun oleh Admin, dan proteksi role.
- **Keamanan:** hash kata sandi, session regeneration/invalidation, CSRF pada form, Blade escaped output untuk mengurangi XSS, validasi server-side, query Eloquent terparameterisasi, login throttling, foreign key, serta otorisasi per role.
- **UI:** antarmuka responsif desktop/mobile, navigasi sidebar, status visual, dan konfirmasi sebelum tindakan hapus.

## 2. Teknologi dan versi

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 13 |
| Bahasa server | PHP 8.3 atau lebih baru |
| Database | MySQL 8.0+ / MariaDB yang kompatibel |
| ORM | Laravel Eloquent ORM |
| View | Blade |
| Frontend | HTML5, CSS3, JavaScript vanilla |
| Session | Laravel session driver (`file` secara default) |
| Testing | PHPUnit melalui Laravel Test Runner |

Extension PHP yang dibutuhkan: `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `session`, `tokenizer`, `xml`. Extension `pdo_sqlite` dapat digunakan untuk menjalankan test yang memakai konfigurasi PHPUnit.

## 3. Menjalankan di komputer lokal (Windows / VS Code)

### Prasyarat

1. PHP 8.3+ dan Composer 2.
2. MySQL 8.0+ (contoh: XAMPP, Laragon, atau MySQL Server).
3. VS Code dan terminal PowerShell.

Pastikan perintah `php -v`, `composer --version`, dan koneksi MySQL tersedia. Buka folder `GudangCerdas` melalui **File → Open Folder** di VS Code.

### Instalasi

Buka terminal di folder proyek, lalu jalankan:

```bash
composer install
```

Salin `.env.example` menjadi `.env` (di PowerShell):

```powershell
Copy-Item .env.example .env
```

Buat database MySQL kosong bernama `gudang_cerdas`, kemudian sesuaikan pengaturan berikut di `.env`:

```dotenv
APP_NAME="GudangCerdas"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gudang_cerdas
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_ENCRYPT=true
```

Buat key aplikasi, jalankan migration, dan isi data contoh:

```bash
php artisan key:generate
php artisan migrate --seed
```

Mulai server development:

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser.

> **Alternatif database:** file `database/gudang_cerdas.sql` berisi schema, catatan migration, akun contoh, dan data demo. Import file tersebut melalui phpMyAdmin/MySQL Client sebagai alternatif dari `php artisan migrate --seed`. Jangan mengimpor SQL itu ke database yang sudah berisi tabel aplikasi.

## 4. Akun demo

Seeder membuat dua akun berikut:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@gudang.local` | `GudangAdmin#2026` |
| Staff | `staff@gudang.local` | `GudangStaff#2026` |

**Keamanan:** kredensial tersebut hanya untuk demo lokal/UAS. Ubah kata sandi atau hapus akun demo sebelum aplikasi dipublikasikan. Registrasi mandiri selalu membuat role `staff`, bukan `admin`.

## 5. Struktur folder utama

```text
GudangCerdas/
├── app/
│   ├── Http/Controllers/       # Controller auth, dashboard, CRUD, report
│   ├── Http/Middleware/        # RoleMiddleware
│   ├── Models/                 # User, Product, Category, Supplier, StockMovement
│   └── Providers/
├── bootstrap/                  # Bootstrap Laravel 13 dan alias middleware
├── config/                     # Konfigurasi app, auth, database, session, dll.
├── database/
│   ├── migrations/             # Definisi tabel relasional
│   ├── seeders/                # Admin/staff dan data dummy
│   └── gudang_cerdas.sql       # Dump MySQL
├── docs/                       # ERD, arsitektur, routing, checklist, panduan demo
├── public/                     # Entry point, CSS, JavaScript
├── resources/views/            # Blade templates
├── routes/web.php              # Routing aplikasi
├── tests/                      # Feature tests
├── .env.example
├── composer.json
└── artisan
```

## 6. ERD dan relasi database

Database menggunakan lima tabel inti. `products` berelasi dengan `categories` dan `suppliers`; `stock_movements` berelasi dengan `products` dan `users`. Diagram lengkap tersedia di [`docs/ERD.mmd`](docs/ERD.mmd).

| Tabel | Fungsi | Relasi penting |
|---|---|---|
| users | akun dan role | 1 user memiliki banyak stock_movements |
| categories | master kategori | 1 category memiliki banyak products |
| suppliers | master pemasok | 1 supplier memiliki banyak products; nullable pada produk |
| products | data barang dan saldo | belongsTo category, belongsTo supplier, hasMany stock_movements |
| stock_movements | histori masuk/keluar | belongsTo product dan user |

## 7. Routing MVC ringkas

| Method | URL | Fungsi | Akses |
|---|---|---|---|
| GET/POST | `/login` | Form dan proses login | Guest |
| GET/POST | `/register` | Registrasi Staff | Guest |
| POST | `/logout` | Logout dan invalidasi session | Auth |
| GET | `/dashboard` | Dashboard ringkasan | Auth |
| Resource | `/products` | CRUD katalog produk | Auth; delete Admin |
| Resource | `/categories` | CRUD kategori | Auth; delete Admin |
| Resource | `/suppliers` | CRUD pemasok | Auth; delete Admin |
| Resource | `/stock-movements` | CRUD mutasi stok | Auth; delete Admin |
| GET | `/reports` | Laporan inventaris | Auth |
| GET | `/reports/export` | Export CSV | Auth |
| Resource | `/users` | Manajemen pengguna | Admin |

Rincian route ada pada [`docs/ROUTING.md`](docs/ROUTING.md).

## 8. Hak akses

| Fitur | Admin | Staff |
|---|:---:|:---:|
| Dashboard dan lihat data | ✓ | ✓ |
| Tambah/edit produk, kategori, pemasok | ✓ | ✓ |
| Hapus produk, kategori, pemasok | ✓ | — |
| Catat/edit transaksi stok | ✓ | ✓ |
| Hapus transaksi stok | ✓ | — |
| Lihat dan ekspor laporan | ✓ | ✓ |
| Tambah/edit/hapus pengguna dan role | ✓ | — |

Perlindungan akses dijalankan di server melalui middleware `auth` dan middleware `role`, bukan hanya dengan menyembunyikan tombol di antarmuka.

## 9. Validasi, keamanan, dan konsistensi data

- Validasi Laravel dijalankan di server untuk semua operasi penyimpanan.
- Password disimpan dengan hash bcrypt melalui Laravel Hash/hashed cast; tidak ada password plaintext di database.
- Session ID dibuat ulang setelah login/register; saat logout session di-invalidasi dan CSRF token diregenerasi.
- Form Blade menggunakan `@csrf`; method PUT/DELETE menggunakan spoofing method Laravel.
- Output dinamis dirender dengan `{{ }}` yang di-escape oleh Blade. Input teks juga dibatasi panjang/format dan field bebas HTML ditolak.
- Query menggunakan Eloquent/Query Builder dengan binding parameter; hindari merangkai SQL mentah dari input pengguna.
- Operasi mutasi stok menggunakan `DB::transaction()` dan `lockForUpdate()` agar saldo tidak berubah sebagian saat validasi gagal atau ada transaksi bersamaan.
- Foreign key mencegah penghapusan kategori/produk/pengguna yang masih memiliki histori terkait.
- Percobaan login dibatasi melalui rate limiter.
- Untuk production, set `APP_DEBUG=false`, HTTPS, `SESSION_SECURE_COOKIE=true`, kredensial database terpisah, dan jangan publikasikan file `.env`.

## 10. Menjalankan test

```bash
php artisan test
```

Test feature mencakup role akun hasil registrasi, penolakan akses staff ke manajemen pengguna, proteksi stok keluar, dan halaman dashboard yang memerlukan autentikasi. PHPUnit dikonfigurasi menggunakan SQLite in-memory bila extension `pdo_sqlite` tersedia; aplikasi tetap menggunakan MySQL pada konfigurasi normal.

## 11. Docker (opsional)

Jika Docker Desktop tersedia, siapkan `.env` dan `APP_KEY` terlebih dahulu. Dari terminal lokal di folder proyek jalankan `composer install`, salin `.env.example` menjadi `.env`, lalu jalankan `php artisan key:generate`. Setelah itu jalankan:

```bash
docker compose up --build -d
```

Aplikasi diakses pada `http://localhost:8080`. Service database berjalan pada container MySQL terpisah. Data database disimpan di volume Docker. Setelah container berjalan, isi data demo untuk membuat akun Admin/Staff:

```bash
docker compose exec app php artisan db:seed
```

Lihat `docker-compose.yml` dan `Dockerfile`.

## 12. Deployment publik

Aplikasi **belum memiliki URL live**. Untuk memperoleh nilai deliverable deployment, siapkan hosting/VPS yang mendukung PHP 8.3+, Composer, dan MySQL, lalu:

1. Push source code ke repository Git privat/publik yang telah dibersihkan dari `.env`, `vendor`, dan kredensial demo.
2. Buat database MySQL production dan user dengan hak akses minimum.
3. Set environment variables production (`APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://...`, `APP_KEY`, dan `DB_*`).
4. Deploy source dan jalankan `composer install --no-dev --optimize-autoloader`; konfigurasi document root ke direktori `public/`.
5. Jalankan `php artisan migrate --force`.
6. Buat akun Admin pertama melalui `php artisan tinker` dengan password kuat milik sendiri: `\App\Models\User::create(['name' => 'Administrator', 'email' => 'admin@domain-anda', 'password' => 'GantiDenganPasswordKuat!', 'role' => 'admin']);`. Model melakukan hashing otomatis.
7. Jalankan `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`, dan pastikan `storage/` serta `bootstrap/cache/` writable.
8. Aktifkan HTTPS, uji login/CRUD/role/export, lalu catat URL live dan screenshot untuk dokumen pengumpulan.

Panduan lebih terstruktur tersedia di [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md). Jangan menyatakan aplikasi sudah live sebelum benar-benar deploy dan mengujinya dari jaringan publik.

## 13. Checklist sebelum pengumpulan UAS

- [ ] Isi identitas mahasiswa dan nama proyek sesuai format dosen.
- [ ] Jalankan aplikasi dan uji seluruh CRUD, login/logout, role, serta ekspor laporan.
- [ ] Pahami setiap controller, model, migration, middleware, dan transaksi stok.
- [ ] Buat repository Git milik sendiri dan commit perubahan secara bertahap.
- [ ] Deploy ke hosting dan uji URL publik.
- [ ] Ambil screenshot, rekam demo/presentasi, dan jawab esai berdasarkan implementasi yang benar-benar diuji.
- [ ] Susun PDF final sesuai template pengumpulan dosen.

---

**Catatan akademik:** AI boleh digunakan sebagai bantuan sesuai brief, tetapi mahasiswa wajib memahami kode. Gunakan aplikasi ini sebagai bahan belajar yang harus Anda jalankan, uji, verifikasi, dan jelaskan dengan kata-kata sendiri.

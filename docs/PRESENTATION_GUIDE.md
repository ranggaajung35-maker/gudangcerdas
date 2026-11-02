# Outline presentasi dan demo (untuk dipelajari dan direkam sendiri)

Dokumen ini adalah outline latihan, bukan video atau bukti bahwa semua skenario sudah diuji.

## 1. Pembukaan dan deskripsi project

“GudangCerdas adalah aplikasi manajemen inventaris gudang berbasis Laravel 13 dan MySQL. Aplikasi dibuat untuk mencatat master barang, kategori, pemasok, transaksi stok masuk dan keluar, serta menyajikan laporan persediaan.”

## 2. Arsitektur MVC

- **Model:** `Product`, `Category`, `Supplier`, `StockMovement`, dan `User` mewakili tabel relasional dan relasinya.
- **View:** file Blade pada `resources/views/` menampilkan dashboard, form, tabel, dan laporan.
- **Controller:** menerima request, memvalidasi input, memanggil model, dan memilih view/redirect.
- **Routing dan middleware:** `routes/web.php`, `auth`, dan `role:admin` mengatur URL serta akses.

## 3. ERD dan alur data

Jelaskan enam tabel dan relasinya menggunakan `docs/ERD.mmd`. Satu kategori dapat mempunyai banyak produk; satu pemasok dapat memasok banyak produk; satu produk mempunyai histori mutasi; setiap mutasi dicatat oleh seorang user.

## 4. Demo aplikasi yang disarankan

1. Login sebagai Admin; tampilkan dashboard.
2. Tambah kategori dan pemasok.
3. Tambah produk (stok awal 0).
4. Catat stok masuk; tunjukkan saldo di daftar/detail produk.
5. Catat stok keluar; tunjukkan saldo berkurang.
6. Coba stok keluar melebihi saldo; tunjukkan validasi gagal dan saldo tidak berubah.
7. Tampilkan laporan rentang tanggal dan unduh CSV.
8. Logout, login sebagai Staff; tunjukkan fitur operasional.
9. Coba akses `/users`; tunjukkan Staff mendapat 403.
10. Kembali sebagai Admin; tunjukkan manajemen pengguna dan aksi hapus.

## 5. Penjelasan keamanan

- Hash password Laravel, session regeneration setelah autentikasi dan invalidation saat logout.
- CSRF token pada form.
- Blade escaping pada output dan validasi input server-side.
- Eloquent ORM/parameter binding.
- Middleware role di server, login rate limiting, foreign key, dan DB transaction + `lockForUpdate` untuk mutasi stok.

## 6. Deployment

Jelaskan environment variables, composer install, migration production, document root `public/`, HTTPS, cache konfigurasi, permission folder, dan hasil uji URL live yang benar-benar Anda deploy.

## 7. Pengingat

- Ganti placeholder identitas dan URL repository/live.
- Rekam aplikasi yang benar-benar berjalan di perangkat sendiri.
- Jangan mengklaim hasil uji atau deployment yang belum dilakukan.
- Siapkan penjelasan sendiri untuk setiap file yang ditunjukkan dosen.

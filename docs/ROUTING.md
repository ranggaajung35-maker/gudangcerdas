# Routing dan struktur MVC

## Alur request

1. Browser mengirim request ke `routes/web.php`.
2. Middleware `guest`/`auth` memeriksa status autentikasi.
3. Middleware `role:admin` membatasi route administrasi dan penghapusan.
4. Controller menerima input dan menjalankan validasi server-side.
5. Controller memanggil Model Eloquent untuk query relasional.
6. Model mengembalikan data; controller meneruskannya ke Blade View.
7. Blade merender output dengan escaping default Laravel.

## Controller utama

| Controller | Tanggung jawab |
|---|---|
| `AuthController` | Login, register, logout, rate limiting percobaan login |
| `DashboardController` | Statistik produk/stok, grafik dan aktivitas terbaru |
| `ProductController` | CRUD produk, cari/filter, detail dan histori |
| `CategoryController` | CRUD kategori dan proteksi penghapusan kategori terpakai |
| `SupplierController` | CRUD pemasok |
| `StockMovementController` | CRUD transaksi stok dan penyesuaian saldo secara atomik |
| `ReportController` | Rekap transaksi dan export CSV |
| `UserController` | CRUD pengguna khusus Admin dan proteksi Admin terakhir |

## Model dan relasi

- `User hasMany StockMovement`
- `Category hasMany Product`
- `Supplier hasMany Product`
- `Product belongsTo Category`, `belongsTo Supplier`, `hasMany StockMovement`
- `StockMovement belongsTo Product` dan `belongsTo User`

## Peta endpoint

- `/`, `/login`, `/register`, `/logout`, `/dashboard`
- Resource `/products`, `/categories`, `/suppliers`, `/stock-movements`
- `/reports`, `/reports/export`
- Resource `/users` (role Admin)

Route detail dapat diperiksa setelah instalasi dengan `php artisan route:list`.

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('email', 'admin@gudang.local')->exists()) {
            return;
        }

        $admin = User::create([
            'name' => 'Administrator Gudang',
            'email' => 'admin@gudang.local',
            'password' => Hash::make('GudangAdmin#2026'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);
        $staff = User::create([
            'name' => 'Staff Operasional',
            'email' => 'staff@gudang.local',
            'password' => Hash::make('GudangStaff#2026'),
            'role' => 'staff',
            'phone' => '081298765432',
        ]);

        $categories = [];
        foreach ([
            ['Elektronik', 'Perangkat elektronik dan aksesorinya.'],
            ['Peralatan Kantor', 'Perlengkapan untuk kebutuhan kantor.'],
            ['Aksesoris Komputer', 'Aksesoris dan periferal komputer.'],
            ['Jaringan', 'Perangkat jaringan dan konektivitas.'],
            ['Perlengkapan Umum', 'Barang operasional dan perlengkapan umum.'],
        ] as [$name, $description]) {
            $categories[$name] = Category::create(['name' => $name, 'description' => $description, 'is_active' => true]);
        }

        $suppliers = [];
        foreach ([
            ['PT Sumber Teknologi', 'Andi Pratama', 'andi@sumbertek.example', '081200000101', 'Jl. Industri No. 12'],
            ['CV Digital Nusantara', 'Siti Rahma', 'siti@digitalnusantara.example', '081200000102', 'Jl. Niaga No. 8'],
            ['PT Prima Komputindo', 'Budi Santoso', 'budi@primakom.example', '081200000103', 'Jl. Merdeka No. 21'],
            ['CV Jaringan Mandiri', 'Rina Putri', 'rina@jaringanmandiri.example', '081200000104', 'Jl. Pembangunan No. 5'],
            ['UD Karya Bersama', 'Dedi Kurniawan', 'dedi@karyabersama.example', '081200000105', 'Jl. Raya Gudang No. 2'],
        ] as [$company, $contact, $email, $phone, $address]) {
            $suppliers[$company] = Supplier::create(['company_name' => $company, 'contact_name' => $contact, 'email' => $email, 'phone' => $phone, 'address' => $address]);
        }

        $rows = [
            ['ELK-001', 'Monitor LED 24 Inch', 'Monitor IPS resolusi Full HD.', 'Elektronik', 'PT Sumber Teknologi', 1250000, 1499000, 18, 5, 'unit', 'Rak A-01'],
            ['ELK-002', 'Printer Ink Tank', 'Printer multifungsi untuk kebutuhan kantor.', 'Elektronik', 'CV Digital Nusantara', 1850000, 2199000, 7, 4, 'unit', 'Rak A-02'],
            ['AKS-001', 'Keyboard Mechanical', 'Keyboard mekanis dengan layout full size.', 'Aksesoris Komputer', 'PT Prima Komputindo', 420000, 549000, 24, 6, 'pcs', 'Rak B-01'],
            ['AKS-002', 'Mouse Wireless', 'Mouse nirkabel dengan sensor optik.', 'Aksesoris Komputer', 'PT Prima Komputindo', 95000, 139000, 42, 10, 'pcs', 'Rak B-02'],
            ['JAR-001', 'Router Gigabit', 'Router gigabit dual band.', 'Jaringan', 'CV Jaringan Mandiri', 680000, 829000, 4, 5, 'unit', 'Rak C-01'],
            ['JAR-002', 'Kabel LAN Cat6 10m', 'Kabel jaringan kategori Cat6.', 'Jaringan', 'CV Jaringan Mandiri', 45000, 69000, 55, 15, 'pcs', 'Rak C-02'],
            ['KTR-001', 'Kertas A4 80gsm', 'Kertas fotokopi ukuran A4.', 'Peralatan Kantor', 'UD Karya Bersama', 48000, 58000, 80, 20, 'rim', 'Rak D-01'],
            ['KTR-002', 'Tinta Printer Hitam', 'Tinta hitam kompatibel printer ink tank.', 'Peralatan Kantor', 'CV Digital Nusantara', 65000, 89000, 9, 8, 'botol', 'Rak D-02'],
            ['UMU-001', 'Lakban Bening', 'Lakban bening lebar 48 mm.', 'Perlengkapan Umum', 'UD Karya Bersama', 8000, 12000, 120, 25, 'roll', 'Rak E-01'],
            ['UMU-002', 'Bubble Wrap 1m', 'Plastik pelindung barang untuk pengemasan.', 'Perlengkapan Umum', 'UD Karya Bersama', 18000, 26000, 3, 5, 'meter', 'Rak E-02'],
        ];

        foreach ($rows as $index => [$sku, $name, $description, $category, $supplier, $purchase, $selling, $stock, $minimum, $unit, $location]) {
            $product = Product::create([
                'sku' => $sku,
                'name' => $name,
                'description' => $description,
                'category_id' => $categories[$category]->id,
                'supplier_id' => $suppliers[$supplier]->id,
                'purchase_price' => $purchase,
                'selling_price' => $selling,
                'stock' => $stock,
                'min_stock' => $minimum,
                'unit' => $unit,
                'location' => $location,
                'is_active' => true,
            ]);

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $index % 2 === 0 ? $admin->id : $staff->id,
                'type' => 'in',
                'quantity' => $stock,
                'reference' => 'INIT-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'notes' => 'Saldo awal data contoh (seeder).',
                'movement_date' => now()->subDays($index % 2),
            ]);
        }

        // Transaksi contoh tambahan untuk mengisi histori dan grafik dashboard.
        $mouse = Product::where('sku', 'AKS-002')->firstOrFail();
        $mouse->decrement('stock', 3);
        StockMovement::create([
            'product_id' => $mouse->id,
            'user_id' => $staff->id,
            'type' => 'out',
            'quantity' => 3,
            'reference' => 'DO-2026-001',
            'notes' => 'Pengeluaran barang untuk unit operasional.',
            'movement_date' => now()->subDays(2),
        ]);
    }
}

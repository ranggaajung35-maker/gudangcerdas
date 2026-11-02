-- GudangCerdas | MySQL 8.0+
-- Import file ini melalui phpMyAdmin atau MySQL Client.
-- Password akun contoh: admin = GudangAdmin#2026 | staff = GudangStaff#2026

CREATE DATABASE IF NOT EXISTS gudang_cerdas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gudang_cerdas;
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS migrations;
CREATE TABLE migrations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  migration VARCHAR(255) NOT NULL,
  batch INT NOT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS stock_movements;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  phone VARCHAR(25) NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_users_role (role)
) ENGINE=InnoDB;

CREATE TABLE categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE suppliers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  company_name VARCHAR(150) NOT NULL,
  contact_name VARCHAR(100) NULL,
  email VARCHAR(255) NULL,
  phone VARCHAR(25) NULL,
  address TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_suppliers_company_name (company_name)
) ENGINE=InnoDB;

CREATE TABLE products (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(40) NOT NULL UNIQUE,
  name VARCHAR(150) NOT NULL,
  description TEXT NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  supplier_id BIGINT UNSIGNED NULL,
  purchase_price DECIMAL(15,2) NOT NULL DEFAULT 0,
  selling_price DECIMAL(15,2) NOT NULL DEFAULT 0,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  min_stock INT UNSIGNED NOT NULL DEFAULT 5,
  unit VARCHAR(30) NOT NULL DEFAULT 'pcs',
  location VARCHAR(80) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_products_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_products_name_sku (name, sku),
  INDEX idx_products_category_active (category_id, is_active)
) ENGINE=InnoDB;

CREATE TABLE stock_movements (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  type ENUM('in','out') NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  reference VARCHAR(60) NULL,
  notes TEXT NULL,
  movement_date DATETIME NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_movements_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_movements_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_movements_type_date (type, movement_date),
  INDEX idx_movements_product_date (product_id, movement_date)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO migrations (migration,batch) VALUES
('2026_01_01_000001_create_users_table',1),
('2026_01_01_000002_create_categories_table',1),
('2026_01_01_000003_create_suppliers_table',1),
('2026_01_01_000004_create_products_table',1),
('2026_01_01_000005_create_stock_movements_table',1);

INSERT INTO users (id,name,email,password,role,phone,created_at,updated_at) VALUES
(1,'Administrator Gudang','admin@gudang.local','$2y$12$0a5z1f4kiVmKZ5o6a6Lote/EKOVkUOv6gdlb90vHigIXDk2PbtS.e','admin','081234567890',NOW(),NOW()),
(2,'Staff Operasional','staff@gudang.local','$2y$12$faz4DQiRZlrzPs4SjLLQkeUc/yiEdZzrz7ZPtxiWmsKDp.6dCmyxS','staff','081298765432',NOW(),NOW());

INSERT INTO categories (id,name,description,is_active,created_at,updated_at) VALUES
(1,'Elektronik','Perangkat elektronik dan aksesorinya.',1,NOW(),NOW()),
(2,'Peralatan Kantor','Perlengkapan untuk kebutuhan kantor.',1,NOW(),NOW()),
(3,'Aksesoris Komputer','Aksesoris dan periferal komputer.',1,NOW(),NOW()),
(4,'Jaringan','Perangkat jaringan dan konektivitas.',1,NOW(),NOW()),
(5,'Perlengkapan Umum','Barang operasional dan perlengkapan umum.',1,NOW(),NOW());

INSERT INTO suppliers (id,company_name,contact_name,email,phone,address,created_at,updated_at) VALUES
(1,'PT Sumber Teknologi','Andi Pratama','andi@sumbertek.example','081200000101','Jl. Industri No. 12',NOW(),NOW()),
(2,'CV Digital Nusantara','Siti Rahma','siti@digitalnusantara.example','081200000102','Jl. Niaga No. 8',NOW(),NOW()),
(3,'PT Prima Komputindo','Budi Santoso','budi@primakom.example','081200000103','Jl. Merdeka No. 21',NOW(),NOW()),
(4,'CV Jaringan Mandiri','Rina Putri','rina@jaringanmandiri.example','081200000104','Jl. Pembangunan No. 5',NOW(),NOW()),
(5,'UD Karya Bersama','Dedi Kurniawan','dedi@karyabersama.example','081200000105','Jl. Raya Gudang No. 2',NOW(),NOW());

INSERT INTO products (id,sku,name,description,category_id,supplier_id,purchase_price,selling_price,stock,min_stock,unit,location,is_active,created_at,updated_at) VALUES
(1,'ELK-001','Monitor LED 24 Inch','Monitor IPS resolusi Full HD.',1,1,1250000,1499000,18,5,'unit','Rak A-01',1,NOW(),NOW()),
(2,'ELK-002','Printer Ink Tank','Printer multifungsi untuk kebutuhan kantor.',1,2,1850000,2199000,7,4,'unit','Rak A-02',1,NOW(),NOW()),
(3,'AKS-001','Keyboard Mechanical','Keyboard mekanis dengan layout full size.',3,3,420000,549000,24,6,'pcs','Rak B-01',1,NOW(),NOW()),
(4,'AKS-002','Mouse Wireless','Mouse nirkabel dengan sensor optik.',3,3,95000,139000,39,10,'pcs','Rak B-02',1,NOW(),NOW()),
(5,'JAR-001','Router Gigabit','Router gigabit dual band.',4,4,680000,829000,4,5,'unit','Rak C-01',1,NOW(),NOW()),
(6,'JAR-002','Kabel LAN Cat6 10m','Kabel jaringan kategori Cat6.',4,4,45000,69000,55,15,'pcs','Rak C-02',1,NOW(),NOW()),
(7,'KTR-001','Kertas A4 80gsm','Kertas fotokopi ukuran A4.',2,5,48000,58000,80,20,'rim','Rak D-01',1,NOW(),NOW()),
(8,'KTR-002','Tinta Printer Hitam','Tinta hitam kompatibel printer ink tank.',2,2,65000,89000,9,8,'botol','Rak D-02',1,NOW(),NOW()),
(9,'UMU-001','Lakban Bening','Lakban bening lebar 48 mm.',5,5,8000,12000,120,25,'roll','Rak E-01',1,NOW(),NOW()),
(10,'UMU-002','Bubble Wrap 1m','Plastik pelindung barang untuk pengemasan.',5,5,18000,26000,3,5,'meter','Rak E-02',1,NOW(),NOW());

INSERT INTO stock_movements (product_id,user_id,type,quantity,reference,notes,movement_date,created_at,updated_at) VALUES
(1,1,'in',18,'INIT-001','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(2,2,'in',7,'INIT-002','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(3,1,'in',24,'INIT-003','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(4,2,'in',42,'INIT-004','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(5,1,'in',4,'INIT-005','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(6,2,'in',55,'INIT-006','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(7,1,'in',80,'INIT-007','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(8,2,'in',9,'INIT-008','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(9,1,'in',120,'INIT-009','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(10,2,'in',3,'INIT-010','Saldo awal data contoh (SQL).',DATE_SUB(NOW(),INTERVAL 1 DAY),NOW(),NOW()),
(4,2,'out',3,'DO-2026-001','Pengeluaran barang untuk unit operasional.',DATE_SUB(NOW(),INTERVAL 2 DAY),NOW(),NOW());

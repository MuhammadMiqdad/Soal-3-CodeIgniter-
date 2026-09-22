# Soal 3 - CodeIgniter 4

CMS sederhana buat simulasi pembelian produk. Gak ada login/registrasi.

## Environment
- Windows 11 Home Single Language 23H2 (build 22631.6199)
- Intel Core i5-1135G7 @ 2.40GHz, RAM 8 GB

## Requirements
- PHP 8.2+
- Composer
- MySQL

## Cara jalanin

```
git clone <repo-ini>
cd <folder-repo>
composer install
```

Copy `env` jadi `.env`, terus isi bagian database:

```
database.default.hostname = localhost
database.default.database = db_wrapstation_test
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

Buat database sesuai nama di atas, lalu:

```
php spark migrate
php spark serve
```

Buka `http://localhost:8080/`

## Skema

- categories (category_id, category_name)
- products (product_id, category_id, product_name, qty_in_stock, price)
- transactions (transaction_id, product_id, payment_method, qty, created_at)

## Fitur

- CRUD kategori & produk
- Filter produk per kategori
- Validasi stok & harga gak boleh minus, kategori wajib dipilih
- Simulasi beli: pilih qty & metode bayar, stok otomatis kepotong, kecatat di transactions
- Bayar cash ada kalkulator kembalian, transfer/QRIS langsung diproses

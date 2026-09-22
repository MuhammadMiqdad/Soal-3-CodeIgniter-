<?php
/**
 * @var array $product
 * @var array $categories
 */
$errors = session()->getFlashdata('errors') ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Edit Produk</h2>
        <div class="subtitle"><a href="/products">&larr; Kembali ke daftar produk</a></div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Periksa kembali input kamu:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/products/update/<?= $product['product_id'] ?>" method="post">
            <label>Kategori</label>
            <select name="category_id" required>
                <option value="">- Pilih Kategori -</option>
                <?php foreach ($categories as $c): ?>
                    <?php $selected = old('category_id') ?? $product['category_id']; ?>
                    <option value="<?= $c['category_id'] ?>" <?= $selected == $c['category_id'] ? 'selected' : '' ?>>
                        <?= esc($c['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Nama Produk</label>
            <input type="text" name="product_name" value="<?= esc(old('product_name') ?? $product['product_name']) ?>" required>

            <label>Stok</label>
            <input type="number" name="qty_in_stock" min="0" step="1" value="<?= esc(old('qty_in_stock') ?? $product['qty_in_stock']) ?>" required>

            <label>Harga</label>
            <input type="number" name="price" min="0" step="0.01" value="<?= esc(old('price') ?? $product['price']) ?>" required>

            <div class="form-actions">
                <button type="submit" class="btn">Update</button>
                <a href="/products">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
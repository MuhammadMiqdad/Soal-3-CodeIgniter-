<?php
/**
 * @var array       $products
 * @var array       $categories
 * @var string|null $selectedCategory
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Daftar Produk</h2>
                <div class="subtitle">Kelola produk dan simulasikan pembelian.</div>
            </div>
            <div class="toolbar-links">
                <a href="/products/new" class="btn">+ Tambah Produk</a>
                <a href="/categories" class="btn btn-secondary">Kelola Kategori</a>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form method="get" action="/products" style="margin-bottom: 18px;">
            <label style="display:inline; margin:0 8px 0 0;">Filter Kategori:</label>
            <select id="category_filter" name="category_id" onchange="this.form.submit()">
                <option value="">Semua</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['category_id'] ?>" <?= $selectedCategory == $c['category_id'] ? 'selected' : '' ?>>
                        <?= esc($c['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if (empty($products)): ?>
            <div class="empty-state">Belum ada produk. Klik "+ Tambah Produk" untuk mulai.</div>
        <?php else: ?>
        <table>
            <tr>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= esc($p['product_name']) ?></td>
                <td><?= esc($p['category_name'] ?? '-') ?></td>
                <td>
                    <?php if ($p['qty_in_stock'] == 0): ?>
                        <span class="badge badge-out">Habis</span>
                    <?php elseif ($p['qty_in_stock'] < 5): ?>
                        <span class="badge badge-low"><?= esc($p['qty_in_stock']) ?> tersisa</span>
                    <?php else: ?>
                        <span class="badge badge-ok"><?= esc($p['qty_in_stock']) ?></span>
                    <?php endif; ?>
                </td>
                <td>Rp <?= number_format((float) $p['price'], 0, ',', '.') ?></td>
                <td class="actions">
                    <a href="/products/buy/<?= $p['product_id'] ?>">Beli</a>
                    <a href="/products/edit/<?= $p['product_id'] ?>">Edit</a>
                    <a href="/products/delete/<?= $p['product_id'] ?>" class="del" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
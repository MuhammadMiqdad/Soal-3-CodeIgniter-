<?php
/** @var array $categories */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Daftar Kategori</h2>
                <div class="subtitle"><a href="/products">&larr; Kembali ke Produk</a></div>
            </div>
            <div class="toolbar-links">
                <a href="/categories/new" class="btn">+ Tambah Kategori</a>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info"><?= esc(session()->getFlashdata('info')) ?></div>
        <?php endif; ?>

        <?php if (empty($categories)): ?>
            <div class="empty-state">Belum ada kategori. Klik "+ Tambah Kategori" untuk mulai.</div>
        <?php else: ?>
        <table>
            <tr>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($categories as $c): ?>
            <tr>
                <td><?= esc($c['category_name']) ?></td>
                <td class="actions">
                    <a href="/categories/edit/<?= $c['category_id'] ?>">Edit</a>
                    <a href="/categories/delete/<?= $c['category_id'] ?>" class="del" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
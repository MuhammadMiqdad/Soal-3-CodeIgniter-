<?php
$errors = session()->getFlashdata('errors') ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Tambah Kategori</h2>
        <div class="subtitle"><a href="/categories">&larr; Kembali ke daftar kategori</a></div>

        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info"><?= esc(session()->getFlashdata('info')) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/categories/create" method="post">
            <label>Nama Kategori</label>
            <input type="text" name="category_name" value="<?= esc(old('category_name')) ?>" required>

            <div class="form-actions">
                <button type="submit" class="btn">Simpan</button>
                <a href="/categories">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
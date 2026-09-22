<?php
/** @var array $category */
$errors = session()->getFlashdata('errors') ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Kategori</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Edit Kategori</h2>
        <div class="subtitle"><a href="/categories">&larr; Kembali ke daftar kategori</a></div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/categories/update/<?= $category['category_id'] ?>" method="post">
            <label>Nama Kategori</label>
            <input type="text" name="category_name" value="<?= esc(old('category_name') ?? $category['category_name']) ?>" required>

            <div class="form-actions">
                <button type="submit" class="btn">Update</button>
                <a href="/categories">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $category = $_POST['category'];
    $price    = $_POST['price'];
    $stock    = $_POST['stock'];

    $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)");
    $stmt->execute([
        'name'     => $name,
        'category' => $category,
        'price'    => $price,
        'stock'    => $stock
    ]);

    header('Location: index.php?msg=created');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - Manajemen Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container form-container">
        <!-- Header dengan tombol Kembali ke Dashboard -->
        <div class="header-section">
            <div>
                <h1>➕ Tambah Produk Baru</h1>
                <p class="subtitle">Masukkan rincian informasi produk baru ke dalam inventaris.</p>
            </div>
            <a href="index.php" class="btn btn-dashboard">⬅️ Kembali ke Dashboard</a>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" id="name" name="name" placeholder="Contoh: Laptop Asus" required>
            </div>

            <div class="form-group">
                <label for="category">Kategori</label>
                <input type="text" id="category" name="category" placeholder="Contoh: Elektronik" required>
            </div>

            <div class="form-group">
                <label for="price">Harga (Rp)</label>
                <input type="number" id="price" name="price" placeholder="Contoh: 15000000" min="0" required>
            </div>

            <div class="form-group">
                <label for="stock">Stok</label>
                <input type="number" id="stock" name="stock" placeholder="Contoh: 10" min="0" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
                <a href="index.php" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
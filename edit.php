<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $category = $_POST['category'];
    $price    = $_POST['price'];
    $stock    = $_POST['stock'];

    $updateStmt = $pdo->prepare("UPDATE products SET name = :name, category = :category, price = :price, stock = :stock WHERE id = :id");
    $updateStmt->execute([
        'name'     => $name,
        'category' => $category,
        'price'    => $price,
        'stock'    => $stock,
        'id'       => $id
    ]);

    header('Location: index.php?msg=updated');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Manajemen Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container form-container">
        <!-- Header dengan tombol Kembali ke Dashboard -->
        <div class="header-section">
            <div>
                <h1>✏️ Edit Produk</h1>
                <p class="subtitle">Perbarui informasi barang di inventaris Anda.</p>
            </div>
            <a href="index.php" class="btn btn-dashboard">⬅️ Kembali ke Dashboard</a>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="category">Kategori</label>
                <input type="text" id="category" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Harga (Rp)</label>
                <input type="number" id="price" name="price" value="<?= $product['price'] ?>" min="0" required>
            </div>

            <div class="form-group">
                <label for="stock">Stok</label>
                <input type="number" id="stock" name="stock" value="<?= $product['stock'] ?>" min="0" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
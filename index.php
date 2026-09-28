<?php
require_once '../config/db.php';

$search =$_GET['search'] ?? '';
$msg =$_GET['msg'] ?? '';

if ($search !== '') {
    $stmt =$pdo->prepare("SELECT * FROM products WHERE name LIKE :search1 OR category LIKE :search2 ORDER BY id DESC");
    $stmt->execute([
        'search1' => '%' . $search . '%',
        'search2' => '%' . $search . '%'
    ]);
} else {
    $stmt =$pdo->query("SELECT * FROM products ORDER BY id DESC");
}

$products =$stmt->fetchAll();

$total_produk = count($products);
$total_stok = array_sum(array_column($products, 'stock'));

// Respon khusus AJAX untuk Live Search saat mengetik
if (isset($_GET['ajax'])) {
    if (empty($products)) {
        echo '<tr><td colspan="6" style="text-align: center; color: #64748b;">Produk tidak ditemukan.</td></tr>';
    } else {
        foreach ($products as$row) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td><strong>' . htmlspecialchars($row['name']) . '</strong></td>';
            echo '<td><span class="badge">' . htmlspecialchars($row['category']) . '</span></td>';
            echo '<td>Rp ' . number_format($row['price'], 0, ',', '.') . '</td>';
            echo '<td>' . $row['stock'] . '</td>';
            echo '<td>';
            echo '<a href="edit.php?id=' . $row['id'] . '" class="btn-edit">Edit</a> ';
            echo '<a href="delete.php?id=' . $row['id'] . '" class="btn-delete">Hapus</a>';
            echo '</td>';
            echo '</tr>';
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Header & Navigasi Dashboard -->
        <div class="header-section">
            <div>
                <h1>📦 Sistem Manajemen Produk</h1>
                <p class="subtitle">Kelola inventaris barang, stok, dan harga toko Anda secara real-time.</p>
            </div>
            <a href="index.php" class="btn btn-dashboard">🏠 Dashboard</a>
        </div>

        <!-- Banner Notifikasi Alert -->
        <?php if ($msg === 'created'): ?>
            <div class="alert alert-success">✅ Produk baru berhasil ditambahkan!</div>
        <?php elseif ($msg === 'updated'): ?>
            <div class="alert alert-success">✅ Data produk berhasil diperbarui!</div>
        <?php elseif ($msg === 'deleted'): ?>
            <div class="alert alert-danger">🗑️ Produk berhasil dihapus dari sistem!</div>
        <?php endif; ?>

        <!-- Kartu Ringkasan Data -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-title">Total Jenis Produk</span>
                <span class="stat-value"><?= $total_produk ?> Item</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Total Stok Tersedia</span>
                <span class="stat-value"><?= $total_stok ?> Unit</span>
            </div>
        </div>

        <!-- Baris Aksi & Pencarian -->
        <div class="action-bar">
            <a href="create.php" class="btn btn-primary">+ Tambah Produk Baru</a>
            
            <form method="GET" action="index.php" class="search-form">
                <input type="text" id="search-input" name="search" placeholder="Cari nama atau kategori..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
                <button type="submit" class="btn">Cari</button>
                <?php if ($search !== ''): ?>
                    <a href="index.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel Produk -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="product-table-body">
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b;">Belum ada data produk.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as$row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                            <td><span class="badge"><?= htmlspecialchars($row['category']) ?></span></td>
                            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                            <td><?= $row['stock'] ?></td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn-delete">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Script Deteksi Huruf Real-time -->
    <script>
        const searchInput = document.getElementById('search-input');
        const tableBody = document.getElementById('product-table-body');

        // Otomatis terdeteksi saat ngetik 1 huruf
        searchInput.addEventListener('input', function() {
            const query = this.value;

            fetch(`index.php?search=${encodeURIComponent(query)}&ajax=1`)
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                })
                .catch(err => console.error('Error:', err));
        });
    </script>
</body>
</html>
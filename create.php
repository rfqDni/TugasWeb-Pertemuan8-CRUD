<?php
require_once 'config/database.php';
$pdo = Database::getInstance()->getConnection();

// Ambil data kategori & supplier untuk dropdown
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$suppliers  = $pdo->query("SELECT id, name FROM suppliers ORDER BY name")->fetchAll();

// Proses form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $categoryId = (int) $_POST['category_id'];
    $supplierId = (int) $_POST['supplier_id'];
    $price      = (float) $_POST['price'];
    $stock      = (int) $_POST['stock'];

    $sql = "INSERT INTO products (name, category_id, supplier_id, price, stock)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $categoryId, $supplierId, $price, $stock]);

    header('Location: index.php?msg=created');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>➕ Tambah Produk Baru</h1>

        <form method="POST">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" required maxlength="150">
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier_id" required>
                    <option value="">-- Pilih Supplier --</option>
                    <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" min="0" value="0" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn">Batal</a>
        </form>
    </div>
</body>
</html>
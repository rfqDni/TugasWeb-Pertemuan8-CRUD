<?php
require_once 'config/database.php';
$pdo = Database::getInstance()->getConnection();

// Ambil data kategori & supplier untuk dropdown
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$suppliers  = $pdo->query("SELECT id, name FROM suppliers ORDER BY name")->fetchAll();

// Ambil ID dari URL
$id = (int) ($_GET['id'] ?? 0);

// Ambil data produk lama
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Produk tidak ditemukan!");
}

// Proses form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $categoryId = (int) $_POST['category_id'];
    $supplierId = (int) $_POST['supplier_id'];
    $price      = (float) $_POST['price'];
    $stock      = (int) $_POST['stock'];

    $sql = "UPDATE products 
            SET name=?, category_id=?, supplier_id=?, price=?, stock=?
            WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $categoryId, $supplierId, $price, $stock, $id]);

    header('Location: index.php?msg=updated');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Edit Produk</h1>

        <form method="POST">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" required maxlength="150"
                       value="<?= htmlspecialchars($product['name']) ?>">
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier_id" required>
                    <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>"
                            <?= $s['id'] == $product['supplier_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" step="0.01" min="0" required
                       value="<?= $product['price'] ?>">
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" min="0" required
                       value="<?= $product['stock'] ?>">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn">Batal</a>
        </form>
    </div>
</body>
</html>
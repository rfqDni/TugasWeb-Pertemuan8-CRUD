<?php
require_once 'config/database.php';
$pdo = Database::getInstance()->getConnection();

$sql = "SELECT p.id, p.name, p.price, p.stock,
               c.name AS category,
               s.name AS supplier
        FROM products p
        JOIN categories c ON p.category_id = c.id
        JOIN suppliers s ON p.supplier_id = s.id
        ORDER BY p.name";

$products = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk - Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>📦 Daftar Produk Inventaris</h1>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?php
                $msgs = [
                    'created' => 'Produk berhasil ditambahkan!',
                    'updated' => 'Produk berhasil diupdate!',
                    'deleted' => 'Produk berhasil dihapus!',
                ];
                echo $msgs[$_GET['msg']] ?? 'Aksi berhasil';
                ?>
            </div>
        <?php endif; ?>

        <a href="create.php" class="btn btn-primary">+ Tambah Produk</a>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="7" style="text-align:center">Belum ada produk</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['category']) ?></td>
                        <td><?= htmlspecialchars($p['supplier']) ?></td>
                        <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                        <td><?= $p['stock'] ?></td>
                        <td>
                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-warning">Edit</a>
                            <form method="POST" action="delete.php" style="display:inline"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
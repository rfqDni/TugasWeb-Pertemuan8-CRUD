<?php
require_once 'config/database.php';
$pdo = Database::getInstance()->getConnection();

// Hanya proses kalau method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }

    header('Location: index.php?msg=deleted');
    exit;
}

// Kalau bukan POST, redirect ke index
header('Location: index.php');
exit;
<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header('Location: index.php?msg=deleted');
exit;
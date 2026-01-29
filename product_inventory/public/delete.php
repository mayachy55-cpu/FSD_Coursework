<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->execute([$_GET['id']]);

header("Location: products.php");
exit;

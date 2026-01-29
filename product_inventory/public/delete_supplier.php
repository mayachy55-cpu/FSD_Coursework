<?php
require '../includes/auth.php';
require '../config/db.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM suppliers WHERE id=?");
    $stmt->execute([$_GET['id']]);
}

header("Location: suppliers.php");
exit;

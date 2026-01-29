<?php
require '../includes/auth.php';
require '../config/db.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$_GET['id']]);
}

header("Location: users.php");
exit;
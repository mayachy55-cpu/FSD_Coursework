<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$baseUrl = "/~np03cs4a240274/product_inventory";

// Get current page for active menu highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory System</title>
<link rel="stylesheet" href="/~np03cs4a240274/product_inventory/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="container nav-container">
            <a href="<?= $baseUrl ?>/public/index.php" class="brand">
                <span class="brand-icon">📦</span>
                <span class="brand-text">Product Inventory</span>
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Hamburger Menu Button -->
                <button class="hamburger" id="hamburger" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <!-- Navigation Links -->
                <div class="nav-links" id="navLinks">
                    <a href="<?= $baseUrl ?>/public/index.php"
                        class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">
                        <span class="nav-icon">📊</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= $baseUrl ?>/public/products.php"
                        class="nav-link <?= $currentPage === 'products.php' ? 'active' : '' ?>">
                        <span class="nav-icon">📦</span>
                        <span>Products</span>
                    </a>
                    <a href="<?= $baseUrl ?>/public/suppliers.php"
                        class="nav-link <?= $currentPage === 'suppliers.php' ? 'active' : '' ?>">
                        <span class="nav-icon">🚚</span>
                        <span>Suppliers</span>
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="<?= $baseUrl ?>/public/users.php"
                            class="nav-link <?= $currentPage === 'users.php' ? 'active' : '' ?>">
                            <span class="nav-icon">👥</span>
                            <span>Users</span>
                        </a>
                    <?php endif; ?>
                    <div class="nav-user">
                        <span class="user-badge">
                            👤 <?= htmlspecialchars($_SESSION['username']) ?>
                            <small
                                style="opacity: 0.7; font-size: 0.7rem; background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 10px; margin-left: 5px;">
                                <?= strtoupper($_SESSION['role'] ?? 'USER') ?>
                            </small>
                        </span>
                        <a href="<?= $baseUrl ?>/public/logout.php" class="btn btn-sm btn-danger">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Hamburger Menu Button for Guest -->
                <button class="hamburger" id="hamburger" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="nav-links" id="navLinks">
                    <a href="<?= $baseUrl ?>/public/login.php"
                        class="nav-link <?= $currentPage === 'login.php' ? 'active' : '' ?>">Login</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="<?= isset($containerClass) ? $containerClass : 'container' ?>">
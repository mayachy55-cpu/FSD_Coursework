<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

// Get statistics
$totalProducts = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalSuppliers = $conn->query("SELECT COUNT(*) FROM suppliers")->fetchColumn();
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$lowStock = $conn->query("SELECT COUNT(*) FROM products WHERE quantity < 10")->fetchColumn();

// Recent products
$recentProducts = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dashboard-header" style="margin-bottom: 2rem">
    <h2 style="margin:0">Dashboard Overview</h2>
    <p style="color: var(--text-muted)">Quick summary of your inventory system</p>
</div>

<div
    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Products Card -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; border-left: 4px solid #4f46e5;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0 0 0.5rem 0;">Total Products</p>
                <h3 style="margin: 0; font-size: 1.75rem;"><?= $totalProducts ?></h3>
            </div>
            <span style="font-size: 1.5rem;">📦</span>
        </div>
        <a href="products.php" style="display: block; margin-top: 1rem; font-size: 0.875rem; color: #4f46e5;">View All
            →</a>
    </div>

    <!-- Suppliers Card -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; border-left: 4px solid #10b981;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0 0 0.5rem 0;">Total Suppliers</p>
                <h3 style="margin: 0; font-size: 1.75rem;"><?= $totalSuppliers ?></h3>
            </div>
            <span style="font-size: 1.5rem;">🚚</span>
        </div>
        <a href="suppliers.php" style="display: block; margin-top: 1rem; font-size: 0.875rem; color: #10b981;">View All
            →</a>
    </div>

    <!-- Users Card -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; border-left: 4px solid #f59e0b;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0 0 0.5rem 0;">Total Users</p>
                <h3 style="margin: 0; font-size: 1.75rem;"><?= $totalUsers ?></h3>
            </div>
            <span style="font-size: 1.5rem;">👥</span>
        </div>
        <a href="users.php" style="display: block; margin-top: 1rem; font-size: 0.875rem; color: #f59e0b;">View All
            →</a>
    </div>

    <!-- Alert Card -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; border-left: 4px solid #ef4444;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0 0 0.5rem 0;">Low Stock Alert</p>
                <h3 style="margin: 0; font-size: 1.75rem; color: #ef4444;"><?= $lowStock ?></h3>
            </div>
            <span style="font-size: 1.5rem;">⚠️</span>
        </div>
        <a href="products.php" style="display: block; margin-top: 1rem; font-size: 0.875rem; color: #ef4444;">Restock
            Now →</a>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.5rem">Recently Added Products</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Added Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentProducts as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['category']) ?></td>
                        <td>$<?= number_format($p['price'], 2) ?></td>
                        <td style="color: var(--text-muted); font-size: 0.875rem;">
                            <?= date('M d, Y', strtotime($p['created_at'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.5rem; text-align: center;">
        <a href="products.php" class="btn btn-outline">View Full Inventory</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
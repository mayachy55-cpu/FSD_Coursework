<?php
$containerClass = 'container-xl';
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

$stmt = $conn->query("
    SELECT p.*, u.username as creator_name, s.name as supplier_name 
    FROM products p 
    LEFT JOIN users u ON p.user_id = u.id 
    LEFT JOIN suppliers s ON p.supplier_id = s.id 
    ORDER BY p.id DESC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card" style="max-width: 100%; overflow: hidden;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem">
        <h2 style="margin:0; color: #d53f8c;"><span style="font-size: 1.2rem; margin-right: 10px;">📊</span>List of
            Products</h2>
        <?php if (isAdmin()): ?>
            <div style="display: flex; gap: 10px;">
                <a href="categories.php" class="btn btn-secondary">📁 Manage Categories</a>
                <a href="add.php" class="btn btn-primary">+ Add Product</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="search-bar" style="margin-bottom: 2rem; width: 100%; max-width: none;">
        <input type="text" id="search" placeholder="Search products..." style="width: 100%">
    </div>

    <div id="result"></div>

    <div id="tableWrapper" class="table-wrapper">
        <table style="font-size: 0.9rem;">
            <thead>
                <tr>
                    <th style="width: 40px">#</th>
                    <th style="width: 100px">IMAGE</th>
                    <th>PRODUCT NAME</th>
                    <th>STOCK</th>
                    <th>DESCRIPTION</th>
                    <th>SUPPLIERS</th>
                    <th>CREATED BY</th>
                    <th>CREATED AT</th>
                    <th>UPDATED AT</th>
                    <?php if (isAdmin()): ?>
                        <th style="width: 120px">ACTION</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php $count = 1;
                    foreach ($products as $p): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td>
                                <?php if ($p['image']): ?>
                                    <?php
                                    $displayPath = $p['image'];
                                    // If it's a local path (doesn't start with http), prepend baseUrl
                                    if (strpos($displayPath, 'http') !== 0) {
                                        $displayPath = $baseUrl . '/public/' . $displayPath;
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($displayPath) ?>" alt="Product" class="product-img-large">
                                <?php else: ?>
                                    <div class="no-image-large">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td style="font-weight: 600"><?= htmlspecialchars($p['name']) ?></td>
                            <td>
                                <span style="font-weight: 500; <?= $p['quantity'] < 10 ? 'color: var(--danger-color)' : '' ?>">
                                    <?= $p['quantity'] ?>
                                </span>
                            </td>
                            <td style="max-width: 200px; font-size: 0.85rem; color: var(--text-muted);">
                                <?= htmlspecialchars($p['description'] ?? 'No description') ?>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem;">
                                    • <?= htmlspecialchars($p['supplier_name'] ?? 'None') ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.75rem; color: #4a5568;">
                                    <?= htmlspecialchars($p['creator_name'] ?? 'System') ?>
                                </span>
                            </td>
                            <td style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.2;">
                                <?= date('M d, Y', strtotime($p['created_at'])) ?><br>
                                <small style="opacity:0.7"><?= date('h:i A', strtotime($p['created_at'])) ?></small>
                            </td>
                            <td style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.2;">
                                <?= date('M d, Y', strtotime($p['updated_at'])) ?><br>
                                <small style="opacity:0.7"><?= date('h:i A', strtotime($p['updated_at'])) ?></small>
                            </td>
                            <?php if (isAdmin()): ?>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 5px;">
                                        <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary"
                                            style="padding: 4px 8px; font-size: 0.8rem;">
                                            ✎ Edit
                                        </a>
                                        <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                                            style="padding: 4px 8px; font-size: 0.8rem;" onclick="return confirm('Delete?')">
                                            🗑 Delete
                                        </a>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= isAdmin() ? 10 : 9 ?>"
                            style="text-align:center; padding: 3rem; color: var(--text-muted)">
                            No products found. <?php if (isAdmin()): ?><a href="add.php">Add one now</a><?php endif; ?>.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div
        style="text-align: right; margin-top: 1rem; font-weight: 700; color: #d53f8c; text-transform: uppercase; font-size: 0.85rem;">
        <?= count($products) ?> PRODUCTS
    </div>
</div>

<?php include '../includes/footer.php'; ?>
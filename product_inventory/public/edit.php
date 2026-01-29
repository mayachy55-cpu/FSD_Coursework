<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();

// Fetch suppliers and categories for the dropdown
$suppliers = $conn->query("SELECT id, name FROM suppliers ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$allCategories = $conn->query("SELECT name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_COLUMN);

if ($_POST) {
    if (!is_numeric($_POST['price']) || !is_numeric($_POST['quantity'])) {
        die("Invalid input");
    }

    $imagePath = $p['image']; // Keep existing image by default

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/products/';
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Delete old local image if it exists
                if ($p['image'] && file_exists($p['image']) && strpos($p['image'], 'uploads/') === 0) {
                    unlink($p['image']);
                }
                $imagePath = $targetFile;
            }
        }
    }

    $stmt = $conn->prepare(
        "UPDATE products SET name=?, image=?, category=?, description=?, quantity=?, price=?, supplier_id=? WHERE id=?"
    );
    $stmt->execute([
        $_POST['name'],
        $imagePath,
        $_POST['category'],
        $_POST['description'],
        $_POST['quantity'],
        $_POST['price'],
        $_POST['supplier_id'],
        $id
    ]);
    header("Location: products.php");
    exit;
}
?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem">Edit Product</h2>

    <form method="post" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($p['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Change Image (Optional)</label>
                <input type="file" name="image" accept="image/*">
                <?php if ($p['image']): ?>
                    <small style="color: var(--text-muted)">Current: <?= basename($p['image']) ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="">Select Category</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= $p['category'] == $cat ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $p['supplier_id'] == $s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Enter product description"
                rows="3"><?= htmlspecialchars($p['description'] ?? '') ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label>Quantity (Stock)</label>
                <input type="number" name="quantity" value="<?= $p['quantity'] ?>" required>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" required>
            </div>
        </div>

        <div style="margin-top: 2rem">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="products.php" class="btn btn-secondary" style="margin-left:10px">Cancel</a>
        </div>
    </form>
</div>
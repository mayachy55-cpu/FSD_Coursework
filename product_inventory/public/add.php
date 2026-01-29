<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}
require '../includes/csrf.php';

// Fetch suppliers and categories for the dropdowns
$suppliers = $conn->query("SELECT id, name FROM suppliers ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$allCategories = $conn->query("SELECT name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_COLUMN);

if ($_POST) {
    if ($_POST['token'] !== $_SESSION['token'])
        die("CSRF Failed");

    if (!is_numeric($_POST['price']) || !is_numeric($_POST['quantity'])) {
        die("Invalid input");
    }

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/products/';
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Allow certain file formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            }
        }
    }

    $stmt = $conn->prepare(
        "INSERT INTO products(name, image, category, description, quantity, price, supplier_id, user_id)
        VALUES (?,?,?,?,?,?,?,?)"
    );
    $stmt->execute([
        $_POST['name'],
        $imagePath,
        $_POST['category'],
        $_POST['description'],
        $_POST['quantity'],
        $_POST['price'],
        $_POST['supplier_id'],
        $_SESSION['user_id']
    ]);

    header("Location: products.php");
    exit;
}
?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem">Add New Product</h2>

    <form method="post" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" placeholder="Enter product name" required>
            </div>

            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="image" accept="image/*">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="">Select Category</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Enter product description" rows="3"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label>Quantity (Stock)</label>
                <input type="number" name="quantity" placeholder="Enter quantity" required>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" placeholder="Enter price" required>
            </div>
        </div>

        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

        <div style="margin-top: 2rem">
            <button class="btn btn-primary">Add Product</button>
            <a href="products.php" class="btn btn-secondary" style="margin-left:10px">Cancel</a>
        </div>
    </form>
</div>
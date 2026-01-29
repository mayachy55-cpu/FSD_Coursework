<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';
$edit_id = $_GET['edit'] ?? null;
$edit_name = '';

// Handle Post (Add or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $id = $_POST['id'] ?? null;

    if (empty($name)) {
        $error = "Category name is required";
    } else {
        try {
            if ($id) {
                // Update
                $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
                $stmt->execute([$name, $id]);
                $success = "Category updated successfully!";
                $edit_id = null; // Clear edit mode
            } else {
                // Add
                $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
                $stmt->execute([$name]);
                $success = "Category added successfully!";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Category already exists";
            } else {
                $error = "Error saving category: " . $e->getMessage();
            }
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit;
}

// Fetch category for editing
if ($edit_id) {
    $stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_name = $stmt->fetchColumn();
}

$stmt = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem">
        <h2 style="margin: 0">Manage Categories</h2>
        <a href="products.php" class="btn btn-secondary">Back to Products</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST"
        style="margin-bottom: 2rem; display: flex; gap: 1rem; background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0;">
        <input type="hidden" name="id" value="<?= $edit_id ?>">
        <div class="form-group" style="flex: 1; margin: 0;">
            <label style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem; display: block;">
                <?= $edit_id ? 'Editing Category' : 'Add New Category' ?>
            </label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_name) ?>" placeholder="Enter category name"
                required style="width: 100%">
        </div>
        <div style="display: flex; align-items: flex-end; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary"><?= $edit_id ? 'Update' : 'Add' ?> Category</button>
            <?php if ($edit_id): ?>
                <a href="categories.php" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px">ID</th>
                    <th>Category Name</th>
                    <th>Created At</th>
                    <th style="width: 150px">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td>#<?= $c['id'] ?></td>
                        <td style="font-weight: 500"><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="categories.php?edit=<?= $c['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="categories.php?delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete category?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
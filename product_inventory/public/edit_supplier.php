<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? die("ID missing");
$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id=?");
$stmt->execute([$id]);
$s = $stmt->fetch();

if (!$s)
    die("Supplier not found");

if ($_POST) {
    $stmt = $conn->prepare("UPDATE suppliers SET name=?, email=?, phone=?, address=? WHERE id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $id
    ]);
    header("Location: suppliers.php");
    exit;
}
?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem">Edit Supplier</h2>

    <form method="post">
        <div class="form-group">
            <label>Supplier Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($s['name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($s['email']) ?>">
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($s['phone']) ?>">
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="3"><?= htmlspecialchars($s['address']) ?></textarea>
        </div>

        <div style="margin-top: 2rem">
            <button type="submit" class="btn btn-primary">Update Supplier</button>
            <a href="suppliers.php" class="btn btn-secondary" style="margin-left:10px">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
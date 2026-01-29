<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}
require '../includes/csrf.php';

if ($_POST) {
    if ($_POST['token'] !== $_SESSION['token'])
        die("CSRF Failed");

    $stmt = $conn->prepare("INSERT INTO suppliers (name, email, phone, address) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address']
    ]);

    header("Location: suppliers.php");
    exit;
}
?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem">Add New Supplier</h2>

    <form method="post">
        <div class="form-group">
            <label>Supplier Name</label>
            <input type="text" name="name" placeholder="Enter supplier name" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter contact email">
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="Enter phone number">
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" placeholder="Enter supplier address" rows="3"></textarea>
        </div>

        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

        <div style="margin-top: 2rem">
            <button class="btn btn-primary">Add Supplier</button>
            <a href="suppliers.php" class="btn btn-secondary" style="margin-left:10px">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
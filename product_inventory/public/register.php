<?php
include '../includes/header.php';
require '../includes/auth.php'; // Includes session_start if needed
require '../config/db.php';

// Check if user is admin
if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$errors = [];

if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'user';

    if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        $errors[] = "Username already exists";
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users(username, password, role) VALUES(?,?,?)"
        );
        $stmt->execute([$username, $hash, $role]);

        header("Location: users.php?added=1");
        exit;
    }
}
?>

<div class="auth-container">
    <div class="card">
        <div class="auth-header">
            <h3>Add New User</h3>
            <p style="color: var(--text-muted)">Create a new system user</p>
        </div>

        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success">User added successfully!</div>
        <?php endif; ?>

        <?php foreach ($errors as $e): ?>
            <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>

        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Choose a username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input name="password" type="password" placeholder="Choose a strong password" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control"
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Add User</button>
        </form>

        <div class="auth-footer">
            <a href="users.php">Back to User Management</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
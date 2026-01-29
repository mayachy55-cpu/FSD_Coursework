<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$stmt = $conn->query("SELECT id, username, role FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem">
        <h2 style="margin: 0">User Management</h2>
        <a href="register.php" class="btn btn-primary">Add New User</a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th style="width: 150px">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>#
                            <?= $u['id'] ?>
                        </td>
                        <td style="font-weight: 500">
                            <?= htmlspecialchars($u['username']) ?>
                        </td>
                        <td>
                            <span
                                style="background: <?= $u['role'] === 'admin' ? '#fee2e2; color: #b91c1c' : '#f1f5f9; color: #475569' ?>; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; font-weight: 600;">
                                <?= strtoupper($u['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete user?')">Delete</a>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 0.85rem">Logged In</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
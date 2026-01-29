<?php
include '../includes/header.php';
require '../includes/auth.php';
require '../config/db.php';

$stmt = $conn->query("SELECT * FROM suppliers ORDER BY id DESC");
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <h2 style="margin:0">Supplier Management</h2>
    <?php if (isAdmin()): ?>
        <a href="add_supplier.php" class="btn btn-primary">+ Add Supplier</a>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <?php if (isAdmin()): ?>
                        <th style="width: 150px">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($suppliers)): ?>
                    <?php foreach ($suppliers as $s): ?>
                        <tr>
                            <td style="font-weight: 500">
                                <?= htmlspecialchars($s['name']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($s['email']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($s['phone']) ?>
                            </td>
                            <td style="font-size: 0.875rem; color: var(--text-muted)">
                                <?= htmlspecialchars($s['address']) ?>
                            </td>
                            <?php if (isAdmin()): ?>
                                <td>
                                    <a href="edit_supplier.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete_supplier.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete supplier?')">Delete</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= isAdmin() ? 5 : 4 ?>"
                            style="text-align:center; padding: 2rem; color: var(--text-muted)">
                            No suppliers found. <?php if (isAdmin()): ?><a href="add_supplier.php">Add one
                                    now</a><?php endif; ?>.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
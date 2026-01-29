<?php
require '../config/db.php';

$q = $_GET['q'] ?? '';

$stmt = $conn->prepare(
  "SELECT * FROM products WHERE name LIKE ? OR category LIKE ? ORDER BY id DESC"
);
$stmt->execute(["%$q%", "%$q%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($results) == 0) {
  echo "<p>No products found</p>";
  exit;
}

echo "<div class='table-wrapper'>
        <table>
          <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Price</th>
                <th style='width: 180px'>Action</th>
            </tr>
          </thead>
          <tbody>";

foreach ($results as $p) {
  echo "<tr>
            <td style='font-weight: 500'>" . htmlspecialchars($p['name']) . "</td>
            <td>
                <span style='background:#e0f2fe; color:#0369a1; padding:2px 8px; border-radius:12px; font-size:0.85rem'>
                    " . htmlspecialchars($p['category']) . "
                </span>
            </td>
            <td>{$p['quantity']}</td>
            <td style='font-weight:600'>$" . number_format($p['price'], 2) . "</td>
            <td>
              <a href='edit.php?id={$p['id']}' class='btn btn-sm btn-primary' style='margin-right:5px'>Edit</a>
              <a href='delete.php?id={$p['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete?')\">Delete</a>
            </td>
          </tr>";
}

echo "</tbody></table></div>";

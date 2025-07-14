<?php
session_start();
include 'navbar.php';
include 'db.php';

if (!isset($_SESSION['uid'])) {
  echo "<p style='text-align:center; margin-top:100px;'>Please <a href='login.php'>login</a> to view your orders.</p>";
  exit;
}

$uid = $_SESSION['uid'];
$query = "SELECT o.*, p.title FROM orders o JOIN product p ON o.pid = p.pid WHERE o.uid = $uid";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders | PetMart</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #F7FDFD;
      margin: 0;
      padding: 0;
    }

    .order-container {
      max-width: 1000px;
      margin: 90px auto;
      padding: 20px;
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    h2 {
      text-align: center;
      color: #2F4F4F;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }

    th, td {
      padding: 14px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    .no-orders {
      text-align: center;
      margin-top: 50px;
      font-size: 18px;
      color: #555;
    }
  </style>
</head>
<body>

<div class="order-container">
  <h2>My Orders</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Price (₹)</th>
          <th>Quantity</th>
          <th>Total (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= number_format($row['price'], 2) ?></td>
          <td><?= $row['quantity'] ?></td>
          <td><?= number_format($row['total'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="no-orders">You have not placed any orders yet.</p>
  <?php endif; ?>
</div>

</body>
</html>

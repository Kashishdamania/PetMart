<?php
ob_start();
session_start();
include 'db.php';
include 'navbar.php';

$uid = $_SESSION['uid'] ?? null;
$cart = $_SESSION['cart'] ?? [];

// Handle actions: update quantity, remove item, place order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_qty'])) {
        $pid = $_POST['pid'];
        $qty = max(1, intval($_POST['quantity']));
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['quantity'] = $qty;
        }
        header("Location: cart.php");
        exit;
    }

    if (isset($_POST['remove'])) {
        $pid = $_POST['pid'];
        unset($_SESSION['cart'][$pid]);
        header("Location: cart.php");
        exit;
    }

    if (isset($_POST['place_order']) && $uid && !empty($cart)) {
        foreach ($_SESSION['cart'] as $pid => $item) {
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total = $price * $quantity;
            

            $stmt = $conn->prepare("INSERT INTO orders (pid, uid, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiidd",$pid, $uid, $quantity, $price, $total);
            $stmt->execute();
        }
        $_SESSION['cart'] = [];
        header("Location: cart.php?success=1");
        exit;
    }
}

// Order placed success flag
$order_success = isset($_GET['success']);

// Totals
$subtotal = 0;
$totalItems = 0;
foreach ($cart as $item) {
    $qty = $item['quantity'] ?? 1;
    $subtotal += $item['price'] * $qty;
    $totalItems += $qty;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PetMart - Cart</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f2f9f7;
    }

    .main-container {
      display: flex;
      padding: 90px 30px 30px;
      gap: 40px;
      flex-wrap: wrap;
    }

    .cart-section {
      flex: 2;
      min-width: 300px;
    }

    .cart-item {
      display: flex;
      gap: 20px;
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .cart-item img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
    }

    .item-details h3 {
      margin: 5px 0;
      color: #3a5a40;
    }

    .item-details p {
      margin: 4px 0;
      font-size: 14px;
    }

    .qty-form input[type='number'] {
      width: 50px;
      padding: 5px;
      margin-right: 5px;
    }

    .qty-form button {
      padding: 5px 10px;
      border-radius: 5px;
      margin-right: 5px;
      border: 1px solid #ccc;
      cursor: pointer;
    }

    .remove-btn {
      background-color: #ff5555;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 5px;
      margin-top: 10px;
      cursor: pointer;
    }

    .remove-btn:hover {
      background-color: #cc0000;
    }

    .summary-section {
      flex: 1;
      min-width: 250px;
      background: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .bill-summary h2, .bill-summary h3 {
      color: #3a5a40;
    }

    .checkout-btn {
      background-color: #3a5a40;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 15px;
      width: 100%;
    }

    .checkout-btn:hover {
      background-color: #2f4d33;
    }

    .bill-summary label {
      display: block;
      margin-top: 8px;
      font-size: 15px;
    }

    .success {
      background-color: #d4edda;
      padding: 10px;
      color: #155724;
      margin-bottom: 15px;
      border-radius: 5px;
    }

    .cart-section h1 {
      margin-bottom: 20px;
      color: #3a5a40;
    }

    .empty-cart {
      font-size: 18px;
      color: #555;
    }

    .empty-cart a {
      color: #3a5a40;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="main-container">
  <div class="cart-section">
    <h1>🛒 Your Cart</h1>

    <?php if ($order_success): ?>
      <div class="success">🎉 Your order has been placed successfully!</div>
    <?php endif; ?>

    <?php if (count($cart) === 0): ?>
      <p class="empty-cart">Your cart is empty. <a href="product.php">Browse Products</a></p>
    <?php else: ?>
      <?php foreach ($cart as $pid => $item): ?>
        <div class="cart-item">
          <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
          <div class="item-details">
            <h3><?= htmlspecialchars($item['title']) ?></h3>
            <p>Price: ₹<?= $item['price'] ?></p>

            <form class="qty-form" method="post" action="cart.php">
              <input type="hidden" name="pid" value="<?= $pid ?>">
              <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
              <button type="submit" name="update_qty">Update</button>
            </form>

            <p>Total: ₹<?= $item['price'] * $item['quantity'] ?></p>

            <form method="post" action="cart.php">
              <input type="hidden" name="pid" value="<?= $pid ?>">
              <button type="submit" name="remove" class="remove-btn">Remove</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="summary-section">
    <div class="bill-summary">
      <h2>Price Details (<?= $totalItems ?> Items)</h2>
      <p>Subtotal: ₹<?= $subtotal ?></p>
      <hr>
      <h3>Total Amount: ₹<?= $subtotal ?></h3>

      <h2>Payment Options</h2>
      <label><input type="radio" name="payment" checked> 🏦 UPI</label>
      <label><input type="radio" name="payment"> 💳 Card</label>
      <label><input type="radio" name="payment"> 💵 Cash</label>

      <form method="post" action="cart.php">
        <button class="checkout-btn" name="place_order" <?= $uid ? '' : 'disabled' ?>>PLACE ORDER</button>
      </form>

      <?php if (!$uid): ?>
        <p style="color:red; margin-top:10px;">Please <a href="login.php">sign in</a> to place an order.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
<?php ob_end_flush(); ?>
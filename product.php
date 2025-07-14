<?php 
//session_start();
include 'db.php';
include 'navbar.php';

$added_to_cart = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $pid = $_POST['pid'];
    $quantity = max(1, intval($_POST['quantity']));

    $res = $conn->query("SELECT * FROM product WHERE pid = $pid");
    $product = $res->fetch_assoc();

    if ($product) {
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$pid] = [
                'title' => $product['title'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }

    $added_to_cart = true;
}

$result = $conn->query("SELECT * FROM product");
?>

<!DOCTYPE html>
<html>
<head>
  <title>PetMart - Products</title>
  <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f2f9f7;
    margin: 0;
    padding: 0;
  }

  h2.section-title {
    text-align: center;
    font-size: 28px;
    color: #2f4f4f;
    margin-top: 100px;
  }

  .product-section-wrapper {
    display: flex;
    justify-content: center;
    padding: 0 20px 50px;
  }

  .product-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 30px;
    max-width: 1200px;
    width: 100%;
  }

  .product-card {
    background: white;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.2s ease;
  }

  .product-card:hover {
    transform: translateY(-5px);
  }

  .product-card img {
  width: 100%;
  height: 180px;
  object-fit: contain;
  background-color: #f9f9f9;
  border-radius: 10px;
  display: block;
  margin: 0 auto;
}


  .product-card h3 {
    color: #3a5a40;
    font-size: 18px;
    text-align: center;
    margin: 10px 0 5px;
  }

  .product-card p {
    margin: 5px 0;
    font-weight: bold;
    color: #444;
  }

  .product-card form {
    display: flex;
    justify-content: center;
    gap: 8px;
    align-items: center;
    margin-top: 10px;
  }

  .product-card input[type='number'] {
    width: 60px;
    padding: 6px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  .product-card button {
    background-color: #3a5a40;
    color: white;
    border: none;
    padding: 7px 14px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
  }

  .product-card button:hover {
    background-color: #2f4d33;
  }

  .success-message {
    text-align: center;
    background-color: #d4edda;
    color: #155724;
    padding: 10px 20px;
    margin: 20px auto 10px;
    border-radius: 5px;
    width: fit-content;
    font-weight: bold;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }
</style>

</head>
<body>

<?php if ($added_to_cart): ?>
  <div class="success-message">✅ Product added to cart!</div>
<?php endif; ?>

<h2 class="section-title">🐾 Our Products</h2>

<div class="product-section-wrapper">
  <div class="product-container">
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="product-card">
      <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
      <h3><?= htmlspecialchars($row['title']) ?></h3>
      <p>Price: ₹<?= $row['price'] ?></p>
      <form method="post" action="product.php">
        <input type="hidden" name="pid" value="<?= $row['pid'] ?>">
        <input type="number" name="quantity" value="1" min="1">
        <button type="submit" name="add_to_cart">Add to Cart</button>
      </form>
    </div>
  <?php endwhile; ?>
</div>
</div>

</body>
</html>
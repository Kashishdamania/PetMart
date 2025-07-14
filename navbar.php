<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['quantity']) ? $item['quantity'] : 1;
    }
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  html {
    scroll-behavior: smooth;
  }

  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f7fdfc;
  }

  .navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 70px;
    background-color: #C9E4DE;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    z-index: 999;
  }

  .navbar-left {
    font-size: 24px;
    font-weight: bold;
    color: #3a5a40;
  }

  .navbar-center {
    flex: 1;
    margin: 0 20px;
    display: flex;
    align-items: center;
  }

  .search-box {
    width: 100%;
    display: flex;
    background-color: #ffffff;
    border-radius: 30px;
    box-shadow: 0 0 5px rgba(0,0,0,0.05);
    padding: 8px 15px;
  }

  .search-box input {
    border: none;
    outline: none;
    flex: 1;
    padding: 5px 10px;
    font-size: 16px;
    background: transparent;
  }

  .search-box i {
    color: #3a5a40;
    font-size: 18px;
    margin-left: 8px;
    cursor: pointer;
  }

  .navbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 16px;
    flex-wrap: wrap;
  }

  .navbar-right a {
    text-decoration: none;
    color: #3a5a40;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    padding: 5px 10px;
    border-radius: 8px;
  }

  .navbar-right a:hover {
    background-color: #a1cfc1;
    transform: scale(1.05);
  }

  .dropdown {
    position: relative;
  }

  .dropdown-toggle {
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 5px 10px;
    border-radius: 8px;
    color: #3a5a40;
  }

  .dropdown-toggle:hover {
    background-color: #a1cfc1;
    transform: scale(1.05);
  }

  .dropdown-menu {
    position: absolute;
    top: 40px;
    right: 0;
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    min-width: 160px;
    display: none;
    flex-direction: column;
    z-index: 1000;
  }

  .dropdown-menu a {
    padding: 10px 15px;
    text-decoration: none;
    color: #3a5a40;
    white-space: nowrap;
  }

  .dropdown-menu a:hover {
    background-color: #f0f0f0;
  }

  /* Show dropdown on hover for desktop */
  @media (hover: hover) {
    .dropdown:hover .dropdown-menu {
      display: flex;
    }
  }
</style>

<!-- JavaScript for click/tap toggle -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    dropdownToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdownMenu.style.display = dropdownMenu.style.display === 'flex' ? 'none' : 'flex';
    });

    // Close dropdown if clicking outside
    document.addEventListener('click', function () {
      dropdownMenu.style.display = 'none';
    });
  });
</script>

<!-- Navbar -->
<div class="navbar">
  <div class="navbar-left">
    <a href="index.php" style="text-decoration: none; color: #3a5a40;">🐶 PetMart</a>
  </div>

  

  <div class="navbar-right">
    <a href="#about"><i class="fas fa-info-circle"></i> <span>About Us</span></a>
    <a href="product.php"><i class="fas fa-paw"></i> <span>Products</span></a>
    <a href="#contact"><i class="fas fa-envelope"></i> <span>Contact Us</span></a>
    <a href="cart.php"><i class="fas fa-shopping-cart"></i> <span>Cart</span> (<?= $cart_count ?>)</a>

    <?php if (isset($_SESSION["username"])): ?>
      <div class="dropdown">
        <div class="dropdown-toggle">
          <i class="fas fa-user"></i> ..Hi, <?= htmlspecialchars($_SESSION["username"]) ?>
        </div>
        <div class="dropdown-menu">
          <a href="order.php"><i class="fas fa-box"></i> My Orders</a>
          <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    <?php else: ?>
      <a href="login.php"><i class="fas fa-sign-in-alt"></i> <span>Login</span></a>
    <?php endif; ?>
  </div>
</div>

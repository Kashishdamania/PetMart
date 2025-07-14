<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PetMart | Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    #about {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 60px 10%;
      background-color: #fff;
      border-top: 2px solid #C9E4DE;
      border-bottom: 2px solid #C9E4DE;
    }

    #about .text {
      flex: 1;
      padding-right: 40px;
    }

    #about .text h2 {
      font-size: 32px;
      color: #3a5a40;
      margin-bottom: 20px;
    }

    #about .text p {
      font-size: 18px;
      color: #444;
      line-height: 1.6;
    }

    #about img {
      max-width: 400px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    #contact {
      background: #C9E4DE;
      padding: 50px;
      text-align: center;
      color: #3a5a40;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Banner -->
<div style="width: 100%; margin-top: 60px; overflow: hidden;">
  <img src="assets/Bannerimg1.jpeg" alt="Healthy Food for Happy Pets"
       style="width: 100%; height: auto; display: block;">
</div>

<!-- About Section -->
<section id="about">
  <div class="text">
    <h2>About PetMart</h2>
    <p>
      Welcome to PetMart – your trusted destination for premium pet products and love-filled care.
      We offer a wide range of nutritious pet food, fun toys, cozy bedding, and essential accessories
      for your furry friends. Our goal is to keep tails wagging and whiskers twitching with happiness.
    </p>
  </div>
  <img src="assets/aboutimg1.jpeg" alt="About PetMart">
</section>

<!-- Products Section -->
<section id="products">
  <?php include 'product.php'; ?>
</section>

<!-- Contact / Footer -->
<section id="contact">
  <?php include 'footer.php'; ?>
</section>

</body>
</html>

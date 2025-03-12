<?php
session_start();
require_once('connectdb.php');

$basket = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];
$userID = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GTECH50 | CHECKOUT</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/checkout.css">
  <link rel="icon" type="image/png" href="images/Externalmade/favicon.png">
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">
        <img src="../images/gtechlogonew.png" alt="G-Tech 50 Logo" class="logo-image">
    </div>
    <div class="nav-links">
        <a href="../index.html">Home</a>
        <div class="dropdown">
          <a href="#">Categories</a>
          <div class="dropdown-content">
            <a href="categories/Mice.html">Mice</a>
            <a href="categories/Monitors.html">Monitors</a>
            <a href="categories/Headphones.html">Headphones</a>
            <a href="categories/Keyboards.html">Keyboards</a>
            <a href="categories/Mousepads.html">Mousepads</a>
          </div>
        </div>
        <a href="../aboutus.html">About Us</a>
        <a href="../contact.html">Contact Us</a>
        <a href="../membershipSignUp.html">G-TECH50+</a>
    </div>

    <div class="icon-container">
        <div class="search-bar">
          <input type="text" id="searchBar" placeholder="Search...">
          <i class="material-icons" id="searchButton">search</i>
        </div>
        <a href="../profile.html"><i class="material-icons">account_circle</i></a>
        <a href="../basket.html"><i class="material-icons">shopping_basket</i></a>
    </div>
</div>

<h2>CHECKOUT SUMMARY</h2>

<ul id="checkout-summary">
    <?php if (empty($basket)): ?>
        <p>Your basket is empty.</p>
    <?php else: ?>
        <?php foreach ($basket as $itemName => $details): ?>
            <li>
                Item: <strong><?= htmlspecialchars($itemName) ?></strong> - 
                Price: £<?= number_format($details['price'], 2) ?> - 
                Quantity: <?= $details['quantity'] ?> - 
                Total: £<?= number_format($details['price'] * $details['quantity'], 2) ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

<!-- Order Button -->
<button onclick="placeOrder()">Confirm Order</button>

<script>
    function placeOrder() {
        fetch('placeOrder.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=place_order'
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            window.location.href = '../orderConfirmation.html';
        });
    }
</script>

</body>
</html>

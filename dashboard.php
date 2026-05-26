<?php
session_start();

// Check if product manager is logged in
if (!isset($_SESSION['product manager'])) {
    header("Location: login.php");
}
?>

<!-- Header section -->
<header>

    <!-- External CSS file -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Logo image -->
    <img src="images/images/AYS logo.png" width="80"><br>

    <!-- Site title -->
    <h2>AYS Clothing Store</h2>

</header>

<!-- Main container -->
<div class="container">

    <!-- Navigation links -->
    <a href="add_product.php">Add Product</a><br><br>
    <a href="list_products.php">View Products</a><br><br>
    <a href="logout.php">Logout</a>

</div>
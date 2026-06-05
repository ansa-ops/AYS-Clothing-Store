<?php
session_start();

// Presentation Layer
// This page displays customer membership levels
// and the discount available for each membership type.

// Customer must be logged in to access this page.
if (!isset($_SESSION["CustomerID"])) {

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Membership - AYS Clothing</title>

    <link rel="stylesheet" href="style.css">

</head>

<body class="customer-bg">

<header class="navbar">

    <div class="brand">

        <img src="images/AYS logo.png" alt="AYS Logo">

        <span>AYS Clothing</span>

    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="products.php">Products</a>

        <a href="logout.php">Logout</a>

    </div>

</header>

<div class="container">

    <h1>Customer Membership</h1>

    <p>
        Loyalty discount system for AYS Clothing customers.
    </p>

    <br>

    <a class="btn btn-light" href="menu.php">
        Back
    </a>

    <h2>Membership Discounts</h2>

    <!-- Membership Cards -->

    <div class="dashboard-cards">

        <!-- Bronze Membership -->

        <div class="stat-card">

            <h3>Bronze</h3>

            <p>5%</p>

        </div>

        <!-- Silver Membership -->

        <div class="stat-card">

            <h3>Silver</h3>

            <p>10%</p>

        </div>

        <!-- Gold Membership -->

        <div class="stat-card">

            <h3>Gold</h3>

            <p>15%</p>

        </div>

    </div>

    <p class="footer-note">

        During checkout, customer membership discounts
        are automatically applied to the final order total.

    </p>

</div>

</body>

</html>
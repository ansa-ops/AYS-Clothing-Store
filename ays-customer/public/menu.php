<?php
session_start();

// Presentation Layer
// This page works as the main menu for logged in customers.
// Customers can access products, cart and customer manager login.

// Customer must be logged in first.
if (!isset($_SESSION["CustomerID"])) {

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>My Account - AYS Clothing</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<header class="navbar">

    <div class="brand">

        <img src="images/AYS logo.png" alt="AYS Logo">

        <span>AYS Clothing</span>

    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="products.php">Products</a>

        <a href="cart.php">Cart</a>

        <a href="logout.php">Logout</a>

        

    </div>

</header>

<div class="container form-box">

    <h1>My Account</h1>

    <p>
        Welcome to your AYS Clothing account dashboard.
    </p>

    <br>

    <!-- Customer Information -->

    <div class="summary-box">

        <p>
            <strong>Customer Name:</strong>
            <?= htmlspecialchars($_SESSION["FullName"] ?? "Customer") ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?= htmlspecialchars($_SESSION["Email"] ?? "Not Available") ?>
        </p>

        <p>
            <strong>Membership:</strong>
            <?= htmlspecialchars($_SESSION["MembershipType"] ?? "Bronze") ?>
        </p>

        <p>
            <strong>Discount Rate:</strong>
            <?= htmlspecialchars($_SESSION["DiscountRate"] ?? 5) ?>%
        </p>
       

    </div>

    <br>

    <!-- Edit Profile Button -->

    <a
        href="edit_profile.php"
        class="btn btn-success">

        Edit Profile

    </a>

    <br><br>

    <!-- Customer Manager Access -->

    <h2>Customer Manager Access</h2>

    <p>
        Customer manager access is required
        to manage customer records.
    </p>

    <br>

    <a
        href="customer_manager_login.php"
        class="btn btn-success">

        Customer Manager Login

    </a>

</div>

</body>

</html>
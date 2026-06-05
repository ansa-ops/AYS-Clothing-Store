<?php
session_start();

// Presentation Layer
// This page displays order records.
// It shows how the order component connects with customer membership discounts.

// Customer must be logged in to view orders.
if (!isset($_SESSION["CustomerID"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/Database.php";

// Create database connection.
$db = new Database();
$conn = $db->connect();

// Get order records with customer names.
$stmt = $conn->query("
    SELECT 
        o.OrderID,
        c.FullName,
        o.OrderDate,
        o.PaymentMethod,
        o.Status,
        o.SubTotal,
        o.DiscountRate,
        o.FinalTotal
    FROM Orders o
    LEFT JOIN Customer c
        ON o.CustomerID = c.CustomerID
    ORDER BY o.OrderID DESC
");

$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Order Management - AYS Clothing</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="customer-bg">

<header class="navbar">
    <div class="brand">
        <img src="images/AYS logo.png" alt="AYS Logo">
        <span>AYS Clothing</span>
    </div>

    <div class="nav-links">
        <a href="menu.php">My Account</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</header>

<div class="container">

    <h1>Order Management</h1>

    <p>
        This page shows customer orders and how membership
        discounts are applied during checkout.
    </p>

    <br>

    <a class="btn btn-light" href="menu.php">
        Back to Menu
    </a>

    <a class="btn" href="products.php">
        Shop Products
    </a>

    <h2>Order List</h2>

    <table>

        <tr>
            <th>No.</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Subtotal</th>
            <th>Discount</th>
            <th>Final Total</th>
        </tr>

        <?php if (count($orders) == 0): ?>

            <tr>
                <td colspan="8">
                    No orders placed yet. Place an order from the checkout page.
                </td>
            </tr>

        <?php endif; ?>

        <?php $number = 1; ?>

        <?php foreach ($orders as $order): ?>

            <tr>
                <td><?= $number++ ?></td>

                <td>
                    <?= htmlspecialchars($order["FullName"] ?? "Checkout Customer") ?>
                </td>

                <td>
                    <?= htmlspecialchars($order["OrderDate"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($order["PaymentMethod"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($order["Status"]) ?>
                </td>

                <td>
                    £<?= number_format($order["SubTotal"], 2) ?>
                </td>

                <td>
                    <?= htmlspecialchars($order["DiscountRate"]) ?>%
                </td>

                <td>
                    £<?= number_format($order["FinalTotal"], 2) ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

    <p class="footer-note">
        This page demonstrates order integration with the
        customer membership discount system.
    </p>

</div>

</body>

</html>
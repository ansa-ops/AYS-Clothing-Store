<?php
session_start();

// Presentation Layer
// This page is used by the customer manager to log in.
// It protects the customer management pages from normal public users.

$error = "";

// This runs when the login form is submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get username and password from the form.
    $managerUsername = trim($_POST["username"] ?? "");
    $managerPassword = trim($_POST["password"] ?? "");

    // Simple customer manager login for project demonstration.
    // In a larger system, this would normally be stored in the database.
    if ($managerUsername == "manager" && $managerPassword == "manager123") {

        $_SESSION["IsCustomerManager"] = true;

        header("Location: customers.php");
        exit;

    } else {

        $error = "Invalid customer manager username or password.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Customer Manager Login - AYS Clothing</title>
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
    </div>

</header>

<div class="container form-box">

    <h1>Customer Manager Login</h1>

    <p>
        Customer manager access is required to manage customer records.
    </p>

    <?php if ($error != ""): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <label>Username</label>

        <input
            type="text"
            name="username"
            placeholder="Enter manager username"
            autocomplete="off"
            required>

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter manager password"
            autocomplete="new-password"
            required>

        <br><br>

        <button type="submit" class="btn">
            Customer Manager Access
        </button>

    </form>

    <br>

    <p>
        <strong>Demo Login:</strong>
        manager / manager123
    </p>

</div>

</body>

</html>
<?php
session_start();

// Presentation Layer
// This page allows customers to log in.
// After successful login, customer details and membership discount
// are stored in the session.

require_once "../config/Database.php";
require_once "../classes/Customer.php";

$error = "";

// This code runs when the login form is submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get login details from the form.
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Create database connection.
    $db = new Database();
    $conn = $db->connect();

    // Create customer object.
    $customerObj = new Customer($conn);

    // Check customer login using the middle layer.
    $customer = $customerObj->login($email, $password);

    if ($customer) {

        // Store important customer details in the session.
        $_SESSION["CustomerID"] = $customer["CustomerID"];
        $_SESSION["FullName"] = $customer["FullName"];
        $_SESSION["Email"] = $customer["Email"];
        $_SESSION["MembershipType"] = $customer["MembershipType"] ?? "Bronze";
        $_SESSION["DiscountRate"] = $customer["DiscountRate"] ?? 5;

        // After login, take customer to My Account page.
        header("Location: index.php");
        exit;

    } else {

        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Customer Login - AYS Clothing</title>
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
        <a href="register.php">Register</a>
    </div>

</header>

<div class="container">

    <div class="form-box">

        <h1>Customer Login</h1>

        <p>
            Log in to view membership discounts and continue shopping.
        </p>

        <br>

        <!-- Error message -->
        <?php if ($error != ""): ?>

            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" autocomplete="off">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                autocomplete="off"
                required>

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                autocomplete="new-password"
                required>

            <br><br>

            <button type="submit" class="btn">
                Login
            </button>

            <a href="register.php" class="btn btn-light">
                Register
            </a>

        </form>

        <br>

        <!-- Forgotten password link -->
        <a href="forgot_password.php" class="btn btn-light">
            Forgotten Password?
        </a>

    </div>

</div>

</body>

</html>
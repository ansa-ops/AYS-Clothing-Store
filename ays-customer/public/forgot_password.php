<?php
session_start();

require_once "../config/Database.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");

    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT * FROM Customer WHERE Email = ?");
    $stmt->execute([$email]);

    $customer = $stmt->fetch();

    if ($customer) {

        $code = rand(100000, 999999);

        $_SESSION["reset_email"] = $email;
        $_SESSION["reset_code"] = $code;

        $message = "Your password reset code is: " . $code;

    } else {
        $error = "Email address not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password - AYS Clothing</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="navbar">
    <div class="brand">
        <img src="images/AYS logo.png" alt="AYS Logo">
        <span>AYS Clothing</span>
    </div>

    <div class="nav-links">
        <a href="login.php">Login</a>
    </div>
</header>

<div class="container form-box">

    <h1>Forgot Password</h1>

    <p>Enter your email address to receive a reset code.</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="success"><?= htmlspecialchars($message) ?></div>

        <a href="reset_password.php" class="btn btn-success">
            Continue to Reset Password
        </a>
    <?php endif; ?>

    <form method="POST">

        <label>Email Address</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your registered email"
            required>

        <br><br>

        <button type="submit">
            Send Reset Code
        </button>

    </form>

</div>

</body>

</html>
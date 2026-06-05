<?php
session_start();

/*
|--------------------------------------------------------------------------
| RESET PASSWORD PAGE
|--------------------------------------------------------------------------
| This page allows customers to reset their password
| using the reset code sent during the forgot password process.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| REQUIRED FILES
|--------------------------------------------------------------------------
| Database connection file.
|--------------------------------------------------------------------------
*/

require_once "../config/Database.php";

// Arrays for validation messages.
$errors = [];
$success = "";

/*
|--------------------------------------------------------------------------
| SECURITY CHECK
|--------------------------------------------------------------------------
| If reset email or reset code session does not exist,
| redirect user back to forgot password page.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["reset_email"]) || !isset($_SESSION["reset_code"])) {

    header("Location: forgot_password.php");

    exit;
}

/*
|--------------------------------------------------------------------------
| HANDLE FORM SUBMISSION
|--------------------------------------------------------------------------
| This runs when the reset password form is submitted.
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values safely.
    $code = trim($_POST["code"] ?? "");

    $newPassword = $_POST["newPassword"] ?? "";

    $confirmPassword = $_POST["confirmPassword"] ?? "";

    /*
    |--------------------------------------------------------------------------
    | VALIDATE RESET CODE
    |--------------------------------------------------------------------------
    */

    if ($code == "") {

        $errors[] = "Reset code is required.";
    }

    if ($code != $_SESSION["reset_code"]) {

        $errors[] = "Invalid reset code.";
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    if ($newPassword == "") {

        $errors[] = "New password is required.";

    } elseif (strlen($newPassword) < 6) {

        $errors[] = "Password must be at least 6 characters.";
    }

    // Confirm password validation.
    if ($newPassword !== $confirmPassword) {

        $errors[] = "Passwords do not match.";
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        // Create database connection.
        $db = new Database();

        $conn = $db->connect();

        // Encrypt password securely.
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update customer password.
        $stmt = $conn->prepare("
            UPDATE Customer
            SET Password = ?
            WHERE Email = ?
        ");

        $stmt->execute([
            $hashedPassword,
            $_SESSION["reset_email"]
        ]);

        // Remove reset sessions after successful reset.
        unset($_SESSION["reset_email"]);

        unset($_SESSION["reset_code"]);

        // Success message.
        $success = "Password reset successfully. You can now log in.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Reset Password - AYS Clothing</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- =========================================================
     NAVIGATION BAR
========================================================= -->

<header class="navbar">

    <div class="brand">

        <img src="images/AYS logo.png" alt="AYS Logo">

        <span>AYS Clothing</span>

    </div>

    <div class="nav-links">

        <a href="login.php">
            Login
        </a>

    </div>

</header>

<!-- =========================================================
     RESET PASSWORD FORM
========================================================= -->

<div class="container form-box">

    <h1>Reset Password</h1>

    <p>
        Enter the reset code and create a new password.
    </p>

    <!-- Show validation errors -->

    <?php foreach ($errors as $error): ?>

        <div class="error">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endforeach; ?>

    <!-- Show success message -->

    <?php if ($success): ?>

        <div class="success">

            <?= htmlspecialchars($success) ?>

        </div>

        <!-- Login button after successful reset -->

        <a href="login.php" class="btn btn-success">

            Go to Login

        </a>

    <?php endif; ?>

    <!-- Show form only if password reset is not complete -->

    <?php if (!$success): ?>

        <form method="POST">

            <!-- Reset Code -->

            <label>Reset Code</label>

            <input
                type="text"
                name="code"
                required>

            <!-- New Password -->

            <label>New Password</label>

            <input
                type="password"
                name="newPassword"
                required>

            <!-- Confirm Password -->

            <label>Confirm Password</label>

            <input
                type="password"
                name="confirmPassword"
                required>

            <br><br>

            <!-- Submit Button -->

            <button type="submit">

                Reset Password

            </button>

        </form>

    <?php endif; ?>

</div>

</body>

</html>
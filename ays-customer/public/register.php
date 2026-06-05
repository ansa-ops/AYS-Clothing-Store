<?php
session_start();

/*
|--------------------------------------------------------------------------
| REGISTER PAGE
|--------------------------------------------------------------------------
| This page allows new customers to create an account.
| Customer details are validated before being saved into the database.
| Membership discounts are also selected during registration.
|--------------------------------------------------------------------------
*/

require_once "../config/Database.php";
require_once "../classes/Customer.php";
require_once "../classes/CustomerValidator.php";

$db = new Database();
$conn = $db->connect();

$customerObj = new Customer($conn);

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName = trim($_POST["FullName"] ?? "");
    $email = trim($_POST["Email"] ?? "");
    $password = $_POST["Password"] ?? "";
    $phone = trim($_POST["PhoneNumber"] ?? "");
    $membershipType = $_POST["MembershipType"] ?? "Bronze";

    $errors = CustomerValidator::validateCustomer(
        $fullName,
        $email,
        $phone
    );

    $errors = array_merge(
        $errors,
        CustomerValidator::validatePassword($password)
    );

    if (empty($errors)) {

        try {

            $customerObj->addCustomer(
                $fullName,
                $email,
                $password,
                $phone,
                1,
                $membershipType
            );

            $success =
                "Registration successful. Your membership is " .
                $membershipType .
                ". You can now log in.";

        } catch (PDOException $e) {

            $errors[] =
                "This email address is already registered.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register - AYS Clothing</title>
    <link rel="stylesheet" href="style.css?v=99">
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
        <a href="login.php">Login</a>
    </div>

</header>

<div class="membership-hero">

    <h1>Become a Member</h1>

    <p class="membership-text">
        Bronze 5% | Silver 10% | Gold 15%
    </p>

    <div class="membership-rules">

        <div class="rule-card">
            <h3>Bronze Member</h3>
            <p>
                Register a customer account and receive
                5% discount on all products.
            </p>
        </div>

        <div class="rule-card">
            <h3>Silver Member</h3>
            <p>
                Spend over £150 total orders to upgrade
                and receive 10% discount.
            </p>
        </div>

        <div class="rule-card">
            <h3>Gold Member</h3>
            <p>
                Spend over £300 total orders to unlock
                premium membership and receive 15% discount.
            </p>
        </div>

    </div>

</div>

<div class="container form-box <?= $success ? 'popup-active' : '' ?>">

    <h2>Customer Sign Up</h2>

    <?php foreach ($errors as $error): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <div class="success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <label>Full Name</label>
        <input
            type="text"
            name="FullName"
            placeholder="Enter full name"
            value="<?= htmlspecialchars($_POST["FullName"] ?? "") ?>"
            autocomplete="off"
            required>

        <label>Email</label>
        <input
            type="email"
            name="Email"
            placeholder="Enter email"
            value="<?= htmlspecialchars($_POST["Email"] ?? "") ?>"
            autocomplete="off"
            required>

        <label>Password</label>
        <input
            type="password"
            name="Password"
            placeholder="Enter password"
            autocomplete="new-password"
            required>

        <label>Phone Number</label>
        <input
            type="text"
            name="PhoneNumber"
            id="phone"
            placeholder="Enter 8 digit phone number"
            value="<?= htmlspecialchars($_POST["PhoneNumber"] ?? "") ?>"
            autocomplete="off"
            onkeyup="validatePhone()"
            required>

        <p id="phoneError" style="color:red; margin-top:6px; font-size:14px;"></p>

        <label>Membership Type</label>
        <select name="MembershipType" required>
            <option value="Bronze">Bronze - 5% Discount</option>
            <option value="Silver">Silver - 10% Discount</option>
            <option value="Gold">Gold - 15% Discount</option>
        </select>

        <br><br>

        <button type="submit" class="btn btn-success">
            Register
        </button>

        <a href="login.php" class="btn">
            Login
        </a>

    </form>

</div>

<script>
function validatePhone(){

    let phone = document.getElementById("phone").value;
    let error = document.getElementById("phoneError");

    if(phone === ""){
        error.innerHTML = "";
    }
    else if(!/^[0-9]+$/.test(phone)){
        error.innerHTML = "Phone number must contain numbers only.";
    }
    else if(phone.length !== 8){
        error.innerHTML = "Phone number must be exactly 8 digits.";
    }
    else{
        error.innerHTML = "";
    }
}

document.addEventListener("click", function() {

    const successBox = document.querySelector(".success");
    const formBox = document.querySelector(".form-box");

    if(successBox){

        successBox.style.display = "none";
        formBox.classList.remove("popup-active");
    }

});
</script>

</body>
</html>
<?php
session_start();

/*
|--------------------------------------------------------------------------
| EDIT CUSTOMER PROFILE PAGE
|--------------------------------------------------------------------------
| This page allows a logged-in customer to update their own
| name, email and phone number.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["CustomerID"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/Database.php";
require_once "../classes/Customer.php";
require_once "../classes/CustomerValidator.php";

$db = new Database();
$conn = $db->connect();

$customerObj = new Customer($conn);

$errors = [];
$success = "";

$customerID = (int)$_SESSION["CustomerID"];

$customer = $customerObj->getCustomerByID($customerID);

if (!$customer) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName = trim($_POST["FullName"] ?? "");
    $email = trim($_POST["Email"] ?? "");
    $phone = trim($_POST["PhoneNumber"] ?? "");

    $errors = CustomerValidator::validateCustomer(
        $fullName,
        $email,
        $phone
    );

    if (empty($errors)) {

        try {

            $customerObj->updateCustomer(
                $customerID,
                $fullName,
                $email,
                $phone,
                1
            );

            $_SESSION["FullName"] = $fullName;
            $_SESSION["Email"] = $email;

            $success = "Profile updated successfully.";

            $customer = $customerObj->getCustomerByID($customerID);

        } catch (PDOException $e) {

            $errors[] = "Profile could not be updated. Email may already exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile - AYS Clothing</title>
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
        <a href="logout.php">Logout</a>
    </div>

</header>

<div class="container form-box">

    <h1>Edit Profile</h1>

    <p>
        Update your personal account details below.
    </p>

    <br>

    <a class="btn btn-light" href="menu.php">
        Back
    </a>

    <br><br>

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
            value="<?= htmlspecialchars($customer["FullName"]) ?>"
            required>

        <label>Email</label>
        <input
            type="email"
            name="Email"
            value="<?= htmlspecialchars($customer["Email"]) ?>"
            required>

        <label>Phone Number</label>
        <input
            type="text"
            name="PhoneNumber"
            id="phone"
            value="<?= htmlspecialchars($customer["PhoneNumber"]) ?>"
            onkeyup="validatePhone()"
            required>

        <p id="phoneError" style="color:red; margin-top:6px; font-size:14px;"></p>

        <br>

        <button type="submit" class="btn btn-success">
            Update Profile
        </button>

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
</script>

</body>
</html>
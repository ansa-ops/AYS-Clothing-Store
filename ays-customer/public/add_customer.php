<?php
session_start();

/*
|--------------------------------------------------------------------------
| ADD CUSTOMER PAGE
|--------------------------------------------------------------------------
| This page allows the customer manager to manually add
| new customer records into the system.
|--------------------------------------------------------------------------
*/

// Only the customer manager can access this page.
if (!isset($_SESSION["IsCustomerManager"]) || $_SESSION["IsCustomerManager"] !== true) {
    header("Location: customer_manager_login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| REQUIRED FILES
|--------------------------------------------------------------------------
| Database connection, customer class and validation class.
|--------------------------------------------------------------------------
*/

require_once "../config/Database.php";
require_once "../classes/Customer.php";
require_once "../classes/CustomerValidator.php";

// Create database connection.
$db = new Database();
$conn = $db->connect();

// Create customer object from the middle layer.
$customerObj = new Customer($conn);

// Arrays used for validation messages.
$errors = [];
$success = "";

/*
|--------------------------------------------------------------------------
| HANDLE FORM SUBMISSION
|--------------------------------------------------------------------------
| This runs when the Add Customer form is submitted.
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect customer form data safely.
    $name = trim($_POST["FullName"] ?? "");
    $email = trim($_POST["Email"] ?? "");
    $password = $_POST["Password"] ?? "";
    $phone = trim($_POST["PhoneNumber"] ?? "");
    $active = isset($_POST["IsActive"]) ? 1 : 0;
    $membershipType = $_POST["MembershipType"] ?? "Bronze";

    /*
    |--------------------------------------------------------------------------
    | VALIDATE CUSTOMER INPUT
    |--------------------------------------------------------------------------
    */

    $errors = CustomerValidator::validateCustomer(
        $name,
        $email,
        $phone
    );

    // Validate password separately.
    $errors = array_merge(
        $errors,
        CustomerValidator::validatePassword($password)
    );

    /*
    |--------------------------------------------------------------------------
    | SAVE CUSTOMER TO DATABASE
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        try {

            // Add customer record using middle layer.
            $customerObj->addCustomer(
                $name,
                $email,
                $password,
                $phone,
                $active,
                $membershipType
            );

            // Success message.
            $success = "Customer added successfully.";

        } catch (PDOException $e) {

            // Show error if email already exists.
            $errors[] = "Customer could not be added. Email may already exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Customer - AYS Clothing</title>

    <link rel="stylesheet" href="style.css?v=99">

</head>

<body class="customer-bg">

<!-- =========================================================
     NAVIGATION BAR
========================================================= -->

<header class="navbar">

    <div class="brand">

        <img src="images/AYS logo.png" alt="AYS Logo">

        <span>AYS Clothing</span>

    </div>

    <div class="nav-links">

        <a href="customers.php">
            Customer Management
        </a>

        <a href="index.php">
            Home
        </a>

        <a href="customer_manager_logout.php">
            Logout
        </a>

    </div>

</header>

<!-- =========================================================
     ADD CUSTOMER FORM
========================================================= -->

<div class="container form-box">

    <h1>Add Customer</h1>

    <p>
        Use this form to manually create a new customer record.
    </p>

    <br>

    <a class="btn btn-light" href="customers.php">
        Back
    </a>

    <br><br>

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

    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <!-- Full Name -->

        <label>Full Name</label>

        <input
            name="FullName"
            placeholder="Enter full name"
            value="<?= htmlspecialchars($_POST["FullName"] ?? "") ?>"
            required>

        <!-- Email -->

        <label>Email</label>

        <input
            type="email"
            name="Email"
            placeholder="Enter email address"
            value="<?= htmlspecialchars($_POST["Email"] ?? "") ?>"
            required>

        <!-- Password -->

        <label>Password</label>

        <input
            type="password"
            name="Password"
            placeholder="Enter password"
            autocomplete="new-password"
            required>

        <!-- Phone Number -->

        <label>Phone Number</label>

        <input
            type="text"
            name="PhoneNumber"
            id="phone"
            placeholder="Enter 8 digit phone number"
            value="<?= htmlspecialchars($_POST["PhoneNumber"] ?? "") ?>"
            onkeyup="validatePhone()"
            required>

        <!-- Real-time phone validation message -->

        <p id="phoneError" style="color:red; margin-top:6px; font-size:14px;"></p>

        <!-- Membership Selection -->

        <label>Membership</label>

        <select name="MembershipType">

            <option value="Bronze">
                Bronze - 5% Discount
            </option>

            <option value="Silver">
                Silver - 10% Discount
            </option>

            <option value="Gold">
                Gold - 15% Discount
            </option>

        </select>

        <!-- Active Customer Checkbox -->

        <label style="margin-top:20px;">

            <input
                type="checkbox"
                name="IsActive"
                checked
                style="width:auto; margin-right:10px;">

            Active Customer

        </label>

        <br><br>

        <!-- Submit Button -->

        <button type="submit" class="btn btn-success">
            Add Customer
        </button>

    </form>

</div>

<!-- =========================================================
     REAL-TIME PHONE VALIDATION SCRIPT
========================================================= -->

<script>

function validatePhone(){

    let phone = document.getElementById("phone").value;

    let error = document.getElementById("phoneError");

    // Empty field.
    if(phone === ""){

        error.innerHTML = "";
    }

    // Non-number validation.
    else if(!/^[0-9]+$/.test(phone)){

        error.innerHTML =
            "Phone number must contain numbers only.";
    }

    // Length validation.
    else if(phone.length !== 8){

        error.innerHTML =
            "Phone number must be exactly 8 digits.";
    }

    // Valid phone number.
    else{

        error.innerHTML = "";
    }
}

</script>

</body>

</html>
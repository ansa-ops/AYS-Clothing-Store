<?php
session_start();

// Presentation Layer
// This page allows the customer manager to edit an existing customer record.
// The manager can update customer details, membership, points and active status.

// Only customer managers can access this page.
if (
    !isset($_SESSION["IsCustomerManager"]) ||
    $_SESSION["IsCustomerManager"] !== true
) {
    header("Location: customer_manager_login.php");
    exit;
}

// Required files.
require_once "../config/Database.php";
require_once "../classes/Customer.php";
require_once "../classes/CustomerValidator.php";

// Create database connection.
$db = new Database();
$conn = $db->connect();

// Create customer object.
$customerObj = new Customer($conn);

// Get customer ID from the URL.
$customerID = (int)($_GET["id"] ?? 0);

// Get selected customer details from the database.
$customer = $customerObj->getCustomerByID($customerID);

// If customer does not exist, stop the page.
if (!$customer) {
    die("Customer not found.");
}

$errors = [];
$success = "";

// This runs when the update form is submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect updated form values.
    $name = trim($_POST["FullName"] ?? "");
    $email = trim($_POST["Email"] ?? "");
    $phone = trim($_POST["PhoneNumber"] ?? "");
    $active = isset($_POST["IsActive"]) ? 1 : 0;
    $membershipType = $_POST["MembershipType"] ?? "Bronze";
    $points = (int)($_POST["Points"] ?? 0);

    // Validate customer input before updating.
    $errors = CustomerValidator::validateCustomer(
        $name,
        $email,
        $phone
    );

    // If validation passes, update customer and membership details.
    if (empty($errors)) {

        try {

            $customerObj->updateCustomer(
                $customerID,
                $name,
                $email,
                $phone,
                $active
            );

            $customerObj->updateMembership(
                $customerID,
                $membershipType,
                $points
            );

            $success = "Customer updated successfully.";

            // Reload the updated customer details.
            $customer = $customerObj->getCustomerByID($customerID);

        } catch (PDOException $e) {

            $errors[] = "Customer could not be updated.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Customer - AYS Clothing</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="customer-bg">

<header class="navbar">

    <div class="brand">
        <img src="images/AYS logo.png" alt="AYS Logo">
        <span>AYS Clothing</span>
    </div>

    <div class="nav-links">
        <a href="customers.php">Customers</a>
        <a href="products.php">Products</a>
        <a href="customer_manager_logout.php">Logout</a>
    </div>

</header>

<div class="container form-box">

    <h1>Edit Customer</h1>

    <p>
        Update the customer information below.
    </p>

    <br>

    <a class="btn btn-light" href="customers.php">
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
            name="FullName"
            value="<?= htmlspecialchars($customer["FullName"]) ?>"
            required>

        <label>Email</label>

        <input
            type="email"
            name="Email"
            value="<?= htmlspecialchars($customer["Email"]) ?>"
            autocomplete="off"
            required>

        <label>Phone Number</label>

        <input
            name="PhoneNumber"
            value="<?= htmlspecialchars($customer["PhoneNumber"]) ?>"
            required>

        <label>Membership Type</label>

        <select name="MembershipType">

            <?php foreach (["Bronze", "Silver", "Gold"] as $type): ?>

                <option
                    value="<?= $type ?>"
                    <?= ($customer["MembershipType"] ?? "Bronze") == $type ? "selected" : "" ?>>

                    <?= $type ?>

                </option>

            <?php endforeach; ?>

        </select>

        <label>Points</label>

        <input
            type="number"
            name="Points"
            value="<?= htmlspecialchars($customer["Points"] ?? 0) ?>">

        <label style="margin-top:20px; display:block;">

            <input
                type="checkbox"
                name="IsActive"
                <?= $customer["IsActive"] ? "checked" : "" ?>
                style="width:auto; margin-right:10px;">

            Active Customer

        </label>

        <br>

        <button type="submit" class="btn btn-success">
            Update Customer
        </button>

    </form>

</div>

</body>

</html>
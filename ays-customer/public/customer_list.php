<?php
session_start();

/*
|--------------------------------------------------------------------------
| CUSTOMER LIST PAGE
|--------------------------------------------------------------------------
| This page displays all registered customer records.
| Only the customer manager can access this page.
|--------------------------------------------------------------------------
*/

// Only customer manager can access.
if (
    !isset($_SESSION["IsCustomerManager"]) ||
    $_SESSION["IsCustomerManager"] !== true
) {
    header("Location: customer_manager_login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| REQUIRED FILES
|--------------------------------------------------------------------------
| Database connection and customer class.
|--------------------------------------------------------------------------
*/

require_once "../config/Database.php";
require_once "../classes/Customer.php";

// Create database connection.
$db = new Database();
$conn = $db->connect();

// Create customer object from middle layer.
$customerObj = new Customer($conn);

// Get all customers from database.
$customers = $customerObj->getAllCustomers();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Customer List - AYS Clothing</title>

    <link rel="stylesheet" href="style.css">

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

        <a href="products.php">
            Products
        </a>

        <a href="customer_manager_logout.php">
            Logout
        </a>

    </div>

</header>

<!-- =========================================================
     CUSTOMER LIST SECTION
========================================================= -->

<div class="customer-page">

    <div class="customer-panel">

        <h1>Customer List</h1>

        <p>
            This page displays all registered customers.
        </p>

        <br>

        <!-- Back button -->

        <a class="btn btn-light" href="customers.php">
            Back
        </a>

        <!-- Customer table -->

        <table class="customer-table">

            <tr>

                <th>No.</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Membership</th>
                <th>Discount</th>
                <th>Actions</th>

            </tr>

            <!-- Show message if no customers exist -->

            <?php if(count($customers) === 0): ?>

                <tr>

                    <td colspan="8">

                        No customer records found.

                    </td>

                </tr>

            <?php endif; ?>

            <!-- Customer numbering -->

            <?php $number = 1; ?>

            <!-- Loop through all customers -->

            <?php foreach($customers as $customer): ?>

                <tr>

                    <!-- Customer Number -->

                    <td>
                        <?= $number++ ?>
                    </td>

                    <!-- Full Name -->

                    <td>
                        <?= htmlspecialchars($customer["FullName"]) ?>
                    </td>

                    <!-- Email -->

                    <td>
                        <?= htmlspecialchars($customer["Email"]) ?>
                    </td>

                    <!-- Phone Number -->

                    <td>
                        <?= htmlspecialchars($customer["PhoneNumber"]) ?>
                    </td>

                    <!-- Active / Inactive Status -->

                    <td>

                        <?php if($customer["IsActive"]): ?>

                            <span class="badge-active">
                                Active
                            </span>

                        <?php else: ?>

                            <span class="badge-inactive">
                                Inactive
                            </span>

                        <?php endif; ?>

                    </td>

                    <!-- Membership Type -->

                    <td>
                        <?= htmlspecialchars($customer["MembershipType"]) ?>
                    </td>

                    <!-- Discount Percentage -->

                    <td>
                        <?= htmlspecialchars($customer["DiscountRate"]) ?>%
                    </td>

                    <!-- Action Buttons -->

                    <td>

                        <div class="table-actions">

                            <!-- Edit Customer -->

                            <a
                                class="edit-btn"
                                href="edit_customer.php?id=<?= $customer["CustomerID"] ?>">

                                ✏ Edit

                            </a>

                            <!-- Delete Customer -->

                            <a
                                class="delete-btn"
                                href="delete_customer.php?id=<?= $customer["CustomerID"] ?>"
                                onclick="return confirm('Confirm delete?');">

                                🗑 Delete

                            </a>

                        </div>

                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    </div>

</div>

</body>

</html>
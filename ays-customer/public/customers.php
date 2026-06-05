<?php
session_start();

/*
|--------------------------------------------------------------------------
| CUSTOMER MANAGEMENT DASHBOARD
|--------------------------------------------------------------------------
| This page allows the customer manager to:
| Add, List, Find, Filter, Edit and Delete customers.
|--------------------------------------------------------------------------
*/

// Only customer manager can access this page.
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

/*
|--------------------------------------------------------------------------
| CUSTOMER DASHBOARD STATISTICS
|--------------------------------------------------------------------------
| Get all customers and calculate dashboard totals.
|--------------------------------------------------------------------------
*/

// Get all customers.
$allCustomers = $customerObj->getAllCustomers();

// Total customers.
$totalCustomers = count($allCustomers);

// Count active customers.
$activeCustomers = count(
    array_filter($allCustomers, fn($c) => $c["IsActive"])
);

// Count inactive customers.
$inactiveCustomers = $totalCustomers - $activeCustomers;

$searched = false;
$pageTitle = "Customer Management";

/*
|--------------------------------------------------------------------------
| FIND CUSTOMER
|--------------------------------------------------------------------------
| Search customer by ID, name, email or phone.
|--------------------------------------------------------------------------
*/

if (isset($_GET["search"]) && trim($_GET["search"]) !== "") {

    $searched = true;

    $customers = $customerObj->findCustomer(
        trim($_GET["search"])
    );

    $pageTitle = "Search Results";

/*
|--------------------------------------------------------------------------
| FILTER CUSTOMERS
|--------------------------------------------------------------------------
| Filter customers by active, inactive or membership type.
|--------------------------------------------------------------------------
*/

} elseif (isset($_GET["filter"]) && $_GET["filter"] !== "") {

    $filter = $_GET["filter"];

    if ($filter === "1" || $filter === "0") {

        $customers = $customerObj->filterCustomers((int)$filter);

        $pageTitle =
            $filter == "1"
            ? "Active Customers"
            : "Inactive Customers";

    } else {

        $customers = array_filter(
            $allCustomers,
            fn($c) => ($c["MembershipType"] ?? "Bronze") === $filter
        );

        $pageTitle = $filter . " Members";
    }

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
| Show empty customer results until search/filter is used.
|--------------------------------------------------------------------------
*/

} else {

    $customers = [];
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Customer Management - AYS Clothing</title>

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

        <a href="index.php">
            Home
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
     CUSTOMER MANAGEMENT PAGE
========================================================= -->

<div class="customer-page">

    <div class="customer-panel">

        <h1>Customer Management</h1>

        <p>
            Manage customer records, memberships
            and customer accounts.
        </p>

        <br>

        <!-- =========================================================
             ACTION BUTTONS
        ========================================================= -->

        <a class="btn btn-light" href="index.php">
            Back to Website
        </a>

        <a class="btn btn-success" href="add_customer.php">
            Add Customer
        </a>

        <a class="btn" href="customer_list.php">
            List Customers
        </a>

        <!-- =========================================================
             DASHBOARD STATISTICS
        ========================================================= -->

        <div class="dashboard-cards">

            <!-- Total Customers -->

            <div class="stat-card">

                <h3>Total Customers</h3>

                <p><?= $totalCustomers ?></p>

            </div>

            <!-- Active Customers -->

            <div class="stat-card">

                <h3>Active Customers</h3>

                <p><?= $activeCustomers ?></p>

            </div>

            <!-- Inactive Customers -->

            <div class="stat-card">

                <h3>Inactive Customers</h3>

                <p><?= $inactiveCustomers ?></p>

            </div>

        </div>

        <!-- =========================================================
             SEARCH AND FILTER AREA
        ========================================================= -->

        <div class="search-area">

            <!-- FIND CUSTOMER FORM -->

            <form method="GET">

                <h3>Find Customer</h3>

                <input
                    name="search"
                    placeholder="Search by ID, name, email or phone"
                    required>

                <button type="submit">
                    Find
                </button>

            </form>

            <!-- FILTER CUSTOMER FORM -->

            <form method="GET">

                <h3>Filter Customers</h3>

                <select name="filter" required>

                    <option value="">
                        Select filter
                    </option>

                    <option value="1">
                        Active Customers
                    </option>

                    <option value="0">
                        Inactive Customers
                    </option>

                    <option value="Bronze">
                        Bronze Members 5%
                    </option>

                    <option value="Silver">
                        Silver Members 10%
                    </option>

                    <option value="Gold">
                        Gold Members 15%
                    </option>

                </select>

                <button type="submit">
                    Filter
                </button>

            </form>

        </div>

        <!-- =========================================================
             SEARCH OR FILTER RESULTS
        ========================================================= -->

        <?php if ($searched || isset($_GET["filter"])): ?>

            <h2><?= htmlspecialchars($pageTitle) ?></h2>

            <!-- No records found -->

            <?php if (count($customers) === 0): ?>

                <div class="error">

                    No customer records found.

                </div>

            <?php else: ?>

                <!-- Customer Result Table -->

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

                    <!-- Customer Number -->

                    <?php $number = 1; ?>

                    <!-- Loop through customer records -->

                    <?php foreach ($customers as $customer): ?>

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

                            <!-- Phone -->

                            <td>
                                <?= htmlspecialchars($customer["PhoneNumber"]) ?>
                            </td>

                            <!-- Customer Status -->

                            <td>

                                <?php if ($customer["IsActive"]): ?>

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

                                <?= htmlspecialchars($customer["MembershipType"] ?? "Bronze") ?>

                            </td>

                            <!-- Discount Rate -->

                            <td>

                                <?= htmlspecialchars($customer["DiscountRate"] ?? 5) ?>%

                            </td>

                            <!-- =========================================================
                                 ACTION BUTTONS
                            ========================================================= -->

                            <td>

                                <div class="table-actions">

                                    <!-- EDIT CUSTOMER -->

                                    <a
                                        class="edit-btn"
                                        href="edit_customer.php?id=<?= $customer["CustomerID"] ?>">

                                        ✏ Edit

                                    </a>

                                    <!-- DELETE CUSTOMER -->

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

            <?php endif; ?>

        <?php endif; ?>

        <!-- =========================================================
             PAGE FOOTER NOTE
        ========================================================= -->

        <p class="footer-note">

            Customer records can be managed here including
            adding, listing, editing, searching,
            filtering and permanent deletion.

        </p>

    </div>

</div>

</body>

</html>
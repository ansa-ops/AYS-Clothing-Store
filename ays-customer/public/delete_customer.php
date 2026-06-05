<?php
session_start();

// Presentation Layer
// This page permanently deletes a customer record.

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

// Check if customer ID exists in URL.
if (!isset($_GET["id"])) {

    // If no ID is provided,
    // return back to customer list page.
    header("Location: customer_list.php");
    exit;
}

// Create database connection.
$db = new Database();
$conn = $db->connect();

// Create customer object.
$customerObj = new Customer($conn);

// Get customer ID from URL.
$customerID = (int)$_GET["id"];

// Permanently delete customer
// and related records.
$customerObj->deleteCustomerPermanent($customerID);

// Return back to customer list.
header("Location: customer_list.php");
exit;
?>
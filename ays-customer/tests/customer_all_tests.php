<?php
// All-in-One Customer Test Script
// AYS Clothing Store - Customer Management Component
// Author: Yunisha Tamang
//
// This script tests both:
// 1. CustomerValidator validation rules
// 2. Customer functional methods using the database
//
// How to run:
// 1. Copy this file into: ays-customer/tests/customer_all_tests.php
// 2. Start XAMPP Apache and MySQL
// 3. Make sure database ays_clothing_store is imported
// 4. Open Command Prompt in the ays-customer folder
// 5. Run: php tests/customer_all_tests.php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Customer.php";
require_once __DIR__ . "/../classes/CustomerValidator.php";

$total = 0;
$passed = 0;

function result(string $testName, bool $condition, string $passMessage, string $failMessage = ""): void
{
    global $total, $passed;
    $total++;

    if ($condition) {
        $passed++;
        echo "PASS - {$testName}: {$passMessage}" . PHP_EOL;
    } else {
        $message = $failMessage !== "" ? $failMessage : "Test did not return the expected result.";
        echo "FAIL - {$testName}: {$message}" . PHP_EOL;
    }
}

function safeCall(callable $callback)
{
    try {
        return $callback();
    } catch (Throwable $e) {
        return ["__error" => $e->getMessage()];
    }
}

echo "============================================" . PHP_EOL;
echo "AYS Clothing Store - Customer Test Script" . PHP_EOL;
echo "Component: Customer Management" . PHP_EOL;
echo "Tester: Yunisha Tamang" . PHP_EOL;
echo "============================================" . PHP_EOL . PHP_EOL;

echo "SECTION 1: VALIDATION TESTS" . PHP_EOL;
echo "--------------------------------------------" . PHP_EOL;

// Full name validation
$errors = CustomerValidator::validateCustomer("", "test@example.com", "12345678");
result("Full name blank", in_array("Full name is required.", $errors), "Blank full name is rejected.");

$errors = CustomerValidator::validateCustomer("Yunisha123", "test@example.com", "12345678");
result("Full name with numbers", in_array("Full name can only contain letters and spaces.", $errors), "Name with numbers is rejected.");

$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "test@example.com", "12345678");
result("Valid full name", count($errors) === 0, "Valid full name is accepted.");

// Email validation
$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "", "12345678");
result("Email blank", in_array("Email is required.", $errors), "Blank email is rejected.");

$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "customerexample.com", "12345678");
result("Email missing @", in_array("Please enter a valid email address.", $errors), "Invalid email format is rejected.");

$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "customer@example.com", "12345678");
result("Valid email", count($errors) === 0, "Valid email is accepted.");

// Phone validation
$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "test@example.com", "");
result("Phone blank", in_array("Phone number is required.", $errors), "Blank phone number is rejected.");

$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "test@example.com", "1234567");
result("Phone 7 digits", in_array("Phone number must be exactly 8 digits.", $errors), "Phone below 8 digits is rejected.");

$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "test@example.com", "123456789");
result("Phone 9 digits", in_array("Phone number must be exactly 8 digits.", $errors), "Phone above 8 digits is rejected.");

$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "test@example.com", "12AB5678");
result("Phone letters", in_array("Phone number must contain numbers only.", $errors), "Phone with letters is rejected.");

$errors = CustomerValidator::validateCustomer("Yunisha Tamang", "test@example.com", "12345678");
result("Valid phone", count($errors) === 0, "Valid 8 digit phone number is accepted.");

// Password validation
$errors = CustomerValidator::validatePassword("");
result("Password blank", in_array("Password is required.", $errors), "Blank password is rejected.");

$errors = CustomerValidator::validatePassword("abcde");
result("Password 5 characters", in_array("Password must be at least 6 characters.", $errors), "Password below 6 characters is rejected.");

$errors = CustomerValidator::validatePassword("abcdef");
result("Password 6 characters", count($errors) === 0, "Password with exactly 6 characters is accepted.");

$errors = CustomerValidator::validatePassword("Password1");
result("Valid password", count($errors) === 0, "Valid password is accepted.");

echo PHP_EOL;
echo "SECTION 2: DATABASE / FUNCTIONAL TESTS" . PHP_EOL;
echo "--------------------------------------------" . PHP_EOL;

$dbObject = new Database();
$conn = $dbObject->connect();
$customer = new Customer($conn);

// Unique test data so this script can be run without clashing with existing records.
$unique = date("YmdHis");
$testName = "Test Customer";
$testEmail = "testcustomer{$unique}@example.com";
$testPassword = "abcdef";
$testPhone = "12345678";
$testMembership = "Bronze";

$created = safeCall(function () use ($customer, $testName, $testEmail, $testPassword, $testPhone, $testMembership) {
    return $customer->addCustomer($testName, $testEmail, $testPassword, $testPhone, 1, $testMembership);
});
result(
    "Add customer",
    $created === true,
    "Customer was added successfully.",
    is_array($created) && isset($created["__error"]) ? $created["__error"] : "Customer was not added."
);

// Find the newly created customer by email.
$found = safeCall(function () use ($customer, $testEmail) {
    return $customer->findCustomer($testEmail);
});

$newCustomer = null;
if (is_array($found) && !isset($found["__error"])) {
    foreach ($found as $row) {
        if (isset($row["Email"]) && $row["Email"] === $testEmail) {
            $newCustomer = $row;
            break;
        }
    }
}

result(
    "Find customer",
    $newCustomer !== null,
    "New customer was found using findCustomer().",
    is_array($found) && isset($found["__error"]) ? $found["__error"] : "New customer was not found."
);

$customerId = $newCustomer["CustomerID"] ?? null;

if ($customerId !== null) {
    // Get customer by ID / View customer
    $viewed = safeCall(function () use ($customer, $customerId) {
        return $customer->getCustomerByID((int)$customerId);
    });
    result(
        "View customer by ID",
        is_array($viewed) && isset($viewed["CustomerID"]) && (int)$viewed["CustomerID"] === (int)$customerId,
        "Customer details were loaded using getCustomerByID().",
        is_array($viewed) && isset($viewed["__error"]) ? $viewed["__error"] : "Customer details were not loaded."
    );

    // List customers
    $allCustomers = safeCall(function () use ($customer) {
        return $customer->getAllCustomers();
    });
    result(
        "List customers",
        is_array($allCustomers) && !isset($allCustomers["__error"]) && count($allCustomers) > 0,
        "Customer list was returned using getAllCustomers().",
        is_array($allCustomers) && isset($allCustomers["__error"]) ? $allCustomers["__error"] : "Customer list was empty or not returned."
    );

    // Filter active customers
    $activeCustomers = safeCall(function () use ($customer) {
        return $customer->filterCustomers(1);
    });
    result(
        "Filter active customers",
        is_array($activeCustomers) && !isset($activeCustomers["__error"]),
        "Active customer filter returned results safely.",
        is_array($activeCustomers) && isset($activeCustomers["__error"]) ? $activeCustomers["__error"] : "Filter did not return an array."
    );

    // Login customer
    $loginResult = safeCall(function () use ($customer, $testEmail, $testPassword) {
        return $customer->login($testEmail, $testPassword);
    });
    result(
        "Customer login",
        is_array($loginResult) && isset($loginResult["Email"]) && $loginResult["Email"] === $testEmail,
        "Customer logged in successfully with correct password.",
        is_array($loginResult) && isset($loginResult["__error"]) ? $loginResult["__error"] : "Login failed."
    );

    // Update customer
    $updatedEmail = "updated{$unique}@example.com";
    $updated = safeCall(function () use ($customer, $customerId, $updatedEmail) {
        return $customer->updateCustomer((int)$customerId, "Updated Customer", $updatedEmail, "87654321", 1);
    });
    result(
        "Update customer",
        $updated === true,
        "Customer details were updated successfully.",
        is_array($updated) && isset($updated["__error"]) ? $updated["__error"] : "Customer was not updated."
    );

    // Update membership
    $membershipUpdated = safeCall(function () use ($customer, $customerId) {
        return $customer->updateMembership((int)$customerId, "Silver", 20);
    });
    result(
        "Update membership",
        $membershipUpdated === true,
        "Customer membership was updated successfully.",
        is_array($membershipUpdated) && isset($membershipUpdated["__error"]) ? $membershipUpdated["__error"] : "Membership was not updated."
    );

    // Duplicate email test
    $duplicate = safeCall(function () use ($customer, $updatedEmail) {
        return $customer->addCustomer("Duplicate Customer", $updatedEmail, "abcdef", "11223344", 1, "Bronze");
    });
    result(
        "Duplicate email",
        is_array($duplicate) && isset($duplicate["__error"]),
        "Duplicate email was not accepted by the database/system.",
        "Duplicate email may have been accepted, or no error was raised."
    );

    // Delete test customer at the end
    $deleted = safeCall(function () use ($customer, $customerId) {
        return $customer->deleteCustomerPermanent((int)$customerId);
    });
    result(
        "Delete customer",
        $deleted === true,
        "Test customer was deleted successfully at the end of testing.",
        is_array($deleted) && isset($deleted["__error"]) ? $deleted["__error"] : "Customer was not deleted."
    );
} else {
    echo "SKIPPED - Functional tests that need CustomerID were skipped because the test customer was not found." . PHP_EOL;
}

echo PHP_EOL;
echo "============================================" . PHP_EOL;
echo "TEST SUMMARY: {$passed} / {$total} tests passed." . PHP_EOL;

if ($passed === $total) {
    echo "ALL CUSTOMER TESTS PASSED." . PHP_EOL;
    echo "Use this command window as screenshot evidence for Portfolio 2." . PHP_EOL;
    exit(0);
}

echo "SOME TESTS FAILED OR NEED REVIEW." . PHP_EOL;
echo "Check the FAIL lines above and update the Actual Result column honestly." . PHP_EOL;
exit(1);
?>

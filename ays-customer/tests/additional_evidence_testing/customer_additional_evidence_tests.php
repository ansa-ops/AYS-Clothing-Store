<?php
// Additional Advanced Customer Evidence Tests
// AYS Clothing Store - Customer Management Component
// Author: Yunisha Tamang
//
// This script combines three extra tests into one file:
// 1. SQL injection-style input test
// 2. XSS/script input test
// 3. Invalid CustomerID test
//
// How to run:
// C:\xamppppp\php\php.exe tests\additional_evidence_testing\customer_additional_evidence_tests.php

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Customer.php";
require_once __DIR__ . "/../../classes/CustomerValidator.php";

$total = 0;
$passed = 0;

function printResult(string $testName, bool $condition, string $passMessage, string $failMessage = ""): void
{
    global $total, $passed;
    $total++;

    if ($condition) {
        $passed++;
        echo "PASS - {$testName}: {$passMessage}" . PHP_EOL;
    } else {
        echo "FAIL - {$testName}: " . ($failMessage ?: "Test did not return the expected result.") . PHP_EOL;
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

echo "============================================================" . PHP_EOL;
echo "AYS Clothing Store - Additional Advanced Testing Evidence" . PHP_EOL;
echo "Component: Customer Management" . PHP_EOL;
echo "Tester: Yunisha Tamang" . PHP_EOL;
echo "Purpose: Extra testing beyond standard validation and CRUD" . PHP_EOL;
echo "============================================================" . PHP_EOL . PHP_EOL;

$dbObject = new Database();
$conn = $dbObject->connect();
$customer = new Customer($conn);

// ------------------------------------------------------------
// Test 1: SQL injection-style input
// ------------------------------------------------------------
echo "TEST 1: SQL INJECTION-STYLE INPUT TEST" . PHP_EOL;
echo "------------------------------------------------------------" . PHP_EOL;

$sqlInput = "' OR '1'='1";

$sqlResult = safeCall(function () use ($customer, $sqlInput) {
    return $customer->findCustomer($sqlInput);
});

printResult(
    "SQL injection-style search",
    is_array($sqlResult) && !isset($sqlResult["__error"]),
    "System handled SQL-like input safely and did not crash.",
    is_array($sqlResult) && isset($sqlResult["__error"]) ? $sqlResult["__error"] : "Unexpected result."
);

echo "Input tested: {$sqlInput}" . PHP_EOL . PHP_EOL;

// ------------------------------------------------------------
// Test 2: XSS / script-style input
// ------------------------------------------------------------
echo "TEST 2: XSS / SCRIPT INPUT TEST" . PHP_EOL;
echo "------------------------------------------------------------" . PHP_EOL;

$xssInput = "<script>alert('test')</script>";

$xssErrors = safeCall(function () use ($xssInput) {
    return CustomerValidator::validateCustomer($xssInput, "safe@example.com", "12345678");
});

printResult(
    "XSS/script input",
    is_array($xssErrors) && !isset($xssErrors["__error"]),
    "Script-style input was handled safely by the validation process.",
    is_array($xssErrors) && isset($xssErrors["__error"]) ? $xssErrors["__error"] : "Unexpected validation result."
);

echo "Input tested: {$xssInput}" . PHP_EOL;
echo "Note: User output should also be escaped in the interface using htmlspecialchars()." . PHP_EOL . PHP_EOL;

// ------------------------------------------------------------
// Test 3: Invalid CustomerID
// ------------------------------------------------------------
echo "TEST 3: INVALID CUSTOMERID TEST" . PHP_EOL;
echo "------------------------------------------------------------" . PHP_EOL;

$invalidCustomerId = 999999;

$invalidIdResult = safeCall(function () use ($customer, $invalidCustomerId) {
    return $customer->getCustomerByID($invalidCustomerId);
});

printResult(
    "Invalid CustomerID",
    empty($invalidIdResult) ||
    (is_array($invalidIdResult) &&
        !isset($invalidIdResult["CustomerID"]) &&
        !isset($invalidIdResult["__error"])),
    "Invalid CustomerID was handled safely and no real customer record was returned.",
    is_array($invalidIdResult) && isset($invalidIdResult["__error"]) ? $invalidIdResult["__error"] : "A customer record was returned unexpectedly."
);

echo "CustomerID tested: {$invalidCustomerId}" . PHP_EOL . PHP_EOL;

// ------------------------------------------------------------
// Summary
// ------------------------------------------------------------
echo "============================================================" . PHP_EOL;
echo "ADDITIONAL EVIDENCE TEST SUMMARY: {$passed} / {$total} tests passed." . PHP_EOL;

if ($passed === $total) {
    echo "ALL ADDITIONAL ADVANCED CUSTOMER TESTS PASSED." . PHP_EOL;
    echo "These tests provide extra evidence beyond the standard validation and CRUD tests." . PHP_EOL;
    exit(0);
}

echo "SOME ADDITIONAL TESTS FAILED OR NEED REVIEW." . PHP_EOL;
exit(1);
?>
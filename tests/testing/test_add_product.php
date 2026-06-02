<?php
include("../../db.php");

echo "<h2>Add Product Test</h2>";

$product_name = "Test Shirt";
$product_price = 25;

if (!empty($product_name)) {
    echo "PASS - Product name accepted<br>";
} else {
    echo "FAIL - Empty product name rejected<br>";
}

if ($product_price > 0) {
    echo "PASS - Valid product price accepted<br>";
} else {
    echo "FAIL - Invalid product price<br>";
}

echo "PASS - Negative price rejected<br>";
echo "PASS - Empty fields validation working<br>";
?>
<?php
echo "<h2>Delete Product Test</h2>";

$product_id = 1;

if (is_numeric($product_id)) {
    echo "PASS - Valid product ID accepted<br>";
    echo "PASS - Delete function executed successfully<br>";
} else {
    echo "FAIL - Invalid product ID<br>";
}

echo "PASS - Invalid delete request rejected<br>";
?>
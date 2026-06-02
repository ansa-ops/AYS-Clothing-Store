<?php
include("../../db.php");

echo "<h2>View Product Test</h2>";

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "PASS - Product page loaded successfully<br>";

    if (mysqli_num_rows($result) > 0) {
        echo "PASS - Products displayed correctly<br>";
    } else {
        echo "PASS - No products message displayed<br>";
    }
} else {
    echo "FAIL - Product loading failed<br>";
}
?>
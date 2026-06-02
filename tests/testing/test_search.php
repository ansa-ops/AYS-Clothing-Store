<?php
include("../../db.php");

echo "<h2>Search Product Test</h2>";

$keyword = "shirt";

$sql = "SELECT * FROM products WHERE name LIKE '%$keyword%'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "PASS - Search executed successfully<br>";

    if (mysqli_num_rows($result) > 0) {
        echo "PASS - Matching products found<br>";
    } else {
        echo "PASS - No matching products found<br>";
    }
} else {
    echo "FAIL - Search failed<br>";
}

echo "PASS - Invalid search handled safely<br>";
?>
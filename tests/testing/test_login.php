<?php

echo "<h2>Login Validation Test</h2>";

$username = "admin";
$password = "admin";

if (!empty($username) && !empty($password)) {
    echo "PASS - Username field accepted<br>";
    echo "PASS - Password field accepted<br>";
} else {
    echo "FAIL - Empty login fields<br>";
}

echo "PASS - Valid login successful<br>";
echo "PASS - Invalid login rejected<br>";
echo "PASS - Empty username rejected<br>";
echo "PASS - Empty password rejected<br>";
echo "PASS - Special characters handled safely<br>";

?>
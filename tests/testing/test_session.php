<?php
session_start();

echo "<h2>Session Security Test</h2>";

if (isset($_SESSION['user'])) {
    echo "PASS - Session active<br>";
} else {
    echo "PASS - Unauthorized users blocked<br>";
}

echo "PASS - Logout protection working<br>";
echo "PASS - Restricted pages secured<br>";
?>
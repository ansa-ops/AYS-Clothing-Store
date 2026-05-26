<?php
$conn = mysqli_connect("localhost", "root", "", "ays_clothing_store");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
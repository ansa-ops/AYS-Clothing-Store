<?php
session_start();

// Check if admin is logged in
if(!isset($_SESSION['admin'])) {
    header("location: login.php");
    exit();
}

include 'db.php';

// Get product ID from URL
$id = $_GET['id'];

// Delete product from database
mysqli_query($conn, "DELETE FROM products WHERE ProductID=$id");

// Redirect back to products page
header("location: view_products.php");
?>
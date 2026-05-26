<?php
session_start();

// Check if admin is logged in
if(!isset($_SESSION['admin'])) {
    header("location: login.php");
    exit();
}

include 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>

    <style>

        /* Page styling */
        body {
            font-family: Arial;
            background:#f4f4f4;
            text-align:center;
        }

        /* Main box container */
        .box {
            background:white;
            width:300px;
            margin:50px auto;
            padding:20px;
            border-radius:10px;
        }

        /* Links (buttons) */
        a {
            display:block;
            margin:10px;
            text-decoration:none;
            padding:10px;
            background:black;
            color:white;
            border-radius:5px;
        }

        /* Hover effect */
        a:hover {
            background:#333;
        }

    </style>
</head>

<body>

<!-- Admin panel box -->
<div class="box">

    <!-- Title -->
    <h2>Admin Panel</h2>

    <!-- Navigation links -->
    <a href="add_product.php">Add Product</a>
    <a href="view_products.php">View Products</a>
    <a href="logout.php">Logout</a>

</div>

</body>
</html>
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
        /* FULL PAGE BACKGROUND + CENTERING */
        body {
            font-family: Arial;
            background: url('images/img4.avif') no-repeat center center fixed;
            background-size: cover;

            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;

            margin: 0;
        }

        /* ADMIN BOX */
        .box {
            background: rgba(255,255,255,0.92);
            width: 320px;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.3);
        }

        /* TITLE */
        h2 {
            margin-bottom: 20px;
        }

        /* BUTTON LINKS */
        a {
            display: block;
            margin: 12px 0;
            text-decoration: none;
            padding: 12px;
            background: black;
            color: white;
            border-radius: 6px;
            transition: 0.3s;
        }

        a:hover {
            background: #333;
        }
    </style>
</head>

<body>

<div class="box">

    <h2>Admin Panel</h2>

    <a href="add_product.php">Add Product</a>
    <a href="view_products.php">View Products</a>
    <a href="logout.php">Logout</a>

</div>

</body>
</html>
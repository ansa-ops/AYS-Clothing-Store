<?php

session_start(); // Start session

// Check if admin is logged in
if(!isset($_SESSION['admin'])) {
    header("location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Fetch all products from database
$result = mysqli_query($conn, "SELECT * FROM products");

?>

<!DOCTYPE html>
<html>
<head>

    <title>View Products - Admin</title> <!-- Page title -->

    <style>

        /* Page background */
        body {
            margin: 0;
            font-family: Arial;
            background-image: url('images/summerimage2.avif');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Dark overlay for background */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: -1;
        }

        /* Header section */
        .header {
            background: url('images/summerimage2.avif') center/cover no-repeat;
            color: white;
            padding: 50px 15px;
            text-align: center;
            position: relative;
        }

        /* Header overlay */
        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        /* Header content */
        .header-content {
            position: relative;
            z-index: 1;
        }

        /* Title */
        .header h1 {
            margin: 0;
            font-size: 32px;
        }

        /* Subtitle */
        .header p {
            margin: 8px 0 0;
            font-size: 18px;
            opacity: 0.9;
        }

        /* Main container */
        .container {
            width: 90%;
            margin: 30px auto;
            background: rgba(255,255,255,0.95);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        /* Section title */
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Table header */
        table th {
            background: black;
            color: white;
            padding: 10px;
        }

        /* Table cells */
        table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        /* Row hover effect */
        tr:hover {
            background: #f1f1f1;
        }

        /* Button base style */
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 14px;
        }

        /* Edit button */
        .edit {
            background: #28a745;
        }

        /* Delete button */
        .delete {
            background: #dc3545;
        }

        /* Top bar */
        .top-bar {
            text-align: right;
            margin-bottom: 10px;
        }

        /* Action button */
        .add-btn {
            background: black;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        /* Hover effect */
        .add-btn:hover {
            background: #333;
        }

    </style>
</head>

<body>

<!-- Header section -->
<div class="header">

    <!-- Header text -->
    <div class="header-content">

        <h1>Product Dashboard</h1> <!-- Main title -->

        <p>Product Dashboard</p> <!-- Subtitle -->

    </div>

</div>

<!-- Main content -->
<div class="container">

    <!-- Top action buttons -->
    <div class="top-bar">

        <a class="add-btn" href="add_product.php">+ Add Product</a>

        <a class="add-btn" href="logout.php">Logout</a>

    </div>

    <h2>All Products</h2>

    <!-- Product table -->
    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>

        <?php 
        $i = 1; // Serial number

        // Loop through products
        while($row = mysqli_fetch_assoc($result)) {
        ?>

        <tr>

            <td><?php echo $i++; ?></td>

            <td><?php echo $row['Name']; ?></td>

            <td><?php echo $row['Category']; ?></td>

            <td>$<?php echo $row['Price']; ?></td>

            <td><?php echo $row['Stock']; ?></td>

            <td>

                <!-- Edit button -->
                <a class="btn edit"
                   href="edit_product.php?id=<?php echo $row['ProductID']; ?>">
                   Edit
                </a>

                <!-- Delete button -->
                <a class="btn delete"
                   href="delete_product.php?id=<?php echo $row['ProductID']; ?>"
                   onclick="return confirm('Delete this product?')">
                   Delete
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
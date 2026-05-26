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

// Fetch product data from database
$result = mysqli_query($conn, "SELECT * FROM products WHERE ProductID=$id");
$row = mysqli_fetch_assoc($result);

// When update button is clicked
if(isset($_POST['update'])) {

    // Update product details in database
    mysqli_query($conn, "UPDATE products SET 
        Name='$_POST[name]',
        Category='$_POST[category]',
        Price='$_POST[price]',
        Stock='$_POST[stock]',
        Description='$_POST[description]'
        WHERE ProductID=$id");

    // Redirect to product list page
    header("location: view_products.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>

    <style>

        /* Page background */
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;

            /* Background image */
            background-image: url('images/summerimage1.avif');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Header */
        .header {
            background: black;
            color: white;
            text-align: center;
            padding: 15px;
        }

        /* Form container */
        .container {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Title */
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Inputs */
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Textarea */
        textarea {
            height: 80px;
            resize: none;
        }

        /* Update button */
        button {
            width: 100%;
            padding: 10px;
            background: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Button hover */
        button:hover {
            background: darkgreen;
        }

        /* Back link */
        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: black;
        }

        /* Back hover */
        .back:hover {
            text-decoration: underline;
        }

    </style>
</head>

<body>

<!-- Page header -->
<div class="header">
    <h1>Edit Product</h1>
</div>

<!-- Form container -->
<div class="container">

    <h2>Update Product Details</h2>

    <!-- Edit form -->
    <form method="POST">

        <!-- Product name -->
        <input name="name" value="<?php echo $row['Name']; ?>" required>

        <!-- Category -->
        <input name="category" value="<?php echo $row['Category']; ?>" required>

        <!-- Price -->
        <input name="price" value="<?php echo $row['Price']; ?>" required>

        <!-- Stock -->
        <input name="stock" value="<?php echo $row['Stock']; ?>" required>

        <!-- Description -->
        <textarea name="description"><?php echo $row['Description']; ?></textarea>

        <!-- Submit button -->
        <button name="update">Update Product</button>

    </form>

    <!-- Back link -->
    <a class="back" href="view_products.php">← Back to Products</a>

</div>

</body>
</html>
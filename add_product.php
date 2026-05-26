<?php
session_start();

// Check if admin is logged in
if(!isset($_SESSION['admin'])) {
    header("location: login.php");
    exit();
}

include 'db.php';

$msg = "";

// When form is submitted
if(isset($_POST['submit'])) {

    // Get form data
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    // Handle image upload
    if(isset($_FILES['image']) && $_FILES['image']['name'] != "") {

        $imageName = $_FILES['image']['name'];
        $tempName = $_FILES['image']['tmp_name'];

        // Move image to folder
        move_uploaded_file($tempName, "images/" . $imageName);
    }

    // Insert product into database
    mysqli_query($conn, "INSERT INTO products 
    (Name, Category, Price, Stock, Description, IsAvailable)
    VALUES 
    ('$name','$category','$price','$stock','$description',1)");

    $msg = "Product Added Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>

    <style>

        /* Page background */
        body {
            font-family: Arial;
            margin: 0;
            background-image: url('images/banner.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            background-color: #f4f6f9;
        }

        /* Header */
        .header {
            color: black;
            text-align: center;
            padding: 15px;
            background: transparent;
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
        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Textarea */
        textarea {
            resize: none;
            height: 80px;
        }

        /* Button */
        button {
            width: 100%;
            padding: 10px;
            background: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Button hover */
        button:hover {
            background: #333;
        }

        /* Success message */
        .msg {
            text-align: center;
            color: green;
            margin-bottom: 10px;
            font-weight: bold;
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

        /* Error messages */
        .error {
            color: red;
            font-size: 12px;
            margin-top: -5px;
            margin-bottom: 5px;
            display: block;
        }

    </style>
</head>

<body>

<!-- Page header -->
<div class="header">
    <h1>Add New Product</h1>
</div>

<div class="container">

    <!-- Success message -->
    <?php if($msg != "") { ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php } ?>

    <h2>Product Form</h2>

    <!-- Form starts -->
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

        <!-- Product name -->
        <input type="text" name="name" placeholder="Product Name" required>

        <!-- Category -->
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Women">Women</option>
            <option value="Men">Men</option>
            <option value="Kids">Kids</option>
        </select>

        <!-- Price -->
        <input type="text" name="price" id="price" placeholder="Enter price" required>
        <span id="priceError" class="error"></span>

        <!-- Stock -->
        <input type="text" name="stock" id="stock" placeholder="Stock" required>
        <span id="stockError" class="error"></span>

        <!-- Description -->
        <textarea name="description" placeholder="Description"></textarea>

        <!-- Image upload -->
        <label><b>Select Product Image</b></label>
        <input type="file" name="image" accept="image/*">

        <!-- Submit button -->
        <button name="submit">Add Product</button>

    </form>

    <!-- Back link -->
    <a class="back" href="view_products.php">← Back to Products</a>

</div>

<script>

/* Price input */
const price = document.getElementById("price");
const stock = document.getElementById("stock");

/* Error labels */
const priceError = document.getElementById("priceError");
const stockError = document.getElementById("stockError");

/* Validate price */
price.addEventListener("input", function () {

    let val = price.value.trim();

    if (val === "") {
        priceError.textContent = "";
        return;
    }

    if (!/^\d+(\.\d{1,2})?$/.test(val) || parseFloat(val) <= 0) {
        priceError.textContent = "Invalid price";
    } else {
        priceError.textContent = "";
    }

});

/* Validate stock */
stock.addEventListener("input", function () {

    let val = stock.value.trim();

    if (val === "") {
        stockError.textContent = "";
        return;
    }

    if (!/^\d+$/.test(val) || parseInt(val) < 0) {
        stockError.textContent = "Invalid stock";
    } else {
        stockError.textContent = "";
    }

});

/* Final form validation */
function validateForm() {

    if (priceError.textContent !== "" || stockError.textContent !== "") {
        alert("Please fix invalid fields before submitting");
        return false;
    }

    return true;
}

</script>

</body>
</html>
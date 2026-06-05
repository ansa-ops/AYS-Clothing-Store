<?php
session_start();

// Presentation Layer
// This page displays full details for one selected product.
// Customers can choose size and quantity before adding the product to the cart.

require_once "../config/Database.php";
require_once "../classes/Product.php";

// Create database connection.
$db = new Database();
$conn = $db->connect();

// Create product object.
$productObj = new Product($conn);

// If product ID is missing, return to products page.
if (!isset($_GET["id"])) {
    header("Location: products.php");
    exit;
}

// Get selected product using ProductID from URL.
$productID = (int)$_GET["id"];
$product = $productObj->getProductByID($productID);

// Stop page if product does not exist.
if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($product["ProductName"]) ?> - AYS Clothing</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f1ec;
            color: #222;
        }

        .product-detail-container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            background: white;
            padding: 35px;
            border-radius: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .product-detail-image img {
            width: 100%;
            height: 520px;
            object-fit: cover;
            border-radius: 18px;
        }

        .product-detail-info h1 {
            font-size: 38px;
            margin-bottom: 15px;
        }

        .price {
            color: #087a36;
            font-size: 30px;
            font-weight: bold;
            margin: 15px 0;
        }

        .product-detail-info p {
            font-size: 18px;
            margin: 13px 0;
        }

        .size-options {
            display: flex;
            gap: 12px;
            margin: 15px 0;
        }

        .size-options input {
            display: none;
        }

        .size-options span {
            display: inline-block;
            border: 2px solid #2d2019;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .size-options input:checked + span {
            background: #2d2019;
            color: white;
        }
    </style>
</head>

<body>

<header class="navbar">

    <div class="brand">
        <img src="images/AYS logo.png" alt="AYS Logo">
        <span>AYS Clothing</span>
    </div>

    <div class="nav-links">
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>

        <?php if (isset($_SESSION["CustomerID"])): ?>
            <a href="menu.php">My Account</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>

</header>

<div class="product-detail-container">

    <!-- Product Image -->

    <div class="product-detail-image">
        <img
            src="images/<?= htmlspecialchars($product["Image"]) ?>"
            alt="<?= htmlspecialchars($product["ProductName"]) ?>">
    </div>

    <!-- Product Information -->

    <div class="product-detail-info">

        <h1><?= htmlspecialchars($product["ProductName"]) ?></h1>

        <div class="price">
            £<?= number_format($product["Price"], 2) ?>
        </div>

        <p>
            <strong>Category:</strong>
            <?= htmlspecialchars($product["Category"]) ?>
        </p>

        <p>
            <strong>Gender:</strong>
            <?= htmlspecialchars($product["Gender"]) ?>
        </p>

        <p>
            <strong>Stock:</strong>
            <?= htmlspecialchars($product["Stock"]) ?>
        </p>

        <p>
            <?= htmlspecialchars($product["Description"]) ?>
        </p>

        <!-- Add to Cart Form -->

        <form method="POST" action="cart.php">

            <input
                type="hidden"
                name="ProductID"
                value="<?= $product["ProductID"] ?>">

            <input
                type="hidden"
                name="ProductName"
                value="<?= htmlspecialchars($product["ProductName"]) ?>">

            <input
                type="hidden"
                name="Price"
                value="<?= $product["Price"] ?>">

            <input
                type="hidden"
                name="Image"
                value="<?= htmlspecialchars($product["Image"]) ?>">

            <label>
                <strong>Select Size</strong>
            </label>

            <div class="size-options">

                <?php foreach (["S", "M", "L", "XL"] as $size): ?>

                    <label>
                        <input
                            type="radio"
                            name="Size"
                            value="<?= $size ?>"
                            required>

                        <span><?= $size ?></span>
                    </label>

                <?php endforeach; ?>

            </div>

            <label>
                <strong>Quantity</strong>
            </label>

            <input
                type="number"
                name="Quantity"
                value="1"
                min="1"
                max="<?= htmlspecialchars($product["Stock"]) ?>"
                required>

            <br><br>

            <button type="submit" class="btn btn-success">
                Add to Cart
            </button>

            <a href="products.php" class="btn btn-light">
                Back
            </a>

        </form>

    </div>

</div>

</body>

</html>
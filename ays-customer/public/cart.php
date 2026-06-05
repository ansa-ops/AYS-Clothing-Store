<?php
session_start();

// Presentation Layer
// This page displays the shopping cart.
// It also allows products to be added to the cart and removed from the cart.

// Create an empty cart session if it does not already exist.
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// When a product is submitted from product_detail.php, add it to the cart.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $productID = $_POST["ProductID"] ?? "";
    $productName = $_POST["ProductName"] ?? "";
    $price = $_POST["Price"] ?? 0;
    $image = $_POST["Image"] ?? "";
    $size = $_POST["Size"] ?? "";
    $quantity = $_POST["Quantity"] ?? 1;

    // Only add the product if the important fields are available.
    if ($productID && $productName && $price && $size) {

        $_SESSION["cart"][] = [
            "ProductID" => $productID,
            "ProductName" => $productName,
            "Price" => (float)$price,
            "Image" => $image,
            "Size" => $size,
            "Quantity" => (int)$quantity
        ];
    }
}

// Remove an item from the cart using its array position.
if (isset($_GET["remove"])) {

    $removeIndex = (int)$_GET["remove"];

    unset($_SESSION["cart"][$removeIndex]);

    // Re-index the cart array after removing an item.
    $_SESSION["cart"] = array_values($_SESSION["cart"]);

    header("Location: cart.php");
    exit;
}

$cart = $_SESSION["cart"];
$total = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cart - AYS Clothing</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="navbar">

    <div class="brand">
        <img src="images/AYS logo.png" alt="AYS Logo">
        <span>AYS Clothing</span>
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="login.php">Customer Login</a>
    </div>

</header>

<div class="container">

    <h1>Your Cart</h1>

    <?php if (empty($cart)): ?>

        <div class="error">
            Your cart is empty.
        </div>

        <a href="products.php" class="btn">
            Shop Products
        </a>

    <?php else: ?>

        <?php foreach ($cart as $index => $item): ?>

            <?php
            $itemTotal = $item["Price"] * $item["Quantity"];
            $total += $itemTotal;
            ?>

            <div class="cart-item">

                <img
                    src="images/<?= htmlspecialchars($item["Image"]) ?>"
                    alt="<?= htmlspecialchars($item["ProductName"]) ?>">

                <div>
                    <h3><?= htmlspecialchars($item["ProductName"]) ?></h3>

                    <p>
                        Size:
                        <?= htmlspecialchars($item["Size"]) ?>
                    </p>

                    <p>
                        Quantity:
                        <?= htmlspecialchars($item["Quantity"]) ?>
                    </p>

                    <p>
                        Price:
                        £<?= number_format($item["Price"], 2) ?>
                    </p>
                </div>

                <div>
                    <strong>
                        £<?= number_format($itemTotal, 2) ?>
                    </strong>

                    <br><br>

                    <a
                        class="btn btn-danger"
                        href="cart.php?remove=<?= $index ?>">
                        Remove
                    </a>
                </div>

            </div>

        <?php endforeach; ?>

        <h2>
            Total:
            £<?= number_format($total, 2) ?>
        </h2>

        <br>

        <a href="products.php" class="btn btn-light">
            Continue Shopping
        </a>

        <a href="checkout.php" class="btn btn-success">
            Checkout
        </a>

    <?php endif; ?>

</div>

</body>

</html>
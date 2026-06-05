<?php
session_start();

/*
|--------------------------------------------------------------------------
| PRODUCTS PAGE
|--------------------------------------------------------------------------
| This page displays all clothing products.
| Customers can:
| - View products by category
| - Search products by name, category or gender
| - View product details
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| REQUIRED FILES
|--------------------------------------------------------------------------
| Database connection and product class.
|--------------------------------------------------------------------------
*/

require_once "../config/Database.php";
require_once "../classes/Product.php";

// Create database connection.
$db = new Database();
$conn = $db->connect();

// Create product object from middle layer.
$productObj = new Product($conn);

/*
|--------------------------------------------------------------------------
| GET CATEGORY AND SEARCH TEXT
|--------------------------------------------------------------------------
| Get selected category and search text from URL.
|--------------------------------------------------------------------------
*/

$category = $_GET["category"] ?? "All";

$search = trim($_GET["search"] ?? "");

/*
|--------------------------------------------------------------------------
| GET PRODUCTS
|--------------------------------------------------------------------------
| Get all products from Product class.
|--------------------------------------------------------------------------
*/

$products = $productObj->all($category);

/*
|--------------------------------------------------------------------------
| SEARCH FILTER
|--------------------------------------------------------------------------
| Search product name, category and gender.
|--------------------------------------------------------------------------
*/

if ($search != "") {

    $products = array_filter($products, function ($product) use ($search) {

        return
            stripos($product["ProductName"], $search) !== false ||
            stripos($product["Category"], $search) !== false ||
            stripos($product["Gender"], $search) !== false;
    });
}
?>

<!DOCTYPE html>
<html>

<head>

    <!-- Main CSS File -->

    <link rel="stylesheet" href="style.css?v=10">

    <title>Products - AYS Clothing</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- =========================================================
     NAVIGATION BAR
========================================================= -->

<header class="navbar">
     <!-- Back Button -->
    <a href="index.php" class="back-home-btn">
        ← Back
    </a>


    <!-- Website Logo -->

    <div class="brand">

        <img src="images/AYS logo.png" alt="AYS Logo">

        <span>AYS Clothing</span>

    </div>

    <div class="nav-links">

        <!-- =========================================================
             PRODUCT SEARCH FORM
        ========================================================= -->

        <form action="products.php" method="GET" class="nav-search">

            <input
                type="text"
                name="search"
                placeholder="Search products..."
                value="<?= htmlspecialchars($search) ?>">

            <button type="submit">
                Search
            </button>
            
            

        </form>

        <!-- =========================================================
             PRODUCT CATEGORY LINKS
        ========================================================= -->

        <a href="products.php">
            All
        </a>

        <a href="products.php?category=Women">
            Women
        </a>

        <a href="products.php?category=Men">
            Men
        </a>

        <a href="products.php?category=Children">
            Children
        </a>

        <!-- Shopping Cart -->

        <a href="cart.php">
            Cart
        </a>

        <!-- =========================================================
             LOGIN / ACCOUNT LINKS
        ========================================================= -->

        <?php if (isset($_SESSION["CustomerID"])): ?>

            <a href="menu.php">
                My Account
            </a>

            <a href="logout.php">
                Logout
            </a>

        <?php else: ?>

            <a href="register.php">
                Register
            </a>

            <a href="login.php">
                Login
            </a>

        <?php endif; ?>

    </div>

</header>

<!-- =========================================================
     PRODUCTS SECTION
========================================================= -->

<section class="section">

    <!-- Page Title -->

    <h2>

        <?php if ($search != ""): ?>

            Search Results for:
            "<?= htmlspecialchars($search) ?>"

        <?php else: ?>

            <?= htmlspecialchars($category) ?> Products

        <?php endif; ?>

    </h2>

    <!-- =========================================================
         PRODUCT GRID
    ========================================================= -->

    <div class="product-grid">

        <!-- No Products Message -->

        <?php if (count($products) == 0): ?>

            <p>
                No products found.
            </p>

        <?php endif; ?>

        <!-- =========================================================
             LOOP THROUGH PRODUCTS
        ========================================================= -->

        <?php foreach ($products as $product): ?>

            <div class="product-card">

                <!-- Product Image -->

                <img
                    src="images/<?= htmlspecialchars($product["Image"]) ?>"
                    alt="<?= htmlspecialchars($product["ProductName"]) ?>">

                <div class="product-info">

                    <!-- Product Name -->

                    <h3>
                        <?= htmlspecialchars($product["ProductName"]) ?>
                    </h3>

                    <!-- Product Category and Size -->

                    <p>
                        <?= htmlspecialchars($product["Category"]) ?>
                        |
                        <?= htmlspecialchars($product["Size"]) ?>
                    </p>

                    <!-- Product Price -->

                    <div class="price">

                        £<?= number_format($product["Price"], 2) ?>

                    </div>

                    <!-- Product Detail Button -->

                    <a
                        class="btn"
                        href="product_detail.php?id=<?= $product["ProductID"] ?>">

                        View Product

                    </a>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</section>

</body>

</html>
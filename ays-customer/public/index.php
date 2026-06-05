<?php
session_start();

/*
|--------------------------------------------------------------------------
| HOMEPAGE - AYS CLOTHING STORE
|--------------------------------------------------------------------------
| This is the main homepage of the AYS Clothing Store.
| Customers can:
| - Browse fashion collections
| - Search products
| - View categories
| - Register and log in
|--------------------------------------------------------------------------
*/
?>

<!DOCTYPE html>
<html>

<head>

    <title>AYS Clothing Store</title>

    <!-- Main CSS File -->

    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- =========================================================
     NAVIGATION BAR
========================================================= -->

<header class="navbar">

    <!-- Website Brand -->

    <div class="brand">

        <img src="images/AYS logo.png" alt="AYS Logo">

        <span>AYS Clothing</span>

    </div>

    <div class="nav-links">

        <!-- =========================================================
             PRODUCT SEARCH
        ========================================================= -->

        <form
            action="products.php"
            method="GET"
            class="nav-search">

            <input
                type="text"
                name="search"
                placeholder="Search products...">

            <button type="submit">
                Search
            </button>

        </form>

        <!-- =========================================================
             NAVIGATION LINKS
        ========================================================= -->

        <a href="products.php">
            Shop All
        </a>

        <a href="products.php?category=Women">
            Women
        </a>

        <a href="products.php?category=Men">
            Men
        </a>

        <a href="products.php?category=Children">
            Kids
        </a>

        <?php if (isset($_SESSION["CustomerID"])): ?>

            <a href="menu.php">
                My Account
            </a>

            <a href="edit_profile.php">
                Edit Profile
            </a>

            <a href="cart.php">
                Cart
            </a>

            <a href="logout.php">
                Logout
            </a>

        <?php else: ?>

            <a href="login.php">
                Customer Login
            </a>

            <a href="customer_manager_login.php">
                Manager Login
            </a>

            <a href="register.php">
                Sign Up
                
            </a>

        <?php endif; ?>

    </div>

</header>

<!-- =========================================================
     FASHION IMAGE GALLERY
========================================================= -->

<section class="fashion-gallery">

    <!-- Main Fashion Banner -->

    <img src="images/summerimage.avif" alt="Fashion">

    <a href="products.php" class="shop-now-overlay">
        Shop Now
    </a>

    <!-- Fashion Collection Image -->

    <img src="images/img3.avif" alt="Fashion">

    <!-- Summer Collection Image -->

    <img src="images/summerimage1.avif" alt="Fashion">

    <!-- Two Side-by-Side Fashion Images -->

    <div class="fashion-row">

        <img src="images/img2.avif" alt="Fashion">

        <img src="images/img1.avif" alt="Fashion">

    </div>

</section>

<!-- =========================================================
     WEBSITE FOOTER
========================================================= -->

<footer class="home-footer">

    <!-- Help Section -->

    <div class="footer-column">

        <h3>Help</h3>

        <a href="#">Customer Service</a>

        <a href="#">Terms & Conditions</a>

        <a href="#">Contact Us</a>

        <a href="#">Cookie Settings</a>

    </div>

    <!-- Membership Section -->

    <div class="footer-column">

        <h3>Become a Member</h3>

        <p>
            Join AYS today and get amazing
            rewards, exclusive offers.
        </p>

        <!-- Join Button -->

        <a href="register.php" class="join-btn">
            Join Now
        </a>

        <!-- Copyright -->

        <p class="footer-copy">
            © 2026 AYS Clothing Store
        </p>

    </div>

    <!-- Help Center Section -->

    <div class="footer-column">

        <h3>Do You Need Help?</h3>

        <p>
            We are here 24/7 to assist you.
        </p>

        <a href="#">
            Visit Help Center
        </a>

    </div>

</footer>

</body>

</html>
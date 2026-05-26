<?php 
// database connection
include 'db.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>AYS Clothing Store</title>

    <!-- css file -->
    <link rel="stylesheet" href="css/style.css">

    <style>

        /* body */
        body {
            margin: 0;
            font-family: Arial;
            background: white;
        }

        /* navbar */
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            border-bottom: 1px solid #eee;
            gap: 20px;
        }

        /* brand */
        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1;
        }

        .logo {
            height: 40px;
            width: auto;
        }

        .brand h1 {
            margin: 5px 0 0 0;
            font-size: 18px;
        }

        /* search */
        .search-box {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .search-box input {
            padding: 7px 12px;
            border: 1px solid #ccc;
            border-radius: 20px;
            width: 220px;
        }

        /* links */
        .nav a {
            margin: 0 10px;
            text-decoration: none;
            color: black;
            font-size: 14px;
        }

        /* hero */
        .hero {
            position: relative;
        }

        .hero img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* button */
        .shop-btn {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 25px;
            background: black;
            color: white;
            text-decoration: none;
            font-size: 14px;
            border-radius: 5px;
        }

        .shop-btn:hover {
            background: #333;
        }

        /* images */
        .images {
            width: 100%;
        }

        .image-box img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* two images */
        .two-images {
            display: flex;
            gap: 10px;
        }

        .left-img,
        .right-img {
            width: 50%;
        }

        .left-img img,
        .right-img img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* footer */
        .footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eee;
            margin-top: 40px;
            font-size: 12px;
        }

        /* footer box */
        .footer-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-box {
            width: 200px;
        }

        /* button */
        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: black;
            color: white;
            text-decoration: none;
        }

    </style>
</head>

<body>

<!-- navbar -->
<div class="nav">

    <!-- logo -->
    <div class="brand">
        <img src="images/AYS logo.png" class="logo">
        <h1>𝓐𝓨𝓢</h1>
    </div>

    <!-- search -->
    <div class="search-box">
        <form action="search_product.php" method="GET">
            <input type="text" name="query" placeholder="Search products...">
        </form>
    </div>

    <!-- menu -->
    <div>
        <a href="list_product.php">Shop All</a>
        <a href="women.php">Women</a>
        <a href="men.php">Men</a>
        <a href="kids.php">Kids</a>
        <a href="login.php">Login</a>
    </div>

</div>

<!-- hero -->
<div class="hero">
    <img src="images/summerimage.avif" alt="AYS Banner">
    
</div>

<!-- images -->
<div class="images">

    <div class="image-box">
        <img src="images/img3.avif">
    </div>

    <div class="image-box">
        <img src="images/summerimage1.avif">
    </div>

    <div class="two-images">

        <div class="left-img">
            <img src="images/img2.avif">
        </div>

        <div class="right-img">
            <img src="images/img1.avif">
        </div>

    </div>

</div>

<!-- footer -->
<div class="footer">

    <div class="footer-container">

        <div class="footer-box">
            <h3>Help</h3>
            <a href="#">Customer Service</a><br><br>
            <a href="#">Terms & Conditions</a><br><br>
            <a href="#">Contact Us</a><br><br>
            <a href="#">Cookie Settings</a>
        </div>

        <div class="footer-box">
            <h3>Become a Member</h3>
            <p>Join AYS today and get rewards.</p>
            <a href="#" class="btn">Join Now</a>
        </div>

        <div class="footer-box">
            <h3>Need Help?</h3>
            <p>We are available 24/7</p>
            <a href="#">Help Center</a>
        </div>

    </div>

    <br>

    <div>
        © 2026 AYS Clothing Store
    </div>

</div>

</body>
</html>
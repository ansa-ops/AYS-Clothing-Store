<?php include 'db.php'; ?> <!-- Database connection -->

<!DOCTYPE html>
<html>
<head>

    <title>Women - AYS Clothing Store</title> <!-- Page title -->

    <link rel="stylesheet" href="css/style.css"> <!-- External CSS -->

    <style>

        /* Navigation bar */
        .nav {
            display:flex;
            justify-content:space-between;
            padding:15px 30px;
            border-bottom:1px solid #eee;
        }

        /* Nav links */
        .nav a {
            margin:0 10px;
            text-decoration:none;
            color:black;
        }

        /* Hero banner */
        .hero img {
            width:100%;
            height:400px;
            object-fit:cover;
        }

        /* Product grid */
        .grid {
            width:90%;
            margin:30px auto;
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:20px;
        }

        /* Product card */
        .card {
            border:1px solid #eee;
            padding:10px;
            text-align:center;
            border-radius:10px;
        }

        /* Product image */
        .card img {
            width:100%;
            height:200px;
            object-fit:cover;
            border-radius:8px;
        }

    </style>
</head>

<body>

<!-- NAVIGATION -->
<div class="nav">

    <div><b>AYS</b></div> <!-- Brand -->

    <div>
        <a href="index.php">Home</a>
        <a href="men.php">Men</a>
        <a href="kids.php">Kids</a>
        <a href="login.php">Login</a>
    </div>

</div>

<!-- HERO SECTION -->
<div class="hero">
    <img src="images/women banner.jpg">
</div>

<!-- PRODUCT GRID -->
<div class="grid">

<?php

// Fetch women products from database
$result = mysqli_query($conn, "SELECT * FROM products WHERE Category='Women'");

$hasData = false; // check if products exist

// Image mapping for products
$images = [

    "Floral Summer Top" => "images/floral summer top.webp",
    "High Waist Jeans" => "images/high weist jeans.webp",
    "Mini Skirt" => "images/mini skirt.webp",
    "Casual T-Shirt" => "images/casual tshirt.webp",
    "Denim Jacket" => "images/denim jacket.webp",
    "Maxi Dress" => "images/maxi dress.webp"

];

// Loop database products
while ($row = mysqli_fetch_assoc($result)) {

    $hasData = true;

    echo "<a href='product.php?id=".$row['ProductID']."' style='text-decoration:none;color:black;'>";
    echo "<div class='card'>";

    // Show product image or fallback
    if (array_key_exists($row['Name'], $images)) {
        echo "<img src='".$images[$row['Name']]."' onerror=\"this.src='images/product.jpg'\">";
    } else {
        echo "<img src='images/product.jpg'>";
    }

    // Product name
    echo "<h3>".$row['Name']."</h3>";

    // Product price
    echo "<p><b>Price:</b> $".$row['Price']."</p>";

    // Availability
    if ($row['IsAvailable'] == 1) {
        echo "<p style='color:green;'><b>Available</b></p>";
    } else {
        echo "<p style='color:red;'><b>Not Available</b></p>";
    }

    echo "</div>";
    echo "</a>";
}

// If no products in database, show fallback products
if (!$hasData) {

    $products = [
        ["Floral Summer Top", 25],
        ["High Waist Jeans", 45],
        ["Mini Skirt", 30],
        ["Casual T-Shirt", 18],
        ["Denim Jacket", 55],
        ["Maxi Dress", 70],
    ];

    foreach ($products as $p) {

        echo "<div class='card'>";

        // Image fallback system
        if (array_key_exists($p[0], $images)) {
            echo "<img src='".$images[$p[0]]."'>";
        } else {
            echo "<img src='images/product.jpg'>";
        }

        echo "<h3>".$p[0]."</h3>";
        echo "<p>$".$p[1]."</p>";

        echo "</div>";
    }
}

?>

</div>

<!-- GO TO HOMEPAGE BUTTON -->
<a href="index.php" style="display:inline-block; text-decoration:none;">
    <button type="button" style="background:black; color:white; border:none;
     padding:10px 20px; border-radius:5px; cursor:pointer;">
        Go to Homepage
    </button>
</a>
</body>
</html>
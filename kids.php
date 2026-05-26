<?php 
// Include database connection
include 'db.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kids - AYS Clothing Store</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>

        /* Navbar */
        .nav {
            display:flex;
            justify-content:space-between;
            padding:15px 30px;
            border-bottom:1px solid #eee;
        }

        /* Navbar links */
        .nav a {
            margin:0 10px;
            text-decoration:none;
            color:black;
        }

        /* Hero image */
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

<!-- NAVBAR -->
<div class="nav">

    <!-- Brand -->
    <div><b>AYS</b></div>

    <!-- Menu links -->
    <div>
        <a href="index.php">Home</a>
        <a href="women.php">Women</a>
        <a href="men.php">Men</a>
        <a href="login.php">Login</a>
    </div>

</div>

<!-- HERO BANNER -->
<div class="hero">
    <img src="images/kids banner.avif">
</div>

<!-- PRODUCT GRID -->
<div class="grid">

<?php
// Get all Kids products from database
$result = mysqli_query($conn, "SELECT * FROM products WHERE Category='Kids'");

$hasData = false;

// Image mapping for products
$images = [
    "Boys Denim Jeans" => "images/kids denim jeans.webp",
    "Girls Floral Dress" => "images/kids floral dress.webp",
    "Kids Hoodie" => "images/kids hoddiee.webp",
    "Baby Romper Set" => "images/kids romper set.webp",
    "Kids Jacket" => "images/kids jacket.webp",
    "Warm Winter Pajamas" => "images/kids winter pajama.webp"
];

// Loop database products
while ($row = mysqli_fetch_assoc($result)) {

    $hasData = true;

    // Clean product name
    $name = trim($row['Name']);

    // Product link
    echo "<a href='product.php?id=".$row['ProductID']."' style='text-decoration:none;color:black;'>";
    echo "<div class='card'>";

    // Show product image (with fallback)
    if (array_key_exists($name, $images)) {
        echo "<img src='".$images[$name]."' onerror=\"this.src='images/product.jpg'\">";
    } else {
        echo "<img src='images/product.jpg'>";
    }

    // Product name
    echo "<h3>".$row['Name']."</h3>";

    // Product price
    echo "<p><b>Price:</b> $".$row['Price']."</p>";

    // Availability status
    if ($row['IsAvailable'] == 1) {
        echo "<p style='color:green;'><b>Available</b></p>";
    } else {
        echo "<p style='color:red;'><b>Not Available</b></p>";
    }

    echo "</div>";
    echo "</a>";
}

// If no database products exist
if (!$hasData) {

    // Default product list
    $products = [
        ["Boys Denim Jeans", 25],
        ["Girls Floral Dress", 30],
        ["Kids Hoodie", 28],
        ["Baby Romper Set", 22],
        ["Kids Jacket", 40],
        ["Warm Winter Pajamas", 27],
    ];

    foreach ($products as $p) {

        echo "<div class='card'>";

        // Show image or fallback
        if (array_key_exists($p[0], $images)) {
            echo "<img src='".$images[$p[0]]."'>";
        } else {
            echo "<img src='images/product.jpg'>";
        }

        // Product name
        echo "<h3>".$p[0]."</h3>";

        // Price
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
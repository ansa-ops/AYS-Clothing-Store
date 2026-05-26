<?php 
// Include database connection
include 'db.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Men - AYS Clothing Store</title>

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
        <a href="kids.php">Kids</a>
        <a href="login.php">Login</a>
    </div>

</div>

<!-- HERO SECTION -->
<div class="hero">
    <img src="images/men banner.jpg">
</div>

<!-- PRODUCT GRID -->
<div class="grid">

<?php
// Get all Men products
$result = mysqli_query($conn, "SELECT * FROM products WHERE Category='Men'");

$hasData = false;

// Image mapping
$images = [
    "Casual T-Shirt" => "images/men tshirt.webp",
    "Denim Jacket" => "images/men denim jacket.webp",
    "Jeans Pants" => "images/men jeans pant.webp",
    "Leather Jacket" => "images/men leather jacket.webp",
    "Jogger Pants" => "images/men joggers pant.webp",
    "Formal Shirt" => "images/Men shirt.webp"
];

// Loop database products
while ($row = mysqli_fetch_assoc($result)) {

    $hasData = true;

    // Product link
    echo "<a href='product.php?id=".$row['ProductID']."' style='text-decoration:none;color:black;'>";
    echo "<div class='card'>";

    // Product image with fallback
    if (array_key_exists($row['Name'], $images)) {
        echo "<img src='".$images[$row['Name']]."' onerror=\"this.src='images/product.jpg'\">";
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

    // Default products
    $products = [
        ["Casual T-Shirt", 20],
        ["Denim Jacket", 65],
        ["Jeans Pants", 55],
        ["Leather Jacket", 110],
        ["Jogger Pants", 40],
        ["Formal Shirt", 38]
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
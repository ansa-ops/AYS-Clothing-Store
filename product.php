<?php 
// Include database connection
include 'db.php'; 

// Get product ID safely from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product from database
$result = mysqli_query($conn, "SELECT * FROM products WHERE ProductID=$id");
$product = mysqli_fetch_assoc($result);

// If product not found, stop page
if (!$product) {
    echo "<h2 style='text-align:center;margin-top:50px;'>Product not found</h2>";
    exit;
}

// Image mapping for products
$images = [
    "Floral Summer Top" => "images/floral summer top.webp",
    "High Waist Jeans" => "images/high weist jeans.webp",
    "Mini Skirt" => "images/mini skirt.webp",
    "Casual T-Shirt" => "images/casual tshirt.webp",
    "Denim Jacket" => "images/denim jacket.webp",
    "Maxi Dress" => "images/maxi dress.webp",

    "Casual T-Shirt" => "images/men tshirt.webp",
    "Denim Jacket" => "images/men denim jacket.webp",
    "Jeans Pants" => "images/men jeans pant.webp",
    "Leather Jacket" => "images/men leather jacket.webp",
    "Jogger Pants" => "images/men joggers pant.webp",
    "Formal Shirt" => "images/Men shirt.webp",

    "Boys Denim Jeans" => "images/kids denim jeans.webp",
    "Girls Floral Dress" => "images/kids floral dress.webp",
    "Kids Hoodie" => "images/kids hoddiee.webp",
    "Baby Romper Set" => "images/kids romper set.webp",
    "Kids Jacket" => "images/kids jacket.webp",
    "Warm Winter Pajamas" => "images/kids winter pajama.webp"
];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['Name']; ?> - AYS Store</title>

    <style>

        /* Page styling */
        body {
            font-family: Arial;
            margin: 0;
            background: #f8f8f8;
        }

        /* Main layout container */
        .container {
            width: 80%;
            margin: 40px auto;
            display: flex;
            gap: 40px;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        /* Product image */
        .container img {
            width: 400px;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Right side info section */
        .info {
            flex: 1;
        }

        /* Product title */
        h2 {
            margin-bottom: 10px;
        }

        /* Price styling */
        .price {
            font-size: 24px;
            color: green;
            margin: 10px 0;
        }

        /* Info blocks */
        .box {
            margin: 10px 0;
        }

        /* Button style */
        .btn {
            background: black;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-top: 15px;
            border-radius: 5px;
        }

        /* Button hover */
        .btn:hover {
            background: #333;
        }

    </style>
</head>

<body>

<!-- PRODUCT SECTION -->
<div class="container">

    <!-- Product image -->
    <div>
        <?php
        // Show product image if exists in mapping
        if (array_key_exists($product['Name'], $images)) {
            echo "<img src='".$images[$product['Name']]."'>";
        } else {
            // Default image fallback
            echo "<img src='images/product.jpg'>";
        }
        ?>
    </div>

    <!-- Product details -->
    <div class="info">

        <!-- Product name -->
        <h2><?php echo $product['Name']; ?></h2>

        <!-- Product price -->
        <div class="price">
            $<?php echo $product['Price']; ?>
        </div>

        <!-- Description section -->
        <div class="box">
            <b>Description:</b><br>
            <?php echo $product['Description']; ?>
            <br><br>

            <b>Material Guide / Versatility:</b><br>
            • Wool and high-twist cotton/crepe fabrics work across seasons.<br>
            • Cotton is a reliable, soft, and durable option for all body types.<br>
            • Cotton jersey or viscose blends are highly recommended for comfort.<br><br>

            <b>Care:</b> Machine Washable
        </div>

        <!-- Category -->
        <div class="box">
            <b>Category:</b> <?php echo $product['Category']; ?>
        </div>

        <!-- Stock -->
        <div class="box">
            <b>Stock:</b> <?php echo $product['Stock']; ?>
        </div>

        <!-- Extra static info -->
        <div class="box">
            <b>Colors:</b> Red, Blue, Black
        </div>

        <div class="box">
            <b>Sizes:</b> S, M, L, XL
        </div>

        <!-- Availability check -->
        <?php if ($product['IsAvailable'] == 1) { ?>
            <p style="color:green;"><b>Available</b></p>
            <button class="btn">Add to Cart</button>
        <?php } else { ?>
            <p style="color:red;"><b>Not Available</b></p>
        <?php } ?>

    </div>

</div>

</body>
</html>
<?php 
// Include database connection
include 'db.php'; 

$results = [];
$search = "";

// Check if search query exists
if (isset($_GET['query'])) {

    // Get search input safely
    $search = trim($_GET['query']);
    $search_safe = mysqli_real_escape_string($conn, $search);

    // Search products by name, category, or description
    $sql = "SELECT * FROM products 
            WHERE Name LIKE '%$search_safe%' 
            OR Category LIKE '%$search_safe%' 
            OR Description LIKE '%$search_safe%'";

    $result = mysqli_query($conn, $sql);

    // Store results in array
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>

    <style>

        /* Page styling */
        body {
            font-family: Arial;
            padding: 20px;
            background: #f8f8f8;
        }

        /* Heading */
        h2 {
            margin-bottom: 30px;
        }

        /* Grid layout */
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Product card */
        .product {
            background: white;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            transition: 0.3s;
        }

        /* Hover effect */
        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Product image */
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            background: white;
        }

        /* Product title */
        .product h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        /* Text */
        .product p {
            margin: 8px 0;
            font-size: 14px;
        }

        /* Availability label */
        .status {
            font-weight: bold;
        }

        .available {
            color: green;
        }

        .unavailable {
            color: red;
        }

        /* Back button */
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            background: black;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

    </style>
</head>

<body>

<!-- Back to home button -->
<a href="index.php" class="back-btn">← Back to Home</a>

<!-- Search title -->
<h2>
    Search Results for:
    "<?php echo htmlspecialchars($search); ?>"
</h2>

<?php if (count($results) > 0): ?>

    <!-- Product grid -->
    <div class="products-container">

        <?php

        // Image mapping (same system as category pages)
        $images = [

            // WOMEN
            "Floral Summer Top" => "images/floral summer top.webp",
            "High Waist Jeans" => "images/high weist jeans.webp",
            "Mini Skirt" => "images/mini skirt.webp",
            "Casual T-Shirt" => "images/casual tshirt.webp",
            "Denim Jacket" => "images/denim jacket.webp",
            "Maxi Dress" => "images/maxi dress.webp",

            // MEN
            "Casual T-Shirt" => "images/men tshirt.webp",
            "Denim Jacket" => "images/men denim jacket.webp",
            "Jeans Pants" => "images/men jeans pant.webp",
            "Leather Jacket" => "images/men leather jacket.webp",
            "Jogger Pants" => "images/men joggers pant.webp",
            "Formal Shirt" => "images/Men shirt.webp",

            // KIDS
            "Boys Denim Jeans" => "images/kids denim jeans.webp",
            "Girls Floral Dress" => "images/kids floral dress.webp",
            "Kids Hoodie" => "images/kids hoddiee.webp",
            "Baby Romper Set" => "images/kids romper set.webp",
            "Kids Jacket" => "images/kids jacket.webp",
            "Warm Winter Pajamas" => "images/kids winter pajama.webp"
        ];

        ?>

        <?php foreach ($results as $product): ?>

            <div class="product">

                <?php
                    // Clean product name
                    $name = trim($product['Name']);

                    // Get image or fallback
                    $img = $images[$name] ?? 'images/product.jpg';
                ?>

                <!-- Product image -->
                <img src="<?php echo $img; ?>" 
                     alt="<?php echo htmlspecialchars($name); ?>"
                     class="product-image"
                     onerror="this.src='images/product.jpg'">

                <!-- Product name -->
                <h3><?php echo htmlspecialchars($product['Name']); ?></h3>

                <!-- Category -->
                <p><b>Category:</b> <?php echo htmlspecialchars($product['Category']); ?></p>

                <!-- Price -->
                <p><b>Price:</b> <?php echo htmlspecialchars($product['Price']); ?> DKK</p>

                <!-- Stock -->
                <p><b>Stock:</b> <?php echo htmlspecialchars($product['Stock']); ?></p>

                <!-- Description -->
                <p><b>Description:</b><br><?php echo htmlspecialchars($product['Description']); ?></p>

                <!-- Availability -->
                <p class="status">
                    <?php if ($product['IsAvailable'] == 1): ?>
                        <span class="available">Available</span>
                    <?php else: ?>
                        <span class="unavailable">Out of Stock</span>
                    <?php endif; ?>
                </p>

            </div>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <!-- No results message -->
    <p>No products found.</p>

<?php endif; ?>

</body>
</html>
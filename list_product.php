<?php
// Include database connection file
include 'db.php';

/* DEFAULT QUERY */
$query = "SELECT * FROM products WHERE 1";

/* SEARCH (BY NAME ONLY) */
if (isset($_GET['search']) && $_GET['search'] != "") {

    $search = $_GET['search'];

    $query .= " AND Name LIKE '%$search%'";
}

/* FILTER PRODUCTS (CATEGORY) */
if (isset($_GET['category']) && $_GET['category'] != "") {

    $cat = $_GET['category'];

    $query .= " AND Category='$cat'";
}

/* EXECUTE QUERY */
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Product List</title>

    <style>

        body {
            font-family: Arial;
            margin: 0;
            padding: 20px;
            background-image: url('images/summerimage1.avif');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

        h2 {
            text-align: center;
            color: white;
        }

        /* TOP BAR (SEARCH + FILTER) */
        .top-bar {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        form {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            width: 250px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .filter-select {
            width: 250px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #333;
        }

        /* PRODUCT BOX */
        .product-box {
            background: white;
            padding: 15px;
            margin: 10px auto;
            width: 60%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-name {
            font-weight: bold;
            font-size: 18px;
        }

        .price {
            color: green;
            margin-left: 10px;
            font-weight: bold;
        }

        .category {
            display: block;
            margin-top: 5px;
            color: gray;
            font-size: 14px;
        }

    </style>
</head>

<body>

<h2>Product List</h2>

<!-- SEARCH + FILTER -->
<div class="top-bar">

    <!-- SEARCH BY NAME -->
    <form method="GET">
        <input type="text" name="search" placeholder="Search product name">

        <!-- keep category while searching -->
        <?php if(isset($_GET['category'])) { ?>
            <input type="hidden" name="category" value="<?= $_GET['category']; ?>">
        <?php } ?>

        <button type="submit">Search</button>
    </form>

    <!-- FILTER BY CATEGORY -->
    <form method="GET">

        <select name="category" class="filter-select">

            <option value="">All Categories</option>

            <option value="Women" <?= (isset($_GET['category']) && $_GET['category']=="Women") ? 'selected' : ''; ?>>
                Women
            </option>

            <option value="Men" <?= (isset($_GET['category']) && $_GET['category']=="Men") ? 'selected' : ''; ?>>
                Men
            </option>

            <option value="Kids" <?= (isset($_GET['category']) && $_GET['category']=="Kids") ? 'selected' : ''; ?>>
                Kids
            </option>

        </select>

        <!-- keep search while filtering -->
        <?php if(isset($_GET['search'])) { ?>
            <input type="hidden" name="search" value="<?= $_GET['search']; ?>">
        <?php } ?>

        <button type="submit">Filter</button>

    </form>

</div>

<br>

<!-- PRODUCTS -->
<?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <div class="product-box">

        <div>

            <span class="product-name">
                <?= $row['Name']; ?>
            </span>

            <span class="price">
                $<?= $row['Price']; ?>
            </span>

            <span class="category">
                Category: <?= $row['Category']; ?>
            </span>

        </div>

    </div>

<?php } ?>

<!-- HOME BUTTON -->
<div style="text-align:center; margin-top:30px;">
    <a href="index.php">
        <button type="button">Go to Homepage</button>
    </a>
</div>

</body>
</html>
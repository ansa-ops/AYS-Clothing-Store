<?php
session_start();

// Presentation Layer
// This page handles the checkout process.
// It displays cart items, calculates membership discount,
// validates delivery details and saves the order into the database.

require_once "../config/Database.php";
require_once "../classes/Customer.php";

// Create database connection.
$db = new Database();
$conn = $db->connect();

// Customer object is used if a new customer needs to be created during checkout.
$customerObj = new Customer($conn);

// Get cart items from the session.
$cart = $_SESSION["cart"] ?? [];

// Calculate subtotal from all cart items.
$subtotal = 0;

foreach ($cart as $item) {
    $subtotal += $item["Price"] * $item["Quantity"];
}

// Get membership discount from session.
// If customer is not logged in, Bronze discount is used by default.
$membershipType = $_SESSION["MembershipType"] ?? "Bronze";
$discountRate = $_SESSION["DiscountRate"] ?? 5;

// Calculate discount and final total.
$discountAmount = ($subtotal * $discountRate) / 100;
$finalTotal = $subtotal - $discountAmount;

$errors = [];
$success = "";

// This code runs when the checkout form is submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect delivery details from the form.
    $firstName = trim($_POST["firstName"] ?? "");
    $lastName = trim($_POST["lastName"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $paymentMethod = trim($_POST["paymentMethod"] ?? "");

    $fullName = trim($firstName . " " . $lastName);

    // Validate delivery form fields.
    if ($firstName == "") {
        $errors[] = "First name is required.";
    }

    if ($lastName == "") {
        $errors[] = "Last name is required.";
    }

    if ($email == "") {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($phone == "") {
        $errors[] = "Phone number is required.";
    }

    if ($address == "") {
        $errors[] = "Delivery address is required.";
    }

    if ($paymentMethod == "") {
        $errors[] = "Payment method is required.";
    }

    if (empty($cart)) {
        $errors[] = "Cart is empty.";
    }

    // If there are no validation errors, save the order.
    if (count($errors) == 0) {

        // If the customer is logged in, use their CustomerID.
        $customerID = $_SESSION["CustomerID"] ?? null;

        // If customer is not logged in, create/find a basic customer record.
        if (!$customerID) {

            try {
                $customerObj->addCustomer(
                    $fullName,
                    $email,
                    "password",
                    $phone,
                    1,
                    "Bronze"
                );
            } catch (Exception $e) {
                // If the email already exists, the customer can still be found below.
            }

            $findCustomer = $customerObj->findCustomer($email);

            $customerID = $findCustomer[0]["CustomerID"] ?? 1;
        }

        // Insert main order record.
        $stmt = $conn->prepare("
            INSERT INTO Orders
            (
                CustomerID,
                OrderDate,
                PaymentMethod,
                Status,
                SubTotal,
                DiscountRate,
                FinalTotal
            )
            VALUES
            (?, NOW(), ?, 'Pending', ?, ?, ?)
        ");

        $stmt->execute([
            $customerID,
            $paymentMethod,
            $subtotal,
            $discountRate,
            $finalTotal
        ]);

        $orderID = $conn->lastInsertId();

        // Insert each cart product into OrderDetail.
        foreach ($cart as $item) {

            $stmtDetail = $conn->prepare("
                INSERT INTO OrderDetail
                (
                    OrderID,
                    ProductID,
                    Quantity,
                    SelectedSize,
                    Price
                )
                VALUES
                (?, ?, ?, ?, ?)
            ");

            $stmtDetail->execute([
                $orderID,
                $item["ProductID"],
                $item["Quantity"],
                $item["Size"],
                $item["Price"]
            ]);
        }

        $success =
            "Order placed successfully for " .
            $fullName .
            ". Final total: £" .
            number_format($finalTotal, 2);

        // Empty the cart after successful order.
        $_SESSION["cart"] = [];
        $cart = [];
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Checkout - AYS Clothing</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background: #f6f1ec;
        }

        .checkout-wrapper {
            width: 92%;
            max-width: 1200px;
            margin: 45px auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 30px;
        }

        .checkout-section {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .checkout-section h2 {
            margin-bottom: 20px;
            color: #2d2019;
        }

        .order-item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            align-items: center;
            border-bottom: 1px solid #e5e5e5;
            padding: 18px 0;
        }

        .order-item img {
            width: 100px;
            height: 110px;
            object-fit: cover;
            border-radius: 12px;
        }

        .summary-box {
            background: #faf6f1;
            margin-top: 25px;
            padding: 20px;
            border-radius: 15px;
            border-left: 6px solid #6b4f3f;
        }

        .summary-box p {
            margin: 10px 0;
            font-size: 17px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-grid .full {
            grid-column: 1 / 3;
        }

        textarea {
            width: 100%;
            min-height: 100px;
            padding: 14px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            font-family: Arial, sans-serif;
        }

        .continue-btn {
            width: 100%;
            margin-top: 20px;
            padding: 15px;
            background: #2d2019;
            color: white;
            font-size: 17px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .continue-btn:hover {
            background: #5a4031;
        }

        @media(max-width: 850px) {
            .checkout-wrapper {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-grid .full {
                grid-column: 1;
            }
        }
    </style>
</head>

<body>

<header class="navbar">

    <div class="brand">
        <img src="images/AYS logo.png" alt="AYS Logo">
        <span>AYS Clothing</span>
    </div>

    <div class="nav-links">
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>

        <?php if (isset($_SESSION["CustomerID"])): ?>
            <a href="menu.php">My Account</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>

</header>

<div class="checkout-wrapper">

    <div class="checkout-section">

        <h2>1. Product Details</h2>

        <?php if (empty($cart)): ?>

            <p>Your cart is empty.</p>

            <br>

            <a href="products.php" class="btn">
                Continue Shopping
            </a>

        <?php else: ?>

            <?php foreach ($cart as $item): ?>

                <?php $itemTotal = $item["Price"] * $item["Quantity"]; ?>

                <div class="order-item">

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

                    <strong>
                        £<?= number_format($itemTotal, 2) ?>
                    </strong>

                </div>

            <?php endforeach; ?>

            <div class="summary-box">

                <p>
                    <strong>Membership:</strong>
                    <?= htmlspecialchars($membershipType) ?>
                </p>

                <p>
                    <strong>Discount:</strong>
                    <?= htmlspecialchars($discountRate) ?>%
                </p>

                <p>
                    <strong>Subtotal:</strong>
                    £<?= number_format($subtotal, 2) ?>
                </p>

                <p>
                    <strong>Discount Amount:</strong>
                    £<?= number_format($discountAmount, 2) ?>
                </p>

                <p>
                    <strong>Final Total:</strong>
                    £<?= number_format($finalTotal, 2) ?>
                </p>

            </div>

        <?php endif; ?>

    </div>

    <div class="checkout-section">

        <h2>2. Your Information</h2>

        <h3>Delivery Details</h3>

        <?php foreach ($errors as $error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endforeach; ?>

        <?php if ($success): ?>
            <div class="success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">

            <div class="form-grid">

                <input
                    name="firstName"
                    placeholder="First name"
                    value="<?= htmlspecialchars($_POST["firstName"] ?? "") ?>">

                <input
                    name="lastName"
                    placeholder="Last name"
                    value="<?= htmlspecialchars($_POST["lastName"] ?? "") ?>">

                <input
                    class="full"
                    type="email"
                    name="email"
                    placeholder="Email"
                    autocomplete="off"
                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">

                <input
                    class="full"
                    name="phone"
                    placeholder="Phone number"
                    autocomplete="off"
                    value="<?= htmlspecialchars($_POST["phone"] ?? "") ?>">

                <textarea
                    class="full"
                    name="address"
                    placeholder="Delivery address"><?= htmlspecialchars($_POST["address"] ?? "") ?></textarea>

                <select class="full" name="paymentMethod" required>
                    <option value="">Select payment method</option>
                    <option value="Card">Card</option>
                    <option value="Online Banking">Online Banking</option>
                </select>

            </div>

            <button class="continue-btn" type="submit">
                Place Order
            </button>

        </form>

    </div>

</div>

</body>

</html>
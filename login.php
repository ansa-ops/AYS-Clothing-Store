<?php
session_start();
include 'db.php';

$error = "";

// Login check
if(isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple hardcoded login
    if($username == "ays123" && $password == "aysproduct123") {

        $_SESSION['admin'] = true;
        header("location: view_products.php");
        exit();

    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Manager Login - AYS Store</title>

    <style>

        /* Page background */
        body {
            margin: 0;
            font-family: Arial;

            background-image: url('images/summerimage1.avif');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;

            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Center login area */
        .center-area {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Login box */
        .login-box {
            width: 350px;
            background: rgba(255,255,255,0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            text-align: center;
            backdrop-filter: blur(6px);
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 95%;
            padding: 10px;
            background: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #333;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        /* Bottom button */
        .bottom-btn {
            text-align: center;
            padding: 20px;
        }

        .bottom-btn button {
            width: 200px;
        }

    </style>
</head>

<body>

<!-- Login section -->
<div class="center-area">

    <div class="login-box">

        <div class="title">AYS Product Manager Login</div>

        <!-- Error message -->
        <?php if($error != "") { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Login form -->
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

    </div>

</div>

<!-- Homepage button -->
<div class="bottom-btn">
    <a href="index.php">
        <button type="button">Go to Homepage</button>
    </a>
</div>

</body>
</html>
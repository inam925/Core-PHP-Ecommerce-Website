<?php
session_start();

if (isset($_GET['source']) && $_GET['source'] === 'buyer' && $_SESSION["loggedin"] === false) {
    header("Location:buyer.php?msg=loginError");
    exit;
}
if (isset($_GET['source']) && $_GET['source'] === 'cart' && $_SESSION["loggedin"] === false) {
    header("Location:cart.php?msg=loginError");
    exit;
}

if ($_SESSION["loggedin"] === false) {
    header("Location:cart.php?msg=loginError");
    exit;
}
if (!isset($_SESSION['cart_item'])) {
    if ((isset($_SESSION['total_price']) && $_SESSION['total_price'] == 0) || !isset($_SESSION['total_price'])) {
        if (isset($_GET['source']) && $_GET['source'] === 'cart') {
            header("Location:cart.php?msg=selectProduct");
        }
        if (isset($_GET['source']) && $_GET['source'] === 'buyer') {
            header("Location:buyer.php?msg=selectProduct");
        }
    }
}

// Include database file
require_once 'Database.php';
require_once 'Orders.php';
require_once 'Functions.php';

// Create an instance of the Database class
$dbVar = new Database();

require_once 'Users.php';
$user = new Functions($dbVar);
$currentUser = $user->displayRecordById("users", $_SESSION['id']);

// Pass the database instance to the Orders class
$order = new Orders($dbVar);
// Insert Record in orders table
if (isset($_POST['submit'])) {
    $order->insertOrderData($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <title>ShopStore</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>
    <div class="body">
        <div class="page">
            <form action="checkout.php" method="POST">
                <h1>
                    <i class="fas fa-shipping-fast"></i>
                    Shipping Details
                </h1>
                <div class="name">
                    <div>
                        <label for="f-name">First</label>
                        <input type="text" name="f-name" required>
                    </div>
                    <div>
                        <label for="l-name">Last</label>
                        <input type="text" name="l-name" required>
                    </div>
                </div>
                <div class="name">
                    <div>
                        <label for="email">Email</label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="street">
                    <label for="name">Street</label>
                    <input type="text" name="address" required>
                </div>
                <div class="address-info">
                    <div>
                        <label for="city">City</label>
                        <input type="text" name="city" required>
                    </div>
                    <div>
                        <label for="state">State</label>
                        <input type="text" name="state" required>
                    </div>
                    <div>
                        <label for="zip">Zip</label>
                        <input type="text" name="zip" required>
                    </div>
                </div>
                <h1>
                    <i class="far fa-credit-card"></i> Payment Information
                </h1>
                <div class="cards">
                    <label>Accepted Cards</label>
                    <span>
                        <i class="fa-brands fa-cc-visa"></i>
                        <i class="fa-brands fa-cc-amex"></i>
                        <i class="card-logo fa-brands fa-cc-mastercard"></i>
                        <i class="card-logo fa-brands fa-cc-discover"></i>
                    </span>
                </div>
                <div class="cc-number">
                    <label for="card-num">Credit Card No.</label>
                    <input type="tel" minlength="16" maxlength="16" name="card" placeholder="0000 0000 0000 0000" required>
                </div>
                <div class="cc-info">
                    <div>
                        <label for="expiry">Exp</label>
                        <input name="expiry" id="expiry" maxlength="5" type="tel" required placeholder="00/00">
                    </div>
                    <div>
                        <label for="cvc">CVC</label>
                        <input type="tel" minlength="3" maxlength="3" id="cvc" name="cvc" placeholder="XXX" required>
                    </div>
                </div>
                <div class="cards">
                    <label for="">Total Amount</label>
                    <?php if (isset($_SESSION['total_price'])) : ?>
                        <span>$<?= $_SESSION['total_price'] ?></span>
                        <input type="hidden" name="amount" value="<?= $_SESSION['total_price'] ?>" />
                        <input type="hidden" name="items" value="<?= implode(',', $_SESSION['itemsBought']) ?>" />
                        <input type="hidden" name="quantities" value="<?= implode(',', $_SESSION["itemsQuantity"]) ?>" />
                    <?php endif ?>

                    <input type="hidden" name="id" value="<?= $currentUser['id'] ?>">
                </div>
                <div class="buttons">
                    <button type="submit" name="submit">Purchase</button>
                    <button type="button"><a href="cart.php">Back to cart</a></button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>
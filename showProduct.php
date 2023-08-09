<?php
session_start();

// Include database file
require_once 'Database.php';
require_once 'Functions.php';

// Create an instance of the Database class
$db = new Database();

$function = new Functions($db);
// Show product record
$id = $_GET['id'];
$table = 'products';
$product = $function->displayRecordById($table, $id);

if (isset($_POST['submitt'])) {
    $id = $_GET['id'];
    $product = $function->displayRecordById($table, $id);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>ShopStore</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/show.css">
</head>

<body>
    <div class="main">
        <nav>
            <a href="buyer.php" class="nav-a">Products</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1) : ?>
                <a href="shop.php" class="nav-a">Admin Panel</a>
            <?php endif ?>
            <a href="cart.php" class="nav-a">Cart</a>
            <a href="checkout.php" class="nav-a">Checkout</a>
            <?php if (!$_SESSION["loggedin"] === true) : ?>
                <a href="signin.php" class="nav-a">Login</a>
            <?php endif ?>
            <?php if ($_SESSION["loggedin"] === true) : ?>
                <a href="myAccount.php" class="nav-a">My Account</a>
                <a href="logout.php" class="nav-a">Sign Out</a>
            <?php endif ?>
        </nav>
        <div class="flash" id=flash>
            <?php
            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
                $function->displayMessage($msg);
            }
            ?>
        </div>
        <h4>Available Product</h4>
        <div class="left">
            <div class="left-img">
                <img src="assets/<?= $product['picture'] ?>" alt="">
            </div>
            <div class="right">
                <span for="name"><?= $product['name'] ?></span>
                <em for="price">$ <?= $product['price'] ?></em>
                <em for="category"><?= $product['category'] ?></em>
                <p><?= $product['description'] ?></p>
                <?php if ($product['quantity'] == 0) { ?>
                    <small>Out of Stock</small>
                <?php } else { ?>
                    <small><?= $product['quantity'] ?> available</small>
                    <form action="cart.php?action=add&id=<?= $product['id']; ?>" method="POST">
                        <a href="#" class="quantity__minus"><span>-</span></a>
                        <input type="number" name="quantity" value="1" min="1" class="quantity__input" max="<?= $product['quantity'] ?>" placeholder="Quantity" id="quantityy" required>
                        <a href="#" class="quantity__plus"><span>+</span></a>
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <input id="namee" class="product-name" type="hidden" value="<?= $product['name'] ?>" />
                        <input id="pricee-1" class="product-price" type="hidden" value="<?= $product['price'] ?>" />
                        <input id="picturee" class="product-picture" type="hidden" value="<?= $product['picture'] ?>" />
                        <input id="totalQuantity" class="product-quantity" type="hidden" value="<?= $product['quantity'] ?>" />
                        <input type="submit" value="Add To Cart" id="cartSubmit" name="submitt">
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/showProduct.js"></script>
    <script src="js/flash.js"></script>
</body>

</html>
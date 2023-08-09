<?php
session_start();
if (!$_SESSION["loggedin"] === true) {
    header("location: signin.php");
    exit;
}
// Include database file
require_once 'Database.php';
require_once 'Functions.php';

// Create an instance of the Database class
$db = new Database();

$function = new Functions($db);
// Show order record
$id = $_GET['id'];
$order = $function->displayRecordById("orders", $id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>ShopStore</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/showOrder.css">
</head>

<body>
    <div class="main">
        <nav>
            <?php if ($_SESSION['role'] === 1) { ?>
                <a href="shop.php" class="nav-a">Products</a>
                <a href="allOrders.php" class="nav-a">Orders</a>
            <?php } else { ?>
                <a href="buyer.php" class="nav-a">Products</a>
                <a href="cart.php" class="nav-a">Cart</a>
                <a href="checkout.php" class="nav-a">Checkout</a>
            <?php } ?>
            <a href="myAccount.php" class="nav-a">My Account</a>
            <a href="logout.php" class="nav-a">Sign Out</a>
        </nav>
        <h4>Order Details</h4>
        <div class="right">
            <div class="left">
                <div>
                    <label>Name</label>
                    <label>Email</label>
                    <label>Amount</label>
                    <label>Address</label>
                </div>
                <div>
                    <span><?= $order['name'] ?></span>
                    <span for="price"><?= $order['email'] ?></span>
                    <span for="category">$ <?= $order['amount'] ?></span>
                    <span><?= $order['address'] ?></span>
                </div>
            </div>
            <?php
            $itemsOnly = [];
            $quantityOnly = [];
            $itemsOnly = array_filter(explode(',', $order['items']), 'is_numeric');
            $quantityOnly = array_filter(explode(',', $order['quantities']), 'is_numeric');
            ?>
            <div class="product">
                <?php
                $i = 0;
                foreach ($itemsOnly as $num) {
                    $product = $function->displayRecordById("products", $num); ?>
                    <main class="product_body">
                        <article class="product_content">
                            <img src="assets/<?= $product['picture'] ?>" alt="pic" class="product_picture">
                            <span class="product_name"><?= $product['name'] ?></span>
                            <span class="product_value"><?= $quantityOnly[$i]; ?>x $<?= $product['price'] ?></span>
                        </article>
                    </main>
                <?php $i++;
                } ?>
            </div>
        </div>
        <?php if (isset($_GET['source'])) { ?>
            <button type="button"><a href="allOrders.php?id=<?= $_SESSION['id'] ?>&source=<?= $_SESSION['role'] ?>">Back to orders</a></button>
        <?php } else { ?>
            <button type="button"><a href="allOrders.php?id=<?= $_SESSION['id'] ?>">Back to orders</a></button>
        <?php } ?>
    </div>
    </div>
</body>

</html>
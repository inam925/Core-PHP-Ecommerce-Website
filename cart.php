<?php
session_start();

// Include database file
require_once 'Database.php';
require_once 'Functions.php';

// Create an instance of the Database class
$db = new Database();

$function = new Functions($db);
$taxes = 24.99;

if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if (!empty($_POST["quantity"])) {
                $id = $_GET['id'];
                $product = $function->displayRecordById("products", $id);
                $itemArray = array(
                    $product["id"] => array(
                        'name' => $product["name"],
                        'id' => $product["id"],
                        'quantity' => $_POST["quantity"],
                        'price' => $product["price"],
                        'picture' => $product["picture"],
                        'totalQuantity' => $product["quantity"]
                    )
                );

                if (!empty($_SESSION["cart_item"])) {
                    $itemUpdated = false;
                    foreach ($_SESSION["cart_item"] as $key => $value) {
                        if ($_GET["id"] === $value['id']) {
                            $newQuantity = $_SESSION["cart_item"][$key]["quantity"] + $_POST["quantity"];
                            if ($newQuantity <= $value['totalQuantity']) { // Check if quantity doesn't exceed totalQuantity
                                $_SESSION["cart_item"][$key]["quantity"] = $newQuantity;
                                $itemUpdated = true;
                            } else {
                                // Handle case when quantity exceeds totalQuantity (e.g., show an error message)
                                header('Location: showProduct.php?id=' . $product["id"] . '&msg=maxQuantity');
                                exit;
                            }
                            break; // Exit the loop once the item is updated
                        }
                    }
                    if (!$itemUpdated) {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }

            break;
        case "update":
            if (!empty($_POST["quantity"])) {
                if (!empty($_SESSION["cart_item"])) {
                    foreach ($_SESSION["cart_item"] as $key => $value) {
                        if ($_GET["id"] == $value['id']) {
                            $_SESSION["cart_item"][$key]["quantity"] = $_POST["quantity"];
                        }
                    }
                }
            }
            break;
        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $key => $value) {
                    if ($_GET["id"] == $value['id']) {
                        $total_price = ($_SESSION["cart_item"][$key]["price"] * $_SESSION["cart_item"][$key]["quantity"]);
                        $_SESSION['total_price'] -= ($total_price + $taxes);
                        unset($_SESSION["cart_item"][$key]);
                    }
                    if (empty($_SESSION["cart_item"])) {
                        unset($_SESSION["cart_item"]);
                        unset($_SESSION['total_price']);
                    }
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            unset($_SESSION['total_price']);
            break;
    }
}
$_SESSION['buyerPage'] = true;
if ($_SESSION["loggedin"] === false) {
    $_SESSION["check"] = true;
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShopStore</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/counter.css">
    <link rel="stylesheet" href="css/nav.css">
</head>

<body>
    <nav>
        <a href="buyer.php" class="nav-a">Products</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1) : ?>
            <a href="shop.php" class="nav-a">Admin Panel</a>
        <?php endif ?>
        <a href="cart.php" class="nav-a">Cart</a>
        <a href="checkout.php?source=cart" class="nav-a">Checkout</a>
        <?php if ($_SESSION["loggedin"] === true) : ?>
            <a href="myAccount.php" class="nav-a">My Account</a>
            <a href="logout.php" class="nav-a">Sign Out</a>
        <?php endif ?>
        <?php if (!$_SESSION["loggedin"] === true) : ?>
            <a href="signin.php" class="nav-a">Login</a>
        <?php endif ?>
    </nav>
    <div class="flash">
        <?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            $function->displayMessage($msg);
        }
        ?>
    </div>
    <header id="title">
        <h1>Shopping Cart</h1>
    </header>
    <div id="page">
        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;
        ?>
            <table id="cart">
                <thead>
                    <tr>
                        <th class="first">Picture</th>
                        <th class="second">Quantity</th>
                        <th class="third">Product</th>
                        <th class="fourth">Price</th>
                        <th class="fifth"><a href="cart.php?action=empty"><i class="fa-sharp fa-solid fa-circle-xmark"></i></a></th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <!-- shopping cart contents -->
                    <?php
                    $itemBought = array();
                    foreach ($_SESSION["cart_item"] as $item) {
                        $item_price = $item["quantity"] * $item["price"];
                    ?>
                        <tr class="prod-item">
                            <td><a href="showProduct.php?id=<?= $item['id'] ?>">
                                    <img src="assets/<?= $item['picture'] ?>" alt='product-image' class="product-img">
                                </a>
                            </td>
                            <td>
                                <form action="cart.php?action=update&id=<?= $item['id']; ?>" method="POST">
                                    <a href="#" class="quantity__minus"><span>-</span></a>
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="quantity__input" max="<?= $item['totalQuantity'] ?>" id="quantityy" required>
                                    <a href="#" class="quantity__plus"><span>+</span></a>
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                </form>
                            </td>
                            <td><?= $item['name'] ?></td>
                            <td>$<?= $item['price'] ?></td>
                            <td class='remove'><a href="cart.php?action=remove&id=<?= $item["id"] ?>"><img src='https://i.imgur.com/h1ldGRr.png' alt='X'></a></td>
                        </tr>
                    <?php
                        $itemBought[] = $item['id'];
                        $_SESSION["itemsBought"] = $itemBought;
                        $total_quantity += $item["quantity"];
                        $itemQuantity[] = $item["quantity"];
                        $_SESSION["itemsQuantity"] = $itemQuantity;
                        $total_price += ($item["price"] * $item["quantity"]);
                        $final_amount = $total_price + $taxes;
                        $_SESSION['total_price'] = $final_amount;
                    }
                    ?>
                    <tr class="extracosts">
                        <td class="light">Subtotal</td>
                        <td colspan="2">&nbsp;</td>
                        <td colspan="1"><span class="light">$<?= $total_price ?></span></td>
                        <td colspan="1">&nbsp;</td>
                    </tr>

                    <tr class="extracosts">
                        <td class="light">Shipping & Taxes</td>
                        <td colspan="2">&nbsp;</td>
                        <td colspan="1"><span class="light">$<?= $taxes ?></span></td>
                        <td colspan="1">&nbsp;</td>
                    </tr>

                    <tr class="totalprice">
                        <td class="light">Total</td>
                        <td colspan="2">&nbsp;</td>
                        <td colspan="1"><span class="thick">$<?= $final_amount ?></span></td>
                        <td colspan="1">&nbsp;</td>
                    </tr>

                    <!-- checkout btn -->
                    <tr class="checkoutrow">
                        <td colspan="5" class="checkout">
                            <button id="cancel-"><a href="buyer.php">Back to Shopping</a></button>
                            <button id="cart-submit">Checkout Now!</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php
        } else {
        ?>
            <div class="no-records">Your Cart is Empty</div>
            <div class="no-records">
                <button id="cancel-"><a href="buyer.php">Back to Shopping</a></button>
            </div>
        <?php
        }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/flash.js"></script>
</body>

</html>
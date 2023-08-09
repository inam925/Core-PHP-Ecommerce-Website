<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['check']) && isset($_SESSION['total_price'])) {
    if ($_SESSION['check'] === true) {
        header('location: checkout.php');
        unset($_SESSION["check"]);
    }
}
if (!isset($_SESSION["loggedin"])) {
    $_SESSION["loggedin"] = false;
}

// Include database file
require_once 'Database.php';
require_once 'Functions.php';
require_once 'Products.php';

// Create an instance of the Database class
$db = new Database();
$function = new Functions($db);
$productVar = new Products($db);
// Delete record from table
if (isset($_GET['deleteId']) && !empty($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $productVar->deleteProduct($deleteId);
}
$_SESSION['buyerPage'] = true;
$table = 'products';

$recordsPerPage = 8;
$totalNumberOfResults = $function->count($table);
// determine the total number of pages available  
$numberOfPages = ceil($totalNumberOfResults / $recordsPerPage);

//determine which page number visitor is currently on  
if (!isset($_GET['page'])) {
    $page = 1;
    $_GET['page'] = $page;
} else {
    $page = $_GET['page'];
}
//determine the sql LIMIT starting number for the results on the displaying page  
$pageLimit = ($page - 1) * $recordsPerPage;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopStore</title>
    <link rel="stylesheet" href="css/buyer.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/card.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
</head>

<body>
    <div class="tablee">
        <nav>
            <a href="buyer.php" class="nav-a">Products</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1) : ?>
                <a href="shop.php" class="nav-a">Admin Panel</a>
            <?php endif ?>
            <a href="cart.php" class="nav-a">Cart</a>
            <a href="checkout.php?source=buyer" class="nav-a">Checkout</a>
            <?php if ($_SESSION["loggedin"] === true) : ?>
                <a href="myAccount.php" class="nav-a">My Account</a>
                <a href="logout.php" class="nav-a">Sign Out</a>
            <?php endif ?>
            <?php if (!$_SESSION["loggedin"] === true) : ?>
                <a href="signin.php" class="nav-a">Login</a>
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
        <h1>Products</h1>
        <div class="products">
            <div class="products-wrapper">
                <?php
                $products = $function->fetchAllRecords($table, $pageLimit, $recordsPerPage);

                foreach ($products as $product) : ?>
                    <div class="product">
                        <div class="product-card">
                            <a class="img" href="showProduct.php?id=<?= $product['id'] ?>">
                                <img src="assets/<?= $product['picture'] ?>" alt="<?= $product['name'] ?>">
                            </a>

                            <div class="content">
                                <h2><?= $product['name'] ?></h2>
                                <a href="showProduct.php?id=<?= $product['id'] ?>">View Product</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="pagination">
            <?php //display pagination links  
            for ($page = 1; $page <= $numberOfPages; $page++) : ?>
                <a href="buyer.php?page=<?= $page ?>" class="pagination-link <?= $function->isActivePage($page, $_GET['page']) ?>"><?= $page ?></a>
            <?php endfor ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/flash.js"></script>
</body>

</html>
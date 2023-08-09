<?php
session_start();
if ($_SESSION["loggedin"] === true) {
    if ($_SESSION["role"] === 0) {
        header("location: buyer.php");
    } else if (isset($_SESSION['total_price']) && (isset($_SESSION['check']) && $_SESSION['check'] === true)) {
        header("location: checkout.php");
        unset($_SESSION["check"]);
    } else {
    }
} else {
    header("location: signin.php");
}

// Include database file
require_once 'Database.php';
require_once 'Products.php';
require_once 'Functions.php';

// Create an instance of the Database class
$db = new Database();
$function = new Functions($db);
$productVar = new Products($db);
unset($_SESSION['buyerPage']);

$table = 'products';
$recordsPerPage = 10;
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/shop.css">
    <link rel="stylesheet" href="css/nav.css">
</head>

<body>
    <div class="tablee">
        <nav>
            <a href="shop.php" class="nav-a">Products</a>
            <a href="allOrders.php" class="nav-a">Orders</a>
            <a href="buyer.php" class="nav-a">Shop</a>
            <a href="myAccount.php" class="nav-a">My Account</a>
            <a href="logout.php" class="nav-a">Sign Out</a>
        </nav>
        <div class="flash" id=flash>
            <?php
            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
                $function->displayMessage($msg);
            }
            ?>
        </div>
        <h1>List of Products</h1>
        <div>
            <button><a href="addProduct.php">Add a new Product</a></button>
        </div>
        <table class="main-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Picture</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $products = $function->fetchAllRecords($table, $pageLimit, $recordsPerPage);
                foreach ($products as $product) : ?>
                    <tr class="i_<?= $product['id'] ?>">
                        <td class="check"><input type="checkbox" class="deleteCheckbox" value="<?= $product['id'] ?>"></td>
                        <td><?= $product['name'] ?></td>
                        <td>$<?= $product['price'] ?></td>
                        <td><img src="assets/<?= $product['picture'] ?>" alt=""></td>
                        <td><?= $product['category'] ?></td>
                        <td>
                            <div class="buttons">
                                <button><a href="editProduct.php?editId=<?= $product['id'] ?>">Edit</a></button>
                                <button class="deleteBtn">Delete</button>
                            </div>

                        </td>
                    </tr>
                <?php endforeach ?>
            <tbody>
        </table>
        <div class="pagination">
            <?php //display pagination links 
            for ($page = 1; $page <= $numberOfPages; $page++) : ?>
                <a href="shop.php?page=<?= $page ?>" class="pagination-link <?= $function->isActivePage($page, $_GET['page']) ?>"><?= $page ?></a>
            <?php endfor ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/flash.js"></script>
    <script src="js/deleteProduct.js"></script>
</body>

</html>
<?php
session_start();
if ($_SESSION["loggedin"] === true) {
} else {
    header("location: signin.php");
}
// Include database file
require_once 'Database.php';
require_once 'Functions.php';

// Create an instance of the Database class
$db = new Database();
// Pass the database instance to the Functions class
$function = new Functions($db);

$id = $_SESSION["id"];
$user = $function->displayRecordById("users", $id);
$table = 'orders';

$recordsPerPage = 10;
$totalNumberOfResults = (isset($_GET['source'])) ? $function->countUserOrders($table, $id) : $function->count($table);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/shop.css">
    <link rel="stylesheet" href="css/nav.css">
</head>

<body>
    <div class="tablee">
        <nav>
            <?php if ($user['role'] == 0) { ?>
                <a href="buyer.php" class="nav-a">Products</a>
                <?php if ($_SESSION['role'] === 1) : ?>
                    <a href="shop.php" class="nav-a">Admin Panel</a>
                <?php endif ?>
                <a href="cart.php" class="nav-a">Cart</a>
                <a href="checkout.php?source=buyer" class="nav-a">Checkout</a>
                <?php } else {
                if (isset($_SESSION['buyerPage'])) { ?>
                    <a href="buyer.php" class="nav-a">Products</a>
                    <?php if ($_SESSION['role'] === 1) : ?>
                        <a href="shop.php" class="nav-a">Admin Panel</a>
                    <?php endif ?>
                    <a href="cart.php" class="nav-a">Cart</a>
                    <a href="checkout.php?source=buyer" class="nav-a">Checkout</a>
                <?php } else { ?>
                    <a href="shop.php" class="nav-a">Products</a>
                    <a href="allOrders.php" class="nav-a">Orders</a>
                    <a href="buyer.php" class="nav-a">Shop</a>
            <?php }
            } ?>
            <a href="myAccount.php" class="nav-a">My Account</a>
            <a href="logout.php" class="nav-a">Sign Out</a>
        </nav>
        <h1>List of Orders</h1>
        <table class="main-table">
            <thead>
                <tr>
                    <th class="order-id">id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th class="view-order"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = ($page > 1) ? 11 : 1;
                if ($_SESSION['role'] === 1) {
                    if (isset($_GET['source']) && $_GET['source'] == $_SESSION['role']) {
                        $orders = $function->fetchUserOrders($table, $id, $pageLimit, $recordsPerPage);
                        foreach ($orders as $order) : ?>
                            <tr>
                                <td class="order-id"><?= $i++ ?></td>
                                <td><?= $order['name'] ?></td>
                                <td><?= $order['email'] ?></td>
                                <td>$<?= $order['amount'] ?></td>
                                <td class="view-order"><a href="showOrder.php?id=<?= $order['id'] ?>&source=<?= $_SESSION['role'] ?>"><i class="fa-solid fa-eye"></i></a></td>
                            </tr>
                        <?php endforeach;
                    } else {
                        $orders = $function->fetchAllRecords($table, $pageLimit, $recordsPerPage);
                        foreach ($orders as $order) : ?>
                            <tr>
                                <td class="order-id"><?= $order['id'] ?></td>
                                <td><?= $order['name'] ?></td>
                                <td><?= $order['email'] ?></td>
                                <td>$<?= $order['amount'] ?></td>
                                <td class="view-order"><a href="showOrder.php?id=<?= $order['id'] ?>"><i class="fa-solid fa-eye"></i></a></td>
                            </tr>
                        <?php endforeach;
                    }
                } else {
                    $orders = $function->fetchUserOrders($table, $id, $pageLimit, $recordsPerPage);
                    foreach ($orders as $order) : ?>
                        <tr>
                            <td class="order-id"><?= $i++ ?></td>
                            <td><?= $order['name'] ?></td>
                            <td><?= $order['email'] ?></td>
                            <td>$<?= $order['amount'] ?></td>
                            <td class="view-order"><a href="showOrder.php?id=<?= $order['id'] ?>&source=<?= $user['role'] ?>"><i class="fa-solid fa-eye"></i></a></td>
                        </tr>
                <?php endforeach;
                }
                unset($_SESSION['buyerPage']);
                ?>
            <tbody>
        </table>
        <div class="pagination">
            <?php
            if ($numberOfPages > 1) {
                //display pagination links 
                for ($page = 1; $page <= $numberOfPages; $page++) {
                    if (isset($_GET['source'])) {
            ?>
                        <a href="allOrders.php?page=<?= $page ?>&source=<?= $user['id'] ?>" class=" pagination-link <?= $function->isActivePage($page, $_GET['page']) ?>"><?= $page ?></a>
                    <?php
                    } else {
                    ?>
                        <a href="allOrders.php?page=<?= $page ?>" class=" pagination-link <?= $function->isActivePage($page, $_GET['page']) ?>"><?= $page ?></a>
            <?php
                    }
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
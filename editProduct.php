<?php
session_start();
if ($_SESSION["loggedin"] === true) {
    if ($_SESSION["role"] === 0) {
        header("location: buyer.php");
    } else {
    }
} else {
    header("location: signin.php");
}

// Include database file
require_once 'Database.php';
require_once 'Functions.php';

// Create an instance of the Database class
$db = new Database();

$function = new Functions($db);
$table = 'products';
// Edit product record
if (isset($_GET['editId']) && !empty($_GET['editId'])) {
    $editId = $_GET['editId'];
    $product = $function->displayRecordById($table, $editId);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>ShopStore</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="main">
        <h4>Edit Product</h4>
        <div>
            <form>
                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']); ?>">
                <div>
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="Enter name" value="<?= $product['name'] ?>" required>
                </div>
                <div>
                    <label for="price">Price</label>
                    <input type="number" name="price" placeholder="Enter price" value="<?= $product['price'] ?>" required>
                </div>
                <div>
                    <label for="picture">Picture </label>
                    <label for="choose-file" class="custom-file-upload" id="choose-file-label">
                        <?= $product['picture'] ?>
                    </label>
                    <input name="picture" type="file" id="choose-file" />
                </div>
                <div>
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" placeholder="Enter quantity" value="<?= $product['quantity'] ?>" required="">
                </div>
                <div>
                    <label for="category">Category</label>
                    <input type="text" name="category" placeholder="Enter category" value="<?= $product['category'] ?>" required>
                </div>
                <div>
                    <label for="description">Description</label>
                    <textarea name="description" placeholder="Enter description" required><?= $product['description'] ?></textarea>
                </div>
                <aside>
                    <button type="button" class="editProductBtn">Update</button>
                    <button type="button"><a href="shop.php">Cancel</a></button>
                </aside>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/editProduct.js"></script>
    <script src="js/input.js"></script>
</body>

</html>
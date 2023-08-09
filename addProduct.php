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
        <h4>Add a new Product</h4>
        <div>
            <form>
                <div>
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="Enter name" required>
                    <pre class="flashes" id="name-error"></pre>
                </div>
                <div>
                    <label for="price">Price</label>
                    <input type="number" name="price" placeholder="Enter price" required>
                    <pre class="flashes" id="price-error"></pre>
                </div>
                <div>
                    <label for="picture">Picture</label>
                    <input type="file" name="picture" placeholder="Enter picture" required>
                    <pre class="flashes" id="picture-error"></pre>
                </div>
                <div>
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" placeholder="Enter quantity" required>
                    <pre class="flashes" id="quantity-error"></pre>
                </div>
                <div>
                    <label for="category">Category</label>
                    <input type="text" name="category" placeholder="Enter category" required>
                    <pre class="flashes" id="category-error"></pre>
                </div>
                <div>
                    <label for="description">Description</label>
                    <textarea name="description" placeholder="Enter description" required></textarea>
                    <pre class="flashes" id="description-error"></pre>
                </div>
                <aside>
                    <button type="button" class="addProductBtn">Submit</button>
                    <button type="button"><a href="shop.php">Cancel</a></button>
                </aside>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/addProduct.js"></script>
    <script src="js/flash.js"></script>
</body>

</html>
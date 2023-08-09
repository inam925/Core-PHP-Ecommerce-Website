<?php
session_start();
if ($_SESSION["loggedin"] === true) {
} else {
    header("location: signin.php");
}
// Include database file
require_once 'Database.php';
require_once 'Functions.php';
require_once 'Users.php';

// Create an instance of the Database class
$db = new Database();

$userVar = new Users($db);
$function = new Functions($db);

// Edit user record
$id = $_SESSION["id"];
$user = $function->displayRecordById("users", $id);
// Update Record in user table
if (isset($_POST['submit'])) {
    $userVar->updateUserRecord($_POST);
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
        <div class="flash" id=flash>
            <?php
            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
                $function->displayMessage($msg);
            }
            ?>
        </div>
        <h4>My Account</h4>
        <div>
            <form action="myAccount.php" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                <div>
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Enter username" value="<?= $user['username'] ?>" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Enter email" value="<?= $user['email'] ?>" required>
                </div>
                <div>
                    <label for="password">New Password</label>
                    <input type="text" name="password" placeholder="Enter new password" value="" required>
                </div>
                <button type="button"><a href="allOrders.php?source=<?= $user['role'] ?>">Previous orders</a></button>
                <aside>
                    <button type="submit" name="submit">Update</button>
                    <button type="button">
                        <?php
                        if ($_SESSION["role"] === 0) {
                        ?>
                            <a href="buyer.php">Cancel</a>
                        <?php
                        } else {
                        ?>
                            <a href="shop.php">Cancel</a>
                        <?php
                        }
                        ?>
                    </button>
                </aside>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/flash.js"></script>
</body>

</html>
<?php
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: shop.php");
    exit;
}

$exists = false;
include 'database.php';

$database = new Database();
$connection = $database->getConnection();
$database->createTables();
$database->insertDefaultData();
require_once 'Functions.php';
$function = new Functions($database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = 0;

    $sql = "Select * from users where email='$email'";

    $result = mysqli_query($connection, $sql);

    $num = mysqli_num_rows($result);

    // This sql query is used to check if the username is already present or not in our Database
    if ($num == 0) {
        if (($password) && $exists == false) {

            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Password Hashing is used here. 
            $sql = "INSERT INTO `users` ( `username`, 
                `email`, `password`, `role`) VALUES ('$username', '$email',
                '$hash', '$role')";

            $result = mysqli_query($connection, $sql);

            if ($result) {
                session_start();

                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["email"] = $email;
                $_SESSION["role"] = $role;

                // Redirect user to welcome page
                header("location: shop.php");
            }
        } else {
            header("Location:signin.php?msg=userLoginError");
        }
    } // end if 

    if ($num > 0) {
        header("Location:signin.php?msg=userExists");
    }
} //end if   

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopStore</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/signup.css">
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
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form action="signin.php" method="post">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="username" placeholder="User name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button>Sign up</button>
            </form>
        </div>

        <div class="login">
            <form action="login.php" method="post">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button>Login</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/flash.js"></script>
</body>

</html>
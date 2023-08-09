<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: shop.php");
    exit;
}

// Include config file
include 'database.php';

$database = new Database();
$conn = $database->getConnection();
$database->createTables();
$database->insertDefaultData();

// Define variables and initialize with empty values
$email = $password = "";
$emailError = $passwordError = $loginError = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $emailError = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $passwordError = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
        $passworde = password_hash(
            $password,
            PASSWORD_DEFAULT
        );
    }

    // Validate credentials
    if (empty($emailError) && empty($passwordError)) {
        // Prepare a select statement
        $sql = "SELECT id, email, password, role FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $paramEmail);

            // Set parameters
            $paramEmail = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {

                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["role"] = $role;
                            $_SESSION["email"] = $email;

                            // Redirect user to welcome page
                            header("location: shop.php");
                        } else {
                            // Password is not valid, display a generic error message
                            header("Location:signin.php?msg=inavlidDetails");
                        }
                    }
                } else {
                    // email doesn't exist, display a generic error message
                    header("Location:signin.php?msg=inavlidDetails");
                }
            } else {
                header("Location:signin.php?msg=userLoginError");
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}

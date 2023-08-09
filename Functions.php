<?php
require_once 'Database.php';
class Functions
{
    private $db;
    private $con;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->con = $this->db->getConnection();
    }
    // Fetch order records for show listing
    public function fetchAllRecords($table, $pageLimit, $recordsPerPage)
    {

        $query = "SELECT * FROM $table Order By id Desc LIMIT " . $pageLimit . ',' . $recordsPerPage;
        $result = $this->con->query($query);
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            echo "No record found";
        }
    }
    public function count($table)
    {
        // Find the total number of results stored in the database  
        $query = "SELECT COUNT(*) AS total FROM `$table`";
        $result = $this->con->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            $number_of_result = $row['total'];
            return $number_of_result;
        } else {
            // Handle query error
            return false;
        }
    }
    public function countUserOrders($table, $id)
    {
        // Find the total number of results stored in the database  
        $query = "SELECT COUNT(*) AS total FROM `$table` WHERE user = '$id'";
        $result = $this->con->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            $number_of_result = $row['total'];
            return $number_of_result;
        } else {
            // Handle query error
            return false;
        }
    }
    public function isActivePage($currentPage, $pageNumber)
    {
        return $currentPage == $pageNumber ? 'active' : '';
    }
    // Fetch order records by user
    public function fetchUserOrders($table, $id, $pageLimit, $recordsPerPage)
    {
        $query = "SELECT * FROM $table WHERE user = " . $id . " Order By id Desc LIMIT " . $pageLimit . ", " . $recordsPerPage;
        $result = $this->con->query($query);
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            echo "No records found";
        }
    }
    // Fetch single data from orders table
    public function displayRecordById($table, $id)
    {
        $query = "SELECT * FROM $table WHERE id = '$id'";
        $result = $this->con->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            echo "Order not found";
        }
    }
    // Display error message to user
    function displayMessage($msg)
    {
        switch ($msg) {
            case "inavlidDetails":
                echo "<pre>Invalid email or password.</pre>";
                break;
            case "userLoginError":
                echo "<pre>Oops! Something went wrong. Please try again later.</pre>";
                break;
            case "userExists":
                echo "<pre>Email not available.</pre>";
                break;
            case "placeOrder":
                echo "<pre>Order placed successfully.</pre>";
                unset($_SESSION["cart_item"]);
                unset($_SESSION['total_price']);
                break;
            case "maxQuantity":
                echo "<pre>Maximum quantity reached, select another product.</pre>";
                break;
            case "loginError":
                echo "<pre>Login to proceed to checkout.</pre>";
                break;
            case "selectProduct":
                echo "<pre>Please add a product to cart before proceeding to checkout.</pre>";
                break;
            case "updateAccount":
                echo "<pre>Account details updated successfully.</pre>";
                break;
            case "invalidAccountDetails":
                echo "<pre>Invalid Account details.</pre>";
                break;
            case "insertProduct":
                echo "<pre>Product added successfully.</pre>";
                break;
            case "updateProduct":
                echo "<pre>Product updated successfully.</pre>";
                break;
            case "deleteProduct":
                echo "<pre>Product deleted successfully.</pre>";
                break;
            case "deleteMultipleProducts":
                echo "<pre>Selected products deleted successfully.</pre>";
                break;
            default:
                echo "<pre>Invalid message.</pre>";
                break;
        }
    }
}

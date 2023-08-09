<?php
require_once 'Database.php';
class Orders
{
    private $db;
    private $con;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->con = $this->db->getConnection();
    }
    // Insert order data into orders table
    public function insertOrderData($post)
    {
        $name = $this->con->real_escape_string($_POST['f-name'] . " " . $_POST['l-name']);
        $card = $this->con->real_escape_string($_POST['card']);
        $amount = $this->con->real_escape_string($_POST['amount']);
        $id = $this->con->real_escape_string($_POST['id']);
        $email = $this->con->real_escape_string($_POST['email']);
        $address = $this->con->real_escape_string($_POST['address'] . ", " . $_POST['city'] . ", " . $_POST['state'] . ", " . $_POST['zip']);
        $items = $this->con->real_escape_string($_POST['items']);
        $quanitities = $this->con->real_escape_string($_POST['quantities']);
        $query = "INSERT INTO orders(name,card,amount,user,email,address,items,quantities) VALUES('$name','$card','$amount','$id','$email', '$address', '$items', '$quanitities')";
        $sql = $this->con->query($query);
        if ($sql == true) {
            header("Location:buyer.php?msg=placeOrder");
        } else {
            echo "An error occured during execution!";
        }
    }
}

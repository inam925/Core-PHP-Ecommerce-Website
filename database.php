<?php
class Database
{
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "ecommerce";
    public $con;

    // Database Connection
    public function __construct()
    {
        $this->con = new mysqli($this->server, $this->username, $this->password, $this->database);
        if (mysqli_connect_error()) {
            trigger_error("Failed to connect to MySQL: " . mysqli_connect_error());
        }
    }

    public function getConnection()
    {
        return $this->con;
    }

    public function createTables()
    {
        $conn = $this->getConnection();

        $conn->query("CREATE DATABASE IF NOT EXISTS `$this->database`");

        mysqli_select_db($conn, $this->database);

        $usersTable = "CREATE TABLE IF NOT EXISTS users (
            id int(11) NOT NULL auto_increment,   
            role int(11) NOT NULL,
            username varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            PRIMARY KEY(id)
        )";
        $conn->query($usersTable);

        $productsTable = "CREATE TABLE IF NOT EXISTS products (
            id int(11) NOT NULL auto_increment,
            name varchar(255) NOT NULL, 
            price int(11) NOT NULL,
            picture Varchar(255) NOT NULL,
            category varchar(255) NOT NULL,
            quantity int(11) NOT NULL,
            description varchar(255) NOT NULL,
            PRIMARY KEY(id)
        )";
        $conn->query($productsTable);

        $ordersTable = "CREATE TABLE IF NOT EXISTS orders (
            id int(11) NOT NULL auto_increment,
            user int(11) NOT NULL, 
            amount int(11) NOT NULL,
            email Varchar(255) NOT NULL,
            name Varchar(255) NOT NULL,
            address Varchar(255) NOT NULL,
            card int(11) NOT NULL,
            items Varchar(255) NOT NULL,
            quantity Varchar(255) NOT NULL,
            PRIMARY KEY(id)
        )";
        $conn->query($ordersTable);
    }

    public function insertDefaultData()
    {
        $conn = $this->getConnection();

        $sql = "SELECT * FROM users";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count == 0) {
            $enter = "INSERT INTO users (role, username, email, password) VALUES('1', 'inam', 'inamulhaq925@gmail.com', 'password')";
            $conn->query($enter);
        }

        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count == 0) {
            $enter = "INSERT INTO products (name, price, picture, category, quantity, description) VALUES
            ('Mobile phone', '266', 'mobile.png', 'Electronics', '4', 'description'),
            ('Camera', '266', 'camera.png', 'Electronics', '4', 'description'),
            ('Headphone', '240', 'headphone.png', 'Electronics', '14', 'description'),
            ('Wallet', '450', 'wallet.png', 'Accessories', '6', 'description'),
            ('Pokeball', '820', 'pokeball.png', 'Electronics', '12', 'description')";
            $conn->query($enter);
        }
    }
}

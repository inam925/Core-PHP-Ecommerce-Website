<?php
// Include database file
require_once 'Database.php';
require_once 'Products.php';

// Create an instance of the Database class
$db = new Database();

$productVar = new Products($db);

$productData = $_POST;
//Delete records
$productVar->updateProduct($productData);

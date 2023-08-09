<?php
// Include database file
require_once 'Database.php';
require_once 'Products.php';

// Create an instance of the Database class
$db = new Database();

$productVar = new Products($db);

$ids = $_POST['id'];
if (is_array($ids) && count($ids) === 1) {
    // Only one ID is present
    $id = $ids[0];
    // Delete record with the ID
    $productVar->deleteProduct($id);
}
// Delete records
$productVar->deleteMultipleProductRecords($ids);

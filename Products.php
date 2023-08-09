<?php
require_once 'Database.php';
class Products
{
    private $db;
    private $con;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->con = $this->db->getConnection();
    }
    // Insert product data into product table
    public function insertProductData($post)
    {
        $name = $this->con->real_escape_string($_POST['name']);
        $price = $this->con->real_escape_string($_POST['price']);
        $picture = $this->con->real_escape_string($_POST['picture']);
        $quantity = $this->con->real_escape_string($_POST['quantity']);
        $category = $this->con->real_escape_string($_POST['category']);
        $description = $this->con->real_escape_string($_POST['description']);
        $query = "INSERT INTO products(name,price,picture,quantity,category, description) VALUES('$name','$price','$picture','$quantity','$category', '$description')";
        $sql = $this->con->query($query);
        if ($sql == true) {
            header("Location:shop.php?msg=insertProduct");
        } else {
            echo "An error occured during execution!";
        }
    }

    public function updateProduct($postData)
    {
        // Check if the required fields are present in the $postData array
        if (isset($postData['name'], $postData['price'], $postData['picture'], $postData['quantity'], $postData['category'], $postData['id'], $postData['description'])) {
            // Escape the input values to prevent SQL injection
            $name = $this->con->real_escape_string($postData['name']);
            $price = $this->con->real_escape_string($postData['price']);
            $picture = $this->con->real_escape_string($postData['picture']);
            $quantity = $this->con->real_escape_string($postData['quantity']);
            $category = $this->con->real_escape_string($postData['category']);
            $description = $this->con->real_escape_string($_POST['description']);
            $id = $this->con->real_escape_string($postData['id']);

            // Check if the ID and postData are not empty
            if (!empty($id) && !empty($postData)) {
                // Construct the SQL query using prepared statements
                $query = "UPDATE products SET name = ?, price = ?, picture = ?, quantity = ?, category = ?, description = ? WHERE id = ?";
                $statement = $this->con->prepare($query);
                $statement->bind_param("ssssssi", $name, $price, $picture, $quantity, $category, $description, $id);
                $statement->execute();

                // Check if the update was successful
                if ($statement->affected_rows > 0) {
                    // Redirect to a success message page
                    header("Location: shop.php?msg=updateProduct");
                    exit; // Terminate the script after redirection
                } else {
                    echo "Product update failed. Please try again!";
                }

                // Close the prepared statement
                $statement->close();
            } else {
                echo "ID or postData is empty.";
            }
        } else {
            echo "Required fields are missing in the postData.";
        }
    }

    // Delete product data from product table
    public function deleteProduct($id)
    {
        $query = "DELETE FROM products WHERE id = '$id'";
        $sql = $this->con->query($query);
        if ($sql == true) {
            header("Location:shop.php?msg=deleteProduct");
        } else {
            echo "Record not deleted, try again";
        }
    }
    // Delete product data from product table
    public function deleteMultipleProductRecords($ids)
    {
        $in = implode(', ', $ids); // [1, 2, 3] becomes '1, 2, 3'
        $query = "DELETE FROM products WHERE id IN ($in)";

        $sql = $this->con->query($query);

        if ($sql == true) {
            header("Location: shop.php?msg=deleteMultipleProducts");
        } else {
            echo "Records not deleted, try again";
        }
    }
}

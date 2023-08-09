<?php
require_once 'Database.php';
class Users
{
    private $db;
    private $connection;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->connection = $this->db->getConnection();
    }

    public function updateUserRecord($postData)
    {
        // Check if the required fields are present in the $postData array
        if (isset($postData['username'], $postData['email'], $postData['password'])) {
            // Escape the input values to prevent SQL injection
            $username = $this->connection->real_escape_string($postData['username']);
            $email = $this->connection->real_escape_string($postData['email']);
            $password = $this->connection->real_escape_string($postData['password']);
            $id = $this->connection->real_escape_string($postData['id']);
            $hash_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Check if the ID and postData are not empty
            if (!empty($id) && !empty($postData)) {
                // Construct the SQL query using prepared statements
                $query = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
                $statement = $this->connection->prepare($query);
                $statement->bind_param("sssi", $username, $email, $hash_password, $id);
                $statement->execute();

                // Check if the update was successful
                if ($statement->affected_rows > 0) {
                    // Redirect to a success message page
                    header("Location: myAccount.php?msg=updateAccount");
                    exit; // Terminate the script after redirection
                } else {
                    header("Location: myAccount.php?msg=invalidAccountDetails");
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
}

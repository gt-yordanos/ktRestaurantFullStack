<?php
// Include the connection.php file
include_once 'connection.php';

class Deleter {
    private $conn;

    public function __construct() {
        // Create a new instance of the DatabaseConnection class
        $dbConnection = new DatabaseConnection();

        // Establish the database connection
        $this->conn = $dbConnection->connect();
    }

    public function deleteRecord($tableName, $primaryKey) {
        // Check if table name and id are provided
        if (!empty($tableName) && !empty($primaryKey)) {
            // Escape the primary key value to prevent SQL injection
            $escapedPrimaryKey = $this->conn->real_escape_string($primaryKey);

            // Determine the correct primary key column name based on the table
            switch ($tableName) {
                case 'customerinfo':
                case 'menumanagerinfo':
                case 'paymentmanagerinfo':
                    $primaryKeyColumnName = 'email';
                    break;
                case 'foodinfo':
                    $primaryKeyColumnName = 'food_id';
                    break;
                case 'orderinfo':
                    $primaryKeyColumnName = 'order_id';
                    break;
                default:
                    echo "Invalid table name!";
                    exit();
            }

            // Perform deletion based on table name and primary key
            $sql = "DELETE FROM $tableName WHERE $primaryKeyColumnName = '$escapedPrimaryKey'";

            if ($this->conn->query($sql) === TRUE) {
                // Redirect back to admin.php after deletion
                header("Location: admin.php");
                exit();
            } else {
                echo "Error deleting record: " . $this->conn->error;
            }
        } else {
            echo "Table name and ID not provided!";
        }
    }
}

// Create an instance of the Deleter class
$deleter = new Deleter();

// Check if table name and id are set in the URL
if (isset($_GET['table']) && isset($_GET['id'])) {
    $tableName = $_GET['table'];
    $primaryKey = $_GET['id'];

    // Delete the record
    $deleter->deleteRecord($tableName, $primaryKey);
}
?>
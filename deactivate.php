<?php
// Include the connection.php file
include_once 'connection.php';

class Deactivator {
    private $conn;
    private $tableName;
    private $primaryKey;
    public function __construct() {
        // Create a new instance of the DatabaseConnection class
        $dbConnection = new DatabaseConnection();

        // Establish the database connection
        $this->conn = $dbConnection->connect();
    }

    public function deactivateRecord($tableName, $primaryKey) {
        // Check if table name and id are provided
        if (!empty($tableName) && !empty($primaryKey)) {
            // Escape the primary key value to prevent SQL injection
            $escapedPrimaryKey = $this->conn->real_escape_string($primaryKey);

            // Determine the correct primary key column name based on the table
            $primaryKeyColumnName = ($tableName === 'customerinfo' || $tableName === 'menumanagerinfo' || $tableName === 'paymentmanagerinfo') ? 'email' : (($tableName === 'foodinfo') ? 'food_id' : 'order_id');

            // Construct the SQL query to update the 'activated' column
            $sql = "UPDATE $tableName SET activated = 0 WHERE $primaryKeyColumnName = '$escapedPrimaryKey'";

            if ($this->conn->query($sql) === TRUE) {
                // Redirect back to admin.php after deactivation
                header("Location: admin.php");
                exit();
            } else {
                echo "Error deactivating record: " . $this->conn->error;
            }
        } else {
            echo "Table name and ID not provided!";
        }
    }
}

// Create an instance of the Deactivator class
$deactivator = new Deactivator();

// Check if table name and id are set in the URL
if (isset($_GET['table']) && isset($_GET['id'])) {
    $tableName = $_GET['table'];
    $primaryKey = $_GET['id'];

    // Deactivate the record
    $deactivator->deactivateRecord($tableName, $primaryKey);
}
?>
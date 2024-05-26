<?php
// Include the connection.php file
include_once 'connection.php';

class Activator {
    private $conn;
    private $tableName;
    private $primaryKey;

    public function __construct($dbConnection, $tableName, $primaryKey) {
        $this->conn = $dbConnection->connect();
        $this->tableName = $tableName;
        $this->primaryKey = $this->conn->real_escape_string($primaryKey);
    }

    private function getPrimaryKeyColumnName() {
        switch ($this->tableName) {
            case 'customerinfo':
            case 'menumanagerinfo':
            case 'paymentmanagerinfo':
                return 'email';
            case 'foodinfo':
                return 'food_id';
            case 'orderinfo':
                return 'order_id';
            default:
                return 'id';
        }
    }

    public function activate() {
        $primaryKeyColumnName = $this->getPrimaryKeyColumnName();
        $sql = "UPDATE $this->tableName SET activated = 1 WHERE $primaryKeyColumnName = '$this->primaryKey'";

        if ($this->conn->query($sql) === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error activating record: " . $this->conn->error;
        }
    }
}

// Check if table name and id are set in the URL
if (isset($_GET['table']) && isset($_GET['id'])) {
    $tableName = $_GET['table'];
    $primaryKey = $_GET['id'];

    $dbConnection = new DatabaseConnection();
    $activator = new Activator($dbConnection, $tableName, $primaryKey);
    $activator->activate();
} else {
    echo "Table name and ID not provided!";
}
?>
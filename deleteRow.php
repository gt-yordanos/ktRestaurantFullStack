<?php
include_once 'connection.php';

// Check if table name and id are set in the URL
if (isset($_GET['table']) && isset($_GET['id'])) {
    $tableName = $_GET['table'];
    $primaryKey = $_GET['id'];

    // Escape the primary key value to prevent SQL injection
    $escapedPrimaryKey = $conn->real_escape_string($primaryKey);

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

    if ($conn->query($sql) === TRUE) {
        // Redirect back to admin.php after deletion
        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Table name and ID not provided!";
}
?>

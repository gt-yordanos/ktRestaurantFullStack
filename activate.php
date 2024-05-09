<?php
include_once 'connection.php';

// Check if table name and id are set in the URL
if (isset($_GET['table']) && isset($_GET['id'])) {
    $tableName = $_GET['table'];
    $primaryKey = $_GET['id'];

    // Escape the primary key value to prevent SQL injection
    $escapedPrimaryKey = $conn->real_escape_string($primaryKey);

    // Determine the correct primary key column name based on the table
    $primaryKeyColumnName = ($tableName === 'customerinfo' || $tableName === 'menumanagerinfo' || $tableName === 'paymentmanagerinfo') ? 'email' : (($tableName === 'foodinfo') ? 'food_id' : 'order_id');

    // Perform activation based on table name and primary key
    $sql = "UPDATE $tableName SET activated = 1 WHERE $primaryKeyColumnName = '$escapedPrimaryKey'";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to admin.php after activation
        header("Location: admin.php");
        exit();
    } else {
        echo "Error activating record: " . $conn->error;
    }
} else {
    echo "Table name and ID not provided!";
}
?>

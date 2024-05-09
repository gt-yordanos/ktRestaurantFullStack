<?php
// Start or resume session
session_start();

// Check if user is logged in and session email is set
if (isset($_SESSION['email'])) {
    // Assuming you have a database connection established already
    // Replace 'your_database_host', 'your_database_name', 'your_database_user', and 'your_database_password' with your actual database credentials
    $conn = new mysqli('your_database_host', 'your_database_user', 'your_database_password', 'your_database_name');

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to fetch user balance
    $email = $_SESSION['email'];
    $sql = "SELECT balance FROM customerinfo WHERE email = '$email'";

    // Execute SQL query
    $result = $conn->query($sql);

    // Check if query executed successfully
    if ($result) {
        // Check if user exists in the database
        if ($result->num_rows > 0) {
            // Fetch user balance
            $row = $result->fetch_assoc();
            $balance = $row['balance'];

            // Return balance as JSON response
            echo json_encode(array('balance' => $balance));
        } else {
            // User not found in the database
            echo json_encode(array('error' => 'User not found'));
        }
    } else {
        // Error executing query
        echo json_encode(array('error' => 'Error executing query'));
    }

    // Close database connection
    $conn->close();
} else {
    // Session email not set, user not logged in
    echo json_encode(array('error' => 'User not logged in'));
}
?>

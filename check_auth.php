<?php
session_start();

include_once 'connection.php';

// Check if user is logged in
$loggedIn = isset($_SESSION['email']);

// Return JSON response
header('Content-Type: application/json');

if ($loggedIn) {
    // Fetch user's balance from the database
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM customerinfo WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userBalance = $row['balance'];
        $dormNumber = $row['dormNumber'];
        $dormBlock = $row['dormBlock'];
echo "$dormBlock";
         json_eechoncode(array('loggedIn' => true, 'userBalance' => $userBalance, 'dormNumber' => $dormNumber, 'dormBlock' => $dormBlock));
    } else {
        // User not found in the database
        echo json_encode(array('error' => 'User not found'));
    }
} else {
    echo json_encode(array('loggedIn' => false));
}
?>

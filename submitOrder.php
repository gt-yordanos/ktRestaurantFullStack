<?php
session_start();

include_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}

// Fetch user's balance from the database
$email = $_SESSION['email'];
$sql = "SELECT * FROM customerinfo WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userBalance = $row['balance'];
    $dormNumber = $row['dormNumber'];
    $dormBlock = $row['dormBlock'];
} else {
    // User not found in the database
    echo json_encode(array('error' => 'User not found'));
    exit();
}

// Check if the form data is received
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(array('error' => 'Invalid data'));
    exit();
}

// Retrieve form data
$foodName = $data['foodName'];
$price = $data['price'];
$quantity = $data['quantity'];
$dormNumber = $data['dormNumber'];
$dormBlock = $data['dormBlock'];

// Calculate total price
$totalPrice = $price * $quantity;

// Check if user has sufficient balance
if ($userBalance < $totalPrice) {
    echo json_encode(array('error' => 'Insufficient balance'));
    exit();
}

// Insert order into orderinfo table
$sql = "INSERT INTO orderinfo (firstName, lastName, dormBlock, dormNumber, email, foodname, quantity, order_datetime, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'Pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $firstName, $lastName, $dormBlock, $dormNumber, $email, $foodName, $quantity);

// Set parameter values and execute the statement
$firstName = $row['firstName'];
$lastName = $row['lastName'];
if ($stmt->execute()) {
    // Update user's balance
    $newBalance = $userBalance - $totalPrice;
    $updateSql = "UPDATE customerinfo SET balance = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ds", $newBalance, $email);
    $updateStmt->execute();
    $updateStmt->close();

    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false));
}

// Close statement
$stmt->close();
?>

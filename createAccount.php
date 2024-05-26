<?php

// Include the connection.php file
include_once 'connection.php';

$dbConnection = new DatabaseConnection();

// Establish the connection
$conn = $dbConnection->connect();
function createAccount($userType, $firstName, $lastName, $email, $password) {

    global $conn;
    $balance = 0.00;
    $profilePicture = null;
    $activated = true;
    $cartItem = ''; 
    $dormBlock = $_POST['dormBlock'];
    $dormNumber = $_POST['dormNumber'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    switch ($userType) {
        case 'customer':
            // Convert array to string
            $cartItem = json_encode($cartItem); // Convert array to JSON string
            $sql = "INSERT INTO customerinfo (firstName, lastName, email, password, balance, profilepicture, activated, cartItem, dormBlock, dormNumber) 
                    VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', $balance, '$profilePicture', $activated, '$cartItem', '$dormBlock', '$dormNumber')";
            break;
        case 'admin':
            $sql = "INSERT INTO admininfo (email, firstName, lastName, password, activated) 
                    VALUES ('$email', '$firstName', '$lastName', '$hashedPassword', $activated)";
            break;
        case 'menuManager':
            $sql = "INSERT INTO menumanagerinfo (email, firstName, lastName, password, activated) 
                    VALUES ('$email', '$firstName', '$lastName', '$hashedPassword', $activated)";
            break;
        case 'paymentManager':
            $sql = "INSERT INTO paymentmanagerinfo (email, firstName, lastName, password, activated) 
                    VALUES ('$email', '$firstName', '$lastName', '$hashedPassword', $activated)";
            break;
        default:
            return "Invalid user type";
    }

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        return "Account created successfully";
    } else {
        return "Error creating account: " . $conn->error;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['userType']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['dormBlock']) && isset($_POST['dormNumber'])) {
        $userType = $_POST['userType'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $dormBlock = $_POST['dormBlock'];
        $dormNumber = $_POST['dormNumber'];

        // Create account
        $result = createAccount($userType, $firstName, $lastName, $email, $password, $dormBlock, $dormNumber);
        echo $result;
    } else {
        echo "All fields are required";
    }
}
?>

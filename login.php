<?php
// Include database connection
include_once 'connection.php';

// Function to perform login
function login($userType, $email, $password) {
    global $conn;
    $location = 0;

    // Prepare SQL statement based on user type
    switch ($userType) {
        case 'customer':
            $tableName = 'customerinfo';
            $location = 'index.php';
            break;
        case 'admin':
            $tableName = 'admininfo';
            $location = 'admin.php';
            $locationUnsuccessful ='adminLogin.php';
            break;
        case 'menuManager':
            $tableName = 'menumanagerinfo';
            break;
        case 'paymentManager':
            $tableName = 'paymentmanagerinfo';
            break;
        default:
            return "Invalid user type";
    }

    // Prepare and bind parameters
    $stmt = $conn->prepare("SELECT password FROM $tableName WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute query
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($hashedPassword);

    // Fetch the value
    if ($stmt->fetch()) {
        // Verify password
        if (password_verify($password, $hashedPassword)) {
            session_start();
            $_SESSION['email'] = $email;
            echo "<script>alert('Login Successful');</script>";
            // Redirect to the successful location after a delay
            echo "<script>setTimeout(function() {";
            echo "window.location.href = '$location';";
            echo "}, 40);</script>";
            exit();
        } else {
            // Password is incorrect
            echo "<script>alert('Password is incorrect');</script>";
        }
    } else {
        // Email not found
        echo "<script>alert('Email not found');</script>";
    }

    // Redirect to the unsuccessful location
    echo "<script>window.location.href = '$locationUnsuccessful';</script>";
    exit();

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['userType']) && isset($_POST['email']) && isset($_POST['password'])) {
        $userType = $_POST['userType'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Perform login
        login($userType, $email, $password);
    }
}
?>

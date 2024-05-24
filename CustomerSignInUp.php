<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign Up & Sign In</title>
    <link rel="stylesheet" href="CSS/CustomerSignInUp.css">
</head>
<body>
<?php
include_once 'connection.php'; // Include the connection.php file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if sign up button is clicked
    if (isset($_POST['signUp'])) {
        // Include the createAccount.php script
        include_once 'createAccount.php';
        
        // Call createAccount function with user type as 'customer'
        $result = createAccount('customer', $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password']);
        
        // Display the result
        echo "<script>alert('$result');</script>";
    }


    if (isset($_POST['signIn'])) {
        // Include the login.php script
        include_once 'login.php';
        
        // Call the login function with user type as 'customer'
        $result = login('customer', $_POST['email'], $_POST['password']);
        if ($result !== true) {
            echo "<script>alert('$result');</script>";
        }
    }
}
?>
<div class="container" id="container">
    <div class="form-container sign-up">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1 class="createAccountTitle" style="color: white;">Create Account</h1>
            <input type="text" placeholder="First Name" name="firstName" required>
            <input type="text" placeholder="Last Name" name="lastName" required>
            <input type="text" placeholder="Dorm Block" name="dormBlock" required>
            <input type="text" placeholder="Dorm Number" name="dormNumber" required>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit" name="signUp">Sign Up</button>
        </form>
    </div>
    <div class="form-container sign-in">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1 class="signInTitle">Sign In</h1>
            <span class="signInDescription">Use your email and password</span>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" placeholder="Password" name="password" required>
            <a href="#">Forget Your password?</a>
            <button type="submit" name="signIn">Sign In</button>
        </form>
    </div>
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Welcome Back!</h1>
                <p>Enter your personal details</p>
                <button class="hidden" id="login">Sign In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hello Dear Customer!</h1>
                <p>Register with your personal details</p>
                <button class="hidden" id="register">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<script src="JS/CustomerSignInUp.js"></script>
</body>
</html>

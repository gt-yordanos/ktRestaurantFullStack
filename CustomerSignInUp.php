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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if sign up button is clicked
    if (isset($_POST['signUp'])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ktrestaurant";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO customerinfo (firstName, lastName, dormBlock, dormNumber, balance, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdss", $firstName, $lastName, $dormBlock, $dormNumber, $balance, $email, $password);

        // Set parameters and hash password
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $dormBlock = $_POST['dormBlock'];
        $dormNumber = $_POST['dormNumber'];
        $balance = 0.00; // Set balance to 0
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Execute query
        if ($stmt->execute()) {
            // Registration successful
            echo "<script>alert('Registration successful!');</script>";
        } else {
            // Registration failed
            echo "<script>alert('Registration failed!');</script>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }

    // Check if sign in button is clicked
    if (isset($_POST['signIn'])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ktrestaurant";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind parameters
        $stmt = $conn->prepare("SELECT password FROM customerinfo WHERE email = ?");
        $stmt->bind_param("s", $email);

        // Set parameters
        $email = $_POST['email'];

        // Execute query
        $stmt->execute();

        // Bind result variables
        $stmt->bind_result($hashed_password);

        // Fetch the value
        if ($stmt->fetch()) {
            // Verify password
            if (password_verify($_POST['password'], $hashed_password)) {
                // Password is correct, sign in successful
                session_start();
                $_SESSION['email'] = $email;
                header("location: index.php?email=" . $email); // Redirect to index.php
                exit();
            } else {
                // Password is incorrect
                echo "<script>alert('Incorrect email or password!');</script>";
            }
        } else {
            // Email not found
            echo "<script>alert('Incorrect email or password!');</script>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
<div class="container" id="container">
    <div class="form-container sign-up">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1 class="createAccountTitle" style = "color: white;">Create Account</h1>
            <span class="createAccountDescription" >Fill the following form in order to register.</span>
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

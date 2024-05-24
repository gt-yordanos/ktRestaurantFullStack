<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In</title>
    <link rel="stylesheet" href="CSS/adminLogin.css">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form method="POST" action="login.php"> <!-- Change action to login.php -->
                <h1 class="signinTitle">Menu Manager Sign In</h1>
                <span class="signinDescription">Use your email and password</span>
                <input type="hidden" name="userType" value="menuManager"> <!-- Add hidden input for user type -->
                <input type="email" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <a href="#">Forget Your password?</a>
                <button type="submit" name="signIn">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>

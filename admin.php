<?php
session_start(); // Start the session

// Include database connection
include_once 'connection.php';

// Check if email is passed from CustomerSignInUp.php
if(isset($_GET['email'])) {
    $_SESSION['email'] = $_GET['email'];
}

// Check if email is set, if not, set it to null
$_SESSION['email'] = $_SESSION['email'] ?? null;

// Logout logic
if(isset($_GET['logout'])) {
    // Unset the email session variable
    unset($_SESSION['email']);
    // Redirect to the sign-in page
    header("Location: CustomerSignInUp.php");
    exit(); // Ensure script execution stops after redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="CSS/styles.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <a href="#" class="logo"> <img src="Image/KT-Logo.png" alt="KT Restaurant Logo"></a>
    <ul class="navlist">
      <li><a href="#home" class="active">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#menu">Menu</a></li>
      <li><a href="#review">Our Customer</a></li>
      <li><a href="#contact">Contact Us</a></li>
      <li><a href="admin.php">Admin Panel</a></li> <!-- New link for Admin Panel -->
    </ul>
  </header>

  <!-- Admin dashboard content -->
  <section class="admin-dashboard">
    <div class="admin-content">
      <h2>Welcome to the Admin Dashboard</h2>
      <p>You can manage products, orders, and customers from here.</p>
      <!-- Admin-specific functionality can be added here -->
      <div class="admin-actions">
        <button id="manage-products">Manage Products</button>
        <button id="manage-orders">Manage Orders</button>
        <button id="manage-customers">Manage Customers</button>
      </div>
      <div class="logout">
        <a href="?logout=true">Logout</a>
      </div>
    </div>
  </section>

  <footer>
    <section class="copyright">
      <p><span class="copyright-symbol">&copy;</span> 2024 KT Restaurant. All rights reserved.</p>
    </section>
  </footer>
</body>
</html>

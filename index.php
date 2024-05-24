<?php
session_start();

include_once 'connection.php';

// Check if user is logged in
$loggedIn = isset($_SESSION['email']);

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
    } else {
        // User not found in the database
        echo json_encode(array('error' => 'User not found'));
        exit();
    }

    if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to index.php
        header('Location: index.php');
        exit();
    }
}

$sql = "SELECT * FROM foodinfo WHERE activated = 1";
$result = $conn->query($sql);

$foods = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foods[] = $row;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KT Restaurant</title>
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
    </ul>

    <div class="icons">
      <!-- Dark & Light Mode -->
    <div class="light-dark">
      <a href="" class="light-mode">
        <i class='bx bxs-sun'></i>
      </a>
     </div>
    <div class="nav-icons">
      <a href="#"><i class='bx bx-search'></i></a>
      <a href="#"><i class='bx bx-cart'></i></a>
      <div class="bx bx-menu" id="menu-icon"></div>
      <div class="count">
        <span id="cart-count">0</span>
      </div>
    </div>
      
    <div class="profile">
    <i class='bx bx-user'></i>
    <!-- Customer Dashboard -->
    <section class="dashboard" id="dashboard" style="display: none;">
      <div class="dashboard-content">
        <h2>Account Information</h2>
        <div id="account-info">
          <?php
          if ($loggedIn) {
              // Fetch account information
              $email = $_SESSION['email'];
              $sql = "SELECT * FROM customerinfo WHERE email = '$email'";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<p><strong>First Name:</strong> " . $row["firstName"] . "</p>";
                      echo "<p><strong>Last Name:</strong> " . $row["lastName"] . "</p>";
                      echo "<p><strong>Dorm Block:</strong> " . $row["dormBlock"] . "</p>";
                      echo "<p><strong>Dorm Number:</strong> " . $row["dormNumber"] . "</p>";
                      echo "<p><strong>Balance:</strong> " . $row["balance"] . "</p>";
                      echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
                  }
                  // Logout link
                  echo "<button id='update-account' >Update Account</button>
                        <div class='logout'>
                          <a href='?logout=true'>Logout</a>
                        </div>";
              } else {
                  echo "0 results";
              }}
              else {
              echo "<p>Please log in to view account information.</p>";
              echo  "<a href='CustomerSignInUp.php' class='login' style='
              background-color: rgb(4, 141, 86);
              text-align: center;
              color: white;
              border: none;
              padding: 10px 20px;
              font-size: 16px;
              border-radius: 5px;
              cursor: pointer;
              transition: background-color 0.3s ease;
              display: block;
              margin: auto;
              width: 90%;
              '>Login</a>";
          }
          ?>
    </div>
  </div>
</section>

    </div>
    </div>
    
  </header>

    <!-- Cart Card -->
  <div class="cart">
    <div class="cart-header">
      <h2>Cart</h2>
      <button class="hide-cart-btn"><i class='bx bx-left-arrow-alt'></i></button>
    </div>
    <div id="cart-items" class="cart-items"></div>
    <div class="cart-footer">
      <p>Total: <span id="cart-count">0</span> items</p>
    </div>
  </div>

  <!-- Home -->
<section class="home" id="home">
  <div class="home-text">
    <h1><span class="main">ከሚወዱት ሰዉ ጋር</span> ወደ እኛ ይምጡ <span class="main2">ይብሉ ፣ </span>ይጠጡ ፣ ያጣጥሙ ይደሰቱ</h1>
    <a href="#" class="btn">Explore Menu <i class='bx bxs-right-arrow'></i></a>

  </div>
  <div class="home-img">
    <img src="Image/Home.jpeg" alt="Shiro picture">
  </div>
</section>

<!-- Container -->
<section class="container">
  <div class="container-box">
    <i class='bx bxs-time' ></i>
    <h3>11:00 am - 8:00 pm</h3>
    <a href="#">Working Hours</a>
  </div>

  <div class="container-box">
    <i class='bx bxs-map'></i>
    <h3>Inside the HU Stadium</h3>
    <a href="#">Get Directions</a>
  </div>

  <div class="container-box">
    <i class='bx bxs-phone'></i>
    <h3>(+251) 97667767</h3>
    <a href="#">Call Us Now</a>
  </div>
</section>
<!-- About us -->
<section class="about" id="about">
  <div class="about-img">
    <img src="Image/About.jpeg" alt="">
  </div>
  <div class="about-text">
    <h2>Living well begins <br> with eating well</h2>
    <p>
      At KT Restaurant, Our dedication lies in providing the beloved campus community 
      with delicious fare at affordable prices, ensuring that every student feels at home away from home. 
      <br> <br>KT Restaurant is more than just a dining spot; it's a haven where good food meets affordability,
       and where the warmth of home is always on the menu.
      </p>
      <a href="#" class="btn">Explore Menu <i class='bx bxs-right-arrow'></i></a>
  </div>
</section>

<!-- Menu section -->
<section class="menu" id="menu">
    <div class="middle-text">
      <h4>Our Menu</h4>
      <h2>Lets Check Some of Our Delicious Dishes</h2>
    </div>
    <div class="menu-content">  
      <?php
      // Display each food item fetched from the database
      if (!empty($foods)) {
          foreach ($foods as $food) {
              ?>
              <div class="row">
                <div class="img-box">
                  <img src="<?php echo $food['foodImage']; ?>" alt="<?php echo $food['foodName']; ?>">
                </div>
                <div class="text-box">
                  <h3><?php echo $food['foodName']; ?></h3>
                  <p><?php echo $food['description']; ?></p>
                  <div class="in-text">
                    <div class="price">
                      <h6><?php echo $food['price']." Br"; ?></h6>
                    </div>
                    <div class="s-btn">
                      <a href="#">Order now</a>
                    </div>
                    <div class="top-icon">
                      <a href="#"><i class='bx bxs-cart-add'></i></a>
                    </div>
                  </div>
                </div>
              </div>
              <?php
          }
      } else {
          echo "No food items available.";
      }
      ?>
    </div>  
  </section>

<!-- Reviews -->
<section class="review" id="review">
  <div class="middle-text">
    <h4>Our Customer</h4>
    <h2>Client Reviews About Our Food</h2>
  </div>
  <div class="review-content">

    <div class="box">
      <p>The shiro at KT Restaurant was simply amazing, packed with flavor and served with injera, 
        it's an absolute must-try!
      </p>
      <div class="in-box">
        <div class="box-img">
          <img src="ClientImage/IMG_7031.jpeg" alt="">
        </div>
        <div class="box-text">
          <h4>Yordanos Genene</h4>
          <h5>Developer</h5>
          <div class="ratings">
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="box">
      <p>Beyaynetu at KT Restaurant brought back memories of home; the variety of dishes, including injera and lentils, 
        were all delicious and authentic.
      </p>
      <div class="in-box">
        <div class="box-img">
          <img src="ClientImage/IMG_7031.jpeg" alt="">
        </div>
        <div class="box-text">
          <h4>Yordanos Genene</h4>
          <h5>Developer</h5>
          <div class="ratings">
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="box">
      <p>The tibs at KT Restaurant were perfectly seasoned and tender,
         making for a delightful meal that I'll definitely come back for.
      </p>
      <div class="in-box">
        <div class="box-img">
          <img src="ClientImage/IMG_7031.jpeg" alt="">
        </div>
        <div class="box-text">
          <h4>Yordanos Genene</h4>
          <h5>Developer</h5>
          <div class="ratings">
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="box">
      <p>Firfir at KT Restaurant was a delightful surprise; the combination of spices and textures made it a standout dish for me.
      </p>
      <div class="in-box">
        <div class="box-img">
          <img src="ClientImage/IMG_7031.jpeg" alt="">
        </div>
        <div class="box-text">
          <h4>Yordanos Genene</h4>
          <h5>Developer</h5>
          <div class="ratings">
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="box">
      <p>Kekel at KT Restaurant was a refreshing dish that perfectly complemented the other flavors on the menu; 
        I highly recommend trying it!
      </p>
      <div class="in-box">
        <div class="box-img">
          <img src="ClientImage/IMG_7031.jpeg" alt="">
        </div>
        <div class="box-text">
          <h4>Yordanos Genene</h4>
          <h5>Developer</h5>
          <div class="ratings">
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="box">
      <p>The shiro at KT Restaurant was simply amazing, packed with flavor and served with injera, 
        it's an absolute must-try!
      </p>
      <div class="in-box">
        <div class="box-img">
          <img src="ClientImage/IMG_7031.jpeg" alt="">
        </div>
        <div class="box-text">
          <h4>Yordanos Genene</h4>
          <h5>Developer</h5>
          <div class="ratings">
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
            <a href="#"><i class='bx bxs-star'></i></a>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</section>

<!-- Scroll Top -->

<a href="#" class="scroll">
  <i class='bx bx-up-arrow-alt'></i>
</a>


<!-- Footer -->
<footer>

  <!-- Contact Us -->

  <section class="contact" id="contact">
    <div class="contact-content">
      <div class="middle-text">
        <h4>Contact Us</h4>
        <h2>Call and Follow us on Social Networks</h2>
      </div>
      <div class="contact-text">
        <div class="social">
          <a href=""><i class='bx bxl-instagram-alt'></i></a>
          <a href=""><i class='bx bxl-facebook'></i></a>
          <a href=""><i class='bx bxl-tiktok'></i></a>
          <a href=""><i class='bx bxl-github'></i></a>
          <a href=""><i class='bx bxl-youtube'></i></a>
        </div>
        <div class="details">
          <div class="main-d">
            <a href=""><i class='bx bxs-location-plus'></i>Inside the HU Stadium</a>
          </div>
          <div class="main-d">
            <a href=""><i class='bx bx-mobile-alt'></i>(+251) 97667767</a>
          </div>
          <div class="main-d">
            <a href=""><i class='bx bxs-envelope'></i>kt@hotmail.com</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Copyright -->

  <section class="copyright">
    <p><span class="copyright-symbol">&copy;</span> 2024 KT Restaurant. All rights reserved.</p>
  </section>
  
</footer>
    


  <script src="JS/Script.js"></script>
  <script>
const dashboard = document.getElementById("dashboard");
const profileIcon = document.querySelector(".profile i");

// Toggle dashboard when profile icon is clicked
profileIcon.addEventListener("click", function() {
    console.log("hey again");
    dashboard.style.display = dashboard.style.display === "none" ? "block" : "none";
});

const orderButtons = document.querySelectorAll(".s-btn a");

orderButtons.forEach(button => {
    button.addEventListener("click", function(event) {
        event.preventDefault();
        fetch("check_auth.php")
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                const foodName = this.closest(".text-box").querySelector("h3").innerText;
                const priceText = this.closest(".text-box").querySelector(".price h6").innerText;
                const price = parseFloat(priceText.replace(' Br', ''));
                console.log(data.userBalance);
                if (data.userBalance >= price) {
                    showOrderPopup(foodName, price, data.dormNumber, data.dormBlock);
                } else {
                    alert("Insufficient balance to place this order.");
                }
            } else {
                alert("Please login first to place an order.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
            alert("An error occurred while fetching user data. Please try again later.");
        });
    });
});

function showOrderPopup(foodName, price, dormNumber, dormBlock) {
    const popupDiv = document.createElement("div");
    popupDiv.classList.add("popup");
    popupDiv.innerHTML = `
        <div class="popup-content">
            <h2>Place Order</h2>
            <label for="quantity">Quantity:</label>
            <input type="number" min="1" id="quantity" value="1" name="quantity" required>
            <label for="dormBlock">Dorm Block:</label>
            <input type="text" id="dormBlock" name="dormBlock" value="${dormBlock}" required>
            <label for="dormNumber">Dorm Number:</label>
            <input type="text" id="dormNumber" name="dormNumber" value="${dormNumber}" required>
            <button id="submitOrderBtn">Submit Order</button>
            <button id="closePopupBtn">Close</button>
        </div>
    `;
    document.body.appendChild(popupDiv);

    const submitOrderBtn = popupDiv.querySelector("#submitOrderBtn");
    const closePopupBtn = popupDiv.querySelector("#closePopupBtn");

    submitOrderBtn.addEventListener("click", function() {
        const quantity = parseInt(document.getElementById("quantity").value);
        // Send order data to server
        fetch("submitOrder.php", {
            method: "POST",
            body: JSON.stringify({
                foodName: foodName,
                price: price,
                quantity: quantity,
                dormNumber: dormNumber,
                dormBlock: dormBlock
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Order placed successfully!");
                // Close the popup
                popupDiv.remove();
            } else {
                alert("Failed to place order. Please try again later.");
            }
        })
        .catch(error => {
            console.error("Error submitting order:", error);
            alert("An error occurred while placing the order. Please try again later.");
        });
    });

    closePopupBtn.addEventListener("click", function() {
        popupDiv.remove();
    });
}

</script>
</body>
</html>

<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to the login page
    header("Location: menuManagerLogin.php");
    exit();
}

// Check if the logout action is triggered
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: menuManagerLogin.php");
    exit();
}

// Include database connection
include_once 'connection.php';

// Fetch all tables
$tables = array(
    'orderinfo' => 'Order Information',
    'foodinfo' => 'Food Information'
);

// Function to display a table
function displayTable($tableName, $tableTitle, $primaryKey) {
    global $conn;

    echo "<h2>$tableTitle</h2>";

    // Fetch data from the table
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display table headers
        echo "<table class='admin-table'>";
        echo "<tr>";
        // Display table headers
        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>$fieldinfo->name</th>";
        }
        // Actions column with colspan
        echo "<th colspan='3'>Actions</th>";
        echo "</tr>";

        // Display table data
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            // Display table data
            foreach ($row as $key => $value) {
                echo "<td>$value</td>";
            }
            // Actions buttons inside individual columns
            echo "<td>";
            if ($tableName === 'orderinfo') {
                echo "<button class='update-status-btn'>Update Status</button>";
            } else {
                $buttonText = $row['activated'] ? 'Deactivate' : 'Activate';
                // Call deactivateRow or activateRow function based on button text
                $onClickFunction = $row['activated'] ? "deactivateRow" : "activateRow";
                echo "<button class='activate-btn' onclick='$onClickFunction(\"foodinfo\", \"{$row[$primaryKey]}\")'>$buttonText</button>";
            }
            echo "</td>";
            // For orderinfo table, add an empty column to maintain the table structure
            if ($tableName === 'orderinfo') {
                echo "<td></td><td></td>";
            } else {
                // For other tables, display two additional buttons
                echo "<td><button class='delete-btn' onclick='deleteRow(\"$tableName\", \"{$row[$primaryKey]}\")'>Delete</button></td>";
                // Redirect to update.php with table name, primary key, columns, and role(admin)
                $columns = json_encode($row);
                echo "<td><button class='update-btn' onclick='updateRow(\"foodinfo\", \"{$row[$primaryKey]}\", " . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ", \"admin\")'>Update</button></td>";

            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No data available for $tableTitle";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Manager Dashboard</title>
    <link rel="stylesheet" href="CSS/admin.css">
    <style>
      .navbar{
        background-color: #ff9f0d;
      }
      .navbar a{
        color: #111111;
      }
      #tableDropdown{
        background-color: #111111;
        color: #ff9f0d;
      }
     
    </style>
</head>
<body>

<div class="navbar" style="">
    <a href="#">Menu Manager</a>
    <a href="#">Home</a>
    <a href="#">About</a>
    <a class="logout" href="menuManager.php?action=logout">Logout</a> <!-- Logout button -->
</div>

<div class="container">
    <h2>Welcome to the Menu Manager Dashboard</h2>
    <select id="tableDropdown">
        <option value="">Select a table</option>
        <?php foreach ($tables as $tableName => $tableTitle) : ?>
            <option value="<?php echo $tableName; ?>"><?php echo $tableTitle; ?></option>
        <?php endforeach; ?>
    </select>
    <?php
    // Display tables with class 'hidden' to hide them by default
    foreach ($tables as $tableName => $tableTitle) {
        // Define primary key column name for each table
        $primaryKey = ($tableName === 'customerinfo' || $tableName === 'menumanagerinfo' || $tableName === 'paymentmanagerinfo') ? 'email' : (($tableName === 'foodinfo') ? 'food_id' : 'order_id');
        echo "<div class='hidden' id='$tableName'>";
        displayTable($tableName, $tableTitle, $primaryKey);
        echo "</div>";
    }
    ?>
</div>

<script>
    function deleteRow(tableName, primaryKey) {
        if (confirm("Are you sure you want to delete this row?")) {
            // Redirect to deleteRow.php with table name and primary key
            window.location.href = `deleteRow.php?table=${tableName}&id=${primaryKey}`;
        }
    }

    function deactivateRow(tableName, primaryKey) {
        // Redirect to deactivate.php with table name and primary key
        window.location.href = `deactivate.php?table=${tableName}&id=${primaryKey}`;
    }

    function activateRow(tableName, primaryKey) {
        // Redirect to activate.php with table name and primary key
        window.location.href = `activate.php?table=${tableName}&id=${primaryKey}`;
    }

    function updateRow(tableName, primaryKey, columns, role) {
        // Redirect to update.php with table name, primary key, columns, and role
        console.log("yes");
        console.log("Update Row clicked", tableName, primaryKey, columns, role);
        window.location.href = `update.php?table=${tableName}&id=${primaryKey}&columns=${encodeURIComponent(columns)}&role=${role}`;
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Get table dropdown and table containers
        const tableDropdown = document.getElementById('tableDropdown');
        const tableContainers = document.querySelectorAll('.container > div');

        // Add event listener to dropdown change
        tableDropdown.addEventListener('change', function() {
            const selectedValue = this.value;
            // Hide all tables
            tableContainers.forEach(container => {
                container.classList.add('hidden');
            });
            // Show selected table
            const selectedTable = document.getElementById(selectedValue);
            if (selectedTable) {
                selectedTable.classList.remove('hidden');
            }
        });

        // Initially hide all tables except the first one
        tableContainers.forEach((container, index) => {
            if (index !== 0) {
                container.classList.add('hidden');
            }
        });
    });
</script>

</body>
</html>

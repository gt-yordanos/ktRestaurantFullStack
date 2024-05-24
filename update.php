<?php
// Include connection.php
include_once 'connection.php';

// Function to fetch table data based on primary key
function fetchTableData($conn, $tableName, $primaryKey, $primaryKeyValue) {
    $sql = "SELECT * FROM $tableName WHERE $primaryKey='$primaryKeyValue'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Function to update table data
function updateTableData($conn, $tableName, $primaryKey, $primaryKeyValue, $data, $userRole) {
    $setClause = '';
    $location = ""; // Default location

    foreach ($data as $key => $value) {
        // Exclude the 'submit' button from the update process
        if ($key === 'submit') {
            continue;
        }
        // Check user role to determine which columns can be updated
        switch ($userRole) {
            case 'admin':
                // Allow updating all columns
                $setClause .= "$key='$value',";
                $location = "admin.php";
                break;
            case 'customer':
                // Exclude balance and activated columns
                if ($key !== 'balance' && $key !== 'activated') {
                    $setClause .= "$key='$value',";
                }
                $location = "customer.php";
                break;
            case 'menuManager':
                // Only allow updating the foodinfo table
                if ($tableName === 'foodinfo') {
                    $setClause .= "$key='$value',";
                    $location = "menuManager.php";
                }
                break;
            case 'paymentManager':
                // Only allow updating the balance column of customerinfo table
                if ($tableName === 'customerinfo' && $key === 'balance') {
                    $setClause .= "$key='$value',";
                    $location = "paymentManager.php";
                }
                break;
            default:
                // Redirect to login.php or show an error message for invalid role
                header("Location: login.php");
                exit;
        }
    }
    // Remove trailing comma
    $setClause = rtrim($setClause, ',');

    // Update table only if the SET clause is not empty
    if ($setClause !== '') {
        $sql = "UPDATE $tableName SET $setClause WHERE $primaryKey='$primaryKeyValue'";
        if ($conn->query($sql)) {
            return $location;
        } else {
            return false; // Error updating data
        }
    } else {
        return false; // No update needed
    }
}

// Check if table name, primary key, and role are provided in the URL
if (isset($_GET['table']) && isset($_GET['id']) && isset($_GET['role'])) {
    $tableName = $_GET['table'];
    // Define primary key column name for each table
    $primaryKey = ($tableName === 'customerinfo' || $tableName === 'menumanagerinfo' || $tableName === 'paymentmanagerinfo') ? 'email' : (($tableName === 'foodinfo') ? 'food_id' : 'order_id');
    $primaryKeyValue = $_GET['id']; // Get primary key value from URL
    $userRole = $_GET['role']; // Get user role from URL

    // Fetch table data based on primary key
    $tableData = fetchTableData($conn, $tableName, $primaryKey, $primaryKeyValue);

    // Check if table data is retrieved successfully
    if ($tableData) {
        // Check if form is submitted for updating
        if (isset($_POST['submit'])) {
            // Update table data with the submitted values
            $redirectLocation = updateTableData($conn, $tableName, $primaryKey, $primaryKeyValue, $_POST, $userRole);
            if ($redirectLocation) {
                // Redirect to the appropriate page after successful update
                echo "<script>alert('Data updated successfully.'); window.location.href = '$redirectLocation';</script>";
                exit;
            } else {
                // Handle update error
                echo "<script>alert('Error updating data. Please try again.');</script>";
            }
        }
    } else {
        // Handle invalid primary key
        echo "<script>alert('Invalid primary key.');</script>";
        // Redirect to admin.php or show an error message
        header("Location: admin.php");
        exit;
    }
} else {
    // Handle missing table name, primary key, or role
    echo "<script>alert('Table name, primary key, or role is missing.');</script>";
    // Redirect to admin.php or show an error message
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data</title>
    <link rel="stylesheet" href="CSS/admin.css">
</head>
<body>

<div class="container">
    <h2>Update Data</h2>

    <form action="" method="post">
        <?php foreach ($tableData as $key => $value) : ?>
            <div>
                <label for="<?php echo $key; ?>"><?php echo ucfirst($key); ?>:</label>
                <?php if ($key === 'cartItem') : ?>
                    <?php $cartItems = json_decode($value, true); ?>
                    
                <?php else : ?>
                    <input type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <input type="submit" name="submit" value="Update">
    </form>
</div>

</body>
</html>

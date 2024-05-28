<?php


$connection = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve the email and password from POST
$email = $_POST['email']; // Assuming email is provided via POST
$newPassword = $_POST['password']; // Assuming password is provided via POST

// Retrieve the userId based on the provided email
$query = "SELECT userId FROM profile_employee WHERE email='$email'";
$result = $connection->query($query);

if ($result) {
    // Fetch the userId
    $row = $result->fetch_assoc();
    $userId = $row['userId'];
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the login table
    $updateQuery = "UPDATE login SET password='$hashedPassword' WHERE userId='$userId'";
    $updateResult = $connection->query($updateQuery);

    if ($updateResult) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password: " . $connection->error;
    }
} else {
    echo "Error retrieving userId: " . $connection->error;
}

// Close the database connection
$connection->close();
?>

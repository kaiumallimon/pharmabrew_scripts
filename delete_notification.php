<?php
// Database connection parameters
$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$dbname = "bcryptsi_pharmabrew";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if message ID is provided through POST
if(isset($_POST['notification_id'])) {
    // Sanitize input to prevent SQL injection
    $notification_id = mysqli_real_escape_string($conn, $_POST['notification_id']);

    // SQL query to delete message
    $sql = "DELETE FROM notification WHERE notification.notificationId='$notification_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Notification deleted successfully";
    } else {
        echo "Error deleting Notification: " . $conn->error;
    }
} else {
    echo "Notification ID not provided";
}

// Close connection
$conn->close();
?>
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
if(isset($_POST['message_id'])) {
    // Sanitize input to prevent SQL injection
    $message_id = mysqli_real_escape_string($conn, $_POST['message_id']);

    // SQL query to delete message
    $sql = "DELETE FROM Messages WHERE Messages.message_id='$message_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Message deleted successfully";
    } else {
        echo "Error deleting message: " . $conn->error;
    }
} else {
    echo "Message ID not provided";
}

// Close connection
$conn->close();
?>

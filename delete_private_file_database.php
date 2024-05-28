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

// Check if user and fileName are provided through POST
if(isset($_POST['user'], $_POST['fileName'])) {
    // Sanitize input to prevent SQL injection
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $fileName = mysqli_real_escape_string($conn, $_POST['fileName']);

    // SQL query to delete the file for the given user
    $sql = "DELETE FROM private_files WHERE user='$user' AND fileName='$fileName'";

    if ($conn->query($sql) === TRUE) {
        echo "File deleted successfully";
    } else {
        echo "Error deleting file: " . $conn->error;
    }
} else {
    echo "User or file name not provided";
}

// Close connection
$conn->close();
?>

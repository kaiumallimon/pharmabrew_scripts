<?php
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Receive username and password from client
$email = $_POST['email'];
$password = $_POST['password'];

// Query to fetch user from database
$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows > 0) {
    // User found, return success message or user data
    // $user = $result->fetch_assoc();
    // , "user" => $user
    echo json_encode(array("success" => true), JSON_PRETTY_PRINT);
} else {
    // User not found, return error message
    echo json_encode(array("success" => false, "message" => "Invalid username or password"), JSON_PRETTY_PRINT);
}

// Close database connection
$conn->close();
?>

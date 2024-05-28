<?php

// Retrieve POST data
if(isset($_POST['userid']) && isset($_POST['password'])) {
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    // Database connection parameters
    $servername = "localhost";
    $username = "bcryptsi_admin";
    $password_db = "#WYcTZ06d4#oy0";
    $dbname = "bcryptsi_pharmabrew";

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT password FROM login WHERE userId = ?");
    $stmt->bind_param("s", $userid);

    // Execute SQL statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct
            $response = array("success" => true, "message" => "Password is correct.");
        } else {
            // Password is incorrect
            $response = array("success" => false, "message" => "Password is incorrect.");
        }
    } else {
        // User not found
        $response = array("success" => false, "message" => "User not found.");
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Missing parameters
    $response = array("success" => false, "message" => "User ID or password not provided.");
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>

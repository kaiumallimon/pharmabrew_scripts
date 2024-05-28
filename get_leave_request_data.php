<?php

// Assuming you have already established a connection to your MySQL database

$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$dbname = "bcryptsi_pharmabrew";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get userId from POST request
$user = $_POST['userId'];

if (empty($user)) {
    http_response_code(400);
    echo json_encode(["error" => "User ID is required"]);
    $conn->close();
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM leave_request WHERE userId = ? ORDER BY request_date DESC");
$stmt->bind_param("s", $user);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $leave_requests = [];

    while ($row = $result->fetch_assoc()) {
        $leave_requests[] = $row;
    }

    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();

    if (!empty($leave_requests)) {
        // Convert data to JSON
        header('Content-Type: application/json');
        echo json_encode($leave_requests);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "No leave requests found"]);
    }
} else {
    // Handle execute error
    http_response_code(500);
    echo json_encode(["error" => "Error executing query: " . $stmt->error]);
    
    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();
    exit;
}

?>

<?php

// Set up connection parameters
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if receiver_id is set in the POST request
if (isset($_POST['notification_id'])) {
    // Sanitize input to prevent SQL injection
    $receiver_id = $_POST['notification_id'];

    // Prepare and execute the update statement
    $sql = "UPDATE notification SET status = 'read' WHERE notificationId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $receiver_id);

    // Execute statement
    if ($stmt->execute()) {
        // Success response
        $response = array("success" => true);
    } else {
        // Error response
        $response = array("success" => false, "error" => $conn->error);
    }

    // Close statement
    $stmt->close();
} else {
    // Error response for missing receiver_id
    $response = array("success" => false, "error" => "Receiver ID is required");
}

// Close connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

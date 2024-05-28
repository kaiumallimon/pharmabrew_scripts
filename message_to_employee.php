<?php

$mysqli = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$currentTimestamp = date('Y-m-d H:i:s');
// Values to be inserted
$senderId = $_POST['sender_id'];
$receiverId = $_POST['receiver_id'];
$message = $_POST['message_content'];
$time = date('Y-m-d H:i:s');

// Prepare the SQL query
$query = "INSERT INTO Messages(sender_id, receiver_id, message_content, TIMESTAMP, status_user, status_admin, send_by) VALUES(?, ?, ?, ?, 'unread', 'read', 'hr')";

// Prepare the statement
if ($stmt = $mysqli->prepare($query)) {
    // Bind parameters
    $stmt->bind_param("ssss", $senderId, $receiverId, $message, $time);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Message inserted successfully.";
        
         // Insert a single notification for all HR administrators
            $notificationContent = "New message from employee Admin";
            $notificationInsertSql = "INSERT INTO notification (sender_id, receiver_id,content, created_at, status, receiver) VALUES ('$senderId','$receiverId', '$notificationContent', '$currentTimestamp', 'unread', 'employee')";
            if ($mysqli->query($notificationInsertSql) !== TRUE) {
                echo "Error: " . $notificationInsertSql . "<br>" . $conn->error;
            }
    } else {
        echo "Error: " . $mysqli->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error: " . $mysqli->error;
}

// Close the connection
$mysqli->close();
?>

<?php
// Connect to MySQL
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch notifications
$sql = "SELECT * FROM notification ORDER BY notification.created_at DESC;";
$result = $conn->query($sql);

// Check if notifications exist
if ($result->num_rows > 0) {
    // Create an array to store notifications
    $notifications = array();

    // Fetch notifications data
    while ($row = $result->fetch_assoc()) {
        $notification = array(
            "notification_id" => $row["notificationId"],
            "sender_id" => $row["sender_id"],
            "receiver_id" => $row["receiver_id"],
            "content" => $row["content"],
            "created_at" => $row["created_at"],
            "status" => $row["status"],
            "receiver"=> $row['receiver']
        );

        // Add notification to the array
        $notifications[] = $notification;
    }

    // Encode notifications array to JSON with indentation
    $jsonNotifications = json_encode($notifications, JSON_PRETTY_PRINT);

    // Output JSON
    echo $jsonNotifications;
} else {
    // No notifications found
    echo "No notifications found";
}

// Close database connection
$conn->close();
?>

<?php
// Connect to MySQL
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch current timestamp
$currentTimestamp = date('Y-m-d H:i:s');

// Query to fetch user from database
$sql = "SELECT userId FROM login WHERE role = 'HR'";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows > 0) {
    // User found, fetch message data from the frontend (assuming it's sent via POST request)
    if(isset($_POST['sender_id'], $_POST['message'], $_POST['from'])) {
        $senderUserId = mysqli_real_escape_string($conn, $_POST['sender_id']); // Sanitize and validate sender ID
        $messageContent = mysqli_real_escape_string($conn, $_POST['message']); // Sanitize and validate message content
        $send = mysqli_real_escape_string($conn, $_POST['from']); // Sanitize and validate sender
        
        // Prepare and execute INSERT query for each HR admin
        while ($row = $result->fetch_assoc()) {
            $receiverUserId = $row['userId'];
    
            $insertSql = "INSERT INTO Messages (sender_id, receiver_id, message_content, timestamp, status_user, status_admin, send_by) VALUES ('$senderUserId', '$receiverUserId', '$messageContent', '$currentTimestamp', 'read', 'unread', '$send')";
            if ($conn->query($insertSql) !== TRUE) {
                echo "Error: " . $insertSql . "<br>" . $conn->error;
            }
        }
        
        // Fetch sender's name
        $nameQuery = "SELECT name FROM profile_employee WHERE userId='$senderUserId'";
        $nameResult = $conn->query($nameQuery);
        if ($nameResult->num_rows > 0) {
            $nameRow = $nameResult->fetch_assoc();
            $senderName = $nameRow['name'];
            
            // Insert a single notification for all HR administrators
            $notificationContent = "New message from employee $senderName";
            $notificationInsertSql = "INSERT INTO notification (sender_id, content, created_at, status, receiver) VALUES ('$senderUserId', '$notificationContent', '$currentTimestamp', 'unread', 'hr')";
            if ($conn->query($notificationInsertSql) !== TRUE) {
                echo "Error: " . $notificationInsertSql . "<br>" . $conn->error;
            }
        } else {
            echo "Error fetching sender's name.";
        }
        
        echo "Messages sent successfully to HR administrators.";
    } else {
        echo "Incomplete data received from frontend.";
    }
} else {
    // User not found, return error message
    echo "No users found with HR role";
}

// Close database connection
$conn->close();
?>

<?php

// Database connection parameters
$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$dbname = "bcryptsi_pharmabrew";

// Check if sender_id is provided via POST
if(isset($_POST['sender_id'])) {
    $sender_id = $_POST['sender_id'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch messages
    $sql = "SELECT sender_id,
       message_id,
       message_content,
       timestamp,
       send_by,
       status_admin,
       status_user,
       sender_name
FROM (
    SELECT Messages.sender_id, 
           Messages.message_id,
           Messages.message_content, 
           Messages.timestamp, 
           Messages.send_by, 
           Messages.status_admin, 
           Messages.status_user,
           CASE 
               WHEN Messages.send_by = 'hr' THEN profile_employee.name
               ELSE NULL 
           END AS sender_name,
           ROW_NUMBER() OVER (PARTITION BY Messages.sender_id, 
                                         Messages.message_content, 
                                         Messages.timestamp, 
                                         Messages.send_by, 
                                         Messages.status_admin, 
                                         Messages.status_user
                              ORDER BY Messages.message_id) AS row_num
    FROM Messages 
    LEFT JOIN profile_employee ON Messages.sender_id = profile_employee.userId
    WHERE Messages.sender_id = '$sender_id' OR Messages.receiver_id = '$sender_id' 
) AS subquery
WHERE row_num = 1
ORDER BY timestamp ASC;
";

    // Execute query
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Array to hold messages
        $messages = array();

        // Fetch data and add to array
        while($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        // Convert array to JSON
        $json_messages = json_encode($messages);

        // Output JSON
        header('Content-Type: application/json');
        echo $json_messages;
    } else {
        // No messages found
        echo json_encode(array('message' => 'No messages found.'));
    }

    // Close connection
    $conn->close();
} else {
    // If sender_id is not provided via POST
    echo json_encode(array('error' => 'Sender ID not provided.'));
}

?>

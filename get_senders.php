<?php
// Connect to MySQL
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "SELECT sender_id, receiver_id, messages, timestamp
FROM (
    SELECT sender_id, receiver_id, GROUP_CONCAT(message_content SEPARATOR '***') AS messages, MAX(timestamp) AS timestamp
    FROM Messages
    GROUP BY sender_id, receiver_id
) AS subquery
ORDER BY timestamp DESC;
";

// Execute query
$result = $conn->query($sql);

// Check if there are rows in the result
if ($result->num_rows > 0) {
    // Initialize an array to hold the results
    $data = array();
    
    // Fetch rows one by one
    while ($row = $result->fetch_assoc()) {
        // Add each row to the data array
        $data[] = $row;
    }
    
    // Output JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
} else {
    // If no rows are found
    echo json_encode(array("message" => "No results found"), JSON_PRETTY_PRINT);
}

// Close database connection
$conn->close();
?>

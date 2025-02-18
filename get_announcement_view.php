<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select announcements
$sql = "SELECT * FROM announcement_view order BY announcement_view.create_time DESC";
$result = $conn->query($sql);

$announcements = array();

if ($result->num_rows > 0) {
    // Fetch associative array
    while($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

// Close connection
$conn->close();

// Convert announcements array to JSON format
$json_output = json_encode($announcements);

// Output JSON
echo $json_output;

?>
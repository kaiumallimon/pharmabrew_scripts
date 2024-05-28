<?php

// Assuming you have already established a connection to your MySQL database


$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$date=$_POST['today'];

// SQL query
$sql = "SELECT * FROM attendance WHERE date='$date'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    // Close connection
    $conn->close();
    
    // Convert data to JSON
    $json_response = json_encode($data);
    
    // Output JSON
    header('Content-Type: application/json');
    echo $json_response;
} else {
    // Close connection
    $conn->close();
    
    echo "No attendance data found";
}
?>
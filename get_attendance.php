<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM `attendance`";


$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    
    while($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    
    $response['message'] = "No attendance data found";
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>

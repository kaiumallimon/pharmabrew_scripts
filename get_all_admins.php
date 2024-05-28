<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT userId FROM login WHERE role = 'HR'"; // Fixed the role condition
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    
    $userIds = array();
    while ($row = $result->fetch_assoc()) {
        $userIds[] = $row['userId'];
    }
    echo json_encode(array("success" => true, "userIds" => $userIds), JSON_PRETTY_PRINT);
} else {
    
    echo json_encode(array("success" => false, "message" => "No users found with HR role"), JSON_PRETTY_PRINT);
}


$conn->close();
?>

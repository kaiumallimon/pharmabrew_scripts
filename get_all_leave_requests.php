<?php

// Assuming you have already established a connection to your MySQL database

$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$dbname = "bcryptsi_pharmabrew";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("SELECT 
    lr.*,
    pe.name AS name,
    pe.profile_pic,
    (SELECT name FROM profile_employee WHERE userId = lr.approved_by) AS approved_by_name
FROM 
    leave_request lr
JOIN 
    profile_employee pe ON lr.userId = pe.userId
WHERE 
    1
ORDER BY 
    lr.request_date DESC;
");
// $stmt->bind_param("s", $user);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $leave_requests = [];

    while ($row = $result->fetch_assoc()) {
        $leave_requests[] = $row;
    }

    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();

    if (!empty($leave_requests)) {
        // Convert data to JSON
        header('Content-Type: application/json');
        echo json_encode($leave_requests);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "No leave requests found"]);
    }
} else {
    // Handle execute error
    http_response_code(500);
    echo json_encode(["error" => "Error executing query: " . $stmt->error]);
    
    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();
    exit;
}

?>
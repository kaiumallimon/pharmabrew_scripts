<?php

// Database connection parameters
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$sql = "SELECT SUM(price) AS total_earnings_bdt
        FROM orderdetails
        WHERE orderDate >= DATE_SUB(DATE_SUB(NOW(), INTERVAL 2 HOUR), INTERVAL 24 HOUR)";

// Execute the query
$result = $conn->query($sql);

// Check if query executed successfully
if ($result) {
    // Fetch the result
    $row = $result->fetch_assoc();
    
    // Prepare response as JSON
    $response = array(
        'totalSales' => $row['total_earnings_bdt']
    );

    // Output response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle query errors
    $response = array(
        'error' => 'Query failed: ' . $conn->error
    );
    
    // Output error response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close connection
$conn->close();

?>

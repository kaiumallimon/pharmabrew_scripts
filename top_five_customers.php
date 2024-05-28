<?php
// Database connection parameters
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "SELECT
    c.customerId,
    c.name AS customerName,
    COUNT(DISTINCT od.orderDate) AS totalOrders,
    SUM(od.price) AS totalBill
FROM
    customer c
JOIN orderdetails od ON
    od.customerId = c.customerId
GROUP BY
    c.customerId,
    c.name
ORDER BY
    totalBill DESC
LIMIT 5;
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    // Pretty print JSON
    echo json_encode($rows, JSON_PRETTY_PRINT);
} else {
    echo "0 results";
}

$conn->close();
?>
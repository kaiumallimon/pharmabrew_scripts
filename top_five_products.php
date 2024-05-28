<?php
// Database connection parameters
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "WITH ProductSales AS (
    SELECT 
        od.product_id,
        p.productName,
        SUM(od.quantity) AS quantity,
        SUM(od.quantity * p.unitPrice*p.unitPerStrips) AS total_revenue
    FROM 
        orderdetails od
    JOIN 
        product p ON od.product_id = p.product_id
    GROUP BY 
        od.product_id, p.productName
)
SELECT 
    product_id,
    productName,
    quantity,
    total_revenue
FROM 
    ProductSales
ORDER BY 
    -- total_revenue DESC
    quantity DESC
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

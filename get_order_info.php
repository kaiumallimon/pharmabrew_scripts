<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve orders and customers info
$sql = "SELECT 
    customer.customerId, 
    MAX(customer.name) AS customerName,
    Max(customer.email) as customerEmail,
    Max(customer.phone) as customerPhone,
    Max(customer.address) as customerAddress,
    MAX(orderdetails.orderDate) AS orderDate,
    GROUP_CONCAT(DISTINCT product.product_id ORDER BY product.product_id ASC SEPARATOR ', ') AS orderedProducts,
    GROUP_CONCAT(DISTINCT orderdetails.quantity ORDER BY product.product_id ASC SEPARATOR ', ') AS quantities,
     SUM(product.unitPrice*product.unitPerStrips * orderdetails.quantity) AS totalCost
FROM customer
INNER JOIN orderdetails ON customer.customerId = orderdetails.customerId
INNER JOIN product ON orderdetails.product_id = product.product_id
GROUP BY customer.customerId
ORDER BY orderDate DESC;
";

$result = $conn->query($sql);

// Create an empty array to store the results
$ordersAndCustomersInfo = array();

// Check if there are any results
if ($result->num_rows > 0) {
    // Loop through each row of the result set
    while ($row = $result->fetch_assoc()) {
        // Split the ordered product IDs by comma
        $orderedProductIds = explode(", ", $row['orderedProducts']);
        
        // Fetch product details for each ordered product ID
        $orderedProductsInfo = array();
        foreach ($orderedProductIds as $productId) {
            $productSql = "SELECT * FROM product WHERE product_id = $productId";
            $productResult = $conn->query($productSql);
            if ($productResult->num_rows > 0) {
                while ($productRow = $productResult->fetch_assoc()) {
                    $orderedProductsInfo[] = $productRow;
                }
            }
        }
        
        // Add ordered products info to the row
        $row['orderedProductsInfo'] = $orderedProductsInfo;
        
        // Store each row in the ordersAndCustomersInfo array
        $ordersAndCustomersInfo[] = $row;
    }
} else {
    echo "No orders found";
}

// Close the database connection
$conn->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($ordersAndCustomersInfo);
?>

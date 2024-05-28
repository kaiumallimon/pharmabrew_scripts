<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current month and year
$currentMonth = $_POST['month'];
$currentYear = $_POST['year'];
$currentDate=$_POST['day'];

// Query to get day-wise sales for the current month
$sql = "SELECT DAY(orderDate) as day, SUM(price) as total_sales 
        FROM orderdetails 
        WHERE MONTH(orderDate) = '$currentMonth' AND YEAR(orderDate) = '$currentYear'
        GROUP BY DAY(orderDate)";

$result = $conn->query($sql);

// Create an array to store day-wise sales
$salesData = [];

// Fill in the sales data
for ($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear) && $day<=$currentDate; $day++) {
    $dayFormatted = date('d/m', strtotime("$currentYear-$currentMonth-$day")); // Format day as day/month
    $salesData[] = array(
        'day' => $dayFormatted,
        'sales' => "0" // Ensure 'sales' is always returned as a string
    );
}

// Update sales data for available days
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $day = $row['day'];
        $sales = $row['total_sales'];
        $salesData[$day - 1]['sales'] = strval($sales); // Convert 'sales' to string
    }
}

// Close the database connection
$conn->close();

// Output day-wise sales in JSON format
header('Content-Type: application/json');
echo json_encode($salesData);
?>

<?php
// Database connection parameters
$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$dbname = "bcryptsi_pharmabrew";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the user ID
$userId = $_POST['userId'];

// Get the current month, year, first day of the month, and yesterday's date
$currentMonth = date('m');
$currentYear = date('Y');
$firstDayOfMonth = date('Y-m-01');
$yesterdayDate = date('Y-m-d', strtotime('-1 day'));

// Query to retrieve existing attendance records for the user within the specified date range

$sql="SELECT *
FROM attendance
WHERE userId = '$userId'
AND YEAR(date) = YEAR(CURRENT_DATE())
AND MONTH(date) = MONTH(CURRENT_DATE());";
$result = $conn->query($sql);

// Initialize an array to store attendance data
$attendanceData = [];

// Generate an array of dates from the first day of the month to yesterday's date
$start_date = new DateTime($firstDayOfMonth);
$end_date = new DateTime($yesterdayDate);
$interval = new DateInterval('P1D');
$date_range = new DatePeriod($start_date, $interval, $end_date);

// Initialize an array to store existing dates in the database
$existingDates = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $existingDates[] = $row['date'];
        $attendanceData[] = $row;
    }
}

// Check for missing dates within the specified date range and mark them as absent
foreach ($date_range as $date) {
    $formattedDate = $date->format('Y-m-d');
    if (!in_array($formattedDate, $existingDates)) {
        $attendanceData[] = [
            'attendanceID' => null,
            'userId' => $userId,
            'date' => $formattedDate,
            'status' => 'Not Checked In',
            'checkInTime' => null,
            'checkOutTime' => null
        ];
    }
}

// Close database connection
$conn->close();

// Convert the attendance data array to JSON format
$attendanceJson = json_encode($attendanceData);

// Output the JSON data
header('Content-Type: application/json');
echo $attendanceJson;
?>

<?php

$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$dbname = "bcryptsi_pharmabrew";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$userId = $_POST['userId'];
$date = $_POST['date'];
$status = $_POST['status'];
$checkInTime = $_POST['checkInTime'];


$sql = "INSERT INTO `attendance`(`userId`, `date`, `status`, `checkInTime`, `checkOutTime`) 
        VALUES ('$userId', '$date', '$status', '$checkInTime', NULL)";

$response = array();
if ($conn->query($sql) === TRUE) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}


$conn->close();


header('Content-Type: application/json');
echo json_encode($response);
?>

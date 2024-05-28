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
$checkoutTime = $_POST['checkoutTime'];


$sql = "UPDATE `attendance` SET `checkOutTime`='$checkoutTime' WHERE `userId`='$userId' AND `date`='$date'";

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

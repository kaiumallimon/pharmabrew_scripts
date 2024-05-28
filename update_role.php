<?php
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isSet($_POST['userId'],$_POST['newRole'])){
$userId = $_POST['userId'];
$role = $_POST['newRole'];

$sql = "UPDATE login SET role = ? WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $role, $userId);

if ($stmt->execute()) {
    echo json_encode(array("success" => true), JSON_PRETTY_PRINT);
} else {
    echo json_encode(array("success" => false, "message" => "Failed to update role!"), JSON_PRETTY_PRINT);
}


$stmt->close();
$conn->close();
}else{
     echo json_encode(array("success" => false, "message" => "Please fill all the fields!"), JSON_PRETTY_PRINT);
}


?>

<?php


$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT profile_employee.userId, profile_employee.name,login.role, profile_employee.profile_pic,profile_employee.designation, profile_employee.joiningdate from login JOIN profile_employee ON login.userId=profile_employee.userId WHERE login.role!='HR'  ORDER BY name;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    $data = array();
    
    
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    
    echo json_encode($data);
} else {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'No results found'));
}


$conn->close();

?>

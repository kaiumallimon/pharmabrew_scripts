<?php

if(isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    $servername = "localhost";
    $username = "bcryptsi_admin";
    $password = "#WYcTZ06d4#oy0";
    $dbname = "bcryptsi_pharmabrew";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "SELECT name FROM profile_employee WHERE userId='$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
        $name = $row['name'];

        
        echo json_encode(array('name' => $name));
    } else {
        
        echo json_encode(array('name' => ''));
    }

    
    $conn->close();
} else {
    
    echo json_encode(array('error' => 'User ID not provided'));
}

?>

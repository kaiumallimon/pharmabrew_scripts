<?php


$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$database = "bcryptsi_pharmabrew";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if(isset($_POST['currentDate'])) {
    
    $date = $conn->real_escape_string($_POST['currentDate']);
    
    
    $sql = "SELECT 
                COUNT(CASE WHEN a.status = 'Present' THEN 1 END) AS present_today,
                COUNT(CASE WHEN a.status = 'Absent' THEN 1 END) AS absent_today,
                COUNT(CASE WHEN a.userId IS NULL THEN 1 END) AS not_checked_in
            FROM 
                login l
            LEFT JOIN 
                attendance a ON l.userId = a.userId AND a.date = '$date'";
    
    
    $result = $conn->query($sql);
    
    if ($result) {
        
        $response = $result->fetch_assoc();
        
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        
        echo json_encode(array("error" => $conn->error));
    }
} else {
    echo json_encode(array("error" => "No currentDate provided"));
}

$conn->close();

?>

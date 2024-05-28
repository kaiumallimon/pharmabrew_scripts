<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function isEmailExists($email, $conn) {
    $email = $conn->real_escape_string($email);
    $query = "SELECT COUNT(*) as count FROM profile_employee WHERE email = '$email'";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['count'] > 0) {
            return true; // Email exists
        } else {
            return false; // Email does not exist
        }
    } else {
        return false; // Query failed
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email1 = $_POST["email"];

    // // Check if email exists
    // if (isEmailExists($email, $conn)) {
    //     $response = array(
    //         "success" => false,
    //         "message" => "Email already exists"
    //     );
    // } else {
    //     $response = array(
    //         "success" => true,
    //         "message" => "Email does not exist in the database"
    //     );
    // }
    
    $responseToReturn=isEmailExists($email1,$conn);
    
    $response=array("success"=>$responseToReturn);
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close connection
$conn->close();
?>

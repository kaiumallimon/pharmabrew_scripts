<?php
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided in the POST request
if(isset($_POST['id'])) {
    // Sanitize the input to prevent SQL injection
    $id = $_POST['id'];

    // New value for status_admin
    $new_status = 'read'; // Change this to your desired new value

    // SQL query to update status_admin
    $update_query = "UPDATE Messages SET status_admin = ? WHERE sender_id = ? OR receiver_id = ?";
    
    // Prepare the statement
    $statement = $conn->prepare($update_query);
    
    // Bind parameters
    $statement->bind_param("sss", $new_status, $id, $id);
    
    // Execute the statement
    $statement->execute();
    
    // Check if any rows were updated
    if ($statement->affected_rows > 0) {
        $response = array("success" => true,"message"=>"Updated Successfully");
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        $response = array("success" => false,"message"=>"Something error occured");
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
} else {
    $response = array("success" => false, "message"=>"ID not provided");
    echo json_encode($response, JSON_PRETTY_PRINT);
}

// Close database connection
$conn->close();
?>

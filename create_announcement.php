<?php

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

// Check if all required parameters are provided
if (isset($_POST['title'], $_POST['description'], $_POST['create_time'], $_POST['creator_id'])) {
    // Extract parameters from POST request
    $title = $_POST['title'];
    $description = $_POST['description'];
    $create_time = $_POST['create_time'];
    $creator_id = $_POST['creator_id'];
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    
    // Prepare statement to retrieve the creator's name from the profile_employee table
    $sqlName = "SELECT name FROM profile_employee WHERE userId=?";
    $stmtName = $conn->prepare($sqlName);
    $stmtName->bind_param("s", $creator_id);
    
    // Execute the statement
    $stmtName->execute();
    $resultName = $stmtName->get_result();
    
    if ($resultName->num_rows > 0) {
        // Fetch the row
        $row = $resultName->fetch_assoc();
        $creator_name = $row['name'];

        // Prepare statement to insert announcement into database
        $stmt = $conn->prepare("INSERT INTO announcement (title, description, create_time, creator_id, creator_name, start_date, end_date) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $title, $description, $create_time, $creator_id, $creator_name, $start_date, $end_date);

        // Execute the statement
        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Announcement added successfully";
        } else {
            $response["success"] = false;
            $response["message"] = "Error adding announcement: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        $response["success"] = false;
        $response["message"] = "No employee found with the provided creator_id";
    }

    // Close statement
    $stmtName->close();
} else {
    $response["success"] = false;
    $response["message"] = "Required parameters are missing";
}

// Close connection
$conn->close();

// Encode response as JSON and echo
echo json_encode($response);

?>

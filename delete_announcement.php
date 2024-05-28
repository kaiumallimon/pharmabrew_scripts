<?php

// Check if the announcement ID is provided via POST
if(isset($_POST['announcement_id'])) {
    
    // Database credentials
    $servername = "localhost";
    $username = "bcryptsi_admin";
    $password = "#WYcTZ06d4#oy0";
    $dbname = "bcryptsi_pharmabrew";

    // Connect to the database
    $mysqli = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($mysqli->connect_error) {
        $response = array("success" => false, "message" => "Connection failed: " . $mysqli->connect_error);
        echo json_encode($response);
        exit();
    }

    // Prepare SQL statement to delete announcement
    $announcement_id = $_POST['announcement_id'];
    $sql = "DELETE FROM announcement WHERE announcementId = ?";

    // Prepare and bind parameters
    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $announcement_id);

        // Execute the statement
        if($stmt->execute()) {
            $response = array("success" => true);
        } else {
            $response = array("success" => false, "message" => "Error deleting announcement: " . $mysqli->error);
        }

        // Close statement
        $stmt->close();
    } else {
        $response = array("success" => false, "message" => "Error preparing statement: " . $mysqli->error);
    }

    // Close connection
    $mysqli->close();

    // Send JSON response
    echo json_encode($response);
} else {
    // Announcement ID not provided
    $response = array("success" => false, "message" => "Announcement ID not provided.");
    echo json_encode($response);
}

?>

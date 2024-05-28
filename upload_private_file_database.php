<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO private_files (fileName, uploadDate, fileType, fileSize, user) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fileName, $uploadDate, $fileType, $fileSize, $user);

    // Set parameters
    $fileName = $_POST['fileName'];
    $uploadDate = $_POST['uploadDate'];
    $fileType = $_POST['fileType'];
    $fileSize = $_POST['fileSize'];
    $user = $_POST['user'];

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

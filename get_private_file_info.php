<?php
// Database connection parameters
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User ID and file name (Assuming these are received through POST)
$user = $_POST['user'];

// Prepare and bind the SELECT statement
$stmt = $conn->prepare("SELECT fileName, uploadDate, fileType, fileSize FROM private_files WHERE user = ?");
$stmt->bind_param("s", $user);

// Execute the statement
$stmt->execute();

// Bind result variables
$stmt->bind_result($fetchedFileName, $uploadDate, $fileType, $fileSize);

// Initialize an array to store the fetched data
$data = array();

// Fetch the data
while ($stmt->fetch()) {
    // Add fetched data to the array
    $data[] = array(
        'fileName' => $fetchedFileName,
        'uploadDate' => $uploadDate,
        'fileType' => $fileType,
        'fileSize' => $fileSize
    );
}

// Close statement and connection
$stmt->close();
$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>

<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user ID and filename are provided
    if (isset($_POST['id']) && isset($_POST['filename'])) {
        $id = $_POST['id'];
        $filename = $_POST['filename'];
        // Directory where files are uploaded
        $target_dir = "../../uploads/files/";

        // Path to the directory with the user's files
        $user_dir = $target_dir . $id . "/";

        // Check if the user directory exists
        if (is_dir($user_dir)) {
            // Check if the file exists
            $file_path = $user_dir . $filename;
            if (file_exists($file_path)) {
                // Attempt to delete the file
                if (unlink($file_path)) {
                    $response = array("status" => "success", "message" => "File deleted successfully.");
                } else {
                    $response = array("status" => "error", "message" => "Failed to delete the file.");
                }
            } else {
                $response = array("status" => "error", "message" => "File not found.");
            }
        } else {
            $response = array("status" => "error", "message" => "No files found for the provided ID.");
        }
    } else {
        $response = array("status" => "error", "message" => "ID or filename not provided.");
    }
} else {
    $response = array("status" => "error", "message" => "Invalid request method.");
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

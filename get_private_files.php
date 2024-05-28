<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user ID is provided
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        // Directory where files are uploaded
        $target_dir = "../../uploads/files/";

        // Path to the directory with the user's files
        $user_dir = $target_dir . $id . "/";

        // Check if the user directory exists
        if (is_dir($user_dir)) {
            // Get list of files in the user directory
            $files = scandir($user_dir);
            // Remove . and .. from the list
            $files = array_diff($files, array('.', '..'));
            
            $file_list = array();
            // Loop through the files and create download links
            foreach ($files as $file) {
                $file_path = $user_dir . $file;
                // Construct download link with the specified URL and file path
                $download_link = "https://bcrypt.site/uploads/files/" . $id . "/" . $file;
                // Add file details to the list
                $file_list[] = array(
                    "file_name" => $file,
                    "download_link" => $download_link
                );
            }
            $response = array("status" => "success", "files" => $file_list);
        } else {
            $response = array("status" => "error", "message" => "No files found for the provided ID.");
        }
    } else {
        $response = array("status" => "error", "message" => "No ID provided.");
    }
} else {
    $response = array("status" => "error", "message" => "Invalid request method.");
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

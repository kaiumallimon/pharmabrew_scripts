<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES['file']['name']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        // Directory where files will be uploaded
        $target_dir = "../../uploads/files/";

        // Create directory if it doesn't exist
        if (!is_dir($target_dir . $id)) {
            mkdir($target_dir . $id, 0777, true);
        }

        // Path to the target directory with the ID
        $target_dir = $target_dir . $id . "/";

        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if the uploaded file is an image
        // $check = getimagesize($_FILES["file"]["tmp_name"]);
        // if ($check !== false) {
        //     $response = array("status" => "success", "message" => "File is an image - " . $check["mime"]);
        // } else {
        //     $response = array("status" => "error", "message" => "File is not an image.");
        //     $uploadOk = 0;
        // }
        
        // Move the uploaded file to the target directory
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $response["file_path"] = $target_file;
            } else {
                $response = array("status" => "error", "message" => "Failed to move the file.");
            }
        }
    } else {
        $response = array("status" => "error", "message" => "No file uploaded or ID provided.");
    }
} else {
    $response = array("status" => "error", "message" => "Invalid request method.");
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

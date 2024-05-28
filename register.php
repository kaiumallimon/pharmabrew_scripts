<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare statements
$stmt1 = $conn->prepare("INSERT INTO phone (userId, phoneNumber) VALUES (?, ?)");
$stmt2 = $conn->prepare("INSERT INTO skills (userId, skill) VALUES (?, ?)");
$stmt3 = $conn->prepare("INSERT INTO location (userId, apartment, building_name, street_name, city, postal_code, country) VALUES (?, ?, ?, ?, ?, ?, ?)");

if (isset($_POST['name'], $_POST['email'], $_POST['dateofbirth'], $_POST['designation'], $_POST['password'], $_POST['role'], $_POST['profile_pic'], $_POST['rating'], $_POST['department_id'], $_POST['phone_numbers'], $_POST['skills'], $_POST['location'], $_POST['base_salary'])) {

    // Retrieve and initialize data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dateofbirth = $_POST['dateofbirth'];
    $designation = $_POST['designation'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $profile_pic = $_POST['profile_pic'];
    $rating = $_POST['rating'];
    $department_id = $_POST['department_id'];
    $phone_numbers = $_POST['phone_numbers'];
    $skills = $_POST['skills'];
    $location = $_POST['location'];
    $fullLocation = explode(",", $location);
    $base_salary = $_POST['base_salary'];
    $allnumbers = explode(",", $phone_numbers);
    $allSkills = explode(",", $skills);

    // Sanitize input data
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $dateofbirth = mysqli_real_escape_string($conn, $dateofbirth);
    $designation = mysqli_real_escape_string($conn, $designation);
    $profile_pic = mysqli_real_escape_string($conn, $profile_pic);
    $password = mysqli_real_escape_string($conn, $password);
    $role = mysqli_real_escape_string($conn, $role);
    $base_salary = mysqli_real_escape_string($conn, $base_salary);

    // Generate unique userId
    $userId = 'EMP' . str_replace(array(' ', '-', ':'), '', date('Y-m-d H:i:s'));

    // Rename the image name as unique userId
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $filename = $userId . '.' . $imageFileType;

    // Set the target file path
    $target_directory = "../../uploads/images/profile/picture/";
    $target_file = $target_directory . $filename;

    // Check if file already exists
    if (file_exists($target_file)) {
        echo json_encode(array("success" => false, "message" => "Sorry, file already exists."), JSON_PRETTY_PRINT);
        exit;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 2097152) {
        echo json_encode(array("success" => false, "message" => "Sorry, your file is too large."), JSON_PRETTY_PRINT);
        exit;
    }

    if (!in_array($imageFileType, array("jpg", "png", "jpeg"))) {
        echo json_encode(array("success" => false, "message" => "Sorry, only JPG, JPEG, PNG files are allowed."), JSON_PRETTY_PRINT);
        exit;
    }

    // Query to insert data into login table
    $sql1 = "INSERT INTO `login`(`userId`, `password`, `role`) VALUES (?, ?, ?)";
    $stmt4 = $conn->prepare($sql1);
    $stmt4->bind_param("sss", $userId, $hashedPassword, $role);

    // Query to insert data into employee table
    $sql2 = "INSERT INTO profile_employee(
                userId,
                name,
                email,
                dateofbirth,
                joiningdate,
                designation,
                rating,
                profile_pic,
                department_id,
                leaves,
                base_salary
            )
            VALUES (?, ?, ?, ?, CURDATE(), ?, ?, ?, ?, 0, ?)";
    $stmt5 = $conn->prepare($sql2);
    $stmt5->bind_param("ssssssssi", $userId, $name, $email, $dateofbirth, $designation, $rating, $filename, $department_id, $base_salary);

    // Execute login table insertion
    if ($stmt4->execute() && $stmt5->execute()) {
        // Upload image file if no errors
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded as $filename.";
        } else {
            echo json_encode(array("success" => false, "message" => "Sorry, there was an error uploading your file."), JSON_PRETTY_PRINT);
            exit;
        }

        // Close previous statement before executing next one
        $stmt5->close();

        

        // Loop through phone numbers
        foreach ($allnumbers as $number) {
            // Bind parameters and execute for phone numbers
            $stmt1->bind_param("ss", $userId, $number);
            $stmt1->execute();
        }

        // Loop through skills
        foreach ($allSkills as $skill) {
            // Bind parameters and execute for skills
            $stmt2->bind_param("ss", $userId, $skill);
            $stmt2->execute();
        }
        $stmt3->bind_param("sssssis", $userId, $fullLocation[0], $fullLocation[1], $fullLocation[2], $fullLocation[3], $fullLocation[4], $fullLocation[5]);
        $stmt3->execute();

        echo json_encode(array("success" => true, "message" => "Account Created Successfully"), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array("success" => false, "message" => $conn->error), JSON_PRETTY_PRINT);
    }
    
    // Close statements and connection
$stmt1->close();
$stmt2->close();
$stmt3->close();
$stmt4->close();
$conn->close();
} else {
    echo json_encode(array("success" => false, "message" => "Not all required data provided"), JSON_PRETTY_PRINT);
}


?>

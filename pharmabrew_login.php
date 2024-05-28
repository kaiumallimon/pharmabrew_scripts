<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure that variables are set and not empty
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $email = stripslashes($email);
    $password = stripslashes($password);
    $email = mysqli_real_escape_string($conn, $email);

    // Prepare and bind SQL statement
    $sql = "SELECT login.userId, role, name, profile_pic, password FROM `login` JOIN profile_employee ON login.userId=profile_employee.userId WHERE email=?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $email);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the password using password_verify
        if (password_verify($password, $hashed_password)) {
            $id = $row['userId'];
            $role = $row['role'];
            $name = $row['name'];
            $pic = $row['profile_pic'];

            // Output JSON response
            echo json_encode(array("success" => true, "userId" => $id, "email" => $email, "name" => $name, "profile_pic" => $pic, "role" => $role), JSON_PRETTY_PRINT);
        } else {
            // Output JSON response for invalid login
            echo json_encode(array("success" => false, "message" => "Invalid username or password"), JSON_PRETTY_PRINT);
        }
    } else {
        // Output JSON response for invalid login
        echo json_encode(array("success" => false, "message" => "Invalid username or password"), JSON_PRETTY_PRINT);
    }

    // Close statement
    $stmt->close();
} else {
    // Output JSON response if email or password is not set
    echo json_encode(array("success" => false, "message" => "Email or password not provided"), JSON_PRETTY_PRINT);
}

// Close connection
$conn->close();
?>

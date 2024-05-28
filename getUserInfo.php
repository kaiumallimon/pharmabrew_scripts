<?php
// Connect to MySQL
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get userId from POST data
$userId = isset($_POST['userId']) ? $_POST['userId'] : null;

// Set JSON header
header('Content-Type: application/json');

if (!is_null($userId)) {
    // Prevent SQL injection by using prepared statements
    $sql = "SELECT 
                login.userId,
                login.role,
                profile_employee.*, 
                departments.*, 
                CONCAT(
                    MAX(location.apartment), 
                    '|', 
                    MAX(location.building_name), 
                    '|', 
                    MAX(location.street_name), 
                    '|', 
                    MAX(location.city), 
                    '|', 
                    MAX(location.postal_code), 
                    '|', 
                    MAX(location.country)
                ) AS address,
                GROUP_CONCAT(skills.skill) AS all_skills,
                phone_numbers.phone_numbers
            FROM 
                login
            JOIN 
                profile_employee ON login.userId = profile_employee.userId
            JOIN 
                departments ON profile_employee.department_id = departments.department_id
            JOIN 
                location ON profile_employee.userId = location.userId
            JOIN 
                skills ON profile_employee.userId = skills.userId
            JOIN 
                (SELECT userId, GROUP_CONCAT(phoneNumber) AS phone_numbers FROM phone GROUP BY userId) AS phone_numbers ON phone_numbers.userId = profile_employee.userId
            WHERE 
                login.userId = ?
            GROUP BY 
                login.userId, 
                profile_employee.userId, 
                departments.department_id;";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        // Encode the data array into JSON format with pretty print
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        // Output JSON
        echo $json_data;
    } else {
        echo json_encode(array("success" => false, "message" => "No user found with the provided userId"), JSON_PRETTY_PRINT);
    }

    // Close prepared statement
    $stmt->close();
} else {
    // If userId is not provided in POST data
    echo json_encode(array("success" => false, "message" => "userId is not provided in POST data"), JSON_PRETTY_PRINT);
}

// Close database connection
$conn->close();
?>

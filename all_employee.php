<?php

$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sortby = isset($_POST['sort-by']) ? $_POST['sort-by'] : null;
$sort = isset($_POST['sort']) ? $_POST['sort'] : "ASC";


header('Content-Type: application/json');

if (is_null($sortby)) {
    
    $sql = "SELECT 
    login.*, 
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
GROUP BY 
    login.userId, 
    profile_employee.userId, 
    departments.department_id;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        
        echo $json_data;
    } else {
        echo "No results found.";
    }
} else {
    $sql = "SELECT 
    login.*, 
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
GROUP BY 
    login.userId, 
    profile_employee.userId, 
    departments.department_id 
ORDER BY $sortby $sort;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        
        echo $json_data;
    } else {
        echo "No results found.";
    }
}


$conn->close();
?>

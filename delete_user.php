<?php
$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$database = "bcryptsi_pharmabrew";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    $sql_skills = "DELETE FROM skills WHERE skills.userId='$userId'";
    $sql_salary = "DELETE FROM salary WHERE salary.userId='$userId'";
    $sql_phone = "DELETE FROM phone WHERE phone.userId='$userId'";
    $sql_notification = "DELETE FROM notification WHERE notification.sender_id='$userId' OR notification.receiver_id='$userId'";
    $sql_messages = "DELETE FROM Messages WHERE Messages.sender_id='$userId' OR Messages.receiver_id='$userId'";
    $sql_location = "DELETE FROM location WHERE location.userId='$userId'";
    $sql_attendance = "DELETE FROM attendance WHERE attendance.userId='$userId'";
    $sql_profile_employee = "DELETE FROM profile_employee WHERE profile_employee.userId='$userId'";
    $sql_login = "DELETE FROM login WHERE login.userId='$userId'";

    
    $conn->query($sql_skills);
    $conn->query($sql_salary);
    $conn->query($sql_phone);
    $conn->query($sql_notification);
    $conn->query($sql_messages);
    $conn->query($sql_location);
    $conn->query($sql_attendance);
    $conn->query($sql_profile_employee);
    $conn->query($sql_login);

    $response = array("success" => true);
    echo json_encode($response);
} else {
    
    $response = array("success" => false);
    echo json_encode($response);
}

$conn->close();
?>

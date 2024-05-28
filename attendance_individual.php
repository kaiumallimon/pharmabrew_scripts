<?php


$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "
    SELECT 
        DATE_FORMAT(attendance.date, '%Y-%m') AS month,
        profile_employee.userId,
        COUNT(CASE WHEN attendance.status = 'Present' THEN 1 END) AS present_count,
        COUNT(CASE WHEN attendance.status = 'Absent' THEN 1 END) AS absent_count,
        GROUP_CONCAT(CASE WHEN attendance.status = 'Absent' THEN attendance.date END ORDER BY attendance.date SEPARATOR ', ') AS absent_dates,
        SEC_TO_TIME(AVG(TIME_TO_SEC(attendance.checkInTime))) AS avg_checkin_time,
        SEC_TO_TIME(AVG(TIME_TO_SEC(attendance.checkOutTime))) AS avg_checkout_time,
        SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(attendance.checkOutTime, attendance.checkInTime)))) AS avg_working_hours
    FROM 
        attendance
    INNER JOIN 
        profile_employee ON attendance.userId = profile_employee.userId
    GROUP BY 
        month, profile_employee.userId
    ORDER BY 
        month, profile_employee.userId;
";


$result = $conn->query($sql);


$data = array();


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}


$conn->close();


$json_data = json_encode($data);


header('Content-Type: application/json');

echo $json_data;
?>

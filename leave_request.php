<?php
// Database connection
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect POST data
$userId = $_POST['userId'];
$leave_type = $_POST['leave_type'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$request_date = date('Y-m-d H:i:s'); // current timestamp
$status = 'Pending'; // default value
$reason = isset($_POST['reason']) ? $_POST['reason'] : NULL;
$approved_by = isset($_POST['approved_by']) ? $_POST['approved_by'] : NULL;
$approval_date = '0000-00-00 00:00:00'; // default value

// Begin transaction
$conn->begin_transaction();

try {
    // Insert into leave_request table
    $stmt = $conn->prepare("INSERT INTO leave_request (userId, leave_type, start_date, end_date, request_date, STATUS, reason, approved_by, approval_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $userId, $leave_type, $start_date, $end_date, $request_date, $status, $reason, $approved_by, $approval_date);

    if (!$stmt->execute()) {
        throw new Exception("Error inserting leave request: " . $stmt->error);
    }

    // // Mark dates as absent in attendance table
    // $current_date = strtotime($start_date);
    // $end_date_timestamp = strtotime($end_date);

    // while ($current_date <= $end_date_timestamp) {
    //     $date = date('Y-m-d', $current_date);
    //     $status = 'Absent';

    //     $attendance_stmt = $conn->prepare("INSERT INTO attendance (userId, date, status) VALUES (?, ?, ?)");
    //     $attendance_stmt->bind_param("sss", $userId, $date, $status);

    //     if (!$attendance_stmt->execute()) {
    //         throw new Exception("Error inserting attendance: " . $attendance_stmt->error);
    //     }

    //     $current_date = strtotime('+1 day', $current_date);
    // }

    // Commit transaction
    $conn->commit();

    echo "Leave request and attendance records submitted successfully";
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    echo "Failed: " . $e->getMessage();
}

// Close statements and connection
$stmt->close();
$conn->close();
?>

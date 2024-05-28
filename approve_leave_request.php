<?php
$conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['status'], $_POST['approved_by'], $_POST['approve_time'], $_POST['requestId'])) {
    
    $status = $_POST['status'];
    $approvedBy = $_POST['approved_by'];
    $time = $_POST['approve_time'];
    $reqId = $_POST['requestId'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the leave_request table
        $sql = "UPDATE leave_request SET STATUS = ?, approved_by = ?, approval_date = ? WHERE requestId = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Failed to prepare statement for updating leave request!");
        }

        $stmt->bind_param("sssi", $status, $approvedBy, $time, $reqId);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update leave request!");
        }

        // Get the start_date and end_date for the given requestId
        $sql = "SELECT userId, start_date, end_date FROM leave_request WHERE requestId = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Failed to prepare statement for retrieving leave dates!");
        }

        $stmt->bind_param("i", $reqId);

        if (!$stmt->execute()) {
            throw new Exception("Failed to retrieve leave dates!");
        }

        $stmt->bind_result($userId, $startDate, $endDate);
        $stmt->fetch();
        $stmt->close();

        // Insert into the attendance table for each date in the range
        $currentDate = $startDate;
        $insertSql = "INSERT INTO attendance (userId, date, status) VALUES (?, ?, 'Absent')";
        $insertStmt = $conn->prepare($insertSql);

        if ($insertStmt === false) {
            throw new Exception("Failed to prepare statement for inserting attendance!");
        }

        while (strtotime($currentDate) <= strtotime($endDate)) {
            $insertStmt->bind_param("ss", $userId, $currentDate);
            if (!$insertStmt->execute()) {
                throw new Exception("Failed to insert attendance record for date: " . $currentDate);
            }
            $currentDate = date("Y-m-d", strtotime($currentDate . ' + 1 day'));
        }

        // Commit the transaction
        $conn->commit();

        echo json_encode(array("success" => true), JSON_PRETTY_PRINT);

    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo json_encode(array("success" => false, "message" => $e->getMessage()), JSON_PRETTY_PRINT);
    }

    $insertStmt->close();
    $conn->close();

} else {
    echo json_encode(array("success" => false, "message" => "Please fill all the fields!"), JSON_PRETTY_PRINT);
}
?>

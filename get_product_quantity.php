<?php

// Check if the name is provided via POST request
if(isset($_POST['name'])) {
    // Get the name from POST data
    $name = $_POST['name'];

    $conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query
    $sql = "SELECT DISTINCT quantity, CONCAT(productName, ' - ', variant, ' - ', expDate) AS name FROM product WHERE CONCAT(productName, ' - ', variant) = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);

    // Execute query
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($quantity, $concatenated_name);

    // Fetch result
    $result = null;
    if ($stmt->fetch()) {
        $result = array(
            'quantity' => $quantity,
            'name' => $concatenated_name
        );
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Convert result to JSON format
    $json_result = json_encode($result);

    // Output JSON
    echo $json_result !== null ? $json_result : json_encode(array('error' => 'Product not found'));
} else {
    // If name is not provided via POST, return an error message
    echo json_encode(array('error' => 'Name not provided in POST data'));
}

?>

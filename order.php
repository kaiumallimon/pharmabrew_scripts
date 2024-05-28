<?php

// Create an empty response array
$response = array();

// Set default success value to false
$response['success'] = false;
$currentTimestamp = date('Y-m-d H:i:s');
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the POST data
    $postData = json_decode(file_get_contents("php://input"), true);

    // Check if the POST data is not empty
    if (!empty($postData)) {
        // Database connection parameters
        $conn = new mysqli("localhost", "bcryptsi_admin", "#WYcTZ06d4#oy0", "bcryptsi_pharmabrew");

        // Check connection
        if ($conn->connect_error) {
            $response['message'] = "Connection failed: " . $conn->connect_error;
        } else {
            // Insert data into the customer table
            $name = $postData['name'];
            $email = $postData['email'];
            $phone = $postData['phone'];
            $address = $postData['address'];
            $senderName = $postData['employeeName'];
            $senderId = $postData['employeeId'];
            $customerSql = "INSERT INTO customer (name, email, phone, address) VALUES (?, ?, ?, ?)";
            $customerStmt = $conn->prepare($customerSql);
            $customerStmt->bind_param("ssss", $name, $email, $phone, $address);

            // Execute the customer statement
            if ($customerStmt->execute()) {
                $response['success'] = true;
                $response['customerId'] = $conn->insert_id; // Get the last inserted id
                $response['message'] = "Customer record inserted successfully";
            } else {
                $response['message'] = "Error inserting customer record: " . $conn->error;
            }

            // Insert data into the orderdetails table
            $orderDetails = $postData['orderdetails'];

            foreach ($orderDetails as $order) {
                $productId = $order['product_id'];
                $quantity = $order['quantity'];
                $orderDate = $order['orderDate'];
                $price = $order['price'];

                // Check if there is enough quantity of the product
                $checkQuantitySql = "SELECT productName, quantity FROM product WHERE product_id = ?";
                $checkQuantityStmt = $conn->prepare($checkQuantitySql);
                $checkQuantityStmt->bind_param("i", $productId);
                $checkQuantityStmt->execute();
                $checkQuantityStmt->bind_result($productName, $availableQuantity);
                $checkQuantityStmt->fetch();
                $checkQuantityStmt->close();

                if ($availableQuantity < $quantity) {
                    $response['success'] = false;
                    $response['message'] = "Not enough quantity available for $productName";
                    // End processing further orders
                    break;
                }

                // Insert data into the orderdetails table only if enough quantity is available
                $orderDetailsSql = "INSERT INTO orderdetails (product_id, quantity, orderDate, price, customerId) VALUES (?, ?, ?, ?, ?)";
                $orderDetailsStmt = $conn->prepare($orderDetailsSql);
                $orderDetailsStmt->bind_param("iissi", $productId, $quantity, $orderDate, $price, $response['customerId']);

                // Execute the orderdetails statement
                if ($orderDetailsStmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Order detail record inserted successfully";
                } else {
                    $response['message'] = "Error inserting order detail record: " . $conn->error;
                }

                // Update the quantity of the product in the product table
                $updateProductSql = "UPDATE product SET quantity = quantity - ? WHERE product_id = ?";
                $updateProductStmt = $conn->prepare($updateProductSql);
                $updateProductStmt->bind_param("ii", $quantity, $productId);

                // Execute the update product statement only if order detail is successfully inserted
                if ($response['success'] && $updateProductStmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Product quantity updated successfully";

                    // Insert notification
                    $notificationContent = "$senderName placed an order!";
                    $notificationInsertSql = "INSERT INTO notification (sender_id, content, created_at, status, receiver) VALUES ('$senderId', '$notificationContent', '$currentTimestamp', 'unread', 'hr')";
                    if ($conn->query($notificationInsertSql) !== TRUE) {
                        echo "Error: " . $notificationInsertSql . "<br>" . $conn->error;
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = "Error updating product quantity: " . $conn->error;
                }
            }

            // Close statements and connection
            $customerStmt->close();
            $orderDetailsStmt->close();
            $updateProductStmt->close();
            $conn->close();
        }
    } else {
        $response['message'] = "No data received";
    }
} else {
    $response['message'] = "Only POST requests are allowed";
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

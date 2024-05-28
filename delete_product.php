<?php

$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$dbname = "bcryptsi_pharmabrew";

$response = array();

if (isset($_POST['productID'])) {
    $productID = $_POST['productID'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Begin a transaction
    $conn->begin_transaction();

    // Delete from orderdetails table first
    $sql_delete_orderdetails = "DELETE FROM orderdetails WHERE product_id='$productID'";
    if ($conn->query($sql_delete_orderdetails) === TRUE) {
        // Then delete from product table
        $sql_delete_product = "DELETE FROM product WHERE product_id='$productID'";
        if ($conn->query($sql_delete_product) === TRUE) {
            // Commit the transaction
            $conn->commit();
            $response['success'] = true;
            $response['message'] = "Records deleted successfully";
        } else {
            // Rollback the transaction if an error occurred
            $conn->rollback();
            $response['success'] = false;
            $response['message'] = "Error deleting product: " . $conn->error;
        }
    } else {
        // Rollback the transaction if an error occurred
        $conn->rollback();
        $response['success'] = false;
        $response['message'] = "Error deleting order details: " . $conn->error;
    }

    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = "Error: Product ID not provided via POST";
}

header('Content-Type: application/json');
echo json_encode($response);

?>

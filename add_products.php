<?php

$servername = "localhost";
$username = "bcryptsi_admin";
$password = "#WYcTZ06d4#oy0";
$dbname = "bcryptsi_pharmabrew";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['productName'], $_POST['variant'], $_POST['productionDate'], $_POST['unitPrice'], $_POST['expDate'], $_POST['quantity'], $_POST['unitPerStrips'])) {

    $productName = $_POST['productName'];
    $variant = $_POST['variant'];
    $productionDate = $_POST['productionDate'];
    $unitPrice = $_POST['unitPrice'];
    $expDate = $_POST['expDate'];
    $quantity = $_POST['quantity'];
    $unitPerStrips = $_POST['unitPerStrips'];

    // Check if product already exists
    $check_stmt = $conn->prepare("SELECT product_id, quantity FROM product WHERE productName = ? AND variant = ? AND productionDate= ? AND expDate= ? AND unitPerStrips=?");
    $check_stmt->bind_param("sssss", $productName, $variant,$productionDate,$expDate,$unitPerStrips);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Product exists, update stock quantity
        $check_stmt->bind_result($productId, $existingQuantity);
        $check_stmt->fetch();
         
        if($quantity>0){
            $newQuantity = $existingQuantity + $quantity;

        $update_stmt = $conn->prepare("UPDATE product SET quantity = ? WHERE product_id = ?");
        $update_stmt->bind_param("ii", $newQuantity, $productId);
        
        if ($update_stmt->execute()) {
            echo "Stock quantity updated successfully";
        } else {
            echo "Error updating stock quantity: " . $update_stmt->error;
        }
        }else{
            
        $update_stmt = $conn->prepare("UPDATE product SET unitPrice = ? WHERE product_id = ?");
        $update_stmt->bind_param("si", $unitPrice, $productId);
        
        if ($update_stmt->execute()) {
            echo "Stock quantity updated successfully";
        } else {
            echo "Error updating stock quantity: " . $update_stmt->error;
        }
        }

        $update_stmt->close();
    } else {
        // Product does not exist, insert new record
        $insert_stmt = $conn->prepare("INSERT INTO product (productName, variant, productionDate, unitPrice, expDate, quantity, unitPerStrips) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("sssssss", $productName, $variant, $productionDate, $unitPrice, $expDate, $quantity, $unitPerStrips);

        if ($insert_stmt->execute()) {
            echo "New product added successfully";
        } else {
            echo "Error adding new product: " . $insert_stmt->error;
        }

        $insert_stmt->close();
    }

    $check_stmt->close();

} else {
    echo "Error: Please fill in all required fields";
}

$conn->close();
?>

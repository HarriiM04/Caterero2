<?php
// Start the session to access the customer ID
session_start();
require_once 'adminback.php';

if (isset($_GET['id'])) {
    $customer_id = 1;
    $package_id = $_GET['id'];
    echo $_GET['id'];
    // Create an instance of your class
    $admin   = new adminback();

    // Prepare and execute the query to remove the item from the cart
    $query = "DELETE FROM cart WHERE customer_id = ? AND package_id = ?";
    $stmt =  $admin->connection->prepare($query);
    $stmt->bind_param("ii", $customer_id, $package_id);
    if ($stmt->execute()) {
        header('Location: index.php');
    } else {
        echo "error";
    }
}
else{
    echo 'something wrong';
}
?>

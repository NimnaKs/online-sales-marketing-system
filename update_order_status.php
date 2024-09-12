<?php
session_start();
include('./includes/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $buyer_status = $_POST['buyer_status'];

    $update_query = "UPDATE Orders SET buyer_status = ? WHERE id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param('si', $buyer_status, $order_id);
    
    if ($stmt->execute()) {
        header('Location: track-orders.php');
        exit;
    } else {
        echo "Error updating order status.";
    }

    $stmt->close();
    $con->close();
}
?>

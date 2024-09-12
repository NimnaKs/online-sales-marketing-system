<?php
    include('../includes/connect.php');

    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];

    $query = "UPDATE Orders SET seller_status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('si', $newStatus, $orderId);
    $stmt->execute();

    $stmt->close();
    $con->close();
?>

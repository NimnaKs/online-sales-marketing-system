<?php
    include('../includes/connect.php');

    session_start();
    
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'seller') {
        header('Location: ../login.php');
        exit();
    }

    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];

    $query = "UPDATE Orders SET seller_status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('si', $newStatus, $orderId);
    $stmt->execute();

    $stmt->close();
    $con->close();
?>

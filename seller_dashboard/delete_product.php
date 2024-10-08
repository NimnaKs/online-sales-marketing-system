<?php

include('../includes/connect.php');

session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'seller') {
    header('Location: ../login.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    $sql = "DELETE FROM Products WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $productId);
    
    if ($stmt->execute()) {
        header("Location: product.php?success=delete");
    } else {
        header("Location: product.php?error=delete");
    }    

    $stmt->close();
    $con->close();
} else {
    header("Location: product.php?error=Invalid product ID");
}
?>

<?php

session_start();
include('./includes/connect.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['qty'])) {
    header("Location: transaction.php");
    exit;
}

$product_id = intval($_GET['id']);
$user_id = $_SESSION['user']['id'];
$quantity = intval($_GET['qty']);

$con->begin_transaction();

try {
    
    $stmt = $con->prepare("SELECT * FROM Products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    $product = $product_result->fetch_assoc();
    $stmt->close();

    if ($product && $product['stock_qty'] >= $quantity && $quantity > 0) {
        $amount = $product['price'] * $quantity;

        $stmt = $con->prepare("INSERT INTO Orders (user_id, product_id, total, quantity, seller_status, buyer_status) VALUES (?, ?, ?, ?, 'pending', 'active')");
        $stmt->bind_param("iiid", $user_id, $product_id, $amount, $quantity);
        $stmt->execute();
        $order_id = $con->insert_id;
        $stmt->close();

        $new_stock_qty = $product['stock_qty'] - $quantity;
        $stmt = $con->prepare("UPDATE Products SET stock_qty = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_stock_qty, $product_id);
        $stmt->execute();
        $stmt->close();

        $con->commit();
        $success_message = "Transaction completed successfully! Order has been placed.";
    } else {
        throw new Exception("Requested quantity is not available. Only {$product['stock_qty']} left in stock.");
    }
} catch (Exception $e) {
    $con->rollback();
    $error_message = "Failed to place the order: " . $e->getMessage();
}

$con->close();

?>

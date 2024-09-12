<?php
include('../includes/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $stmt = $con->prepare("SELECT id, name, description, price, stock_qty, category, product_image FROM Products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'No product ID provided']);
}

$con->close();
?>

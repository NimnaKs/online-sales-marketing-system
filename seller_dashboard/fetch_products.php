<?php
session_start();
include('../includes/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user']['id'];

$sql = "
    SELECT p.id, p.name, p.description, p.price, p.stock_qty, p.product_image, p.category, p.created_at
    FROM Products p
    JOIN seller s ON p.seller_id = s.seller_id
    WHERE s.user_id = ?
";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);  
$stmt->execute();
$result = $stmt->get_result();

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$con->close();

echo json_encode($products);
?>

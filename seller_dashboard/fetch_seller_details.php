<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];


$query = "SELECT seller_name, seller_image FROM seller WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$seller = $result->fetch_assoc();

$seller_name = $seller['seller_name'] ?? 'Sample Seller';
$seller_image = $seller['seller_image'] ? 'data:image/jpeg;base64,' . $seller['seller_image'] : '../assets/images/dp.jpg'; 

echo json_encode([
    'seller_name' => $seller_name,
    'seller_image' => $seller_image
]);

$stmt->close();
$conn->close();

?>
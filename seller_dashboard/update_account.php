<?php 
include('../includes/connect.php');

session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'seller') {
    header('Location: ../login.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_image_base64 = '';

    if (isset($_FILES['seller_image']) && $_FILES['seller_image']['error'] === UPLOAD_ERR_OK) {
        $image_data = file_get_contents($_FILES['seller_image']['tmp_name']);
        $seller_image_base64 = base64_encode($image_data);
    } else {
        $query = "SELECT seller_image FROM seller WHERE user_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $seller = $result->fetch_assoc();
        $seller_image_base64 = $seller['seller_image'];
        $stmt->close();
    }

    $seller_name = $_POST['seller_name'];
    $business_name = $_POST['business_name'];
    $seller_email = $_POST['seller_email'];
    $seller_phone = $_POST['seller_phone'];

    $query = "UPDATE seller 
              SET seller_name = ?, business_name = ?, seller_email = ?, seller_phone = ?, seller_image = ?
              WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $seller_name, $business_name, $seller_email, $seller_phone, $seller_image_base64, $user_id);

    if ($stmt->execute()) {
        header("Location: my_account.php?success=1");
        exit();
    } else {
        header("Location: my_account.php?error=1");
        exit();
    }
}
?>
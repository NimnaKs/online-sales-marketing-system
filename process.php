<?php

session_start();
include('./includes/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$order_query = "SELECT id FROM Orders ORDER BY id DESC LIMIT 1";
$order_result = $con->query($order_query);

if ($order_result->num_rows > 0) {
    $row = $order_result->fetch_assoc();
    $order_id = $row['id'] + 1; 
} else {
    $order_id = 1; 
}

$merchant_id = "1227731";
$name = "order-" . $order_id;
$price = $_POST["totalPrice"];
$currency = "LKR";
$merchant_secret = "MTQ1MzQyMDE4NDE4OTI5MTg3NTQzOTcxNDY3NjY5MTMyODk2MzMxMQ==";

$hash = strtoupper(
    md5(
        $merchant_id .
        $order_id .
        number_format($price, 2, '.', '') .
        $currency .
        strtoupper(md5($merchant_secret))
    )
);

$obj = new stdClass();
$obj->order_id = $order_id;
$obj->merchant_id = $merchant_id;
$obj->name = $name;
$obj->price = $price;
$obj->currency = $currency;
$obj->hash = $hash;

echo json_encode($obj);

?>

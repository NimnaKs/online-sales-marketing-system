<?php
include('./includes/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$sql = "SELECT * FROM products";
$result = $con->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$con->close();

echo json_encode($products);
?>

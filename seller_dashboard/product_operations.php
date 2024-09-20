<?php
include('../includes/connect.php');

session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'seller') {
    header('Location: ../login.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$userId = $_SESSION['user']['id']; 

$sellerId = null;
$sellerQuery = "SELECT seller_id FROM seller WHERE user_id = ?";
$sellerStmt = $con->prepare($sellerQuery);
$sellerStmt->bind_param('i', $userId);
$sellerStmt->execute();
$sellerStmt->bind_result($sellerId);
$sellerStmt->fetch();
$sellerStmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock_qty = trim($_POST['stock_qty']);
    $category = trim($_POST['category']);
    // $product_image = $_POST['product_image'];

    $errors = [];

    if (empty($name)) {
        $errors[] = 'Product name is required.';
    }

    if (empty($description)) {
        $errors[] = 'Description is required.';
    }

    if (!is_numeric($price) || $price <= 0) {
        $errors[] = 'Price must be a positive number.';
    }

    if (!is_numeric($stock_qty) || $stock_qty < 0) {
        $errors[] = 'Stock quantity must be a non-negative number.';
    }

    if (empty($category)) {
        $errors[] = 'Please select a category.';
    }

    $product_image = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $imageData = file_get_contents($_FILES['product_image']['tmp_name']);
        $product_image = base64_encode($imageData);
    }
    

    if (empty($errors)) {
        if ($id) {
            if (!empty($product_image)) {
                $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock_qty = ?, category = ?, product_image = ? WHERE id = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ssdissi", $name, $description, $price, $stock_qty, $category, $product_image, $id);
            } else {
                $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock_qty = ?, category = ? WHERE id = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ssdisi", $name, $description, $price, $stock_qty, $category, $id);
            }
            
            if ($stmt->execute()) {
                header("Location: product.php?success=update");
            } else {
                header("Location: product.php?error=update");
            }
        } else {

            $sql = "INSERT INTO products (name, description, price, stock_qty, category, seller_id, product_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            
            $stmt->bind_param("ssdisis", $name, $description, $price, $stock_qty, $category, $sellerId, $product_image);
            
            if ($stmt->execute()) {
                header("Location: product.php?success=save");
            } else {
                header("Location: product.php?error=save");
            }
        }
    } else {
        foreach ($errors as $error) {
            echo '<p style="color:red;">' . $error . '</p>';
        }
    }
    
}
?>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page - Shop Master</title>
    <link rel="icon" href="assets/images/icon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/product.css"> 
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
</head>

<body>

    <?php include('header.php'); ?>

    <section class="product-center">

        <div class="product-header">
            <div class="product-header-content">
                <h1>Our Products</h1>
                <p>Browse products by categories and choose your favorite items. Click Buy Now to proceed with the purchase.</p>
            </div>
        </div>

        <div class="product-content">
        <?php
            include('./includes/connect.php');

            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            $category_query = "SELECT DISTINCT category FROM Products";
            $category_result = $con->query($category_query);

            if ($category_result->num_rows > 0) {
                while ($category_row = $category_result->fetch_assoc()) {
                    $category = $category_row['category'];

                    echo "<h2 class='product-category-title'>$category</h2>";
                    echo "<div class='product-section'>";

                    $product_query = "SELECT p.*, s.seller_name, s.seller_email FROM Products p
                                    JOIN seller s ON p.seller_id = s.seller_id
                                    WHERE p.category = '$category'";
                    $product_result = $con->query($product_query);

                    if ($product_result->num_rows > 0) {
                        while ($product_row = $product_result->fetch_assoc()) {
                            
                            $product_image_base64 = $product_row['product_image']; 

                            echo "<div class='product-card'>
                                    <div class='product-image'>
                                        <img src='data:image/jpeg;base64,$product_image_base64' alt='" . $product_row['name'] . "'>
                                    </div>
                                    <div class='product-info'>
                                        <h3 class='product-name'>" . $product_row['name'] . "</h3>
                                        <p class='product-description'>" . $product_row['description'] . "</p>
                                        <p class='product-seller'><strong>Seller:</strong> " . $product_row['seller_name'] . "</p>
                                        <p class='product-stock'><strong>Stock:</strong> " . $product_row['stock_qty'] . " available</p>
                                        <span class='product-price'>$" . number_format($product_row['price'], 2) . "</span>
                                        <div class='product-actions'>
                                            <a href='transaction.php?id=" . $product_row['id'] . "' class='buy-now-btn'>Buy Now</a>
                                        </div>
                                    </div>
                                </div>";
                        }
                    } else {
                        echo "<p>No products available in this category.</p>";
                    }

                    echo "</div>";
                }
            } else {
                echo "<p>No categories available.</p>";
            }

            $con->close();
        ?>
        </div>

        <div class="cta-track-orders">
            <div class="cta-overlay"></div>
            <div class="cta-content">
                <h2>Track Your Orders</h2>
                <p>Keep an eye on your purchases and stay updated on their delivery status!</p>
                <a href="track-orders.php" class="cta-button">Order Details</a>
            </div>
        </div>

    </section>

    <?php include('footer.php'); ?>
    
</body>

</html>

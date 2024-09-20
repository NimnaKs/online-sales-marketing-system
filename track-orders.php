<?php
include('./includes/connect.php');

session_start();

if (!isset($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Orders - Shop Master</title>
    <link rel="stylesheet" href="assets/css/track_orders.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <?php include('header.php'); ?>

    <section class="order-center">

        <section class="order-hero">
            <div class="order-hero-content">
                <h1>Track Your Orders</h1>
                <p>View your past orders, current order status, and detailed information about your purchases.</p>
            </div>
        </section>

        <div id="orders-content" style="display: flex;
                                        flex-direction: row;
                                        flex-wrap: wrap;
                                        gap: 30px;
                                        justify-content: space-evenly;
                                        padding: 40px 20px;">
            <?php
            include('./includes/connect.php');

            $user_id = $_SESSION['user']['id'];
            $order_query = "SELECT o.*, p.name AS product_name, p.price, p.product_image, s.seller_name 
                            FROM Orders o
                            JOIN Products p ON o.product_id = p.id
                            JOIN seller s ON p.seller_id = s.seller_id
                            WHERE o.user_id = '$user_id'
                            ORDER BY o.order_date DESC";
            $order_result = $con->query($order_query);

            if ($order_result->num_rows > 0) {
                while ($order_row = $order_result->fetch_assoc()) {

                    $product_image_base64 = $order_row['product_image'];

                    echo "<div class='order-card' style='width: fit-content;'>
                            <h3 class='order-product-name'>" . $order_row['product_name'] . "</h3>
                            <div class='order-image'>
                                <img src='data:image/jpeg;base64,$product_image_base64' alt='" . $order_row['product_name'] . "'>
                            </div>
                            <div class='order-info'>
                                <p class='order-quantity'><strong>Quantity:</strong> " . $order_row['quantity'] . "</p>
                                <p class='order-seller'><strong>Seller:</strong> " . $order_row['seller_name'] . "</p>
                                <p class='order-status'><strong>Seller Status:</strong> " . ucfirst($order_row['seller_status']) . "</p>
                                <form method='POST' action='update_order_status.php'>
                                    <p class='order-status'>
                                        <strong>Your Status:</strong>
                                        <select name='buyer_status' onchange='this.form.submit()'>
                                            <option value='active'" . ($order_row['buyer_status'] == 'active' ? 'selected' : '') . ">Active</option>
                                            <option value='cancelled'" . ($order_row['buyer_status'] == 'cancelled' ? 'selected' : '') . ">Cancelled</option>
                                            <option value='delivered'" . ($order_row['buyer_status'] == 'delivered' ? 'selected' : '') . ">Delivered</option>
                                        </select>
                                        <input type='hidden' name='order_id' value='" . $order_row['id'] . "' />
                                    </p>
                                </form>
                                <p class='order-date'><strong>Order Date:</strong> " . date('F j, Y, g:i a', strtotime($order_row['order_date'])) . "</p>
                                <p class='order-total'><strong>Total:</strong> $" . number_format($order_row['total'], 2) . "</p>
                            </div>
                          </div>";
                }
            } else {
                echo "<p>No orders found.</p>";
            }

            $con->close();
            ?>
        </div>

        <div class="cta-back-to-shop">
            <div class="cta-overlay"></div>
            <div class="cta-content">
                <h2>Continue Shopping</h2>
                <p>Explore our wide range of products and find more great deals!</p>
                <a href="product_page.php" class="cta-button">Back to Products</a>
            </div>
        </div>

    </section>

    <?php include('footer.php'); ?>

</body>

</html>
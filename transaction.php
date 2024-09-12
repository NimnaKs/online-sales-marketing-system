<?php
session_start();
include('./includes/connect.php');

if (!isset($_GET['id']) || !isset($_SESSION['user'])) {
    header("Location: login.php"); 
    exit;
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user']['id'];
$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_number = $_POST['card_number'];
    $payment_method = $_POST['payment_method'];
    $quantity = intval($_POST['quantity']);
    
    $product_query = "SELECT * FROM Products WHERE id = $product_id";
    $product_result = $con->query($product_query);
    $product = $product_result->fetch_assoc();

    if ($product['stock_qty'] >= $quantity && $quantity > 0) {

        $amount = $product['price'] * $quantity; 
        $order_insert_query = "INSERT INTO Orders (user_id, product_id, total, quantity, seller_status, buyer_status) VALUES 
                                ($user_id, $product_id, $amount, $quantity, 'pending', 'active')";
        if ($con->query($order_insert_query)) {
            $order_id = $con->insert_id;

            $transaction_query = "INSERT INTO Transactions (order_id, amount, payment_method, card_number) 
                                  VALUES ($order_id, $amount, '$payment_method', '$card_number')";
            
            if ($con->query($transaction_query)) {
                
                $new_stock_qty = $product['stock_qty'] - $quantity; 
                $update_stock_query = "UPDATE Products SET stock_qty = $new_stock_qty WHERE id = $product_id";
                $con->query($update_stock_query);

                $success_message = "Transaction completed successfully! Order has been placed.";
            } else {
                $error_message = "Failed to process transaction.";
            }
        } else {
            $error_message = "Failed to place the order.". $con->error;
        }
    } else {
        $error_message = "Requested quantity is not available. Only {$product['stock_qty']} left in stock.";
    }
}

$product_query = "SELECT p.*, s.seller_name FROM Products p 
                  JOIN seller s ON p.seller_id = s.seller_id WHERE p.id = $product_id";
$product_result = $con->query($product_query);
$product = $product_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction - Shop Master</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/product.css"> 
    <link rel="stylesheet" href="assets/css/transaction.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <section class="transaction-hero">
        <div class="transaction-hero-content">
            <h1>Secure Your Purchase</h1>
            <p>Finalize your order quickly and securely.</p>
        </div>
    </section>

    <section class="transaction-section">
        <div class="transaction-container">
            <h1>Complete Your Purchase</h1>

            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php else: ?>

                <div class="product-details">
                    <div class="product-image">
                        <img src="data:image/jpeg;base64,<?php echo $product['product_image']; ?>" alt="<?php echo $product['name']; ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo $product['name']; ?></h3>
                        <p><?php echo $product['description']; ?></p>
                        <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                        <p><strong>Stock Quantity:</strong> <?php echo $product['stock_qty']; ?></p>
                        <p><strong>Seller:</strong> <?php echo $product['seller_name']; ?></p>
                    </div>
                </div>

                <form method="POST" class="transaction-form">
                    <div class="quantity-group">
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" min="1" value="1" required>
                        </div>
                        <div class="form-group">
                            <label for="card_number">Card Number:</label>
                            <input type="text" name="card_number" id="card_number" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <select name="payment_method" id="payment_method" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="buy-now-btn">Buy Now</button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <div class="cta-track-orders">
        <div class="cta-overlay"></div>
        <div class="cta-content">
            <h2>Track Your Orders</h2>
            <p>Keep an eye on your purchases and stay updated on their delivery status!</p>
            <a href="track-orders.php" class="cta-button">Order Details</a>
        </div>
    </div>


    <?php include('footer.php'); ?>
</body>
</html>

<?php
session_start();
include('./includes/connect.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: track-orders.php");
    exit;
}

$product_id = intval($_GET['id']);
$user_id = $_SESSION['user']['id'];
$error_message = '';
$success_message = '';

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
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
            <?php else: ?>

                <div class="product-details">
                    <div class="product-image">
                        <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($product['product_image']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>Price:</strong>$<?php echo number_format($product['price'], 2); ?></p>
                        <p><strong>Stock Quantity:</strong><span
                                id="stock_qty"><?php echo htmlspecialchars($product['stock_qty']); ?></span>
                        </p>
                        <p><strong>Seller:</strong> <?php echo htmlspecialchars($product['seller_name']); ?></p>
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" min="1"
                                max="<?php echo htmlspecialchars($product['stock_qty']); ?>" value="1" required>
                        </div>
                        <p><strong>Total Price:</strong>$<span
                                id="totalPrice"><?php echo number_format($product['price'], 2); ?></span></p>
                    </div>
                </div>

                <button type="button" class="buy-now-btn" onclick="buyNow();">Buy Now</button>
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

    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityInput = document.getElementById('quantity');
            const totalPriceSpan = document.getElementById('totalPrice');
            const stockQtySpan = document.getElementById('stock_qty');
            const productPrice = <?php echo json_encode($product['price']); ?>;
            let productStock = <?php echo json_encode($product['stock_qty']); ?>;

            function updateOrderDetailsInfo() {
                const quantity = parseInt(quantityInput.value);
                const totalPrice = (productPrice * quantity).toFixed(2);
                totalPriceSpan.textContent = totalPrice;
                stockQtySpan.textContent = (productStock - quantity);
            }

            quantityInput.addEventListener('input', updateOrderDetailsInfo);

            window.buyNow = function () {
                const quantity = parseInt(quantityInput.value);
                const totalPrice = (productPrice * quantity).toFixed(2);

                let form = new FormData();
                form.append("totalPrice", totalPrice);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "process.php", true);
                xhr.onreadystatechange = function () {
                    if (xhr.status === 200 && xhr.readyState === 4) {
                        console.log("Process up to this point");
                        console.log(xhr.responseText);
                        var data = JSON.parse(xhr.responseText);

                        payhere.onCompleted = function onCompleted(orderId) {
                            let xhr = new XMLHttpRequest();
                            xhr.open("POST", `order-process.php?id=<?php echo $product_id; ?>& qty=${ quantity }`, true);
                            xhr.onreadystatechange = function () {
                                if (xhr.status === 200 && xhr.readyState === 4) {
                                    alert("Payment completed. OrderID: " + orderId);
                                } 
                            };
                            xhr.send();       
                        };

                        payhere.onDismissed = function onDismissed() {
                            alert("Payment dismissed");
                        };

                        payhere.onError = function onError(error) {
                            alert("Error: " + error);
                        };

                        let payment = {
                            sandbox: true,
                            merchant_id: data.merchant_id,
                            return_url: undefined, 
                            cancel_url: undefined, 
                            notify_url: "http://sample.com/notify",
                            order_id: data.order_id,
                            items: data.name,
                            amount: data.price,
                            currency: data.currency,
                            hash: data.hash,
                            first_name: "<?php echo explode(' ', $_SESSION['user']['name'])[0]; ?>",
                            last_name: "<?php echo explode(' ', $_SESSION['user']['name'])[1]; ?>",
                            email: "<?php echo $_SESSION['user']['email']; ?>",
                            phone: "0768539902",
                            address: "No.1, Galle Road",
                            city: "Colombo",
                            country: "Sri Lanka",
                        };

                        console.log(payment);

                        payhere.startPayment(payment);
                    }
                };
                xhr.send(form);
            };
        });
    </script>
</body>

</html>
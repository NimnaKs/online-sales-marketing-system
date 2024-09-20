<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Master</title>
    <link rel="icon" href="assets/images/icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    
    <?php include('header.php'); ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Our Online Store</h1>
            <p>Shop the latest products and manage your business with our simple, easy-to-use platform.</p>
            <a href="product_page.php" class="cta-button">Shop Now</a>
        </div>
    </section>

    <section id="features" class="features">
        <h2>Why Choose Us?</h2>
        <div class="feature-cards">
            <div class="feature-card">
                <i class="fas fa-cogs feature-icon"></i>
                <h3>Easy Setup</h3>
                <p>Quickly create your account and start managing your products in minutes.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shield-alt feature-icon"></i>
                <h3>Secure Payments</h3>
                <p>We ensure all transactions are secure and encrypted for your safety.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-headset feature-icon"></i>
                <h3>24/7 Support</h3>
                <p>Get round-the-clock assistance for any issues or questions you might have.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shipping-fast feature-icon"></i>
                <h3>Fast Shipping</h3>
                <p>Enjoy quick and reliable shipping for all your orders worldwide.</p>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-overlay"></div>
        <div class="cta-content">
            <h2>Get Started with Your Own Store</h2>
            <p>Join us today and experience the best online shopping and sales platform!</p>
            <a href="register.php" class="cta-button">Create an Account</a>
        </div>
    </section>

    <?php include('footer.php'); ?>

    <script src="assets/js/script.js"></script>
</body>
</html>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Master</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Top Banner -->
    <div class="top-banner">
        <p>Exclusive Offer: Boost Your Sales with Our Advanced Marketing Tools – Get 50% OFF! <a href="#">Learn More</a></p>
    </div>

    <!-- Header -->
    <header>
        <div class="logo">
            <h1>SHOP MASTER</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#">Products</a></li>
                <?php if(!isset($_SESSION['user'])): ?>
                    <li><a href="register.php">Register</a></li>
                <?php else: ?>
                    <li><a href="#">My Account</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="search-cart-user">
            <input type="text" placeholder="Search" class="search-bar">
            <button class="search-button">Search</button>
            <a href="#" class="cart-icon"><i class="fas fa-shopping-cart"></i></a>
            <?php if(!isset($_SESSION['user'])): ?>
                <a href="login.php" class="user-icon"><i class="fas fa-user"></i></a>
            <?php else: ?>
                <div class="user-menu">
                    <div class="dropdown">
                        <button class="dropdown-button"><i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-content">
                            <?php if($_SESSION['user']['role'] == 'seller'): ?>
                                <a href="dashboard.php">Go to Dashboard</a>
                            <?php endif; ?>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                    <a href="#" class="user-icon"><i class="fas fa-user"></i> <?php echo $_SESSION['user']['name']; ?></a>
                </div>
            <?php endif; ?>
        </div>

    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Our Online Store</h1>
            <p>Shop the latest products and manage your business with our simple, easy-to-use platform.</p>
            <a href="products.php" class="cta-button">Shop Now</a>
        </div>
    </section>

    <!-- Features Section -->
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

    <!-- Product Section -->
    <!-- <section class="products">
        <h2>Our Products</h2>
        <div class="product-categories">
            <div class="category-card">
                <i class="fas fa-tshirt category-icon"></i>
                <h3>Apparel</h3>
                <p>Find the latest fashion trends and accessories.</p>
            </div>
            <div class="category-card">
                <i class="fas fa-laptop category-icon"></i>
                <h3>Electronics</h3>
                <p>Discover the newest gadgets and electronics.</p>
            </div>
            <div class="category-card">
                <i class="fas fa-couch category-icon"></i>
                <h3>Home & Living</h3>
                <p>Decorate your home with our exclusive collections.</p>
            </div>
        </div>
        <div class="product-cards">
            <div class="product-card">
                <img src="assets/images/product1.jpg" alt="Product 1" class="product-image">
                <h3 class="product-title">Stylish Jacket</h3>
                <p class="product-price">$49.99</p>
                <a href="#" class="product-button">Buy Now</a>
            </div>
            <div class="product-card">
                <img src="assets/images/product2.jpg" alt="Product 2" class="product-image">
                <h3 class="product-title">Wireless Headphones</h3>
                <p class="product-price">$89.99</p>
                <a href="#" class="product-button">Buy Now</a>
            </div>
            <div class="product-card">
                <img src="assets/images/product3.jpg" alt="Product 3" class="product-image">
                <h3 class="product-title">Modern Sofa</h3>
                <p class="product-price">$399.99</p>
                <a href="#" class="product-button">Buy Now</a>
            </div>
        </div>
    </section>-->

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="cta-overlay"></div>
        <div class="cta-content">
            <h2>Get Started with Your Own Store</h2>
            <p>Join us today and experience the best online shopping and sales platform!</p>
            <a href="register.php" class="cta-button">Create an Account</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-bottom">
            <p>&copy; 2024 ShopMaster | Designed by NimnaKs</p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>

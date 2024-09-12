<div class="top-banner">
    <p>Exclusive Offer: Boost Your Sales with Our Advanced Marketing Tools – Get 50% OFF! <a href="#">Learn More</a></p>
</div>

<header>
    <div class="logo">
        <h1>SHOP MASTER</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="product_page.php">Products</a></li>
            <li><a href="help_center.php">Help Center</a></li>
            <?php if(!isset($_SESSION['user'])): ?>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="search-cart-user">
        <input type="text" placeholder="Search" class="search-bar">
        <button class="search-button">Search</button>
        <a href="track-orders.php" class="cart-icon"><i class="fas fa-shopping-cart"></i></a>
        <?php if(!isset($_SESSION['user'])): ?>
            <a href="login.php" class="user-icon"><i class="fas fa-user"></i></a>
        <?php else: ?>
            <div class="user-menu">
                <div class="dropdown">
                    <button class="dropdown-button"><i class="fas fa-chevron-down"></i></button>
                    <div class="dropdown-content">
                        <?php if($_SESSION['user']['role'] == 'seller'): ?>
                            <a href="seller_dashboard/dashboard.php">Go to Dashboard</a>
                        <?php endif; ?>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <a href="#" class="user-icon"><i class="fas fa-user"></i> <?php echo $_SESSION['user']['name']; ?></a>
            </div>
        <?php endif; ?>
    </div>
</header>
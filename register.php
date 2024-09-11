<?php
session_start();
include('./includes/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = strtolower($_POST['role']); 

    if (!empty($name) && !empty($email) && !empty($password) && !empty($role)) {
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . $con->error);
        }

        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id;

            if ($role === 'seller') {
                $sellerQuery = "INSERT INTO seller (user_id, seller_name, seller_email) VALUES (?, ?, ?)";
                $sellerStmt = $con->prepare($sellerQuery);

                if ($sellerStmt === false) {
                    die("Prepare failed: " . $con->error);
                }

                $sellerStmt->bind_param("iss", $userId, $name, $email);

                if ($sellerStmt->execute()) {
                    $success = true;
                } else {
                    $error = "Error: " . $sellerStmt->error;
                }

                $sellerStmt->close();
            } else {
                $success = true;
            }

            $_SESSION['user'] = [
                'id' => $userId,  
                'email' => $email,
                'name' => $name,
                'role' => $role
            ];
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();  
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/reg-style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="assets/js/script.js"></script>
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
                <li><a href="#">Features</a></li>
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
                    <a href="#" class="user-icon"><i class="fas fa-user"></i> <?php echo $_SESSION['user']['name']; ?></a>
                    <div class="dropdown">
                        <button class="dropdown-button">▼</button>
                        <div class="dropdown-content">
                            <a href="logout.php">Logout</a>
                            <?php if($_SESSION['user']['role'] == 'seller'): ?>
                                <a href="dashboard.php">Go to Dashboard</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <section class="registration-block">
        <div class="registration-container">
            <div class="form-header">
                <div class="logo">
                    <h1>SHOP MASTER</h1>
                </div>
                <h2>Register</h2>
            </div>
            
            <?php if ($success): ?>
                <div class="alert success">Registration successful! Welcome to Shop MASTER.</div>
            <?php elseif ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="registration-form" onsubmit="return validateForm()">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <select name="role" id="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="User">User</option>
                    <option value="Seller">Seller</option>
                </select>

                <button type="submit" class="register-button">Register</button>
            </form>

            
            <p class="login-link">Already have an account? <a href="login.php">Log In</a></p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-bottom">
            <p>&copy; 2024 ShopMaster | Designed by NimnaKs</p>
        </div>
    </footer>
</body>
</html>

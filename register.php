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
    <link rel="icon" href="assets/images/icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/reg-style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="assets/js/script.js"></script>
</head>
<body>

    <?php include('header.php'); ?>

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

    <?php include('footer.php'); ?>

    
</body>
</html>

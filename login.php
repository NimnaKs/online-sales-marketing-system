<?php
session_start();
include('./includes/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM Users WHERE email = ?";
        $stmt = $con->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . $con->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],  
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ];

                if ($user['role'] === 'admin') {
                    header('Location: admin_dashboard/dashboard.php');
                }else {
                    header('Location: index.php'); 
                }
                exit;
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "No account found with that email!";
        }

        $stmt->close();
    } else {
        $error = "Both fields are required!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                <h2>Login</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="registration-form" onsubmit="return validateForm()">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="register-button">Login</button>
            </form>

            <p class="login-link">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </section>

    <?php include('footer.php'); ?>

</body>
</html>

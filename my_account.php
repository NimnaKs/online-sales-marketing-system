<?php
session_start();
include('./includes/connect.php');

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$query = "SELECT u.name, u.email, s.seller_name, s.business_name, s.seller_email, s.seller_phone, s.seller_image 
            FROM Users u
            LEFT JOIN seller s ON u.id = s.user_id
            WHERE u.id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$seller = $result->fetch_assoc();
$stmt->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account Page | Sales & Marketing System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <link rel="stylesheet" href="assets/css/sidebar-style.css" />
</head>
<body>
  <div class="container">

    <?php include('sidebar.php')?>

    <div class="main">
      
      <div class="main-top">
        <h1>My Account</h1>
      </div>

      <?php if (isset($_GET['success'])): ?>
        <div class="alert success">
          Details updated successfully!
          <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
        </div>
      <?php elseif (isset($_GET['error'])): ?>
        <div class="alert error">
          Error updating details. Please try again.
          <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
        </div>
      <?php endif; ?>


      <div class="account-form">
        <form action="update_account.php" method="POST" enctype="multipart/form-data">
          
          <div class="profile-section">
            <div class="file-upload" id="uploadContainer">
                <?php if (!empty($seller['seller_image'])): ?>
                    <img id="imagePreview" src="data:image/jpeg;base64,<?php echo $seller['seller_image']; ?>" alt="Seller Image">
                <?php else: ?>
                    <img id="imagePreview" src="assets/images/preview-img.png" alt="Seller Image">
                <?php endif; ?>
                
                <input type="file" id="seller_image" name="seller_image" accept="image/*" onchange="previewImage(event)">
                <label for="seller_image" class="custom-file-upload">Choose File</label>
            </div>
          </div>

          <div>
            <label for="seller_name">Seller Name</label>
            <input type="text" id="seller_name" name="seller_name" value="<?php echo htmlspecialchars($seller['seller_name']); ?>" required>
          </div>

          <div>
            <label for="business_name">Business Name</label>
            <input type="text" id="business_name" name="business_name" value="<?php echo htmlspecialchars($seller['business_name']); ?>">
          </div>

          <div>
            <label for="seller_email">Seller Email</label>
            <input type="email" id="seller_email" name="seller_email" value="<?php echo htmlspecialchars($seller['seller_email']); ?>" readonly>
          </div>

          <div>
            <label for="seller_phone">Seller Phone</label>
            <input type="text" id="seller_phone" name="seller_phone" value="<?php echo htmlspecialchars($seller['seller_phone']); ?>">
          </div>

          <button type="submit">Update Details</button>
        </form>
      </div>
    </div>
  </div>

  <script src="assets/js/script.js"></script>
</body>
</html>

<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'admin') {
  header("Location: ../login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller Details | Sales & Marketing System</title>
  <link rel="icon" href="assets/images/icon.png" type="image/png">
  <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
  <link rel="stylesheet" href="../assets/css/admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
</head>
<body>
  <div class="container">

    <?php include('sidebar.php'); ?>

    <div class="main">
      <div class="main-top">
        <h1>Seller Details</h1>
      </div>

      <div class="seller-details">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Business Name</th>
              <th>Phone</th>
              <th>Image</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include('../includes/connect.php');

            $query = "SELECT * FROM seller ORDER BY created_at DESC";
            $result = $con->query($query);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $image = !empty($row['seller_image']) ? 'data:image/jpeg;base64,' . $row['seller_image'] : '../assets/images/preview-img.png';
                echo "<tr>
                        <td>{$row['seller_id']}</td>
                        <td>{$row['seller_name']}</td>
                        <td>{$row['seller_email']}</td>
                        <td>{$row['business_name']}</td>
                        <td>{$row['seller_phone']}</td>
                        <td>
                          <img src='{$image}' alt='Seller Image' class='seller-image'>
                        </td>
                        <td>{$row['created_at']}</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='7' class='no-record'>No sellers found</td></tr>";
            }

            $con->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>

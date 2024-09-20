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
  <title>Admin Dashboard | Sales & Marketing System</title>
  <link rel="icon" href="assets/images/icon.png" type="image/png">
  <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
  <link rel="stylesheet" href="../assets/css/admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" />
</head>

<body>
  <div class="container">
    <?php include('sidebar.php'); ?>

    <div class="main">
      <div class="main-top">
        <h1>Admin Dashboard</h1>
      </div>

      <div class="dashboard-card">
        <i class="fas fa-users"></i>
        <div>
          <h2>Total Users</h2>
          <?php
          include('../includes/connect.php');

          $query = "SELECT COUNT(*) AS total_users FROM Users";
          $result = $con->query($query);
          $data = $result->fetch_assoc();
          echo "<p>{$data['total_users']}</p>";
          ?>
        </div>
      </div>

      <div class="dashboard-card">
        <i class="fas fa-box"></i>
        <div>
          <h2>Total Products</h2>
          <?php
          $query = "SELECT COUNT(*) AS total_products FROM Products";
          $result = $con->query($query);
          $data = $result->fetch_assoc();
          echo "<p>{$data['total_products']}</p>";
          ?>
        </div>
      </div>

      <div class="dashboard-card">
        <i class="fas fa-shopping-cart"></i>
        <div>
          <h2>Total Orders</h2>
          <?php
          $query = "SELECT COUNT(*) AS total_orders FROM Orders";
          $result = $con->query($query);
          $data = $result->fetch_assoc();
          echo "<p>{$data['total_orders']}</p>";
          ?>
        </div>
      </div>

      <div class="dashboard-card">
        <i class="fas fa-comment-dots"></i>
        <div>
          <h2>Total Feedback</h2>
          <?php
          $query = "SELECT COUNT(*) AS total_feedback FROM feedback";
          $result = $con->query($query);
          $data = $result->fetch_assoc();
          echo "<p>{$data['total_feedback']}</p>";
          ?>
        </div>
      </div>

      <h2>Recent Orders</h2>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Order Date</th>
            <th>Seller Status</th>
            <th>Buyer Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT o.id AS order_id, u.name AS user_name, p.name AS product_name, o.quantity, o.total, o.order_date, o.seller_status, o.buyer_status
                    FROM Orders o
                    JOIN Users u ON o.user_id = u.id
                    JOIN Products p ON o.product_id = p.id
                    ORDER BY o.order_date DESC
                    LIMIT 5";
          $result = $con->query($query);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $seller_status_class = '';
              switch ($row['seller_status']) {
                case 'pending':
                  $seller_status_class = 'status-pending';
                  break;
                case 'shipped':
                  $seller_status_class = 'status-shipped';
                  break;
              }

              $buyer_status_class = '';
              switch ($row['buyer_status']) {
                case 'active':
                  $buyer_status_class = 'status-active';
                  break;
                case 'cancelled':
                  $buyer_status_class = 'status-cancelled';
                  break;
                case 'delivered':
                  $buyer_status_class = 'status-delivered';
                  break;
              }

              echo "<tr>
                      <td>{$row['order_id']}</td>
                      <td>{$row['user_name']}</td>
                      <td>{$row['product_name']}</td>
                      <td>{$row['quantity']}</td>
                      <td>{$row['total']}</td>
                      <td>{$row['order_date']}</td>
                      <td class='{$seller_status_class}'>{$row['seller_status']}</td>
                      <td class='{$buyer_status_class}'>{$row['buyer_status']}</td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='8' class='no-record'>No recent orders found</td></tr>";
          }

          $con->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>
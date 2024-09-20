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
  <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" />
  <style>
    :root {
      --primary-color: #3498db;
      --secondary-color: #2ecc71;
      --tertiary-color: #e74c3c;
      --light-bg: #ecf0f1;
      --dark-bg: #34495e;
      --card-bg: #fff;
      --card-gradient: linear-gradient(145deg, #ffffff, #e0e0e0);
      --text-color: #333;
      --border-radius: 12px;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background-color: var(--light-bg);
      color: var(--text-color);
      margin: 0;
    }

    .container {
      display: flex;
      min-height: 100vh;
    }

    .main {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .main-top {
      margin-bottom: 20px;
    }

    h1 {
      font-size: 28px;
      color: var(--dark-bg);
      margin: 0;
    }

    .dashboard-card {
      background: var(--card-bg);
      border-radius: var(--border-radius);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin: 12px 0;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-left: 5px solid var(--primary-color);
      transition: all 0.3s ease;
      background: var(--card-gradient);
    }

    .dashboard-card:hover {
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
      transform: translateY(-5px);
    }

    .dashboard-card i {
      font-size: 40px;
      color: var(--primary-color);
      margin-right: 15px;
    }

    .dashboard-card h2 {
      margin: 0;
      font-size: 18px;
      color: var(--dark-bg);
    }

    .dashboard-card p {
      font-size: 24px;
      font-weight: bold;
      margin: 0;
    }

    .dashboard-card .status {
      color: var(--primary-color);
    }

    .dashboard-card .status.pending {
      color: var(--tertiary-color);
    }

    .dashboard-card .status.reviewed {
      color: var(--secondary-color);
    }

    .dashboard-card .status.resolved {
      color: var(--secondary-color);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff;
      border-radius: var(--border-radius);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    th,
    td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      font-size: 14px;
    }

    th {
      background-color: var(--light-bg);
      font-weight: bold;
      color: var(--dark-bg);
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .no-record {
      text-align: center;
      color: var(--secondary-color);
    }

    @media (max-width: 768px) {

      table,
      th,
      td {
        font-size: 12px;
        padding: 8px;
      }
    }
  </style>
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
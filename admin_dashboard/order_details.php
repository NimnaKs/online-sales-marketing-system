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
  <title>Order Details | Sales & Marketing System</title>
  <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
  <link rel="stylesheet" href="../assets/css/admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
</head>
<body>
  <div class="container">
    <?php include('sidebar.php'); ?>

    <div class="main">
      <div class="main-top">
        <h1>Order Details</h1>
      </div>

      <div class="order-details">
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>User</th>
              <th>Product</th>
              <th>Seller</th>
              <th>Quantity</th>
              <th>Total</th>
              <th>Order Date</th>
              <th>Seller Status</th>
              <th>Buyer Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include('../includes/connect.php');

            $query = "SELECT o.id AS order_id, u.name AS user_name, p.name AS product_name, s.seller_name, o.quantity, o.total, o.order_date, o.seller_status, o.buyer_status, p.product_image, s.seller_image 
                      FROM Orders o
                      JOIN Users u ON o.user_id = u.id
                      JOIN Products p ON o.product_id = p.id
                      JOIN seller s ON p.seller_id = s.seller_id
                      ORDER BY o.order_date DESC";
            $result = $con->query($query);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $product_image = !empty($row['product_image']) ? 'data:image/jpeg;base64,' . $row['product_image'] : '../assets/images/preview-img.png';
                $seller_image = !empty($row['seller_image']) ? 'data:image/jpeg;base64,' . $row['seller_image'] : '../assets/images/preview-img.png';

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
                        <td>
                          <img src='{$product_image}' alt='Product Image' class='product-image'>
                          <div class='product-name'>{$row['product_name']}</div>
                        </td>
                        <td>
                          <img src='{$seller_image}' alt='Seller Image' class='seller-image'>
                          <div class='seller-name'>{$row['seller_name']}</div>
                        </td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['total']}</td>
                        <td>{$row['order_date']}</td>
                        <td class='{$seller_status_class}'>{$row['seller_status']}</td>
                        <td class='{$buyer_status_class}'>{$row['buyer_status']}</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='9' class='no-record'>No orders found</td></tr>";
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

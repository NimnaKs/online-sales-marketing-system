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
  <title>Product Details | Sales & Marketing System</title>
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
        <h1>Product Details</h1>
      </div>

      <div class="product-details">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Stock Quantity</th>
              <th>Category</th>
              <th>Image</th>
              <th>Seller</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include('../includes/connect.php');

            $query = "
              SELECT p.id, p.name, p.description, p.price, p.stock_qty, p.category, p.product_image, p.created_at,
                     s.seller_name
              FROM Products p
              LEFT JOIN seller s ON p.seller_id = s.seller_id
              ORDER BY p.created_at DESC
            ";
            $result = $con->query($query);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $image = !empty($row['product_image']) ? 'data:image/jpeg;base64,' . $row['product_image'] : 'path/to/preview-image.jpg';
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['description']}</td>
                        <td>\${$row['price']}</td>
                        <td>{$row['stock_qty']}</td>
                        <td>{$row['category']}</td>
                        <td>
                          <img src='{$image}' alt='Product Image' class='product-image'>
                        </td>
                        <td>{$row['seller_name']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='9' class='no-record'>No products found</td></tr>";
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

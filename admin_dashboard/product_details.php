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
  <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #ecf0f1;
      color: #333;
    }
    .container {
      display: flex;
      min-height: 100vh;
    }
    .main {
      flex: 1;
      padding: 20px;
    }
    .main-top {
      margin-bottom: 20px;
    }
    .main-top h1 {
      font-size: 24px;
      color: #333;
    }
    .product-details {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f4f4f4;
      font-weight: bold;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .product-image {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
    }
    .preview-image {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .preview-image img {
      width: 50px;
      height: 50px;
    }
    .no-record {
      text-align: center;
      color: #888;
      padding: 20px;
    }
  </style>
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

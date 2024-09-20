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
    .seller-details {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin: 12px;
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
    .seller-image {
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

            // Modify the query to order by created_at in descending order
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

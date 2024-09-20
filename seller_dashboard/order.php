<?php
include('../includes/connect.php');

session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'seller') {
    header('Location: ../login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Page | Sales & Marketing System</title>
    <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
    <link rel="stylesheet" href="../assets/css/order.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>

    <div class="container">

        <?php include('sidebar.php'); ?>

        <div class="main">
            <div class="main-top">
                <h1>Orders</h1>
            </div>

            <div class="order-content">
                
                <div class="order-filters">
                    <form method="GET" action="">
                        <input type="date" name="startDate" placeholder="Start Date" />
                        <input type="date" name="endDate" placeholder="End Date" />
                        <select name="orderStatusFilter">
                            <option value="">All Statuses</option>
                            <option value="Pending">Pending</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Canceled">Canceled</option>
                        </select>
                        <button type="submit" class="filter-button">Filter</button>
                    </form>
                </div>

                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        include('../includes/connect.php');

                        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
                        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
                        $orderStatusFilter = isset($_GET['orderStatusFilter']) ? $_GET['orderStatusFilter'] : '';

                        $user_id = $_SESSION['user']['id'];

                        $query = "SELECT o.id, o.order_date, u.name AS customer, p.name AS product, o.quantity, o.total, o.seller_status
                                  FROM Orders o
                                  JOIN Products p ON o.product_id = p.id
                                  JOIN Users u ON o.user_id = u.id
                                  WHERE u.id = ?";

                          if ($startDate) {
                              $query .= " AND o.order_date >= ?";
                          }
                          if ($endDate) {
                              $query .= " AND o.order_date <= ?";
                          }
                          if ($orderStatusFilter) {
                              $query .= " AND o.seller_status = ?";
                          }

                          $query .= " ORDER BY o.order_date DESC";

                        $stmt = $con->prepare($query);
                        $params = [$user_id];

                        if ($startDate) {
                            $params[] = $startDate;
                        }
                        if ($endDate) {
                            $params[] = $endDate;
                        }
                        if ($orderStatusFilter) {
                            $params[] = $orderStatusFilter;
                        }

                        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                        $stmt->execute();
                        $result = $stmt->get_result();


                        if ($result->num_rows > 0) {
                            while ($order = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$order['id']}</td>
                                        <td>{$order['order_date']}</td>
                                        <td>{$order['customer']}</td>
                                        <td>{$order['product']}</td>
                                        <td>{$order['quantity']}</td>
                                        <td>\${$order['total']}</td>
                                        <td>
                                            <select class='status-select' data-order-id='{$order['id']}'>
                                                <option value='Pending'" . ($order['seller_status'] === 'pending' ? ' selected' : '') . ">Pending</option>
                                                <option value='Shipped'" . ($order['seller_status'] === 'shipped' ? ' selected' : '') . ">Shipped</option>
                                            </select>
                                        </td>
                                        <td><button class='update-btn' data-order-id='{$order['id']}'>Update</button></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No orders found.</td></tr>";
                        }

                        $con->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
      document.querySelectorAll('.update-btn').forEach(button => {
          button.addEventListener('click', function () {
              const orderId = this.getAttribute('data-order-id');
              const statusSelect = document.querySelector(`select[data-order-id='${orderId}']`);
              const newStatus = statusSelect.value;

              const xhr = new XMLHttpRequest();
              xhr.open("POST", "update-order-status.php", true);
              xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xhr.onreadystatechange = function () {
                  if (xhr.readyState === 4 && xhr.status === 200) {
                      alert(`Order ID ${orderId} updated to ${newStatus}`);
                  }
              };
              xhr.send(`orderId=${orderId}&newStatus=${newStatus}`);
          });
      });

    </script>

</body>

</html>

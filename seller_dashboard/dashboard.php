<?php
include('../includes/connect.php');

session_start();

if (!isset($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];


$sellerQuery = "SELECT * FROM seller WHERE user_id = ?";
$sellerStmt = $con->prepare($sellerQuery);
$sellerStmt->bind_param("i", $user_id);
$sellerStmt->execute();
$sellerResult = $sellerStmt->get_result();
$seller = $sellerResult->fetch_assoc();
$seller_id = $seller['seller_id'];

$salesQuery = "SELECT SUM(total) AS total_sales FROM Orders WHERE seller_status = 'shipped' AND user_id = ?";
$salesStmt = $con->prepare($salesQuery);
$salesStmt->bind_param("i", $user_id);
$salesStmt->execute();
$salesResult = $salesStmt->get_result();
$total_sales = $salesResult->fetch_assoc()['total_sales'] ?? 0;

$pendingOrdersQuery = "SELECT COUNT(*) AS orders_pending FROM Orders WHERE seller_status = 'pending' AND user_id = ?";
$pendingOrdersStmt = $con->prepare($pendingOrdersQuery);
$pendingOrdersStmt->bind_param("i", $user_id);
$pendingOrdersStmt->execute();
$pendingOrdersResult = $pendingOrdersStmt->get_result();
$orders_pending = $pendingOrdersResult->fetch_assoc()['orders_pending'];

$productsQuery = "SELECT COUNT(*) AS products_in_stock FROM Products WHERE seller_id = ?";
$productsStmt = $con->prepare($productsQuery);
$productsStmt->bind_param("i", $seller_id);
$productsStmt->execute();
$productsResult = $productsStmt->get_result();
$products_in_stock = $productsResult->fetch_assoc()['products_in_stock'];

$monthlyRevenueQuery = "SELECT SUM(total) AS monthly_revenue FROM Orders WHERE seller_status = 'shipped' AND user_id = ? AND MONTH(order_date) = MONTH(CURRENT_DATE())";
$monthlyRevenueStmt = $con->prepare($monthlyRevenueQuery);
$monthlyRevenueStmt->bind_param("i", $user_id);
$monthlyRevenueStmt->execute();
$monthlyRevenueResult = $monthlyRevenueStmt->get_result();
$monthly_revenue = $monthlyRevenueResult->fetch_assoc()['monthly_revenue'] ?? 0;

$recentOrdersQuery = "SELECT * FROM Orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 5";
$recentOrdersStmt = $con->prepare($recentOrdersQuery);
$recentOrdersStmt->bind_param("i", $user_id);
$recentOrdersStmt->execute();
$recentOrdersResult = $recentOrdersStmt->get_result();

$inventoryQuery = "SELECT * FROM Products WHERE seller_id = ? ORDER BY stock_qty ASC LIMIT 5";
$inventoryStmt = $con->prepare($inventoryQuery);
$inventoryStmt->bind_param("i", $seller_id);
$inventoryStmt->execute();
$inventoryResult = $inventoryStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Seller Dashboard | Sales & Marketing System</title>
    <link rel="stylesheet" href="../assets/css/sidebar-style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <link rel="stylesheet" href="../assets/css/seller_dashboard.css" />
</head>
<body>
    <div class="container">

        <?php include('sidebar.php'); ?>

        <div class="main">
            <div class="main-top">
                <h1>Dashboard</h1>
            </div>

            <div class="cards">
                <div class="card">
                    <i class="fas fa-dollar-sign"></i>
                    <div>
                        <h3>Total Sales</h3>
                        <p>$<?php echo number_format($total_sales, 2); ?></p>
                    </div>
                </div>
                <div class="card">
                    <i class="fas fa-box"></i>
                    <div>
                        <h3>Orders Pending</h3>
                        <p><?php echo $orders_pending; ?></p>
                    </div>
                </div>
                <div class="card">
                    <i class="fas fa-cubes"></i>
                    <div>
                        <h3>Products in Stock</h3>
                        <p><?php echo $products_in_stock; ?></p>
                    </div>
                </div>
                <div class="card">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <h3>Monthly Revenue</h3>
                        <p>$<?php echo number_format($monthly_revenue, 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="recent-orders">
                <h2>Recent Orders</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $recentOrdersResult->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><span style="color: <?php echo $order['seller_status'] === 'pending' ? '#ffc107' : '#28a745'; ?>;"><?php echo ucfirst($order['seller_status']); ?></span></td>
                            <td>$<?php echo number_format($order['total'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="order.php" class="btn">View All Orders</a>
            </div>

            <div class="inventory-status">
                <h2>Inventory Status</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Stock Qty</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $inventoryResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo $product['stock_qty']; ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="product.php" class="btn">Manage Inventory</a>
            </div>
        </div>

    </div>
</body>
</html>

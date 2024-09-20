<?php
include('../includes/connect.php');

session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'seller') {
    header('Location: ../login.php');
    exit();
}

$user_id = (int)$_SESSION['user']['id']; 

$sellerQuery = "SELECT * FROM seller WHERE user_id = ?";
$sellerStmt = $con->prepare($sellerQuery);
if ($sellerStmt === false) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}
$sellerStmt->bind_param("i", $user_id);
if (!$sellerStmt->execute()) {
    die("Execute failed: (" . $sellerStmt->errno . ") " . $sellerStmt->error);
}
$sellerResult = $sellerStmt->get_result();
$seller = $sellerResult->fetch_assoc();

if (!$seller) {
    die("No seller found for the current user.");
}

$seller_id = (int)$seller['seller_id'];

$salesQuery = "
    SELECT SUM(o.total) AS total_sales 
    FROM Orders o
    JOIN Products p ON o.product_id = p.id
    WHERE p.seller_id = ? AND o.seller_status = 'shipped'
";
$salesStmt = $con->prepare($salesQuery);
if ($salesStmt === false) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}
$salesStmt->bind_param("i", $seller_id);
if (!$salesStmt->execute()) {
    die("Execute failed: (" . $salesStmt->errno . ") " . $salesStmt->error);
}
$salesResult = $salesStmt->get_result();
$total_sales = $salesResult->fetch_assoc()['total_sales'] ?? 0;

$pendingOrdersQuery = "
    SELECT COUNT(*) AS orders_pending 
    FROM Orders o
    JOIN Products p ON o.product_id = p.id
    WHERE p.seller_id = ? AND o.seller_status = 'pending'
";
$pendingOrdersStmt = $con->prepare($pendingOrdersQuery);
if ($pendingOrdersStmt === false) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}
$pendingOrdersStmt->bind_param("i", $seller_id);
if (!$pendingOrdersStmt->execute()) {
    die("Execute failed: (" . $pendingOrdersStmt->errno . ") " . $pendingOrdersStmt->error);
}
$pendingOrdersResult = $pendingOrdersStmt->get_result();
$orders_pending = $pendingOrdersResult->fetch_assoc()['orders_pending'] ?? 0;

$productsQuery = "
    SELECT COUNT(*) AS products_in_stock 
    FROM Products 
    WHERE seller_id = ?
";
$productsStmt = $con->prepare($productsQuery);
if ($productsStmt === false) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}
$productsStmt->bind_param("i", $seller_id);
if (!$productsStmt->execute()) {
    die("Execute failed: (" . $productsStmt->errno . ") " . $productsStmt->error);
}
$productsResult = $productsStmt->get_result();
$products_in_stock = $productsResult->fetch_assoc()['products_in_stock'] ?? 0;

$monthlyRevenueQuery = "
    SELECT SUM(o.total) AS monthly_revenue 
    FROM Orders o
    JOIN Products p ON o.product_id = p.id
    WHERE p.seller_id = ? 
      AND o.seller_status = 'shipped' 
      AND MONTH(o.order_date) = MONTH(CURRENT_DATE()) 
      AND YEAR(o.order_date) = YEAR(CURRENT_DATE())
";
$monthlyRevenueStmt = $con->prepare($monthlyRevenueQuery);
if ($monthlyRevenueStmt === false) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}
$monthlyRevenueStmt->bind_param("i", $seller_id);
if (!$monthlyRevenueStmt->execute()) {
    die("Execute failed: (" . $monthlyRevenueStmt->errno . ") " . $monthlyRevenueStmt->error);
}
$monthlyRevenueResult = $monthlyRevenueStmt->get_result();
$monthly_revenue = $monthlyRevenueResult->fetch_assoc()['monthly_revenue'] ?? 0;

$recentOrdersQuery = "
    SELECT o.id, o.order_date, u.name AS customer_name, o.seller_status, o.total 
    FROM Orders o
    JOIN Products p ON o.product_id = p.id
    JOIN Users u ON o.user_id = u.id
    WHERE p.seller_id = ?
    ORDER BY o.order_date DESC 
    LIMIT 5
";
$recentOrdersStmt = $con->prepare($recentOrdersQuery);
if ($recentOrdersStmt === false) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}
$recentOrdersStmt->bind_param("i", $seller_id);
if (!$recentOrdersStmt->execute()) {
    die("Execute failed: (" . $recentOrdersStmt->errno . ") " . $recentOrdersStmt->error);
}
$recentOrdersResult = $recentOrdersStmt->get_result();

$inventoryQuery = "
    SELECT name, category, stock_qty, price 
    FROM Products 
    WHERE seller_id = ?
    ORDER BY stock_qty ASC 
    LIMIT 5
";
$inventoryStmt = $con->prepare($inventoryQuery);
if ($inventoryStmt === false) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}
$inventoryStmt->bind_param("i", $seller_id);
if (!$inventoryStmt->execute()) {
    die("Execute failed: (" . $inventoryStmt->errno . ") " . $inventoryStmt->error);
}
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

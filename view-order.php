<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) header('location: login.php');
$user = $_SESSION['user'];

include('database/connection.php');

$stmt = $conn->prepare("SELECT * FROM purchase_orders ORDER BY created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Orders - Inventory System</title>
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="css/purchase_order_styles.css">
	<link rel="stylesheet" href="css/main.css">
  <?php include('partials/app-header-scripts.php'); ?>
</head>
<body>
  <div id="dashboardMainContainer">
    <?php include('partials/app-sidebar.php') ?>
    <div class="dasboard_content_container" id="dasboard_content_container">
      <?php include('partials/app-topnav.php') ?>
      <div class="dashboard_content">
        <div class="dashboard_content_main">
          <h1 class="section_header"><i class="fa fa-truck"></i> Purchase Orders</h1>
          <div class="section_content">
            <div class="table-responsive">
              <table class="table table-dark table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Supplier</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $index => $order) { ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td><?= htmlspecialchars($order['order_id']) ?></td>
                      <td><?= htmlspecialchars($order['supplier_name']) ?></td>
                      <td>$<?= number_format($order['total_amount'], 2) ?></td>
                      <td><?= ucfirst($order['status']) ?></td>
                      <td><?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></td>
                      <td><?= date('M d, Y h:i A', strtotime($order['updated_at'])) ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <p class="userCount"><?= count($orders) ?> orders</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('partials/app-scripts.php'); ?>
</body>
</html>

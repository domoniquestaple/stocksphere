<?php
    // Start the session.
    session_start();
    if (!isset($_SESSION['user'])) header('location: login.php');

    $user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports - Inventory Management System</title>
    <!-- Use dashboard.css for shared sidebar & topnav styles -->
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/report.css">
    <script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
</head>
<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sidebar.php') ?> <!-- Same sidebar as dashboard -->
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topnav.php') ?> <!-- Same topnav as dashboard -->
            
            <?php if (in_array('report_view', $user['permissions'])) { ?>
            <div id="reportsContainer">
                <h1 class="pageTitle">Reports</h1>
                <p class="pageDescription">Export data in Excel or PDF format for products, suppliers, deliveries, and purchase orders.</p>
                
                <div class="reportGrid">
                    <div class="reportCard">
                        <h2>Export Products</h2>
                        <div class="alignRight">
                            <a href="database/report_csv.php?report=product" class="reportExportBtn">Excel</a>
                            <a href="database/report_pdf.php?report=product" target="_blank" class="reportExportBtn">PDF</a>
                        </div>
                    </div>
                    
                    <div class="reportCard">
                        <h2>Export Suppliers</h2>
                        <div class="alignRight">
                            <a href="database/report_csv.php?report=supplier" class="reportExportBtn">Excel</a>
                            <a href="database/report_pdf.php?report=supplier" target="_blank" class="reportExportBtn">PDF</a>
                        </div>
                    </div>
                    
                    <div class="reportCard">
                        <h2>Export Deliveries</h2>
                        <div class="alignRight">
                            <a href="database/report_csv.php?report=delivery" class="reportExportBtn">Excel</a>
                            <a href="database/report_pdf.php?report=delivery" target="_blank" class="reportExportBtn">PDF</a>
                        </div>
                    </div>
                    
                    <div class="reportCard">
                        <h2>Export Purchase Orders</h2>
                        <div class="alignRight">
                            <a href="database/report_csv.php?report=purchase_orders" class="reportExportBtn">Excel</a>
                            <a href="database/report_pdf.php?report=purchase_orders" target="_blank" class="reportExportBtn">PDF</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
                <div id="errorMessage">Access denied.</div>
            <?php } ?>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>

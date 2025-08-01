<?php
// Start the session.
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');

// Get SESSION user from data.
$user = $_SESSION['user'];

// Get graph data - purchase order by status
include('database/po_status_pie_graph.php');

// Get graph data - supplier product count
include('database/supplier_product_bar_graph.php');

// Get line graph data - delivery history per day
include('database/delivery_history.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inventory Management System</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
</head>
<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sidebar.php') ?>
        <div class="dashboard_content_container">
            <?php include('partials/app-topnav.php') ?>

            <?php if (in_array('dashboard_view', $user['permissions'])) { ?>
            <div class="dashboard_content">
                <h1 class="dashboard_title">Dashboard Overview</h1>
                
                <div class="dashboard_grid">
                    <div class="dashboard_card">
                        <h2>Purchase Orders By Status</h2>
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                        </figure>
                    </div>

                    <div class="dashboard_card">
                        <h2>Products Per Supplier</h2>
                        <figure class="highcharts-figure">
                            <div id="containerBarChart"></div>
                        </figure>
                    </div>

                    <div class="dashboard_card full_width">
                        <h2>Delivery History Per Day</h2>
                        <div id="deliveryHistory"></div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
                <div id="errorMessage"> Access denied.</div>
            <?php } ?>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        var graphData = <?= json_encode($results) ?>;
        Highcharts.chart('container', {
            chart: { type: 'pie' },
            title: { text: null },
            tooltip: {
                pointFormatter: function() {
                    return `<b>${this.name}</b>: ${this.y}`
                }
            },
            plotOptions: {
                pie: { allowPointSelect: true, dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.y}' } }
            },
            series: [{ name: 'Status', colorByPoint: true, data: graphData }]
        });

        var barGraphData = <?= json_encode($bar_chart_data) ?>;
        var barGraphCategories = <?= json_encode($categories) ?>;
        Highcharts.chart('containerBarChart', {
            chart: { type: 'column' },
            title: { text: null },
            xAxis: { categories: barGraphCategories, crosshair: true },
            yAxis: { min: 0, title: { text: 'Product Count' } },
            series: [{ name: 'Suppliers', data: barGraphData }]
        });

        var lineCategories = <?= json_encode($line_categories) ?>;
        var lineData = <?= json_encode($line_data) ?>;
        Highcharts.chart('deliveryHistory', {
            chart: { type: 'spline' },
            title: { text: null },
            xAxis: { categories: lineCategories },
            yAxis: { title: { text: 'Products Delivered' } },
            series: [{ name: 'Product Delivered', data: lineData }]
        });
    </script>
</body>
</html>

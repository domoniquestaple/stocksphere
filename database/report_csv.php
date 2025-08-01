<?php
// Include database connection
include('connection.php');

// Set report type
$type = $_GET['report'] ?? 'product';

// Output headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $type . '_report.csv"');

// Open output stream
$output = fopen('php://output', 'w');

if ($type === 'product') {
    // CSV Headers
    fputcsv($output, ['ID', 'Product Name', 'Stock', 'Description', 'Created By', 'Created At', 'Updated At']);

    // Query
    $stmt = $conn->prepare("SELECT products.id, products.product_name, products.stock, products.description, users.first_name, users.last_name, products.created_at, products.updated_at
                            FROM products
                            INNER JOIN users ON products.created_by = users.id
                            ORDER BY products.created_at DESC");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $created_by = $row['first_name'] . ' ' . $row['last_name'];
        fputcsv($output, [$row['id'], $row['product_name'], $row['stock'], $row['description'], $created_by, $row['created_at'], $row['updated_at']]);
    }
}

elseif ($type === 'supplier') {
    fputcsv($output, ['ID', 'Supplier Name', 'Location', 'Email', 'Created By', 'Created At']);

    $stmt = $conn->prepare("SELECT suppliers.id, suppliers.supplier_name, suppliers.supplier_location, suppliers.email, users.first_name, users.last_name, suppliers.created_at
                            FROM suppliers
                            INNER JOIN users ON suppliers.created_by = users.id
                            ORDER BY suppliers.created_at DESC");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $created_by = $row['first_name'] . ' ' . $row['last_name'];
        fputcsv($output, [$row['id'], $row['supplier_name'], $row['supplier_location'], $row['email'], $created_by, $row['created_at']]);
    }
}

elseif ($type === 'delivery') {
    fputcsv($output, ['Date Received', 'Quantity Received', 'Product', 'Supplier', 'Batch', 'Created By']);

    $stmt = $conn->prepare("SELECT order_product_history.date_received, order_product_history.qty_received, products.product_name, suppliers.supplier_name, order_product.batch, users.first_name, users.last_name
                            FROM order_product_history
                            INNER JOIN order_product ON order_product_history.order_product_id = order_product.id
                            INNER JOIN products ON order_product.product = products.id
                            INNER JOIN suppliers ON order_product.supplier = suppliers.id
                            INNER JOIN users ON order_product.created_by = users.id
                            ORDER BY order_product.batch DESC");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $created_by = $row['first_name'] . ' ' . $row['last_name'];
        fputcsv($output, [$row['date_received'], $row['qty_received'], $row['product_name'], $row['supplier_name'], $row['batch'], $created_by]);
    }
}

elseif ($type === 'purchase_orders') {
    fputcsv($output, ['Quantity Ordered', 'Quantity Received', 'Remaining', 'Status', 'Batch', 'Supplier', 'Product', 'Created At', 'Created By']);

    $stmt = $conn->prepare("SELECT order_product.quantity_ordered, order_product.quantity_received, order_product.quantity_remaining, order_product.status, order_product.batch, products.product_name, suppliers.supplier_name, order_product.created_at, users.first_name, users.last_name
                            FROM order_product
                            INNER JOIN products ON order_product.product = products.id
                            INNER JOIN suppliers ON order_product.supplier = suppliers.id
                            INNER JOIN users ON order_product.created_by = users.id
                            ORDER BY order_product.batch DESC");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $created_by = $row['first_name'] . ' ' . $row['last_name'];
        fputcsv($output, [$row['quantity_ordered'], $row['quantity_received'], $row['quantity_remaining'], $row['status'], $row['batch'], $row['supplier_name'], $row['product_name'], $row['created_at'], $created_by]);
    }
}

fclose($output);
exit;

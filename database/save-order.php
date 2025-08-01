<?php
session_start();
include('connection.php');

$post_data = $_POST;
$products = $post_data['products'];
$qty = array_values($post_data['quantity']);
$requested_by = $post_data['requested_bys'];

// Generate numeric batch and string order_id
$batch = time();
$order_id = 'PO-' . strtoupper(uniqid());

$post_data_arr = [];

// Prepare the product/supplier/requested_by mapping
foreach ($products as $key => $pid) {
    if (isset($qty[$key])) {
        $post_data_arr[$pid]['sup_qty'] = $qty[$key];
        $post_data_arr[$pid]['req_by'] = isset($requested_by[$key]) ? $requested_by[$key] : 'NOT SET';
    }
}

$success = false;
$total_amount = 0.0;

try {
    // Insert order_product records
    foreach ($post_data_arr as $pid => $post_data) {
        $supplier_qty = $post_data['sup_qty'];
        $requested_by = $post_data['req_by'];

        foreach ($supplier_qty as $sid => $quantity) {
            // Get product price
            $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$pid]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $price = $product ? $product['price'] : 0;

            // Calculate total for this item
            $total_amount += $price * $quantity;

            $values = [
                'supplier' => $sid,
                'product' => $pid,
                'quantity_ordered' => $quantity,
                'status' => 'pending',
                'batch' => $batch,
                'created_by' => $_SESSION['user']['id'],
                'requested_by' => $requested_by,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $sql = "INSERT INTO order_product 
                    (supplier, product, quantity_ordered, status, batch, created_by, requested_by, updated_at, created_at)
                    VALUES 
                    (:supplier, :product, :quantity_ordered, :status, :batch, :created_by, :requested_by, :updated_at, :created_at)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);
        }
    }

    // Insert into purchase_orders table (optional but recommended)
    $stmt = $conn->prepare("INSERT INTO purchase_orders 
        (order_id, supplier_name, total_amount, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, NOW(), NOW())");

    $stmt->execute([
        $order_id,
        'Multiple', // If multiple suppliers, or change logic if one
        $total_amount,
        'Pending'
    ]);

    $success = true;
    $message = 'Order successfully created!';
} catch (Exception $e) {
    $message = $e->getMessage();
}

$_SESSION['response'] = [
    'message' => $message,
    'success' => $success
];

header('location: ../product-order.php');
exit;

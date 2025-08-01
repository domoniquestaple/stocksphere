<?php
include('connection.php');

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Get supplier info
$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
    echo json_encode([]);
    exit;
}

// Get all linked products for the supplier
$stmt = $conn->prepare("SELECT product FROM productsuppliers WHERE supplier = ?");
$stmt->execute([$id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$product_ids = array_column($rows, 'product');

$supplier['products'] = $product_ids;

echo json_encode($supplier);
?>

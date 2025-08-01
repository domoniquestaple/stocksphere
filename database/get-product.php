<?php
include('connection.php');

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch suppliers linked to this product
$stmt = $conn->prepare("SELECT supplier FROM productsuppliers WHERE product = ?");
$stmt->execute([$id]);
$suppliers = $stmt->fetchAll(PDO::FETCH_COLUMN);
$product['suppliers'] = $suppliers;

// Return product data as JSON
echo json_encode($product);

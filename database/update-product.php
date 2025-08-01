<?php
include('connection.php');

$pid = isset($_POST['pid']) ? (int) $_POST['pid'] : 0;
if ($pid <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    exit;
}

$product_name = $_POST['product_name'] ?? '';
$description  = $_POST['description'] ?? '';
$stock        = isset($_POST['stock']) ? (int) $_POST['stock'] : 0;
$target_dir   = "../uploads/products/";
$file_name_value = null;

// Handle image upload
if (!empty($_FILES['img']['tmp_name'])) {
    $file_data = $_FILES['img'];
    $file_ext  = pathinfo($file_data['name'], PATHINFO_EXTENSION);
    $file_name = 'product-' . time() . '.' . $file_ext;

    if (getimagesize($file_data['tmp_name']) &&
        move_uploaded_file($file_data['tmp_name'], $target_dir . $file_name)) {
        $file_name_value = $file_name;
    }
}

try {
    if ($file_name_value) {
        $sql = "UPDATE products 
                SET product_name = ?, description = ?, stock = ?, img = ?, updated_at = NOW()
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_name, $description, $stock, $file_name_value, $pid]);
    } else {
        $sql = "UPDATE products 
                SET product_name = ?, description = ?, stock = ?, updated_at = NOW()
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_name, $description, $stock, $pid]);
    }

    // Update suppliers
    $sql = "DELETE FROM productsuppliers WHERE product = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$pid]);

    $suppliers = $_POST['suppliers'] ?? [];
    foreach ($suppliers as $supplier) {
        $stmt = $conn->prepare("INSERT INTO productsuppliers (supplier, product, updated_at, created_at) VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([(int)$supplier, $pid]);
    }

    echo json_encode([
        'success' => true,
        'message' => "<strong>$product_name</strong> has been successfully updated."
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => "Error processing your request: " . $e->getMessage()
    ]);
}

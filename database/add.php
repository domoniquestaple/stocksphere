<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('connection.php');

$table = $_SESSION['table'] ?? null;

if (!$table) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "No table specified for insertion."
    ];
    header("Location: ../dashboard.php");
    exit;
}

// Add Product
if ($table === 'products') {
    $product_name = $_POST['product_name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user']['id'];

    $target_dir = "../uploads/products/";
    $file_name_value = null;
    $file_data = $_FILES['img'];

    if (!empty($file_data['tmp_name'])) {
        $file_name = $file_data['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = 'product-' . time() . '.' . $file_ext;

        $check = getimagesize($file_data['tmp_name']);
        if ($check) {
            if (move_uploaded_file($file_data['tmp_name'], $target_dir . $file_name)) {
                $file_name_value = $file_name;
            }
        }
    }

    try {
        $sql = "INSERT INTO products 
                (product_name, stock, price, description, img, created_by, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_name, $stock, $price, $description, $file_name_value, $created_by]);

        $pid = $conn->lastInsertId();

        $suppliers = $_POST['suppliers'] ?? [];
        foreach ($suppliers as $supplier_id) {
            $sql = "INSERT INTO productsuppliers (product, supplier, created_at, updated_at)
                    VALUES (?, ?, NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$pid, $supplier_id]);
        }

        $_SESSION['response'] = [
            'success' => true,
            'message' => "$product_name successfully added."
        ];
    } catch (Exception $e) {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Error: " . $e->getMessage()
        ];
    }

    header("Location: ../product-add.php");
    exit;
}

// Add Supplier
if ($table === 'suppliers') {
    $name = $_POST['supplier_name'];
    $location = $_POST['supplier_location'];
    $email = $_POST['email'];

    try {
        $stmt = $conn->prepare("INSERT INTO suppliers (supplier_name, supplier_location, email, created_at, updated_at)
                                VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$name, $location, $email]);

        $_SESSION['response'] = [
            'success' => true,
            'message' => "$name successfully added."
        ];
    } catch (Exception $e) {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Error adding supplier: " . $e->getMessage()
        ];
    }

    header("Location: ../supplier-add.php");
    exit;
}

// Add User
if ($table === 'users') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $permissions = $_POST['permissions'] ?? '';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users 
            (first_name, last_name, email, password, permissions, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, 'active', NOW(), NOW())");
        $stmt->execute([$first_name, $last_name, $email, $hashed_password, $permissions]);

        $_SESSION['response'] = [
            'success' => true,
            'message' => "$first_name $last_name successfully added."
        ];
    } catch (Exception $e) {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Error adding user: " . $e->getMessage()
        ];
    }

    header("Location: ../users-add.php");
    exit;
}

// Fallback
$_SESSION['response'] = [
    'success' => false,
    'message' => "Unrecognized table: $table"
];
header("Location: ../dashboard.php");
exit;

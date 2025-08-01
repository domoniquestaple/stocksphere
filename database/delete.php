<?php
session_start();
include('connection.php');

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$table = $_SESSION['table'] ?? null;
$id = $_GET['id'] ?? null;

if (!$table || !$id) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Missing table or ID for deletion.'
    ];
    header("Location: ../dashboard.php");
    exit;
}

try {
    switch ($table) {
        case 'users':
            // Prevent deletion of admin account (e.g., user ID 1)
            if ($id == 1) {
                throw new Exception("Cannot delete the primary admin user.");
            }
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            break;

        case 'products':
            // Optional: Delete related entries from productsuppliers
            $stmt = $conn->prepare("DELETE FROM productsuppliers WHERE product = ?");
            $stmt->execute([$id]);
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            break;

        case 'suppliers':
            // Optional: Ensure productsuppliers integrity
            $stmt = $conn->prepare("DELETE FROM productsuppliers WHERE supplier = ?");
            $stmt->execute([$id]);
            $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
            $stmt->execute([$id]);
            break;

        default:
            throw new Exception("Invalid table specified for deletion.");
    }

    $_SESSION['response'] = [
        'success' => true,
        'message' => ucfirst($table) . ' entry deleted successfully.'
    ];

} catch (Exception $e) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Deletion failed: ' . $e->getMessage()
    ];
}

// Redirect back to the appropriate view page
$redirect_map = [
    'users' => '../users-view.php',
    'products' => '../product-view.php',
    'suppliers' => '../supplier-view.php'
];

header('Location: ' . ($redirect_map[$table] ?? '../dashboard.php'));
exit;

<?php
session_start();
include('database/connection.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid user ID.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST" action="database/update-user.php">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <label>First Name:</label><input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>"><br>
        <label>Last Name:</label><input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>"><br>
        <label>Email:</label><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>

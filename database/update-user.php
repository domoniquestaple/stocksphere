<?php
session_start();
include('connection.php');

$id = $_POST['id'];
$first = $_POST['first_name'];
$last = $_POST['last_name'];
$email = $_POST['email'];

$stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, updated_at = NOW() WHERE id = ?");
$stmt->execute([$first, $last, $email, $id]);

$_SESSION['response'] = [
    'success' => true,
    'message' => 'User updated successfully.'
];

header('Location: ../users-view.php');
exit;

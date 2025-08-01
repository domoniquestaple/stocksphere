<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include('database/connection.php');

// Fetch all users
$stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/main.css">
    <?php include('partials/app-header-scripts.php'); ?>
</head>
<body>
<div id="dashboardMainContainer">
    <?php include('partials/app-sidebar.php'); ?>
    <div class="dasboard_content_container" id="dasboard_content_container">
        <?php include('partials/app-topnav.php'); ?>
        <div class="dashboard_content">
            <div class="dashboard_content_main">
                <h1 class="section_header"><i class="fa fa-users"></i> Manage Users</h1>
                <a href="users-add.php" class="appBtn"><i class="fa fa-plus"></i> Add New User</a>

                <?php if (isset($_SESSION['response'])): ?>
                    <div class="responseMessage">
                        <p class="responseMessage <?= $_SESSION['response']['success'] ? 'responseMessage__success' : 'responseMessage__error' ?>">
                            <?= $_SESSION['response']['message'] ?>
                        </p>
                    </div>
                    <?php unset($_SESSION['response']); ?>
                <?php endif; ?>

                <div class="tableContainer">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= $user['status'] ?? 'active' ?></td>
                                <td>
                                    <?php if ($user['email'] !== 'admin@example.com'): ?>
                                        <a href="users-edit.php?id=<?= $user['id'] ?>" class="appBtn tableBtn">Edit</a>
                                        <a href="database/delete.php?table=users&id=<?= $user['id'] ?>" class="appBtn tableBtn dangerBtn">Delete</a>
                                    <?php else: ?>
                                        <span class="text-muted">Protected</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="userCount"><?= count($users) ?> user(s)</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('partials/app-scripts.php'); ?>
</body>
</html>

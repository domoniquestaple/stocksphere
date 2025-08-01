<?php
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'users';
$user = $_SESSION['user'];
$users = include('database/show-users.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - Inventory Management System</title>
  <link rel="stylesheet" type="text/css" href="css/dashboard.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css" crossorigin="anonymous" />
</head>
<body>
  <div id="dashboardMainContainer">
    <?php include('partials/app-sidebar.php') ?>
    <div class="dasboard_content_container" id="dasboard_content_container">
      <?php include('partials/app-topnav.php') ?>
      <div class="dashboard_content">
        <div class="dashboard_content_main">
          <div class="row">
            <div class="column column-5">
              <h1 class="section_header"><i class="fa fa-plus"></i> Create User</h1>
              <div id="userAddFormContainer">
                <form action="database/add.php" method="POST" class="appForm">
                  <div class="appFormInputContainer">
                    <label for="first_name">First Name</label>
                    <input type="text" class="appFormInput" id="first_name" name="first_name" required />
                  </div>
                  <div class="appFormInputContainer">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="appFormInput" id="last_name" name="last_name" required />
                  </div>
                  <div class="appFormInputContainer">
                    <label for="email">Email</label>
                    <input type="email" class="appFormInput" id="email" name="email" required />
                  </div>
                  <div class="appFormInputContainer">
                    <label for="password">Password</label>
                    <input type="password" class="appFormInput" id="password" name="password" required />
                  </div>
                  <button type="submit" class="appBtn"><i class="fa fa-plus"></i> Add User</button>
                </form>
                <?php if(isset($_SESSION['response'])): ?>
                  <div class="responseMessage">
                    <p class="responseMessage <?= $_SESSION['response']['success'] ? 'responseMessage__success' : 'responseMessage__error' ?>">
                      <?= $_SESSION['response']['message'] ?>
                    </p>
                  </div>
                  <?php unset($_SESSION['response']); endif; ?>
              </div>
            </div>
            <div class="column column-7">
              <h1 class="section_header"><i class="fa fa-list"></i> List of Users</h1>
              <div class="section_content">
                <div class="users">
                  <table>
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($users as $index => $user): ?>
                      <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="firstName"><?= $user['first_name'] ?></td>
                        <td class="lastName"><?= $user['last_name'] ?></td>
                        <td class="email"><?= $user['email'] ?></td>
                        <td><?= date('M d, Y @ h:i:s A', strtotime($user['created_at'])) ?></td>
                        <td><?= date('M d, Y @ h:i:s A', strtotime($user['updated_at'])) ?></td>
                        <td>
                          <a href="#" class="updateUser" data-userid="<?= $user['id'] ?>"> <i class="fa fa-pencil"></i> Edit</a>
                          <a href="#" class="deleteUser" data-userid="<?= $user['id'] ?>" data-fname="<?= $user['first_name'] ?>" data-lname="<?= $user['last_name'] ?>"> <i class="fa fa-trash"></i> Delete</a>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                  <p class="userCount"><?= count($users) ?> Users </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<script src="js/script.js?v=<?= time(); ?>"></script>
<script src="js/jquery/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.js"></script>
<script>
  // Same JS logic from original script to handle dialog actions.
</script>
</body>
</html>

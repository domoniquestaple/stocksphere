<?php
// Start the session.
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');

$_SESSION['table'] = 'products';
$_SESSION['redirect_to'] = 'product-add.php';

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Inventory Management System</title>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/product.css">
    <?php include('partials/app-header-scripts.php'); ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sidebar.php') ?>
        <div class="dasboard_content_container" id="dasboard_content_container">
            <?php include('partials/app-topnav.php') ?>
            <div class="dashboard_content">
                <?php if (in_array('product_create', $user['permissions'])) { ?>
                <div class="dashboard_content_main">        
                    <div class="row">
                        <div class="column column-12">
                            <h1 class="section_header"><i class="fa fa-plus"></i> Create Product</h1>
                            <div id="userAddFormContainer" class="dark-form-container">                        
                                <form action="database/add.php" method="POST" class="appForm" enctype="multipart/form-data">
                                    
                                    <!-- Product Name -->
                                    <div class="appFormInputContainer form-group">
                                        <label for="product_name">Product Name</label>
                                        <input type="text" class="appFormInput form-control" id="product_name" placeholder="Enter product name..." name="product_name" required />    
                                    </div>

                                    <!-- Stock -->
                                    <div class="appFormInputContainer form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" class="appFormInput form-control" id="stock" placeholder="Enter stock quantity..." name="stock" min="0" required />    
                                    </div>

                                    <!-- Price -->
                                    <div class="appFormInputContainer form-group">
                                        <label for="price">Price</label>
                                        <input type="number" class="appFormInput form-control" id="price" name="price" step="0.01" placeholder="Enter product price..." required />
                                    </div>

                                    <!-- Description -->
                                    <div class="appFormInputContainer form-group">
                                        <label for="description">Description</label>
                                        <textarea class="appFormInput form-control productTextAreaInput" placeholder="Enter product description..." id="description" name="description" rows="3" required></textarea>    
                                    </div>

                                    <!-- Suppliers -->
                                    <div class="appFormInputContainer form-group">
                                        <label for="suppliersSelect">Suppliers</label>
                                        <select name="suppliers[]" id="suppliersSelect" class="form-control" multiple required>
                                            <option value="">Select Supplier</option>
                                            <?php
                                                $show_table = 'suppliers';
                                                $suppliers = include('database/show.php');

                                                foreach ($suppliers as $supplier) {
                                                    echo "<option value='".  $supplier['id']  . "'> ".$supplier['supplier_name'] ."</option>";
                                                }
                                            ?>
                                        </select>
                                        <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple suppliers.</small>
                                    </div>

                                    <!-- Product Image -->
                                    <div class="appFormInputContainer form-group">
                                        <label for="img">Product Image</label>
                                        <input type="file" class="form-control-file" name="img" id="img" required />
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="appBtn btn btn-primary">
                                        <i class="fa fa-plus"></i> Create Product
                                    </button>
                                </form>    

                                <!-- Response Messages -->
                                <?php 
                                    if (isset($_SESSION['response'])) {
                                        $response_message = $_SESSION['response']['message'];
                                        $is_success = $_SESSION['response']['success'];
                                ?>
                                    <div class="responseMessage mt-3">
                                        <p class="responseMessage <?= $is_success ? 'responseMessage__success' : 'responseMessage__error' ?>">
                                            <?= $response_message ?>
                                        </p>
                                    </div>
                                <?php unset($_SESSION['response']); }  ?>
                            </div>    
                        </div>
                    </div>                    
                </div>
                <?php } else { ?>
                    <div id="errorMessage"> Access denied.</div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php include('partials/app-scripts.php'); ?>
</body>
</html>

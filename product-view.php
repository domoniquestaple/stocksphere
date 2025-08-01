<?php
    // Start the session.
    session_start();
    if (!isset($_SESSION['user'])) header('location: login.php');

    $user = $_SESSION['user'];

    // Get all products.
    $show_table = 'products';
    $products = include('database/show.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Products - Inventory Management System</title>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
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
                <?php if (in_array('product_view', $user['permissions'])) { ?>
                <div class="dashboard_content_main">        
                    <div class="row">
                        <div class="column column-12">
                            <h1 class="section_header"><i class="fa fa-list"></i> List of Products</h1>
                            <div class="section_content">
                                <div class="users table-responsive">
                                    <table class="table table-dark table-striped table-bordered">
                                        <thead>
                                            <tr>                                                
                                                <th>#</th>                    
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Stock</th>
                                                <th>Description</th>
                                                <th>Suppliers</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($products as $index => $product){ 
                                                $qty_class = 'bg-success';
                                                $qty_int = (int) $product['stock'];
                                                if ($qty_int <= 10) $qty_class = 'bg-danger';
                                                if ($qty_int >= 11 && $qty_int <= 30) $qty_class = 'bg-warning';
                                            ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td>
                                                        <img class="productImages" src="uploads/products/<?= $product['img'] ?>" alt="Product" style="width:60px; height:60px; object-fit:cover;" />
                                                    </td>
                                                    <td><?= $product['product_name'] ?></td>
                                                    <td class="<?= $qty_class ?> text-white text-center"><?= number_format($product['stock']) ?></td>
                                                    <td><?= $product['description'] ?></td>
                                                    <td>
                                                        <?php
                                                            $supplier_list = '-';
                                                            $pid = $product['id'];
                                                            $stmt = $conn->prepare("
                                                                SELECT supplier_name 
                                                                FROM suppliers, productsuppliers 
                                                                WHERE 
                                                                    productsuppliers.product = ? 
                                                                    AND 
                                                                    productsuppliers.supplier = suppliers.id
                                                            ");
                                                            $stmt->execute([$pid]);
                                                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                            if ($row) {                                                                
                                                                $supplier_arr = array_column($row, 'supplier_name');
                                                                $supplier_list = '<ul><li>' . implode("</li><li>", $supplier_arr) . '</li></ul>';
                                                            }
                                                            echo $supplier_list;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $uid = $product['created_by'];
                                                            $stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
                                                            $stmt->execute([$uid]);
                                                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            echo $row['first_name'] . ' ' . $row['last_name'];
                                                        ?>
                                                    </td>
                                                    <td><?= date('M d, Y @ h:i:s A', strtotime($product['created_at'])) ?></td>
                                                    <td><?= date('M d, Y @ h:i:s A', strtotime($product['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="#" 
                                                            class="<?= in_array('product_edit', $user['permissions']) ? 'updateProduct' : 'accessDeniedErr' ?>" 
                                                            data-pid="<?= $product['id'] ?>"> 
                                                            <i class="fa fa-pencil"></i> Edit
                                                        </a> | 
                                                        <a href="#" 
                                                            class="<?= in_array('product_delete', $user['permissions']) ? 'deleteProduct' : 'accessDeniedErr'?>" 
                                                            data-name="<?= $product['product_name'] ?>" 
                                                            data-pid="<?= $product['id'] ?>"> 
                                                            <i class="fa fa-trash"></i> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($products) ?> products </p>
                                </div>
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

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header">
            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editProductForm" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="pid" id="edit_pid">

              <div class="form-group">
                <label for="edit_product_name">Product Name</label>
                <input type="text" class="form-control" name="product_name" id="edit_product_name" required>
              </div>

              <div class="form-group">
                <label for="edit_stock">Stock</label>
                <input type="number" class="form-control" name="stock" id="edit_stock" required>
              </div>

              <div class="form-group">
                <label for="edit_description">Description</label>
                <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
              </div>

              <div class="form-group">
                <label for="edit_img">Product Image</label>
                <input type="file" class="form-control-file" name="img" id="edit_img">
                <img id="edit_preview_img" src="" alt="Product Image" style="max-width:100px; margin-top:10px;">
              </div>

              <div class="form-group">
                <label>Suppliers</label>
                <select name="suppliers[]" id="edit_suppliers" class="form-control" multiple>
                  <?php
                    $show_table = 'suppliers';
                    $suppliers = include('database/show.php');
                    foreach($suppliers as $supplier){
                      echo "<option value='{$supplier['id']}'> {$supplier['supplier_name']} </option>";
                    }
                  ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php include('partials/app-scripts.php'); ?>

    <script>
    $(document).ready(function(){
      // Open Edit Modal
      $('.updateProduct').on('click', function(e){
        e.preventDefault();
        const pid = $(this).data('pid');
        $.ajax({
          url: 'database/get-product.php',
          method: 'GET',
          data: { id: pid },
          dataType: 'json',
          success: function(product){
            $('#edit_pid').val(product.id);
            $('#edit_product_name').val(product.product_name);
            $('#edit_stock').val(product.stock);
            $('#edit_description').val(product.description);
            $('#edit_preview_img').attr('src', 'uploads/products/' + product.img);

            $('#edit_suppliers option').prop('selected', false);
            if(product.suppliers){
              product.suppliers.forEach(function(sid){
                $('#edit_suppliers option[value="'+sid+'"]').prop('selected', true);
              });
            }

            $('#editProductModal').modal('show');
          }
        });
      });

      // Submit Edit Form
      $('#editProductForm').on('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
          url: 'database/update-product.php',
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function(response){
            if(response.success){
              alert(response.message);
              $('#editProductModal').modal('hide');
              location.reload();
            } else {
              alert('Error: ' + response.message);
            }
          }
        });
      });
    });
    </script>
</body>
</html>

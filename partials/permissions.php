<div id="permissions">
    <h4>Permissions</h4>
    <hr>
    <div id="permissionsContainer">
        <?php
        $modules = [
            'Dashboard' => ['dashboard_view' => 'View'],
            'Reports' => ['report_view' => 'View'],
            'Purchase Order' => [
                'po_view' => 'View',
                'po_create' => 'Create',
                'po_edit' => 'Edit'
            ],
            'Product' => [
                'product_view' => 'View',
                'product_create' => 'Create',
                'product_edit' => 'Edit',
                'product_delete' => 'Delete'
            ],
            'Supplier' => [
                'supplier_view' => 'View',
                'supplier_create' => 'Create',
                'supplier_edit' => 'Edit',
                'supplier_delete' => 'Delete'
            ],
            'User' => [
                'user_view' => 'View',
                'user_create' => 'Create',
                'user_edit' => 'Edit',
                'user_delete' => 'Delete'
            ],
            'Point of Sale' => [
                'pos' => 'Grant'
            ]
        ];

        foreach ($modules as $module => $actions) {
            echo '<div class="permission"><div class="row">';
            echo '<div class="col-md-3"><p class="moduleName">' . $module . '</p></div>';
            foreach ($actions as $value => $label) {
                echo '<div class="col-md-2"><p class="moduleFunc" data-value="' . $value . '">' . $label . '</p></div>';
            }
            echo '</div></div>';
        }
        ?>
    </div>
</div>

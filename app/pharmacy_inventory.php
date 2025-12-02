<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pharmacy /</span> Inventory Management
    </h4>

    <!-- Inventory Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total Items</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $total_items = DB::select('select count(*) as count from pharmacy_inventory WHERE pharmacy_id='.$user[0]->id);
                                    echo $total_items[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-primary">(Active)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-package bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Low Stock</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $low_stock = DB::select('select count(*) as count from pharmacy_inventory WHERE pharmacy_id='.$user[0]->id.' AND stock_quantity <= 10');
                                    echo $low_stock[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-warning">(Alert)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-error bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Expiring Soon</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $expiring = DB::select('select count(*) as count from pharmacy_inventory WHERE pharmacy_id='.$user[0]->id.' AND expiry_date <= DATE_ADD(NOW(), INTERVAL 30 DAY)');
                                    echo $expiring[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-danger">(30 days)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-time-five bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total Value</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    $<?php 
                                    $total_value = DB::select('select sum(stock_quantity * wholesale_price) as value from pharmacy_inventory WHERE pharmacy_id='.$user[0]->id);
                                    echo number_format($total_value[0]->value ?? 0, 2); 
                                    ?>
                                </h4>
                                <small class="text-success">(Wholesale)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-dollar bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Medication Inventory</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterInventory(this.value)">
                            <option value="">All Categories</option>
                            <option value="tablet">Tablets</option>
                            <option value="capsule">Capsules</option>
                            <option value="syrup">Syrups</option>
                            <option value="injection">Injections</option>
                            <option value="cream">Creams</option>
                            <option value="drops">Drops</option>
                        </select>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                            <i class="bx bx-plus me-1"></i> Add Item
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Medication</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Pricing</th>
                                    <th>Expiry</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $inventory = DB::select('
                                    SELECT * FROM pharmacy_inventory 
                                    WHERE pharmacy_id = '.$user[0]->id.'
                                    ORDER BY medication_name ASC
                                    LIMIT 50
                                ');
                                
                                if(!empty($inventory)){
                                    foreach($inventory as $item){
                                        $stock_status = '';
                                        $stock_class = '';
                                        if($item->stock_quantity <= 0){
                                            $stock_status = 'Out of Stock';
                                            $stock_class = 'bg-danger';
                                        } elseif($item->stock_quantity <= 10){
                                            $stock_status = 'Low Stock';
                                            $stock_class = 'bg-warning';
                                        } else {
                                            $stock_status = 'In Stock';
                                            $stock_class = 'bg-success';
                                        }
                                        
                                        $expiry_class = '';
                                        $days_to_expiry = (strtotime($item->expiry_date) - time()) / (60 * 60 * 24);
                                        if($days_to_expiry <= 30){
                                            $expiry_class = 'text-danger';
                                        } elseif($days_to_expiry <= 90){
                                            $expiry_class = 'text-warning';
                                        }
                                ?>
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0"><?php echo $item->medication_name; ?></h6>
                                            <small class="text-muted"><?php echo $item->generic_name; ?></small>
                                            <br><small class="text-muted">Batch: <?php echo $item->batch_number; ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info"><?php echo ucfirst($item->category); ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium"><?php echo $item->stock_quantity; ?> <?php echo $item->unit; ?></span>
                                            <br><span class="badge <?php echo $stock_class; ?>"><?php echo $stock_status; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="text-muted">Wholesale: $<?php echo number_format($item->wholesale_price, 2); ?></small><br>
                                            <small class="text-muted">Retail: $<?php echo number_format($item->retail_price, 2); ?></small><br>
                                            <small class="text-muted">Doctor: $<?php echo number_format($item->doctor_price, 2); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="<?php echo $expiry_class; ?>">
                                            <?php echo date('M d, Y', strtotime($item->expiry_date)); ?>
                                        </span>
                                        <?php if($days_to_expiry <= 30){ ?>
                                        <br><small class="text-danger">Expires in <?php echo floor($days_to_expiry); ?> days</small>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $item->status == 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo ucfirst($item->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" onclick="editItem('<?php echo $item->id; ?>')">
                                                    <i class="bx bx-edit me-1"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" onclick="updateStock('<?php echo $item->id; ?>')">
                                                    <i class="bx bx-plus-circle me-1"></i> Update Stock
                                                </a>
                                                <a class="dropdown-item" href="#" onclick="viewHistory('<?php echo $item->id; ?>')">
                                                    <i class="bx bx-history me-1"></i> View History
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteItem('<?php echo $item->id; ?>')">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bx bx-package bx-lg text-muted"></i>
                                        <p class="text-muted mt-2">No inventory items yet</p>
                                        <small class="text-muted">Add medications to start managing your inventory</small>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Inventory Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="action" value="add_inventory">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Medication Name</label>
                            <input type="text" class="form-control" name="medication_name" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
                                <option value="">Select Category</option>
                                <option value="tablet">Tablet</option>
                                <option value="capsule">Capsule</option>
                                <option value="syrup">Syrup</option>
                                <option value="injection">Injection</option>
                                <option value="cream">Cream</option>
                                <option value="drops">Drops</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Generic Name</label>
                            <input type="text" class="form-control" name="generic_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Manufacturer</label>
                            <input type="text" class="form-control" name="manufacturer">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" name="stock_quantity" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit</label>
                            <select class="form-select" name="unit" required>
                                <option value="pieces">Pieces</option>
                                <option value="bottles">Bottles</option>
                                <option value="boxes">Boxes</option>
                                <option value="vials">Vials</option>
                                <option value="tubes">Tubes</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Batch Number</label>
                            <input type="text" class="form-control" name="batch_number" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Wholesale Price ($)</label>
                            <input type="number" class="form-control" name="wholesale_price" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Retail Price ($)</label>
                            <input type="number" class="form-control" name="retail_price" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Doctor Price ($)</label>
                            <input type="number" class="form-control" name="doctor_price" step="0.01" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Manufacturing Date</label>
                            <input type="date" class="form-control" name="manufacturing_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="expiry_date" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editItem(itemId) {
    // Implementation for editing inventory item
    alert('Edit item ID: ' + itemId);
}

function updateStock(itemId) {
    let newStock = prompt('Enter new stock quantity:');
    if(newStock && !isNaN(newStock)) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'update_stock',
            item_id: itemId,
            stock_quantity: newStock
        }, function(response) {
            location.reload();
        });
    }
}

function viewHistory(itemId) {
    // Implementation for viewing stock history
    alert('View history for item ID: ' + itemId);
}

function deleteItem(itemId) {
    if(confirm('Are you sure you want to delete this item?')) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'delete_inventory',
            item_id: itemId
        }, function(response) {
            location.reload();
        });
    }
}

function filterInventory(category) {
    if(category) {
        window.location.href = '?pg=inventory&category=' + category;
    } else {
        window.location.href = '?pg=inventory';
    }
}
</script>

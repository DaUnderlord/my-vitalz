<?php
// Handle both array and object formats
$user_obj = is_array($user) ? $user[0] : $user;

// Current doctor's ID
$uid = isset($user_obj->id) ? (int)$user_obj->id : 0;

// Get doctor's storefront inventory
$storefront_inventory = DB::select('
    SELECT dsi.*, md.drug_name, md.generic_name, md.category, md.unit, md.photo, md.stock_quantity as supplier_stock,
        u.company_name, u.first_name as rep_first_name, u.last_name as rep_last_name
    FROM doctor_storefront_inventory dsi
    INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id
    INNER JOIN users u ON md.sales_rep_id = u.id
    WHERE dsi.doctor_id = '.$uid.'
    ORDER BY dsi.is_featured DESC, dsi.created_at DESC
');

// Get storefront settings
$storefront_settings = DB::select('SELECT * FROM doctor_storefront_settings WHERE doctor_id = '.$uid);
$has_settings = !empty($storefront_settings);
?>

<div class="row">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h5 class="mb-1">My Storefront</h5>
        <p class="text-muted mb-0">Manage your virtual pharmacy inventory</p>
      </div>
      <div class="d-flex gap-2">
        <a href="?pg=marketplace" class="btn btn-outline-primary">
          <i class="bx bx-plus me-1"></i> Add Products
        </a>
        <a href="?pg=storefront-settings" class="btn btn-primary">
          <i class="bx bx-cog me-1"></i> Settings
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Storefront Stats -->
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted d-block mb-1">Total Products</span>
            <h3 class="mb-0"><?php echo count($storefront_inventory); ?></h3>
            <small class="text-muted">In your storefront</small>
          </div>
          <span class="badge bg-label-primary rounded p-2">
            <i class="bx bx-package bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted d-block mb-1">Active Products</span>
            <h3 class="mb-0">
              <?php 
                $active_count = 0;
                foreach($storefront_inventory as $item) {
                  if($item->is_active) $active_count++;
                }
                echo $active_count;
              ?>
            </h3>
            <small class="text-success">Visible to patients</small>
          </div>
          <span class="badge bg-label-success rounded p-2">
            <i class="bx bx-show bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted d-block mb-1">Featured</span>
            <h3 class="mb-0">
              <?php 
                $featured_count = 0;
                foreach($storefront_inventory as $item) {
                  if($item->is_featured) $featured_count++;
                }
                echo $featured_count;
              ?>
            </h3>
            <small class="text-warning">Highlighted items</small>
          </div>
          <span class="badge bg-label-warning rounded p-2">
            <i class="bx bx-star bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted d-block mb-1">Avg Markup</span>
            <h3 class="mb-0">
              <?php 
                $total_markup = 0;
                $count = count($storefront_inventory);
                foreach($storefront_inventory as $item) {
                  $total_markup += $item->markup_percentage;
                }
                echo $count > 0 ? number_format($total_markup / $count, 1) : 0;
              ?>%
            </h3>
            <small class="text-info">Profit margin</small>
          </div>
          <span class="badge bg-label-info rounded p-2">
            <i class="bx bx-trending-up bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Storefront Preview Link -->
<?php if($has_settings && $storefront_settings[0]->is_active){ ?>
<div class="row mb-3">
  <div class="col-12">
    <div class="alert alert-primary d-flex align-items-center justify-content-between">
      <div>
        <i class="bx bx-info-circle me-2"></i>
        <strong>Your Storefront is Live!</strong> Patients can now browse and purchase from your storefront.
      </div>
      <a href="/patient/storefront/<?php echo $user[0]->ref_code; ?>" target="_blank" class="btn btn-sm btn-light">
        <i class="bx bx-show me-1"></i> Preview Storefront
      </a>
    </div>
  </div>
</div>
<?php } ?>

<!-- Products Table -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Storefront Inventory</h6>
        <div class="btn-group btn-group-sm" role="group">
          <button type="button" class="btn btn-outline-primary active">All</button>
          <button type="button" class="btn btn-outline-primary">Active</button>
          <button type="button" class="btn btn-outline-primary">Inactive</button>
        </div>
      </div>
      <div class="card-body">
        <?php if(empty($storefront_inventory)){ ?>
          <div class="text-center py-5">
            <i class="bx bx-store bx-lg text-muted mb-3"></i>
            <h5 class="text-muted">Your Storefront is Empty</h5>
            <p class="text-muted mb-3">Start adding products from the marketplace to build your virtual pharmacy</p>
            <a href="?pg=marketplace" class="btn btn-primary">
              <i class="bx bx-plus me-1"></i> Browse Marketplace
            </a>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Product</th>
                <th>Supplier</th>
                <th>Cost Price</th>
                <th>Retail Price</th>
                <th>Markup</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($storefront_inventory as $item){ ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <?php if($item->photo){ ?>
                      <img src="/assets/products/<?php echo $item->photo; ?>" alt="<?php echo $item->drug_name; ?>" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php } else { ?>
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="bx bx-package"></i>
                        </span>
                      </div>
                    <?php } ?>
                    <div>
                      <strong><?php echo $item->drug_name; ?></strong>
                      <?php if($item->is_featured){ ?>
                        <i class="bx bx-star text-warning ms-1" title="Featured"></i>
                      <?php } ?>
                      <?php if($item->generic_name){ ?>
                        <br><small class="text-muted"><?php echo $item->generic_name; ?></small>
                      <?php } ?>
                      <br><span class="badge bg-label-info"><?php echo $item->category; ?></span>
                    </div>
                  </div>
                </td>
                <td>
                  <small class="text-muted"><?php echo $item->company_name; ?></small>
                </td>
                <td>
                  <strong>₦<?php echo number_format($item->wholesale_price, 2); ?></strong>
                </td>
                <td>
                  <strong class="text-primary">₦<?php echo number_format($item->retail_price, 2); ?></strong>
                </td>
                <td>
                  <span class="badge bg-success">
                    +₦<?php echo number_format($item->retail_price - $item->wholesale_price, 2); ?>
                    (<?php echo number_format($item->markup_percentage, 1); ?>%)
                  </span>
                </td>
                <td>
                  <span class="badge bg-label-secondary"><?php echo $item->stock_quantity; ?> <?php echo $item->unit; ?></span>
                  <br><small class="text-muted">Supplier: <?php echo $item->supplier_stock; ?></small>
                </td>
                <td>
                  <?php if($item->is_active){ ?>
                    <span class="badge bg-success">Active</span>
                  <?php } else { ?>
                    <span class="badge bg-secondary">Inactive</span>
                  <?php } ?>
                </td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                      Actions
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="editProduct(<?php echo $item->id; ?>, <?php echo $item->retail_price; ?>, <?php echo $item->stock_quantity; ?>, <?php echo $item->is_featured ? 1 : 0; ?>, <?php echo $item->is_active ? 1 : 0; ?>)">
                        <i class="bx bx-edit me-1"></i> Edit Pricing
                      </a></li>
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="toggleFeatured(<?php echo $item->id; ?>, <?php echo $item->is_featured ? 0 : 1; ?>)">
                        <i class="bx bx-star me-1"></i> <?php echo $item->is_featured ? 'Unfeature' : 'Feature'; ?>
                      </a></li>
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="toggleActive(<?php echo $item->id; ?>, <?php echo $item->is_active ? 0 : 1; ?>)">
                        <i class="bx bx-<?php echo $item->is_active ? 'hide' : 'show'; ?> me-1"></i> <?php echo $item->is_active ? 'Deactivate' : 'Activate'; ?>
                      </a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="removeProduct(<?php echo $item->id; ?>)">
                        <i class="bx bx-trash me-1"></i> Remove from Storefront
                      </a></li>
                    </ul>
                  </div>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" id="editProductForm">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="action" value="update_storefront_product">
        <input type="hidden" name="inventory_id" id="edit_inventory_id">
        
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Retail Price (₦)</label>
            <input type="number" class="form-control" name="retail_price" id="edit_retail_price" step="0.01" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Stock Quantity</label>
            <input type="number" class="form-control" name="stock_quantity" id="edit_stock_quantity" min="0">
          </div>
          
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_featured" id="edit_is_featured">
            <label class="form-check-label" for="edit_is_featured">
              Feature this product
            </label>
          </div>
          
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active">
            <label class="form-check-label" for="edit_is_active">
              Active (visible to patients)
            </label>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function editProduct(id, retailPrice, stock, featured, active) {
  document.getElementById('edit_inventory_id').value = id;
  document.getElementById('edit_retail_price').value = retailPrice;
  document.getElementById('edit_stock_quantity').value = stock;
  document.getElementById('edit_is_featured').checked = featured == 1;
  document.getElementById('edit_is_active').checked = active == 1;
  
  var modal = new bootstrap.Modal(document.getElementById('editProductModal'));
  modal.show();
}

function toggleFeatured(id, value) {
  var form = document.createElement('form');
  form.method = 'POST';
  form.innerHTML = '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">' +
                   '<input type="hidden" name="action" value="toggle_featured">' +
                   '<input type="hidden" name="inventory_id" value="' + id + '">' +
                   '<input type="hidden" name="is_featured" value="' + value + '">';
  document.body.appendChild(form);
  form.submit();
}

function toggleActive(id, value) {
  var form = document.createElement('form');
  form.method = 'POST';
  form.innerHTML = '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">' +
                   '<input type="hidden" name="action" value="toggle_active">' +
                   '<input type="hidden" name="inventory_id" value="' + id + '">' +
                   '<input type="hidden" name="is_active" value="' + value + '">';
  document.body.appendChild(form);
  form.submit();
}

function removeProduct(id) {
  if(confirm('Are you sure you want to remove this product from your storefront?')) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">' +
                     '<input type="hidden" name="action" value="remove_from_storefront">' +
                     '<input type="hidden" name="inventory_id" value="' + id + '">';
    document.body.appendChild(form);
    form.submit();
  }
}
</script>

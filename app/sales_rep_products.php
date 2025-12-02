<div class="row">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h5 class="mb-1">My Products</h5>
        <p class="text-muted mb-0">Manage your product catalog</p>
      </div>
      <a href="?pg=upload" class="btn btn-primary">
        <i class="bx bx-upload me-1"></i> Upload New Product
      </a>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="row mb-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" id="searchProduct" placeholder="Search products...">
          </div>
          <div class="col-md-2">
            <select class="form-select form-select-sm" id="filterCategory">
              <option value="">All Categories</option>
              <option value="Analgesics">Analgesics</option>
              <option value="Antibiotics">Antibiotics</option>
              <option value="Antimalarials">Antimalarials</option>
              <option value="Antihypertensives">Antihypertensives</option>
              <option value="Antidiabetics">Antidiabetics</option>
              <option value="Vitamins">Vitamins</option>
            </select>
          </div>
          <div class="col-md-2">
            <select class="form-select form-select-sm" id="filterStatus">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="out_of_stock">Out of Stock</option>
            </select>
          </div>
          <div class="col-md-2">
            <select class="form-select form-select-sm" id="filterStock">
              <option value="">All Stock Levels</option>
              <option value="low">Low Stock</option>
              <option value="in_stock">In Stock</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Products List -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <?php if(empty($all_products)){ ?>
          <div class="text-center py-5">
            <i class="bx bx-package bx-lg text-muted mb-3"></i>
            <h5 class="text-muted">No Products Yet</h5>
            <p class="text-muted mb-3">Start by uploading your first product to the marketplace</p>
            <a href="?pg=upload" class="btn btn-primary">
              <i class="bx bx-upload me-1"></i> Upload Product
            </a>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Wholesale Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($all_products as $product){ ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <?php if($product->photo){ ?>
                      <img src="/assets/products/<?php echo $product->photo; ?>" alt="<?php echo $product->drug_name; ?>" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php } else { ?>
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="bx bx-package"></i>
                        </span>
                      </div>
                    <?php } ?>
                    <div>
                      <strong><?php echo $product->drug_name; ?></strong>
                      <?php if($product->generic_name){ ?>
                        <br><small class="text-muted"><?php echo $product->generic_name; ?></small>
                      <?php } ?>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-label-info"><?php echo $product->category ?? 'Uncategorized'; ?></span>
                </td>
                <td>
                  <strong>₦<?php echo number_format($product->wholesale_price, 2); ?></strong>
                  <?php if($product->suggested_retail_price){ ?>
                    <br><small class="text-muted">RRP: ₦<?php echo number_format($product->suggested_retail_price, 2); ?></small>
                  <?php } ?>
                </td>
                <td>
                  <?php 
                    $stock_class = 'success';
                    if($product->stock_quantity == 0) {
                      $stock_class = 'danger';
                    } elseif($product->stock_quantity <= $product->reorder_level) {
                      $stock_class = 'warning';
                    }
                  ?>
                  <span class="badge bg-<?php echo $stock_class; ?>">
                    <?php echo $product->stock_quantity; ?> <?php echo $product->unit; ?>
                  </span>
                  <?php if($product->stock_quantity <= $product->reorder_level && $product->stock_quantity > 0){ ?>
                    <br><small class="text-warning"><i class="bx bx-error-circle"></i> Low Stock</small>
                  <?php } ?>
                </td>
                <td>
                  <?php 
                    $status_class = 'success';
                    $status_text = 'Active';
                    if($product->status == 'inactive') {
                      $status_class = 'secondary';
                      $status_text = 'Inactive';
                    } elseif($product->status == 'out_of_stock') {
                      $status_class = 'danger';
                      $status_text = 'Out of Stock';
                    }
                  ?>
                  <span class="badge bg-<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                </td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                      Actions
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="editProduct(<?php echo $product->id; ?>)">
                        <i class="bx bx-edit me-1"></i> Edit
                      </a></li>
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="viewProduct(<?php echo $product->id; ?>)">
                        <i class="bx bx-show me-1"></i> View Details
                      </a></li>
                      <?php if($product->status == 'active'){ ?>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deactivateProduct(<?php echo $product->id; ?>)">
                        <i class="bx bx-x-circle me-1"></i> Deactivate
                      </a></li>
                      <?php } ?>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" enctype="multipart/form-data" id="editProductForm">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="action" value="update_drug">
        <input type="hidden" name="drug_id" id="edit_drug_id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Drug Name</label>
              <input type="text" class="form-control" name="drug_name" id="edit_drug_name" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Generic Name</label>
              <input type="text" class="form-control" name="generic_name" id="edit_generic_name">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Category</label>
              <select class="form-select" name="category" id="edit_category" required>
                <option value="Analgesics">Analgesics</option>
                <option value="Antibiotics">Antibiotics</option>
                <option value="Antimalarials">Antimalarials</option>
                <option value="Antihypertensives">Antihypertensives</option>
                <option value="Antidiabetics">Antidiabetics</option>
                <option value="Vitamins">Vitamins</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Unit</label>
              <select class="form-select" name="unit" id="edit_unit" required>
                <option value="tablets">Tablets</option>
                <option value="capsules">Capsules</option>
                <option value="bottles">Bottles</option>
                <option value="boxes">Boxes</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Wholesale Price (₦)</label>
              <input type="number" class="form-control" name="wholesale_price" id="edit_wholesale_price" step="0.01" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Suggested Retail Price (₦)</label>
              <input type="number" class="form-control" name="suggested_retail_price" id="edit_suggested_retail_price" step="0.01">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Stock Quantity</label>
              <input type="number" class="form-control" name="stock_quantity" id="edit_stock_quantity" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Reorder Level</label>
              <input type="number" class="form-control" name="reorder_level" id="edit_reorder_level">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Update Photo</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
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
function editProduct(id) {
  // In a real implementation, fetch product data via AJAX
  // For now, we'll just show the modal
  var modal = new bootstrap.Modal(document.getElementById('editProductModal'));
  document.getElementById('edit_drug_id').value = id;
  modal.show();
}

function viewProduct(id) {
  alert('View product details for ID: ' + id);
}

function deactivateProduct(id) {
  if(confirm('Are you sure you want to deactivate this product?')) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">' +
                     '<input type="hidden" name="action" value="delete_drug">' +
                     '<input type="hidden" name="drug_id" value="' + id + '">';
    document.body.appendChild(form);
    form.submit();
  }
}
</script>

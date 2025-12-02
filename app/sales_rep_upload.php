<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Upload New Product</h5>
    <p class="text-muted mb-4">Add a new drug to the marketplace for doctors to discover.</p>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">Product Information</h6>
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="action" value="upload_drug">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="drug_name" class="form-label">Drug Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="drug_name" name="drug_name" placeholder="e.g., Paracetamol" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="generic_name" class="form-label">Generic Name</label>
              <input type="text" class="form-control" id="generic_name" name="generic_name" placeholder="e.g., Acetaminophen">
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
              <select class="form-select" id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="Analgesics">Analgesics (Pain Relief)</option>
                <option value="Antibiotics">Antibiotics</option>
                <option value="Antifungals">Antifungals</option>
                <option value="Antivirals">Antivirals</option>
                <option value="Antimalarials">Antimalarials</option>
                <option value="Antihypertensives">Antihypertensives (Blood Pressure)</option>
                <option value="Antidiabetics">Antidiabetics (Diabetes)</option>
                <option value="Antihistamines">Antihistamines (Allergies)</option>
                <option value="Antacids">Antacids (Stomach)</option>
                <option value="Vitamins">Vitamins & Supplements</option>
                <option value="Cardiovascular">Cardiovascular</option>
                <option value="Respiratory">Respiratory</option>
                <option value="Dermatology">Dermatology (Skin)</option>
                <option value="Gastrointestinal">Gastrointestinal</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
              <select class="form-select" id="unit" name="unit" required>
                <option value="tablets">Tablets</option>
                <option value="capsules">Capsules</option>
                <option value="bottles">Bottles</option>
                <option value="boxes">Boxes</option>
                <option value="vials">Vials</option>
                <option value="sachets">Sachets</option>
                <option value="tubes">Tubes</option>
                <option value="packs">Packs</option>
              </select>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the drug, its uses, and benefits"></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="wholesale_price" class="form-label">Wholesale Price (₦) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="wholesale_price" name="wholesale_price" step="0.01" placeholder="0.00" required>
              <small class="text-muted">Price you sell to doctors</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="suggested_retail_price" class="form-label">Suggested Retail Price (₦)</label>
              <input type="number" class="form-control" id="suggested_retail_price" name="suggested_retail_price" step="0.01" placeholder="0.00">
              <small class="text-muted">Recommended price for patients</small>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" placeholder="0" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="reorder_level" class="form-label">Reorder Level</label>
              <input type="number" class="form-control" id="reorder_level" name="reorder_level" value="10" placeholder="10">
              <small class="text-muted">Alert when stock falls below this level</small>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="photo" class="form-label">Product Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            <small class="text-muted">Upload a clear image of the product (JPG, PNG, max 2MB)</small>
          </div>
          
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-upload me-1"></i> Upload Product
            </button>
            <a href="?pg=products" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card bg-light">
      <div class="card-body">
        <h6 class="mb-3"><i class="bx bx-info-circle me-1"></i> Upload Guidelines</h6>
        <ul class="list-unstyled mb-0">
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Accurate Information:</strong> Ensure drug names and details are correct
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Competitive Pricing:</strong> Set wholesale prices that attract doctors
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Quality Images:</strong> Use clear product photos for better visibility
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Stock Management:</strong> Keep stock quantities updated
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Geo-Location:</strong> Products are only visible to doctors in <?php echo $user[0]->state; ?>
          </li>
        </ul>
      </div>
    </div>
    
    <div class="card mt-3">
      <div class="card-body">
        <h6 class="mb-3"><i class="bx bx-map me-1"></i> Your Coverage Area</h6>
        <p class="mb-2"><strong>State:</strong> <?php echo $user[0]->state; ?></p>
        <p class="mb-2"><strong>City:</strong> <?php echo $user[0]->city; ?></p>
        <small class="text-muted">Products will only be visible to doctors in your state</small>
      </div>
    </div>
  </div>
</div>

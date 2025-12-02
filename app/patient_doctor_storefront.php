<?php
// Get patient ID from user array passed by controller
$uid = $user[0]->id;

// Get doctor ref code from URL
$doctor_ref = isset($_GET['doctor']) ? $_GET['doctor'] : '';

if(!$doctor_ref){
    echo '<div class="alert alert-danger">Invalid storefront link</div>';
    return;
}

// Get doctor details
$doctor = DB::select('select * from users where ref_code="'.$doctor_ref.'" and doctor=1');
if(empty($doctor)){
    echo '<div class="alert alert-danger">Storefront not found</div>';
    return;
}

$doctor_id = $doctor[0]->id;

// Get storefront settings
$settings = DB::select('select * from doctor_storefront_settings where doctor_id='.$doctor_id.' and is_active=1');
if(empty($settings)){
    echo '<div class="alert alert-warning">This storefront is currently inactive</div>';
    return;
}

// Get storefront products
$products = DB::select('
    SELECT dsi.*, md.drug_name, md.generic_name, md.category, md.unit, md.photo, md.description,
        u.company_name
    FROM doctor_storefront_inventory dsi
    INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id
    INNER JOIN users u ON md.sales_rep_id = u.id
    WHERE dsi.doctor_id = '.$doctor_id.' AND dsi.is_active = 1
    ORDER BY dsi.is_featured DESC, dsi.created_at DESC
');

// Get cart count for this doctor
$cart_count = DB::select('select count(*) as count from storefront_cart where patient_id='.$uid.' and doctor_id='.$doctor_id);
$cart_items = $cart_count[0]->count ?? 0;

$storefront_name = $settings[0]->storefront_name;
$primary_color = $settings[0]->primary_color;
$logo = $settings[0]->storefront_logo;
$banner = $settings[0]->storefront_banner;
$description = $settings[0]->description;
?>

<style>
:root {
    --storefront-primary: <?php echo $primary_color; ?>;
}
.btn-storefront {
    background: var(--storefront-primary);
    border-color: var(--storefront-primary);
    color: white;
}
.btn-storefront:hover {
    background: var(--storefront-primary);
    opacity: 0.9;
    color: white;
}
.badge-storefront {
    background: var(--storefront-primary);
}
</style>

<!-- Storefront Header -->
<div class="row mb-4">
  <div class="col-12">
    <?php if($banner){ ?>
      <div class="card" style="background: url('/assets/storefronts/<?php echo $banner; ?>') center/cover; height: 200px; position: relative;">
        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.7)); padding: 20px;">
          <div class="d-flex align-items-center">
            <?php if($logo){ ?>
              <img src="/assets/storefronts/<?php echo $logo; ?>" alt="<?php echo $storefront_name; ?>" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white;">
            <?php } ?>
            <div class="ms-3 text-white">
              <h4 class="mb-0 text-white"><?php echo $storefront_name; ?></h4>
              <p class="mb-0">Dr. <?php echo $doctor[0]->first_name.' '.$doctor[0]->last_name; ?></p>
            </div>
          </div>
        </div>
      </div>
    <?php } else { ?>
      <div class="card" style="background: linear-gradient(135deg, <?php echo $primary_color; ?>, <?php echo $primary_color; ?>dd);">
        <div class="card-body text-white">
          <div class="d-flex align-items-center">
            <?php if($logo){ ?>
              <img src="/assets/storefronts/<?php echo $logo; ?>" alt="<?php echo $storefront_name; ?>" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white;">
            <?php } else { ?>
              <div class="avatar avatar-xl me-3" style="border: 3px solid white;">
                <span class="avatar-initial rounded-circle" style="background: white; color: <?php echo $primary_color; ?>;">
                  <?php echo strtoupper(substr($doctor[0]->first_name, 0, 1)); ?>
                </span>
              </div>
            <?php } ?>
            <div>
              <h4 class="mb-1 text-white"><?php echo $storefront_name; ?></h4>
              <p class="mb-0">Dr. <?php echo $doctor[0]->first_name.' '.$doctor[0]->last_name; ?></p>
              <?php if($doctor[0]->specialization){ ?>
                <span class="badge bg-white text-dark mt-1"><?php echo $doctor[0]->specialization; ?></span>
              <?php } ?>
            </div>
            <div class="ms-auto">
              <a href="?pg=storefront-cart&doctor=<?php echo $doctor_ref; ?>" class="btn btn-light">
                <i class="bx bx-cart me-1"></i> Cart (<?php echo $cart_items; ?>)
              </a>
            </div>
          </div>
          <?php if($description){ ?>
            <p class="mt-3 mb-0 text-white"><?php echo $description; ?></p>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<!-- Filters & Search -->
<div class="row mb-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <input type="text" class="form-control form-control-sm" placeholder="Search products...">
          </div>
          <div class="col-md-3">
            <select class="form-select form-select-sm">
              <option value="">All Categories</option>
              <option value="Analgesics">Analgesics</option>
              <option value="Antibiotics">Antibiotics</option>
              <option value="Antimalarials">Antimalarials</option>
              <option value="Vitamins">Vitamins</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select form-select-sm">
              <option value="">Sort By</option>
              <option value="price_low">Price: Low to High</option>
              <option value="price_high">Price: High to Low</option>
              <option value="name">Name: A-Z</option>
              <option value="featured">Featured First</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Products Grid -->
<div class="row">
  <?php if(empty($products)){ ?>
    <div class="col-12">
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="bx bx-package bx-lg text-muted mb-3"></i>
          <h5 class="text-muted">No Products Available</h5>
          <p class="text-muted">This storefront doesn't have any products yet.</p>
          <a href="?pg=storefronts" class="btn btn-primary">Browse Other Storefronts</a>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <?php foreach($products as $product){ ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card h-100">
        <?php if($product->is_featured){ ?>
          <div class="position-absolute top-0 start-0 m-2">
            <span class="badge bg-warning"><i class="bx bx-star"></i> Featured</span>
          </div>
        <?php } ?>
        
        <?php if($product->photo){ ?>
          <img src="/assets/products/<?php echo $product->photo; ?>" class="card-img-top" alt="<?php echo $product->drug_name; ?>" style="height: 180px; object-fit: cover;">
        <?php } else { ?>
          <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
            <i class="bx bx-package bx-lg text-muted"></i>
          </div>
        <?php } ?>
        
        <div class="card-body d-flex flex-column">
          <h6 class="card-title mb-1"><?php echo $product->drug_name; ?></h6>
          <?php if($product->generic_name){ ?>
            <small class="text-muted mb-2"><?php echo $product->generic_name; ?></small>
          <?php } ?>
          
          <div class="mb-2">
            <span class="badge bg-label-info"><?php echo $product->category; ?></span>
          </div>
          
          <?php if($product->description){ ?>
          <p class="card-text small text-muted mb-2" style="font-size: 0.8rem; line-height: 1.4;">
            <?php echo substr($product->description, 0, 60); ?><?php echo strlen($product->description) > 60 ? '...' : ''; ?>
          </p>
          <?php } ?>
          
          <div class="mb-2">
            <small class="text-muted d-block">Stock: <?php echo $product->stock_quantity; ?> <?php echo $product->unit; ?></small>
            <small class="text-muted d-block">Supplier: <?php echo $product->company_name; ?></small>
          </div>
          
          <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h5 class="mb-0" style="color: <?php echo $primary_color; ?>;">₦<?php echo number_format($product->retail_price, 2); ?></h5>
            </div>
            
            <button class="btn btn-storefront btn-sm w-100" onclick="addToCart(<?php echo $product->id; ?>, '<?php echo addslashes($product->drug_name); ?>', <?php echo $product->retail_price; ?>)">
              <i class="bx bx-cart me-1"></i> Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  <?php } ?>
</div>

<!-- Back Button -->
<div class="row mt-4">
  <div class="col-12">
    <a href="?pg=storefronts" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-1"></i> Back to Storefronts
    </a>
  </div>
</div>

<script>
function addToCart(inventoryId, drugName, price) {
  // AJAX call to add to cart
  fetch('/dashboard', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
    },
    body: new URLSearchParams({
      'action': 'add_to_storefront_cart',
      'doctor_inventory_id': inventoryId,
      'doctor_id': <?php echo $doctor_id; ?>,
      'quantity': 1
    })
  })
  .then(response => response.json())
  .then(data => {
    if(data.success) {
      // Update cart count
      location.reload();
    } else {
      alert(data.message || 'Failed to add to cart');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    // Fallback to form submission
    var form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">' +
                     '<input type="hidden" name="action" value="add_to_storefront_cart">' +
                     '<input type="hidden" name="doctor_inventory_id" value="' + inventoryId + '">' +
                     '<input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">' +
                     '<input type="hidden" name="quantity" value="1">';
    document.body.appendChild(form);
    form.submit();
  });
}
</script>

<?php
// Get patient ID from user array passed by controller
$uid = $user[0]->id;

// Get doctors managing this patient (approved relationship)
$my_doctor_ids = [];
$my_doctors_rel = DB::select('SELECT doctor FROM patients WHERE user = ? AND doctor_approve = 1 AND user_approve = 1 AND doctor IS NOT NULL', [$uid]);
foreach($my_doctors_rel as $rel) {
    $my_doctor_ids[] = $rel->doctor;
}

// Get active doctor storefronts from managing doctors only
$doctor_storefronts = [];
if(!empty($my_doctor_ids)){
    $placeholders = implode(',', array_fill(0, count($my_doctor_ids), '?'));
    $doctor_storefronts = DB::select("
        SELECT u.id, u.first_name, u.last_name, u.ref_code, u.specialization, u.city, u.state,
            dss.storefront_name, dss.storefront_logo, dss.primary_color, dss.description,
            COUNT(DISTINCT dsi.id) as product_count,
            AVG(dsi.retail_price) as avg_price
        FROM users u
        INNER JOIN doctor_storefront_settings dss ON u.id = dss.doctor_id
        INNER JOIN doctor_storefront_inventory dsi ON u.id = dsi.doctor_id
        WHERE u.id IN ($placeholders)
            AND dss.is_active = 1 AND dsi.is_active = 1
        GROUP BY u.id, u.first_name, u.last_name, u.ref_code, u.specialization, u.city, u.state,
            dss.storefront_name, dss.storefront_logo, dss.primary_color, dss.description
        ORDER BY product_count DESC
    ", $my_doctor_ids);
}

$patient_state = $user[0]->state ?? '';
?>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Doctor Storefronts</h5>
    <p class="text-muted mb-4">Browse virtual pharmacies from your managing doctors</p>
  </div>
</div>

<?php if(empty($my_doctor_ids)){ ?>
<div class="row">
  <div class="col-12">
    <div class="alert alert-info d-flex align-items-center">
      <i class="bx bx-info-circle me-2"></i>
      <div>
        <strong>No Managing Doctors</strong> You don't have any doctors managing your care yet. 
        <a href="?pg=affiliates" class="alert-link">Find and connect with doctors</a>
      </div>
    </div>
  </div>
</div>
<?php } else { ?>

<!-- Stats -->
<div class="row mb-4">
  <div class="col-md-4 mb-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0"><?php echo count($doctor_storefronts); ?></h3>
            <small>Available Storefronts</small>
          </div>
          <i class="bx bx-store-alt bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card bg-success text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0">
              <?php 
                $total_products = 0;
                foreach($doctor_storefronts as $store) {
                  $total_products += $store->product_count;
                }
                echo $total_products;
              ?>
            </h3>
            <small>Total Products</small>
          </div>
          <i class="bx bx-package bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card bg-info text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0"><?php echo count($my_doctor_ids); ?></h3>
            <small>Managing Doctors</small>
          </div>
          <i class="bx bx-user-check bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="row mb-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <input type="text" class="form-control form-control-sm" placeholder="Search doctors...">
          </div>
          <div class="col-md-3">
            <select class="form-select form-select-sm">
              <option value="">All Specializations</option>
              <option value="General">General Practice</option>
              <option value="Cardiology">Cardiology</option>
              <option value="Pediatrics">Pediatrics</option>
              <option value="Dermatology">Dermatology</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select form-select-sm">
              <option value="">Sort By</option>
              <option value="products">Most Products</option>
              <option value="price_low">Lowest Prices</option>
              <option value="price_high">Highest Prices</option>
              <option value="name">Name: A-Z</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Storefronts Grid -->
<div class="row">
  <?php if(empty($doctor_storefronts)){ ?>
    <div class="col-12">
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="bx bx-store-alt bx-lg text-muted mb-3"></i>
          <h5 class="text-muted">No Storefronts Available</h5>
          <p class="text-muted">None of your managing doctors have active storefronts yet.</p>
          <small class="text-muted">Check back soon as your doctors set up their virtual pharmacies.</small>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <?php foreach($doctor_storefronts as $storefront){ ?>
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 hover-shadow" style="border-top: 4px solid <?php echo $storefront->primary_color; ?>;">
        <div class="card-body d-flex flex-column">
          <!-- Doctor Info -->
          <div class="d-flex align-items-center mb-3">
            <?php if($storefront->storefront_logo){ ?>
              <img src="/assets/storefronts/<?php echo $storefront->storefront_logo; ?>" alt="<?php echo $storefront->storefront_name; ?>" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
            <?php } else { ?>
              <div class="avatar avatar-lg me-3">
                <span class="avatar-initial rounded-circle" style="background: <?php echo $storefront->primary_color; ?>;">
                  <?php echo strtoupper(substr($storefront->first_name, 0, 1)); ?>
                </span>
              </div>
            <?php } ?>
            <div>
              <h6 class="mb-0"><?php echo $storefront->storefront_name; ?></h6>
              <small class="text-muted">Dr. <?php echo $storefront->first_name.' '.$storefront->last_name; ?></small>
              <?php if($storefront->specialization){ ?>
                <br><span class="badge bg-label-info"><?php echo $storefront->specialization; ?></span>
              <?php } ?>
            </div>
          </div>
          
          <!-- Description -->
          <?php if($storefront->description){ ?>
          <p class="text-muted small mb-3" style="line-height: 1.5;">
            <?php echo substr($storefront->description, 0, 120); ?><?php echo strlen($storefront->description) > 120 ? '...' : ''; ?>
          </p>
          <?php } ?>
          
          <!-- Stats -->
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-2">
              <small class="text-muted"><i class="bx bx-package me-1"></i> Products</small>
              <strong><?php echo $storefront->product_count; ?></strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <small class="text-muted"><i class="bx bx-money me-1"></i> Avg Price</small>
              <strong>₦<?php echo number_format($storefront->avg_price, 0); ?></strong>
            </div>
            <div class="d-flex justify-content-between">
              <small class="text-muted"><i class="bx bx-map me-1"></i> Location</small>
              <small><?php echo $storefront->city; ?></small>
            </div>
          </div>
          
          <!-- Action Button -->
          <div class="mt-auto">
            <a href="?pg=doctor-storefront&doctor=<?php echo $storefront->ref_code; ?>" class="btn btn-sm w-100" style="background: <?php echo $storefront->primary_color; ?>; color: white;">
              <i class="bx bx-shopping-bag me-1"></i> Visit Storefront
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  <?php } ?>
</div>

<!-- Info Card -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card bg-light">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h6 class="mb-2"><i class="bx bx-info-circle me-1"></i> How It Works</h6>
            <p class="mb-0 small text-muted">
              Browse virtual pharmacies from verified doctors in your area. Each storefront offers authentic medications at competitive prices. 
              Add items to your cart and checkout securely. Your order will be fulfilled by pharmaceutical sales representatives in your state.
            </p>
          </div>
          <div class="col-md-4 text-end">
            <i class="bx bx-store-alt" style="font-size: 4rem; opacity: 0.1;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.hover-shadow {
  transition: all 0.3s ease;
}
.hover-shadow:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
</style>

<?php } ?>

<?php
// Handle both array and object formats
$user_obj = is_array($user) ? $user[0] : $user;
$uid = isset($user_obj->id) ? (int)$user_obj->id : 0;

// Get sales rep ID from query string
$sales_rep_id = isset($_GET['sales_rep']) ? (int)$_GET['sales_rep'] : 0;

if(!$sales_rep_id){
    echo '<div class="alert alert-danger">Invalid storefront</div>';
    return;
}

// Get storefront details
$storefront = DB::select('
    SELECT * FROM users 
    WHERE id = ? AND sales_rep = 1 AND storefront_active = 1
', [$sales_rep_id]);

if(empty($storefront)){
    echo '<div class="alert alert-danger">Storefront not found or inactive</div>';
    return;
}

$storefront = $storefront[0];

// Get products from this storefront
$products = DB::select('
    SELECT * FROM marketplace_drugs 
    WHERE sales_rep_id = ? AND status = "active"
    ORDER BY drug_name ASC
', [$sales_rep_id]);

// Handle add to storefront action
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_storefront'){
    $product_id = (int)$_POST['product_id'];
    $retail_price = (float)$_POST['retail_price'];
    $doctor_price = (float)$_POST['doctor_price'];
    
    // Check if already in storefront
    $existing = DB::select('
        SELECT * FROM doctor_storefront_inventory 
        WHERE doctor_id = ? AND marketplace_drug_id = ?
    ', [$uid, $product_id]);
    
    if(empty($existing)){
        DB::insert('
            INSERT INTO doctor_storefront_inventory 
            (doctor_id, marketplace_drug_id, retail_price, doctor_price, is_featured, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, 0, 1, ?, ?)
        ', [$uid, $product_id, $retail_price, $doctor_price, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        
        echo '<script>window.location.href = "?pg=storefront-products&sales_rep='.$sales_rep_id.'&success=product_added";</script>';
        exit;
    }
}
?>

<div class="container-xxl flex-grow-1 container-p-y">

<!-- Back Button -->
<div class="mb-3">
    <a href="?pg=marketplace" class="btn btn-sm btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to Storefronts
    </a>
</div>

<!-- Storefront Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?> 0%, <?php echo $storefront->storefront_secondary_color ?? '#4a4eb3'; ?> 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center">
                    <!-- Logo -->
                    <div class="me-3">
                        <?php if($storefront->storefront_logo){ ?>
                            <img src="/assets/storefronts/<?php echo $storefront->storefront_logo; ?>" alt="<?php echo $storefront->company_name; ?>" class="rounded-circle border border-3 border-white" style="width: 80px; height: 80px; object-fit: cover; background: white;">
                        <?php } else { ?>
                            <div class="rounded-circle border border-3 border-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: white; color: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>; font-size: 2rem; font-weight: bold;">
                                <?php echo strtoupper(substr($storefront->company_name ?? 'C', 0, 1)); ?>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-grow-1">
                        <h4 class="text-white mb-1"><?php echo $storefront->company_name ?? ($storefront->first_name.' '.$storefront->last_name); ?></h4>
                        <?php if($storefront->storefront_tagline){ ?>
                            <p class="mb-2 opacity-90"><?php echo $storefront->storefront_tagline; ?></p>
                        <?php } ?>
                        <div class="d-flex gap-3">
                            <?php if($storefront->storefront_phone){ ?>
                                <span><i class="bx bx-phone me-1"></i><?php echo $storefront->storefront_phone; ?></span>
                            <?php } ?>
                            <?php if($storefront->storefront_email){ ?>
                                <span><i class="bx bx-envelope me-1"></i><?php echo $storefront->storefront_email; ?></span>
                            <?php } ?>
                            <?php if($storefront->storefront_website){ ?>
                                <span><i class="bx bx-globe me-1"></i><?php echo $storefront->storefront_website; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="text-end">
                        <div class="badge bg-white text-primary fs-6 mb-2"><?php echo count($products); ?> Products</div>
                        <br>
                        <span class="badge bg-white bg-opacity-25"><?php echo $storefront->state; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Message -->
<?php if(isset($_GET['success']) && $_GET['success'] === 'product_added'){ ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-2"></i>
    Product added to your storefront successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php } ?>

<!-- Description -->
<?php if($storefront->storefront_description){ ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-2">About <?php echo $storefront->company_name ?? 'This Company'; ?></h6>
                <p class="text-muted mb-0"><?php echo $storefront->storefront_description; ?></p>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Products Grid -->
<?php if(empty($products)){ ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bx bx-package bx-lg text-muted mb-3"></i>
                    <h5 class="text-muted">No Products Available</h5>
                    <p class="text-muted">This storefront doesn't have any products yet.</p>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row g-4">
        <?php foreach($products as $product){ 
            // Check if already in doctor's storefront
            $in_storefront = DB::select('
                SELECT * FROM doctor_storefront_inventory 
                WHERE doctor_id = ? AND marketplace_drug_id = ?
            ', [$uid, $product->id]);
        ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100 product-card" style="transition: all 0.3s;">
                <!-- Product Image -->
                <div class="position-relative">
                    <?php if($product->photo){ ?>
                        <img src="/assets/marketplace/<?php echo $product->photo; ?>" alt="<?php echo $product->drug_name; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <?php } else { ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>20 0%, <?php echo $storefront->storefront_secondary_color ?? '#4a4eb3'; ?>20 100%);">
                            <i class="bx bx-capsule" style="font-size: 4rem; color: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>; opacity: 0.5;"></i>
                        </div>
                    <?php } ?>
                    
                    <!-- Stock Badge -->
                    <div class="position-absolute top-0 end-0 m-2">
                        <?php if($product->stock_quantity > 0){ ?>
                            <span class="badge bg-success" style="font-size: 0.7rem; max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?php echo $product->stock_quantity; ?> <?php echo $product->unit; ?>
                            </span>
                        <?php } else { ?>
                            <span class="badge bg-danger" style="font-size: 0.7rem;">Out of stock</span>
                        <?php } ?>
                    </div>
                    
                    <!-- Category Badge -->
                    <?php if($product->category){ ?>
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-info" style="font-size: 0.7rem; max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?php echo $product->category; ?>
                        </span>
                    </div>
                    <?php } ?>
                </div>
                
                <div class="card-body">
                    <!-- Product Name -->
                    <h6 class="mb-1"><?php echo $product->drug_name; ?></h6>
                    
                    <!-- Generic Name -->
                    <?php if($product->generic_name){ ?>
                        <p class="text-muted small mb-2"><?php echo $product->generic_name; ?></p>
                    <?php } ?>
                    
                    <!-- Description -->
                    <?php if($product->description){ ?>
                        <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px;">
                            <?php echo $product->description; ?>
                        </p>
                    <?php } else { ?>
                        <div style="min-height: 40px;"></div>
                    <?php } ?>
                    
                    <!-- Price -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Wholesale Price</span>
                            <strong class="fs-5" style="color: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>;">₦<?php echo number_format($product->wholesale_price, 2); ?></strong>
                        </div>
                        <?php if($product->suggested_retail_price){ ?>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted small">Suggested Retail</span>
                            <small class="text-muted">₦<?php echo number_format($product->suggested_retail_price, 2); ?></small>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <!-- Action Button -->
                    <?php if(!empty($in_storefront)){ ?>
                        <button class="btn btn-success w-100" disabled>
                            <i class="bx bx-check me-1"></i> In Your Storefront
                        </button>
                    <?php } else { ?>
                        <button class="btn btn-primary w-100" onclick="addToStorefront(<?php echo $product->id; ?>, '<?php echo addslashes($product->drug_name); ?>', <?php echo $product->wholesale_price; ?>)" style="background: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>; border-color: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>;">
                            <i class="bx bx-plus me-1"></i> Add to My Storefront
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
<?php } ?>

</div>

<!-- Add to Storefront Modal -->
<div class="modal fade" id="addToStorefrontModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-plus-circle me-2"></i>Add to Your Storefront</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_to_storefront">
                <input type="hidden" name="product_id" id="modalProductId">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" id="modalProductName" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Wholesale Price</label>
                        <input type="text" class="form-control" id="modalWholesalePrice" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Your Retail Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="retail_price" id="modalRetailPrice" required placeholder="e.g., 5000">
                        <small class="text-muted">Price for your patients</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Your Doctor Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="doctor_price" id="modalDoctorPrice" required placeholder="e.g., 4500">
                        <small class="text-muted">Discounted price for doctors in your network</small>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Markup Calculation:</strong> Your profit margin will be calculated automatically based on wholesale vs retail price.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>; border-color: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>;">
                        <i class="bx bx-check me-1"></i> Add to Storefront
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>

<script>
function addToStorefront(productId, productName, wholesalePrice) {
    document.getElementById('modalProductId').value = productId;
    document.getElementById('modalProductName').value = productName;
    document.getElementById('modalWholesalePrice').value = '₦' + wholesalePrice.toLocaleString();
    
    // Suggest retail price (20% markup)
    const suggestedRetail = wholesalePrice * 1.2;
    document.getElementById('modalRetailPrice').value = suggestedRetail.toFixed(2);
    
    // Suggest doctor price (10% markup)
    const suggestedDoctor = wholesalePrice * 1.1;
    document.getElementById('modalDoctorPrice').value = suggestedDoctor.toFixed(2);
    
    new bootstrap.Modal(document.getElementById('addToStorefrontModal')).show();
}
</script>

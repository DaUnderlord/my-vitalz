<?php
// Handle both array and object formats
$user_obj = is_array($user) ? $user[0] : $user;
$uid = isset($user_obj->id) ? (int)$user_obj->id : 0;

// Get doctor's state for filtering
$doctor_state = $user_obj->state ?? '';

// Get all sales reps with active storefronts and their product counts
$storefronts = DB::select('
    SELECT 
        u.id as sales_rep_id,
        u.first_name,
        u.last_name,
        u.email,
        u.company_name,
        u.storefront_logo,
        u.storefront_banner,
        u.storefront_primary_color,
        u.storefront_secondary_color,
        u.storefront_description,
        u.storefront_tagline,
        u.storefront_phone,
        u.storefront_email,
        u.storefront_website,
        u.state,
        (SELECT COUNT(*) FROM marketplace_drugs WHERE sales_rep_id = u.id AND status = "active") as product_count,
        (SELECT MIN(wholesale_price) FROM marketplace_drugs WHERE sales_rep_id = u.id AND status = "active") as min_price,
        (SELECT MAX(wholesale_price) FROM marketplace_drugs WHERE sales_rep_id = u.id AND status = "active") as max_price
    FROM users u
    WHERE u.sales_rep = 1 
    AND u.storefront_active = 1
    AND u.state = ?
    HAVING product_count > 0
    ORDER BY u.company_name ASC
', [$doctor_state]);

?>

<div class="container-xxl flex-grow-1 container-p-y">

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #5a5fc7 0%, #4a4eb3 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white mb-1"><i class="bx bx-store me-2"></i>Marketplace - Company Storefronts</h4>
                        <p class="mb-0 opacity-75">Browse premium pharmaceutical suppliers and add products to your virtual pharmacy</p>
                    </div>
                    <div>
                        <span class="badge bg-white text-primary fs-6"><?php echo count($storefronts); ?> Companies</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Alert -->
<div class="alert alert-info mb-4">
    <i class="bx bx-info-circle me-2"></i>
    <strong>How it works:</strong> Browse company storefronts below, click to view their product catalog, and add products to your virtual pharmacy with your custom pricing.
</div>

<!-- Storefronts Grid -->
<?php if(empty($storefronts)){ ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bx bx-store-alt bx-lg text-muted mb-3"></i>
                    <h5 class="text-muted">No Storefronts Available</h5>
                    <p class="text-muted">No pharmaceutical suppliers are currently available in your state (<?php echo $doctor_state; ?>).</p>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row g-4">
        <?php foreach($storefronts as $storefront){ ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 storefront-card" style="cursor: pointer; transition: all 0.3s;" onclick="window.location.href='?pg=storefront-products&sales_rep=<?php echo $storefront->sales_rep_id; ?>'">
                <!-- Banner -->
                <?php if($storefront->storefront_banner){ ?>
                    <div class="card-img-top" style="height: 150px; background: url('/assets/storefronts/<?php echo $storefront->storefront_banner; ?>') center/cover; position: relative;">
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.3));"></div>
                    </div>
                <?php } else { ?>
                    <div class="card-img-top" style="height: 150px; background: linear-gradient(135deg, <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?> 0%, <?php echo $storefront->storefront_secondary_color ?? '#4a4eb3'; ?> 100%);"></div>
                <?php } ?>
                
                <!-- Logo -->
                <div class="text-center" style="margin-top: -40px; position: relative; z-index: 1;">
                    <?php if($storefront->storefront_logo){ ?>
                        <img src="/assets/storefronts/<?php echo $storefront->storefront_logo; ?>" alt="<?php echo $storefront->company_name; ?>" class="rounded-circle border border-4 border-white" style="width: 80px; height: 80px; object-fit: cover; background: white;">
                    <?php } else { ?>
                        <div class="rounded-circle border border-4 border-white d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>; color: white; font-size: 2rem; font-weight: bold;">
                            <?php echo strtoupper(substr($storefront->company_name ?? 'C', 0, 1)); ?>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="card-body text-center pt-2">
                    <!-- Company Name -->
                    <h5 class="mb-1"><?php echo $storefront->company_name ?? ($storefront->first_name.' '.$storefront->last_name); ?></h5>
                    
                    <!-- Tagline -->
                    <?php if($storefront->storefront_tagline){ ?>
                        <p class="text-muted small mb-3"><?php echo $storefront->storefront_tagline; ?></p>
                    <?php } ?>
                    
                    <!-- Description -->
                    <?php if($storefront->storefront_description){ ?>
                        <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            <?php echo $storefront->storefront_description; ?>
                        </p>
                    <?php } ?>
                    
                    <!-- Stats -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="p-2 rounded" style="background: rgba(<?php echo hexdec(substr($storefront->storefront_primary_color ?? '#5a5fc7', 1, 2)); ?>, <?php echo hexdec(substr($storefront->storefront_primary_color ?? '#5a5fc7', 3, 2)); ?>, <?php echo hexdec(substr($storefront->storefront_primary_color ?? '#5a5fc7', 5, 2)); ?>, 0.1);">
                                <div class="fw-bold" style="color: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>;"><?php echo $storefront->product_count; ?></div>
                                <small class="text-muted">Products</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded" style="background: rgba(<?php echo hexdec(substr($storefront->storefront_secondary_color ?? '#4a4eb3', 1, 2)); ?>, <?php echo hexdec(substr($storefront->storefront_secondary_color ?? '#4a4eb3', 3, 2)); ?>, <?php echo hexdec(substr($storefront->storefront_secondary_color ?? '#4a4eb3', 5, 2)); ?>, 0.1);">
                                <div class="fw-bold" style="color: <?php echo $storefront->storefront_secondary_color ?? '#4a4eb3'; ?>;">₦<?php echo number_format($storefront->min_price); ?>+</div>
                                <small class="text-muted">From</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <?php if($storefront->storefront_phone || $storefront->storefront_email){ ?>
                        <div class="mb-3">
                            <?php if($storefront->storefront_phone){ ?>
                                <div class="mb-2">
                                    <span class="badge bg-label-primary" style="font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: inline-block;">
                                        <i class="bx bx-phone me-1"></i><?php echo $storefront->storefront_phone; ?>
                                    </span>
                                </div>
                            <?php } ?>
                            <?php if($storefront->storefront_email){ ?>
                                <div>
                                    <span class="badge bg-label-info" style="font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: inline-block;">
                                        <i class="bx bx-envelope me-1"></i><?php echo $storefront->storefront_email; ?>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    
                    <!-- View Button -->
                    <button class="btn btn-primary w-100" style="background: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>; border-color: <?php echo $storefront->storefront_primary_color ?? '#5a5fc7'; ?>;">
                        <i class="bx bx-shopping-bag me-1"></i> Browse Products
                    </button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
<?php } ?>

</div>

<style>
.storefront-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>

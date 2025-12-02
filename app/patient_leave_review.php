<?php
// Get order ID from URL
$order_id = isset($_GET['order']) ? $this->sanitizeInput($_GET['order']) : '';

if(!$order_id){
    echo '<div class="alert alert-danger">Invalid review link</div>';
    return;
}

// Get order details
$order = DB::select('select * from storefront_orders where id='.$order_id.' and patient_id='.$uid);
if(empty($order)){
    echo '<div class="alert alert-danger">Order not found</div>';
    return;
}

// Check if order is delivered
if($order[0]->order_status != 'delivered'){
    echo '<div class="alert alert-warning">You can only review delivered orders</div>';
    return;
}

// Get order items
$order_items = DB::select('
    SELECT soi.*, md.photo, md.category
    FROM storefront_order_items soi
    INNER JOIN doctor_storefront_inventory dsi ON soi.doctor_inventory_id = dsi.id
    INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id
    WHERE soi.order_id = '.$order_id.'
');

// Get doctor details
$doctor = DB::select('select * from users where id='.$order[0]->doctor_id);
$doctor_name = $doctor[0]->first_name.' '.$doctor[0]->last_name;

// Get existing reviews for this order
$existing_reviews = DB::select('select * from product_reviews where order_id='.$order_id.' and patient_id='.$uid);
?>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Leave Product Review</h5>
    <p class="text-muted mb-4">Order #<?php echo $order[0]->order_number; ?> from Dr. <?php echo $doctor_name; ?></p>
  </div>
</div>

<?php if(!empty($existing_reviews)){ ?>
<div class="row mb-3">
  <div class="col-12">
    <div class="alert alert-success">
      <i class="bx bx-check-circle me-2"></i>
      <strong>Thank you!</strong> You have already reviewed this order.
    </div>
  </div>
</div>
<?php } ?>

<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">Rate Your Products</h6>
      </div>
      <div class="card-body">
        <form method="POST">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="action" value="submit_product_reviews">
          <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
          
          <?php foreach($order_items as $index => $item){ ?>
          <div class="mb-4 pb-4 <?php echo $index < count($order_items) - 1 ? 'border-bottom' : ''; ?>">
            <div class="d-flex align-items-start mb-3">
              <?php if($item->photo){ ?>
                <img src="/assets/products/<?php echo $item->photo; ?>" alt="<?php echo $item->drug_name; ?>" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
              <?php } else { ?>
                <div class="avatar avatar-lg me-3">
                  <span class="avatar-initial rounded bg-label-primary">
                    <i class="bx bx-package bx-sm"></i>
                  </span>
                </div>
              <?php } ?>
              <div>
                <h6 class="mb-1"><?php echo $item->drug_name; ?></h6>
                <span class="badge bg-label-info"><?php echo $item->category; ?></span>
                <p class="mb-0 small text-muted mt-1">Quantity: <?php echo $item->quantity; ?></p>
              </div>
            </div>
            
            <input type="hidden" name="reviews[<?php echo $index; ?>][doctor_inventory_id]" value="<?php echo $item->doctor_inventory_id; ?>">
            
            <div class="mb-3">
              <label class="form-label">Rating <span class="text-danger">*</span></label>
              <div class="rating-stars" data-index="<?php echo $index; ?>">
                <?php for($i = 5; $i >= 1; $i--){ ?>
                  <input type="radio" name="reviews[<?php echo $index; ?>][rating]" id="star<?php echo $index; ?>_<?php echo $i; ?>" value="<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                  <label for="star<?php echo $index; ?>_<?php echo $i; ?>" class="star">
                    <i class="bx bxs-star"></i>
                  </label>
                <?php } ?>
              </div>
              <small class="text-muted">Click to rate (5 stars = Excellent)</small>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Your Review (Optional)</label>
              <textarea class="form-control" name="reviews[<?php echo $index; ?>][review_text]" rows="3" placeholder="Share your experience with this product..."></textarea>
            </div>
          </div>
          <?php } ?>
          
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" <?php echo !empty($existing_reviews) ? 'disabled' : ''; ?>>
              <i class="bx bx-send me-1"></i> Submit Reviews
            </button>
            <a href="?pg=storefront-orders" class="btn btn-outline-secondary">
              <i class="bx bx-arrow-back me-1"></i> Back to Orders
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card bg-light">
      <div class="card-body">
        <h6 class="mb-3"><i class="bx bx-info-circle me-1"></i> Review Guidelines</h6>
        <ul class="list-unstyled mb-0 small">
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Be Honest:</strong> Share your genuine experience
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Be Specific:</strong> Mention product quality, effectiveness
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Be Respectful:</strong> Keep reviews professional
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            <strong>Be Helpful:</strong> Help other patients make informed decisions
          </li>
        </ul>
      </div>
    </div>
    
    <div class="card mt-3">
      <div class="card-body">
        <h6 class="mb-2"><i class="bx bx-shield-check me-1"></i> Privacy</h6>
        <p class="mb-0 small text-muted">
          Your reviews will be displayed publicly to help other patients. Your name will be shown as "<?php echo $user[0]->first_name; ?> <?php echo substr($user[0]->last_name, 0, 1); ?>."
        </p>
      </div>
    </div>
  </div>
</div>

<style>
.rating-stars {
  display: inline-flex;
  flex-direction: row-reverse;
  gap: 5px;
}

.rating-stars input[type="radio"] {
  display: none;
}

.rating-stars label.star {
  cursor: pointer;
  font-size: 2rem;
  color: #ddd;
  transition: color 0.2s;
}

.rating-stars input[type="radio"]:checked ~ label.star,
.rating-stars label.star:hover,
.rating-stars label.star:hover ~ label.star {
  color: #ffc107;
}
</style>

<?php
// Get patient ID from user array passed by controller
$uid = $user[0]->id;

// Get doctor ref code from URL
$doctor_ref = isset($_GET['doctor']) ? $_GET['doctor'] : '';

if(!$doctor_ref){
    echo '<div class="alert alert-danger">Invalid checkout link</div>';
    return;
}

// Get doctor details
$doctor = DB::select('select * from users where ref_code="'.$doctor_ref.'" and doctor=1');
if(empty($doctor)){
    echo '<div class="alert alert-danger">Doctor not found</div>';
    return;
}

$doctor_id = $doctor[0]->id;

// Get storefront settings
$settings = DB::select('select * from doctor_storefront_settings where doctor_id='.$doctor_id);
$storefront_name = !empty($settings) ? $settings[0]->storefront_name : $doctor[0]->first_name.' '.$doctor[0]->last_name.' Pharmacy';
$primary_color = !empty($settings) ? $settings[0]->primary_color : '#696cff';

// Get cart items
$cart_items = DB::select('
    SELECT sc.*, dsi.retail_price, dsi.wholesale_price, md.drug_name, md.photo, md.unit,
        u.id as sales_rep_id, u.state as sales_rep_state
    FROM storefront_cart sc
    INNER JOIN doctor_storefront_inventory dsi ON sc.doctor_inventory_id = dsi.id
    INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id
    INNER JOIN users u ON md.sales_rep_id = u.id
    WHERE sc.patient_id = '.$uid.' AND sc.doctor_id = '.$doctor_id.'
');

if(empty($cart_items)){
    echo '<div class="alert alert-warning">Your cart is empty. <a href="?pg=doctor-storefront&doctor='.$doctor_ref.'">Browse products</a></div>';
    return;
}

// Calculate totals
$subtotal = 0;
$doctor_commission = 0;
$sales_rep_amount = 0;
$sales_rep_id = $cart_items[0]->sales_rep_id;

foreach($cart_items as $item){
    $item_total = $item->retail_price * $item->quantity;
    $item_wholesale = $item->wholesale_price * $item->quantity;
    
    $subtotal += $item_total;
    $doctor_commission += ($item_total - $item_wholesale);
    $sales_rep_amount += $item_wholesale;
}

$delivery_fee = 0;
$total = $subtotal + $delivery_fee;

// Get patient info
$patient_state = $user[0]->state ?? '';
$patient_city = $user[0]->city ?? '';
$patient_phone = $user[0]->phone ?? '';
$patient_address = $user[0]->address ?? '';

// Geo-lock validation
$sales_rep_state = $cart_items[0]->sales_rep_state;
$geo_lock_valid = ($patient_state == $doctor[0]->state && $patient_state == $sales_rep_state);
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
</style>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Checkout</h5>
    <p class="text-muted mb-4"><?php echo $storefront_name; ?></p>
  </div>
</div>

<?php if(!$geo_lock_valid){ ?>
<div class="row">
  <div class="col-12">
    <div class="alert alert-danger">
      <i class="bx bx-error-circle me-2"></i>
      <strong>Delivery Restriction!</strong> Orders can only be placed within the same state. 
      Your state (<?php echo $patient_state; ?>) doesn't match the doctor's state (<?php echo $doctor[0]->state; ?>) or sales rep's coverage area.
    </div>
  </div>
</div>
<?php } ?>

<form method="POST" id="checkoutForm">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="action" value="place_storefront_order">
  <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
  <input type="hidden" name="sales_rep_id" value="<?php echo $sales_rep_id; ?>">
  <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
  <input type="hidden" name="doctor_commission" value="<?php echo $doctor_commission; ?>">
  <input type="hidden" name="sales_rep_amount" value="<?php echo $sales_rep_amount; ?>">
  
  <div class="row">
    <!-- Delivery Information -->
    <div class="col-lg-8 mb-4">
      <div class="card mb-4">
        <div class="card-header">
          <h6 class="mb-0">Delivery Information</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Full Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="delivery_name" value="<?php echo $user[0]->first_name.' '.$user[0]->last_name; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Phone Number <span class="text-danger">*</span></label>
              <input type="tel" class="form-control" name="delivery_phone" value="<?php echo $patient_phone; ?>" required>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Delivery Address <span class="text-danger">*</span></label>
            <textarea class="form-control" name="delivery_address" rows="2" required><?php echo $patient_address; ?></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">State <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="delivery_state" value="<?php echo $patient_state; ?>" readonly required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">City <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="delivery_city" value="<?php echo $patient_city; ?>" required>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Delivery Notes (Optional)</label>
            <textarea class="form-control" name="notes" rows="2" placeholder="Any special instructions for delivery..."></textarea>
          </div>
        </div>
      </div>
      
      <div class="card">
        <div class="card-header">
          <h6 class="mb-0">Payment Method</h6>
        </div>
        <div class="card-body">
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
            <label class="form-check-label" for="payment_cod">
              <strong>Cash on Delivery</strong>
              <br><small class="text-muted">Pay when you receive your order</small>
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" id="payment_online" value="online" disabled>
            <label class="form-check-label" for="payment_online">
              <strong>Online Payment</strong>
              <br><small class="text-muted">Coming soon</small>
            </label>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Order Summary -->
    <div class="col-lg-4">
      <div class="card mb-3">
        <div class="card-header">
          <h6 class="mb-0">Order Summary</h6>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <?php foreach($cart_items as $item){ ?>
            <div class="d-flex justify-content-between mb-2">
              <div>
                <small><?php echo $item->drug_name; ?></small>
                <br><small class="text-muted">Qty: <?php echo $item->quantity; ?> × ₦<?php echo number_format($item->retail_price, 2); ?></small>
              </div>
              <small><strong>₦<?php echo number_format($item->retail_price * $item->quantity, 2); ?></strong></small>
            </div>
            <?php } ?>
          </div>
          
          <hr>
          
          <div class="d-flex justify-content-between mb-2">
            <span>Subtotal</span>
            <strong>₦<?php echo number_format($subtotal, 2); ?></strong>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Delivery Fee</span>
            <strong>₦<?php echo number_format($delivery_fee, 2); ?></strong>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-3">
            <strong>Total</strong>
            <h5 class="mb-0" style="color: <?php echo $primary_color; ?>;">₦<?php echo number_format($total, 2); ?></h5>
          </div>
          
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="terms" required>
            <label class="form-check-label small" for="terms">
              I agree to the <a href="#">terms and conditions</a>
            </label>
          </div>
          
          <button type="submit" class="btn btn-storefront w-100 mb-2" <?php echo !$geo_lock_valid ? 'disabled' : ''; ?>>
            <i class="bx bx-check-circle me-1"></i> Place Order
          </button>
          
          <a href="?pg=storefront-cart&doctor=<?php echo $doctor_ref; ?>" class="btn btn-outline-secondary w-100">
            <i class="bx bx-arrow-back me-1"></i> Back to Cart
          </a>
        </div>
      </div>
      
      <div class="card bg-light">
        <div class="card-body">
          <h6 class="mb-2"><i class="bx bx-shield-check me-1"></i> Secure Checkout</h6>
          <p class="mb-0 small text-muted">
            Your order information is encrypted and secure. You will receive order confirmation and tracking details via SMS and email.
          </p>
        </div>
      </div>
    </div>
  </div>
</form>

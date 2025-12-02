<?php
// Get patient ID from user array passed by controller
$uid = $user[0]->id;

// Get doctor ref code from URL
$doctor_ref = isset($_GET['doctor']) ? $_GET['doctor'] : '';

if(!$doctor_ref){
    echo '<div class="alert alert-danger">Invalid cart link</div>';
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
    SELECT sc.*, dsi.retail_price, dsi.wholesale_price, md.drug_name, md.generic_name, md.photo, md.unit,
        u.company_name, u.id as sales_rep_id, u.state as sales_rep_state
    FROM storefront_cart sc
    INNER JOIN doctor_storefront_inventory dsi ON sc.doctor_inventory_id = dsi.id
    INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id
    INNER JOIN users u ON md.sales_rep_id = u.id
    WHERE sc.patient_id = '.$uid.' AND sc.doctor_id = '.$doctor_id.'
    ORDER BY sc.created_at DESC
');

// Calculate totals
$subtotal = 0;
$sales_rep_id = null;
foreach($cart_items as $item){
    $subtotal += $item->retail_price * $item->quantity;
    if(!$sales_rep_id) $sales_rep_id = $item->sales_rep_id;
}

$delivery_fee = 0; // Can be calculated based on location
$total = $subtotal + $delivery_fee;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h5 class="mb-1">Shopping Cart</h5>
        <p class="text-muted mb-0"><?php echo $storefront_name; ?></p>
      </div>
      <a href="?pg=doctor-storefront&doctor=<?php echo $doctor_ref; ?>" class="btn btn-outline-primary">
        <i class="bx bx-arrow-back me-1"></i> Continue Shopping
      </a>
    </div>
  </div>
</div>

<?php if(empty($cart_items)){ ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body text-center py-5">
        <i class="bx bx-cart bx-lg text-muted mb-3"></i>
        <h5 class="text-muted">Your Cart is Empty</h5>
        <p class="text-muted mb-3">Add some products from the storefront to get started</p>
        <a href="?pg=doctor-storefront&doctor=<?php echo $doctor_ref; ?>" class="btn btn-primary">
          <i class="bx bx-shopping-bag me-1"></i> Browse Products
        </a>
      </div>
    </div>
  </div>
</div>
<?php } else { ?>

<div class="row">
  <!-- Cart Items -->
  <div class="col-lg-8 mb-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Cart Items (<?php echo count($cart_items); ?>)</h6>
        <form method="POST" style="display: inline;">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="action" value="clear_cart">
          <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
          <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Clear all items from cart?')">
            <i class="bx bx-trash me-1"></i> Clear Cart
          </button>
        </form>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($cart_items as $item){ ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <?php if($item->photo){ ?>
                      <img src="/assets/products/<?php echo $item->photo; ?>" alt="<?php echo $item->drug_name; ?>" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <?php } else { ?>
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="bx bx-package"></i>
                        </span>
                      </div>
                    <?php } ?>
                    <div>
                      <strong><?php echo $item->drug_name; ?></strong>
                      <?php if($item->generic_name){ ?>
                        <br><small class="text-muted"><?php echo $item->generic_name; ?></small>
                      <?php } ?>
                      <br><small class="text-muted">From: <?php echo $item->company_name; ?></small>
                    </div>
                  </div>
                </td>
                <td>
                  <strong>₦<?php echo number_format($item->retail_price, 2); ?></strong>
                  <br><small class="text-muted">per <?php echo $item->unit; ?></small>
                </td>
                <td>
                  <div class="input-group" style="width: 130px;">
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(<?php echo $item->id; ?>, <?php echo $item->quantity - 1; ?>)">
                      <i class="bx bx-minus"></i>
                    </button>
                    <input type="text" class="form-control form-control-sm text-center" value="<?php echo $item->quantity; ?>" readonly>
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(<?php echo $item->id; ?>, <?php echo $item->quantity + 1; ?>)">
                      <i class="bx bx-plus"></i>
                    </button>
                  </div>
                </td>
                <td>
                  <strong style="color: <?php echo $primary_color; ?>;">₦<?php echo number_format($item->retail_price * $item->quantity, 2); ?></strong>
                </td>
                <td>
                  <form method="POST" style="display: inline;">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="action" value="remove_from_cart">
                    <input type="hidden" name="cart_id" value="<?php echo $item->id; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this item?')">
                      <i class="bx bx-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
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
        
        <a href="?pg=storefront-checkout&doctor=<?php echo $doctor_ref; ?>" class="btn btn-storefront w-100 mb-2">
          <i class="bx bx-check-circle me-1"></i> Proceed to Checkout
        </a>
        
        <a href="?pg=doctor-storefront&doctor=<?php echo $doctor_ref; ?>" class="btn btn-outline-secondary w-100">
          <i class="bx bx-shopping-bag me-1"></i> Continue Shopping
        </a>
      </div>
    </div>
    
    <div class="card bg-light">
      <div class="card-body">
        <h6 class="mb-2"><i class="bx bx-info-circle me-1"></i> Delivery Information</h6>
        <p class="mb-0 small text-muted">
          Your order will be fulfilled by <?php echo !empty($cart_items) ? $cart_items[0]->company_name : 'our pharmaceutical partner'; ?> 
          and delivered to your address in <?php echo $user[0]->state ?? 'your state'; ?>.
        </p>
      </div>
    </div>
  </div>
</div>

<script>
function updateQuantity(cartId, newQuantity) {
  if(newQuantity < 1) {
    if(confirm('Remove this item from cart?')) {
      var form = document.createElement('form');
      form.method = 'POST';
      form.innerHTML = '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">' +
                       '<input type="hidden" name="action" value="remove_from_cart">' +
                       '<input type="hidden" name="cart_id" value="' + cartId + '">';
      document.body.appendChild(form);
      form.submit();
    }
    return;
  }
  
  var form = document.createElement('form');
  form.method = 'POST';
  form.innerHTML = '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">' +
                   '<input type="hidden" name="action" value="update_cart_quantity">' +
                   '<input type="hidden" name="cart_id" value="' + cartId + '">' +
                   '<input type="hidden" name="quantity" value="' + newQuantity + '">';
  document.body.appendChild(form);
  form.submit();
}
</script>

<?php } ?>

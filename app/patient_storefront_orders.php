<?php
// Get patient ID from user array passed by controller
$uid = $user[0]->id;

// Get patient's orders
$orders = DB::select('
    SELECT so.*, u.first_name as doctor_first_name, u.last_name as doctor_last_name,
        dss.storefront_name, dss.primary_color
    FROM storefront_orders so
    INNER JOIN users u ON so.doctor_id = u.id
    LEFT JOIN doctor_storefront_settings dss ON u.id = dss.doctor_id
    WHERE so.patient_id = ?
    ORDER BY so.created_at DESC
', [$uid]);
?>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">My Orders</h5>
    <p class="text-muted mb-4">Track your medication orders from doctor storefronts</p>
  </div>
</div>

<!-- Order Stats -->
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card border-start border-primary border-3">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="badge bg-label-primary rounded p-2 me-2">
            <i class="bx bx-package bx-sm"></i>
          </div>
          <div>
            <small class="text-muted d-block">Total Orders</small>
            <h5 class="mb-0"><?php echo count($orders); ?></h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-start border-warning border-3">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="badge bg-label-warning rounded p-2 me-2">
            <i class="bx bx-time-five bx-sm"></i>
          </div>
          <div>
            <small class="text-muted d-block">Pending</small>
            <h5 class="mb-0">
              <?php 
                $pending = 0;
                foreach($orders as $order) {
                  if($order->order_status == 'pending') $pending++;
                }
                echo $pending;
              ?>
            </h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-start border-info border-3">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="badge bg-label-info rounded p-2 me-2">
            <i class="bx bx-car bx-sm"></i>
          </div>
          <div>
            <small class="text-muted d-block">In Transit</small>
            <h5 class="mb-0">
              <?php 
                $in_transit = 0;
                foreach($orders as $order) {
                  if($order->order_status == 'shipped') $in_transit++;
                }
                echo $in_transit;
              ?>
            </h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-start border-success border-3">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="badge bg-label-success rounded p-2 me-2">
            <i class="bx bx-check-circle bx-sm"></i>
          </div>
          <div>
            <small class="text-muted d-block">Delivered</small>
            <h5 class="mb-0">
              <?php 
                $delivered = 0;
                foreach($orders as $order) {
                  if($order->order_status == 'delivered') $delivered++;
                }
                echo $delivered;
              ?>
            </h5>
          </div>
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
            <input type="text" class="form-control form-control-sm" placeholder="Search by order number...">
          </div>
          <div class="col-md-3">
            <select class="form-select form-select-sm">
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="shipped">Shipped</option>
              <option value="delivered">Delivered</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="col-md-3">
            <input type="date" class="form-control form-control-sm" placeholder="From Date">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Orders List -->
<div class="row">
  <div class="col-12">
    <?php if(empty($orders)){ ?>
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="bx bx-shopping-bag bx-lg text-muted mb-3"></i>
          <h5 class="text-muted">No Orders Yet</h5>
          <p class="text-muted mb-3">You haven't placed any orders from doctor storefronts</p>
          <a href="?pg=storefronts" class="btn btn-primary">
            <i class="bx bx-store-alt me-1"></i> Browse Storefronts
          </a>
        </div>
      </div>
    <?php } else { ?>
      <?php foreach($orders as $order){ 
        // Get order items
        $order_items = DB::select('select * from storefront_order_items where order_id='.$order->id);
        
        $status_class = 'secondary';
        if($order->order_status == 'delivered') $status_class = 'success';
        elseif($order->order_status == 'shipped') $status_class = 'info';
        elseif($order->order_status == 'confirmed') $status_class = 'primary';
        elseif($order->order_status == 'cancelled') $status_class = 'danger';
        elseif($order->order_status == 'pending') $status_class = 'warning';
        
        $primary_color = $order->primary_color ?? '#696cff';
      ?>
      <div class="card mb-3">
        <div class="card-header" style="background: <?php echo $primary_color; ?>10;">
          <div class="row align-items-center">
            <div class="col-md-3">
              <small class="text-muted d-block">Order Number</small>
              <strong><?php echo $order->order_number; ?></strong>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block">Storefront</small>
              <strong><?php echo $order->storefront_name ?? 'Dr. '.$order->doctor_first_name.' '.$order->doctor_last_name; ?></strong>
            </div>
            <div class="col-md-2">
              <small class="text-muted d-block">Date</small>
              <strong><?php echo date('M d, Y', strtotime($order->created_at)); ?></strong>
            </div>
            <div class="col-md-2">
              <small class="text-muted d-block">Total</small>
              <strong>₦<?php echo number_format($order->total_amount, 2); ?></strong>
            </div>
            <div class="col-md-2 text-end">
              <span class="badge bg-<?php echo $status_class; ?>"><?php echo ucfirst($order->order_status); ?></span>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <h6 class="mb-2">Order Items (<?php echo count($order_items); ?>)</h6>
              <ul class="list-unstyled mb-0">
                <?php foreach($order_items as $item){ ?>
                <li class="mb-1">
                  <i class="bx bx-package me-1"></i>
                  <strong><?php echo $item->drug_name; ?></strong> - 
                  Qty: <?php echo $item->quantity; ?> × ₦<?php echo number_format($item->unit_price, 2); ?> = 
                  <strong>₦<?php echo number_format($item->subtotal, 2); ?></strong>
                </li>
                <?php } ?>
              </ul>
            </div>
            <div class="col-md-4">
              <h6 class="mb-2">Delivery Address</h6>
              <p class="mb-0 small">
                <?php echo $order->delivery_address; ?><br>
                <?php echo $order->delivery_city; ?>, <?php echo $order->delivery_state; ?><br>
                <?php echo $order->delivery_phone; ?>
              </p>
            </div>
          </div>
          
          <hr>
          
          <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetails(<?php echo $order->id; ?>)">
              <i class="bx bx-show me-1"></i> View Details
            </button>
            
            <?php if($order->order_status == 'delivered'){ ?>
              <a href="?pg=leave-review&order=<?php echo $order->id; ?>" class="btn btn-sm btn-outline-warning">
                <i class="bx bx-star me-1"></i> Leave Review
              </a>
            <?php } ?>
            
            <?php if($order->order_status == 'pending' || $order->order_status == 'confirmed'){ ?>
              <form method="POST" style="display: inline;">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="action" value="cancel_order">
                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this order?')">
                  <i class="bx bx-x-circle me-1"></i> Cancel Order
                </button>
              </form>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php } ?>
    <?php } ?>
  </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="orderDetailsContent">
        <!-- Order details will be loaded here -->
      </div>
    </div>
  </div>
</div>

<script>
function viewOrderDetails(orderId) {
  var modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
  modal.show();
  // In real implementation, load order details via AJAX
}
</script>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Orders Management</h5>
    <p class="text-muted mb-4">Track and fulfill orders from doctors' patients</p>
  </div>
</div>

<!-- Order Stats -->
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card border-start border-primary border-3">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="badge bg-label-primary rounded p-2 me-2">
            <i class="bx bx-time-five bx-sm"></i>
          </div>
          <div>
            <small class="text-muted d-block">Pending</small>
            <h5 class="mb-0"><?php echo $pending_orders; ?></h5>
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
            <i class="bx bx-package bx-sm"></i>
          </div>
          <div>
            <small class="text-muted d-block">To Ship</small>
            <h5 class="mb-0">
              <?php 
                $confirmed_orders = DB::select('select count(*) as count from storefront_orders WHERE sales_rep_id='.Cookie::get('uid').' AND order_status="confirmed"');
                echo $confirmed_orders[0]->count ?? 0;
              ?>
            </h5>
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
            <i class="bx bx-car bx-sm"></i>
          </div>
          <div>
            <small class="text-muted d-block">In Transit</small>
            <h5 class="mb-0">
              <?php 
                $shipped_orders = DB::select('select count(*) as count from storefront_orders WHERE sales_rep_id='.Cookie::get('uid').' AND order_status="shipped"');
                echo $shipped_orders[0]->count ?? 0;
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
                $delivered_orders = DB::select('select count(*) as count from storefront_orders WHERE sales_rep_id='.Cookie::get('uid').' AND order_status="delivered"');
                echo $delivered_orders[0]->count ?? 0;
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
          <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" placeholder="Search by order number...">
          </div>
          <div class="col-md-2">
            <select class="form-select form-select-sm">
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="shipped">Shipped</option>
              <option value="delivered">Delivered</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="date" class="form-control form-control-sm" placeholder="From Date">
          </div>
          <div class="col-md-2">
            <input type="date" class="form-control form-control-sm" placeholder="To Date">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Orders Table -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <?php if(empty($recent_orders)){ ?>
          <div class="text-center py-5">
            <i class="bx bx-cart bx-lg text-muted mb-3"></i>
            <h5 class="text-muted">No Orders Yet</h5>
            <p class="text-muted">Orders will appear here when doctors' patients purchase your products</p>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Items</th>
                <th>Amount</th>
                <th>Delivery</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($recent_orders as $order){ 
                // Get doctor details
                $doctor = DB::select('select first_name, last_name from users WHERE id='.$order->doctor_id);
                // Get order items count
                $items_count = DB::select('select count(*) as count from storefront_order_items WHERE order_id='.$order->id);
              ?>
              <tr>
                <td><strong>#<?php echo $order->order_number; ?></strong></td>
                <td>
                  <div>
                    <strong><?php echo $order->first_name.' '.$order->last_name; ?></strong>
                    <br><small class="text-muted"><?php echo $order->delivery_phone; ?></small>
                  </div>
                </td>
                <td>
                  <?php if(!empty($doctor)){ ?>
                    Dr. <?php echo $doctor[0]->first_name.' '.$doctor[0]->last_name; ?>
                  <?php } ?>
                </td>
                <td>
                  <span class="badge bg-label-secondary"><?php echo $items_count[0]->count; ?> items</span>
                </td>
                <td>
                  <strong>₦<?php echo number_format($order->sales_rep_amount, 2); ?></strong>
                  <br><small class="text-muted">Total: ₦<?php echo number_format($order->total_amount, 2); ?></small>
                </td>
                <td>
                  <div>
                    <small class="text-muted"><?php echo $order->delivery_city; ?>, <?php echo $order->delivery_state; ?></small>
                  </div>
                </td>
                <td>
                  <?php 
                    $status_class = 'secondary';
                    if($order->order_status == 'delivered') $status_class = 'success';
                    elseif($order->order_status == 'shipped') $status_class = 'info';
                    elseif($order->order_status == 'confirmed') $status_class = 'primary';
                    elseif($order->order_status == 'cancelled') $status_class = 'danger';
                    elseif($order->order_status == 'pending') $status_class = 'warning';
                  ?>
                  <span class="badge bg-<?php echo $status_class; ?>"><?php echo ucfirst($order->order_status); ?></span>
                </td>
                <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                      Actions
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="viewOrderDetails(<?php echo $order->id; ?>)">
                        <i class="bx bx-show me-1"></i> View Details
                      </a></li>
                      <?php if($order->order_status == 'pending'){ ?>
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="updateOrderStatus(<?php echo $order->id; ?>, 'confirmed')">
                        <i class="bx bx-check me-1"></i> Confirm Order
                      </a></li>
                      <?php } ?>
                      <?php if($order->order_status == 'confirmed'){ ?>
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="updateOrderStatus(<?php echo $order->id; ?>, 'shipped')">
                        <i class="bx bx-car me-1"></i> Mark as Shipped
                      </a></li>
                      <?php } ?>
                      <?php if($order->order_status == 'shipped'){ ?>
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="updateOrderStatus(<?php echo $order->id; ?>, 'delivered')">
                        <i class="bx bx-check-circle me-1"></i> Mark as Delivered
                      </a></li>
                      <?php } ?>
                      <?php if($order->order_status == 'pending' || $order->order_status == 'confirmed'){ ?>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="updateOrderStatus(<?php echo $order->id; ?>, 'cancelled')">
                        <i class="bx bx-x-circle me-1"></i> Cancel Order
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
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function updateOrderStatus(orderId, newStatus) {
  var statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
  if(confirm('Are you sure you want to mark this order as ' + statusText + '?')) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = '<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">' +
                     '<input type="hidden" name="action" value="update_order_status">' +
                     '<input type="hidden" name="order_id" value="' + orderId + '">' +
                     '<input type="hidden" name="new_status" value="' + newStatus + '">';
    document.body.appendChild(form);
    form.submit();
  }
}

function viewOrderDetails(orderId) {
  var modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
  modal.show();
  // In real implementation, load order details via AJAX
}
</script>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Dashboard Overview</h5>
    <p class="text-muted mb-4">Welcome back, <?php echo $user[0]->first_name; ?>! Here's what's happening with your products.</p>
  </div>
</div>

<div class="row">
  <!-- Total Products -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div class="content-left">
            <span class="text-muted d-block mb-1">Total Products</span>
            <h3 class="mb-0"><?php echo $total_products; ?></h3>
            <small class="text-success"><i class="bx bx-check-circle"></i> <?php echo $active_products; ?> Active</small>
          </div>
          <span class="badge bg-label-primary rounded p-2">
            <i class="bx bx-package bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Doctors -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div class="content-left">
            <span class="text-muted d-block mb-1">Active Doctors</span>
            <h3 class="mb-0"><?php echo $total_doctors; ?></h3>
            <small class="text-muted">In your network</small>
          </div>
          <span class="badge bg-label-success rounded p-2">
            <i class="bx bx-user-plus bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Orders -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div class="content-left">
            <span class="text-muted d-block mb-1">Total Orders</span>
            <h3 class="mb-0"><?php echo $total_orders; ?></h3>
            <small class="text-warning"><i class="bx bx-time-five"></i> <?php echo $pending_orders; ?> Pending</small>
          </div>
          <span class="badge bg-label-info rounded p-2">
            <i class="bx bx-cart bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Revenue -->
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div class="content-left">
            <span class="text-muted d-block mb-1">Total Revenue</span>
            <h3 class="mb-0">₦<?php echo number_format($total_revenue, 2); ?></h3>
            <small class="text-muted">From delivered orders</small>
          </div>
          <span class="badge bg-label-warning rounded p-2">
            <i class="bx bx-wallet bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Low Stock Alert -->
<?php if($low_stock_products > 0){ ?>
<div class="row">
  <div class="col-12 mb-4">
    <div class="alert alert-warning d-flex align-items-center" role="alert">
      <i class="bx bx-error-circle bx-sm me-2"></i>
      <div>
        <strong>Low Stock Alert!</strong> You have <?php echo $low_stock_products; ?> product(s) running low on stock. 
        <a href="?pg=products" class="alert-link">View Products</a>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<!-- Recent Orders -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Orders</h5>
        <a href="?pg=orders" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="card-body">
        <?php if(empty($recent_orders)){ ?>
          <div class="text-center py-4">
            <i class="bx bx-cart bx-lg text-muted mb-2"></i>
            <p class="text-muted">No orders yet. Your products will appear in the doctor marketplace.</p>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Patient</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($recent_orders as $order){ ?>
              <tr>
                <td><strong>#<?php echo $order->order_number; ?></strong></td>
                <td><?php echo $order->first_name.' '.$order->last_name; ?></td>
                <td>₦<?php echo number_format($order->sales_rep_amount, 2); ?></td>
                <td>
                  <?php 
                    $status_class = 'secondary';
                    if($order->order_status == 'delivered') $status_class = 'success';
                    elseif($order->order_status == 'shipped') $status_class = 'info';
                    elseif($order->order_status == 'confirmed') $status_class = 'primary';
                    elseif($order->order_status == 'cancelled') $status_class = 'danger';
                  ?>
                  <span class="badge bg-<?php echo $status_class; ?>"><?php echo ucfirst($order->order_status); ?></span>
                </td>
                <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                <td>
                  <a href="?pg=orders" class="btn btn-sm btn-outline-primary">View</a>
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

<!-- Quick Actions -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <h5 class="text-white mb-3">Quick Actions</h5>
        <div class="d-flex gap-2 flex-wrap">
          <a href="?pg=upload" class="btn btn-light">
            <i class="bx bx-upload me-1"></i> Upload New Product
          </a>
          <a href="?pg=products" class="btn btn-outline-light">
            <i class="bx bx-package me-1"></i> Manage Products
          </a>
          <a href="?pg=orders" class="btn btn-outline-light">
            <i class="bx bx-cart me-1"></i> View Orders
          </a>
          <a href="?pg=analytics" class="btn btn-outline-light">
            <i class="bx bx-bar-chart-alt-2 me-1"></i> View Analytics
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

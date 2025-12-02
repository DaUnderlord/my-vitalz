<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Analytics & Reports</h5>
    <p class="text-muted mb-4">Track your sales performance and insights</p>
  </div>
</div>

<?php
// Get analytics data
$uid = Cookie::get('uid');

// Revenue by month (last 6 months)
$monthly_revenue = DB::select('
  SELECT DATE_FORMAT(created_at, "%Y-%m") as month, 
         COALESCE(SUM(sales_rep_amount), 0) as revenue,
         COUNT(*) as orders
  FROM storefront_orders 
  WHERE sales_rep_id = '.$uid.' AND payment_status = "paid"
  AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
  GROUP BY DATE_FORMAT(created_at, "%Y-%m")
  ORDER BY month DESC
');

// Top selling products
$top_products = DB::select('
  SELECT md.drug_name, md.category, md.photo,
         COUNT(soi.id) as times_sold,
         SUM(soi.quantity) as total_quantity,
         SUM(soi.wholesale_price * soi.quantity) as revenue
  FROM marketplace_drugs md
  INNER JOIN doctor_storefront_inventory dsi ON md.id = dsi.marketplace_drug_id
  INNER JOIN storefront_order_items soi ON dsi.id = soi.doctor_inventory_id
  INNER JOIN storefront_orders so ON soi.order_id = so.id
  WHERE md.sales_rep_id = '.$uid.' AND so.payment_status = "paid"
  GROUP BY md.id, md.drug_name, md.category, md.photo
  ORDER BY revenue DESC
  LIMIT 5
');

// Performance metrics
$total_sales = DB::select('SELECT COALESCE(SUM(sales_rep_amount), 0) as total FROM storefront_orders WHERE sales_rep_id='.$uid.' AND payment_status="paid"');
$avg_order_value = DB::select('SELECT COALESCE(AVG(sales_rep_amount), 0) as avg FROM storefront_orders WHERE sales_rep_id='.$uid.' AND payment_status="paid"');
$conversion_rate = 0; // Placeholder for future implementation
?>

<!-- Key Metrics -->
<div class="row mb-4">
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted d-block mb-1">Total Sales</span>
            <h4 class="mb-0">₦<?php echo number_format($total_sales[0]->total, 0); ?></h4>
            <small class="text-success"><i class="bx bx-trending-up"></i> All time</small>
          </div>
          <span class="badge bg-label-success rounded p-2">
            <i class="bx bx-wallet bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted d-block mb-1">Avg Order Value</span>
            <h4 class="mb-0">₦<?php echo number_format($avg_order_value[0]->avg, 0); ?></h4>
            <small class="text-muted">Per transaction</small>
          </div>
          <span class="badge bg-label-primary rounded p-2">
            <i class="bx bx-cart bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted d-block mb-1">Total Orders</span>
            <h4 class="mb-0"><?php echo $total_orders; ?></h4>
            <small class="text-muted">Completed orders</small>
          </div>
          <span class="badge bg-label-info rounded p-2">
            <i class="bx bx-package bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted d-block mb-1">Active Products</span>
            <h4 class="mb-0"><?php echo $active_products; ?></h4>
            <small class="text-muted">In marketplace</small>
          </div>
          <span class="badge bg-label-warning rounded p-2">
            <i class="bx bx-box bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Revenue Chart -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Revenue Trend (Last 6 Months)</h6>
        <div class="btn-group btn-group-sm" role="group">
          <button type="button" class="btn btn-outline-primary active">6 Months</button>
          <button type="button" class="btn btn-outline-primary">12 Months</button>
        </div>
      </div>
      <div class="card-body">
        <?php if(empty($monthly_revenue)){ ?>
          <div class="text-center py-4">
            <p class="text-muted">No revenue data available yet</p>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Month</th>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Growth</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $prev_revenue = 0;
              foreach($monthly_revenue as $index => $month){ 
                $growth = 0;
                if($prev_revenue > 0) {
                  $growth = (($month->revenue - $prev_revenue) / $prev_revenue) * 100;
                }
                $prev_revenue = $month->revenue;
              ?>
              <tr>
                <td><?php echo date('M Y', strtotime($month->month.'-01')); ?></td>
                <td><?php echo $month->orders; ?></td>
                <td><strong>₦<?php echo number_format($month->revenue, 2); ?></strong></td>
                <td>
                  <?php if($growth > 0){ ?>
                    <span class="text-success"><i class="bx bx-trending-up"></i> <?php echo number_format($growth, 1); ?>%</span>
                  <?php } elseif($growth < 0){ ?>
                    <span class="text-danger"><i class="bx bx-trending-down"></i> <?php echo number_format(abs($growth), 1); ?>%</span>
                  <?php } else { ?>
                    <span class="text-muted">-</span>
                  <?php } ?>
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

<!-- Top Products -->
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bx bx-star me-1"></i> Top Selling Products</h6>
      </div>
      <div class="card-body">
        <?php if(empty($top_products)){ ?>
          <div class="text-center py-4">
            <p class="text-muted">No sales data available yet</p>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Times Sold</th>
                <th>Quantity</th>
                <th>Revenue</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($top_products as $product){ ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <?php if($product->photo){ ?>
                      <img src="/assets/products/<?php echo $product->photo; ?>" alt="<?php echo $product->drug_name; ?>" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                    <?php } else { ?>
                      <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="bx bx-package"></i>
                        </span>
                      </div>
                    <?php } ?>
                    <strong><?php echo $product->drug_name; ?></strong>
                  </div>
                </td>
                <td><span class="badge bg-label-info"><?php echo $product->category; ?></span></td>
                <td><?php echo $product->times_sold; ?> orders</td>
                <td><?php echo $product->total_quantity; ?> units</td>
                <td><strong>₦<?php echo number_format($product->revenue, 2); ?></strong></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bx bx-info-circle me-1"></i> Quick Insights</h6>
      </div>
      <div class="card-body">
        <div class="mb-3 pb-3 border-bottom">
          <small class="text-muted d-block mb-1">Best Selling Category</small>
          <?php if(!empty($top_products)){ ?>
            <strong><?php echo $top_products[0]->category; ?></strong>
          <?php } else { ?>
            <span class="text-muted">No data</span>
          <?php } ?>
        </div>
        
        <div class="mb-3 pb-3 border-bottom">
          <small class="text-muted d-block mb-1">Active Doctors</small>
          <strong><?php echo $total_doctors; ?> doctors</strong>
        </div>
        
        <div class="mb-3 pb-3 border-bottom">
          <small class="text-muted d-block mb-1">Coverage Area</small>
          <strong><?php echo $user[0]->state; ?></strong>
        </div>
        
        <div>
          <small class="text-muted d-block mb-1">Pending Orders</small>
          <strong><?php echo $pending_orders; ?> orders</strong>
        </div>
      </div>
    </div>
    
    <div class="card mt-3 bg-primary text-white">
      <div class="card-body">
        <h6 class="text-white mb-2"><i class="bx bx-bulb me-1"></i> Pro Tip</h6>
        <p class="mb-0 small">Keep your stock levels updated and respond quickly to orders to maintain high doctor satisfaction and repeat business.</p>
      </div>
    </div>
  </div>
</div>

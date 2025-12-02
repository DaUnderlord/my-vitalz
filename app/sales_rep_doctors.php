<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Doctors Network</h5>
    <p class="text-muted mb-4">Doctors who have added your products to their storefronts</p>
  </div>
</div>

<?php
// Get doctors who added products from this sales rep
$doctors_network = DB::select('
  SELECT DISTINCT u.id, u.first_name, u.last_name, u.email, u.phone, u.specialization, u.city, u.state,
    COUNT(DISTINCT dsi.id) as products_count,
    COUNT(DISTINCT so.id) as orders_count,
    COALESCE(SUM(so.sales_rep_amount), 0) as total_revenue
  FROM users u
  INNER JOIN doctor_storefront_inventory dsi ON u.id = dsi.doctor_id
  INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id
  LEFT JOIN storefront_orders so ON u.id = so.doctor_id AND so.sales_rep_id = '.Cookie::get('uid').'
  WHERE md.sales_rep_id = '.Cookie::get('uid').' AND u.doctor = 1
  GROUP BY u.id, u.first_name, u.last_name, u.email, u.phone, u.specialization, u.city, u.state
  ORDER BY total_revenue DESC
');
?>

<!-- Network Stats -->
<div class="row mb-4">
  <div class="col-md-4 mb-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0"><?php echo count($doctors_network); ?></h3>
            <small>Active Doctors</small>
          </div>
          <i class="bx bx-user-plus bx-lg"></i>
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
                $total_products_added = 0;
                foreach($doctors_network as $doc) {
                  $total_products_added += $doc->products_count;
                }
                echo $total_products_added;
              ?>
            </h3>
            <small>Products Stocked</small>
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
            <h3 class="text-white mb-0">
              ₦<?php 
                $network_revenue = 0;
                foreach($doctors_network as $doc) {
                  $network_revenue += $doc->total_revenue;
                }
                echo number_format($network_revenue, 0);
              ?>
            </h3>
            <small>Network Revenue</small>
          </div>
          <i class="bx bx-wallet bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Doctors List -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <?php if(empty($doctors_network)){ ?>
          <div class="text-center py-5">
            <i class="bx bx-user-plus bx-lg text-muted mb-3"></i>
            <h5 class="text-muted">No Doctors Yet</h5>
            <p class="text-muted">Doctors will appear here when they add your products to their storefronts</p>
            <small class="text-muted">Make sure your products are active and competitively priced</small>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Doctor</th>
                <th>Specialization</th>
                <th>Location</th>
                <th>Products Stocked</th>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($doctors_network as $doctor){ ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial rounded-circle bg-label-primary">
                        <?php echo strtoupper(substr($doctor->first_name, 0, 1)); ?>
                      </span>
                    </div>
                    <div>
                      <strong>Dr. <?php echo $doctor->first_name.' '.$doctor->last_name; ?></strong>
                      <br><small class="text-muted"><?php echo $doctor->email; ?></small>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-label-info"><?php echo $doctor->specialization ?? 'General'; ?></span>
                </td>
                <td>
                  <small><?php echo $doctor->city; ?>, <?php echo $doctor->state; ?></small>
                </td>
                <td>
                  <span class="badge bg-label-primary"><?php echo $doctor->products_count; ?> products</span>
                </td>
                <td>
                  <span class="badge bg-label-success"><?php echo $doctor->orders_count; ?> orders</span>
                </td>
                <td>
                  <strong>₦<?php echo number_format($doctor->total_revenue, 2); ?></strong>
                </td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                      Actions
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="viewDoctorDetails(<?php echo $doctor->id; ?>)">
                        <i class="bx bx-show me-1"></i> View Details
                      </a></li>
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="viewDoctorProducts(<?php echo $doctor->id; ?>)">
                        <i class="bx bx-package me-1"></i> View Products
                      </a></li>
                      <li><a class="dropdown-item" href="tel:<?php echo $doctor->phone; ?>">
                        <i class="bx bx-phone me-1"></i> Call Doctor
                      </a></li>
                      <li><a class="dropdown-item" href="mailto:<?php echo $doctor->email; ?>">
                        <i class="bx bx-envelope me-1"></i> Send Email
                      </a></li>
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

<!-- Top Performing Doctors -->
<?php if(!empty($doctors_network)){ ?>
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0"><i class="bx bx-trophy me-1"></i> Top Performing Doctors</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <?php 
          $top_doctors = array_slice($doctors_network, 0, 3);
          foreach($top_doctors as $index => $doctor){ 
            $medal_color = ['warning', 'secondary', 'danger'];
            $medal_icon = ['bx-medal', 'bx-medal', 'bx-medal'];
          ?>
          <div class="col-md-4 mb-3">
            <div class="card border">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <i class="bx <?php echo $medal_icon[$index]; ?> bx-sm text-<?php echo $medal_color[$index]; ?> me-2"></i>
                  <strong>#<?php echo $index + 1; ?> Top Doctor</strong>
                </div>
                <h6>Dr. <?php echo $doctor->first_name.' '.$doctor->last_name; ?></h6>
                <div class="mt-3">
                  <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">Revenue</small>
                    <strong>₦<?php echo number_format($doctor->total_revenue, 0); ?></strong>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">Orders</small>
                    <strong><?php echo $doctor->orders_count; ?></strong>
                  </div>
                  <div class="d-flex justify-content-between">
                    <small class="text-muted">Products</small>
                    <strong><?php echo $doctor->products_count; ?></strong>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<!-- Doctor Details Modal -->
<div class="modal fade" id="doctorDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Doctor Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="doctorDetailsContent">
        <!-- Doctor details will be loaded here -->
      </div>
    </div>
  </div>
</div>

<script>
function viewDoctorDetails(doctorId) {
  var modal = new bootstrap.Modal(document.getElementById('doctorDetailsModal'));
  modal.show();
}

function viewDoctorProducts(doctorId) {
  alert('View products for doctor ID: ' + doctorId);
}
</script>

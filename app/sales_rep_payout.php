<?php
// Ensure $uid is available when this partial is included directly
if (!isset($uid) || empty($uid)) {
    $uid = \Cookie::get('uid');
}

// Get sales rep's commission records
$commissions = DB::select('
    SELECT src.*, so.order_number, so.created_at as order_date, so.order_status,
        u.first_name as doctor_first_name, u.last_name as doctor_last_name
    FROM sales_rep_commissions src
    INNER JOIN storefront_orders so ON src.order_id = so.id
    INNER JOIN users u ON src.doctor_id = u.id
    WHERE src.sales_rep_id = '.$uid.'
    ORDER BY src.created_at DESC
');

// Calculate totals
$total_earned = 0;
$total_paid = 0;
$total_pending = 0;

foreach($commissions as $commission){
    $total_earned += $commission->amount;
    if($commission->status == 'paid'){
        $total_paid += $commission->amount;
    } else {
        $total_pending += $commission->amount;
    }
}

// Get payout requests
$payout_requests = DB::select('
    SELECT * FROM payout_requests 
    WHERE user_id = '.$uid.' 
    ORDER BY created_at DESC
');
?>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Payouts & Commissions</h5>
    <p class="text-muted mb-4">Track your earnings and request payouts</p>
  </div>
</div>

<!-- Earnings Summary -->
<div class="row mb-4">
  <div class="col-md-4 mb-3">
    <div class="card bg-success text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0">₦<?php echo number_format($total_earned, 2); ?></h3>
            <small>Total Earned</small>
          </div>
          <i class="bx bx-wallet bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-4 mb-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0">₦<?php echo number_format($total_paid, 2); ?></h3>
            <small>Paid Out</small>
          </div>
          <i class="bx bx-check-circle bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-4 mb-3">
    <div class="card bg-warning text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0">₦<?php echo number_format($total_pending, 2); ?></h3>
            <small>Pending</small>
          </div>
          <i class="bx bx-time-five bx-lg"></i>
        </div>
        <?php if($total_pending >= 10000){ ?>
        <button class="btn btn-sm btn-light mt-2 w-100" data-bs-toggle="modal" data-bs-target="#requestPayoutModal">
          <i class="bx bx-money me-1"></i> Request Payout
        </button>
        <?php } else { ?>
        <small class="d-block mt-2">Minimum payout: ₦10,000</small>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<!-- Commission Records -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">Commission History</h6>
      </div>
      <div class="card-body">
        <?php if(empty($commissions)){ ?>
          <div class="text-center py-4">
            <i class="bx bx-wallet bx-lg text-muted mb-2"></i>
            <p class="text-muted">No commissions yet</p>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Doctor</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Paid Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($commissions as $commission){ ?>
              <tr>
                <td><strong><?php echo $commission->order_number; ?></strong></td>
                <td>Dr. <?php echo $commission->doctor_first_name.' '.$commission->doctor_last_name; ?></td>
                <td><strong>₦<?php echo number_format($commission->amount, 2); ?></strong></td>
                <td>
                  <?php if($commission->status == 'paid'){ ?>
                    <span class="badge bg-success">Paid</span>
                  <?php } else { ?>
                    <span class="badge bg-warning">Pending</span>
                  <?php } ?>
                </td>
                <td><?php echo date('M d, Y', strtotime($commission->order_date)); ?></td>
                <td>
                  <?php if($commission->paid_at){ ?>
                    <?php echo date('M d, Y', strtotime($commission->paid_at)); ?>
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

<!-- Payout Requests -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">Payout Requests</h6>
      </div>
      <div class="card-body">
        <?php if(empty($payout_requests)){ ?>
          <div class="text-center py-4">
            <i class="bx bx-money bx-lg text-muted mb-2"></i>
            <p class="text-muted">No payout requests yet</p>
          </div>
        <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Request ID</th>
                <th>Amount</th>
                <th>Bank Details</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($payout_requests as $request){ ?>
              <tr>
                <td><strong>#<?php echo $request->id; ?></strong></td>
                <td><strong>₦<?php echo number_format($request->amount, 2); ?></strong></td>
                <td>
                  <?php echo $request->bank_name; ?><br>
                  <small class="text-muted"><?php echo $request->account_number; ?></small>
                </td>
                <td>
                  <?php 
                    $status_class = 'warning';
                    if($request->status == 'approved') $status_class = 'success';
                    elseif($request->status == 'rejected') $status_class = 'danger';
                  ?>
                  <span class="badge bg-<?php echo $status_class; ?>"><?php echo ucfirst($request->status); ?></span>
                </td>
                <td><?php echo date('M d, Y', strtotime($request->created_at)); ?></td>
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

<!-- Request Payout Modal -->
<div class="modal fade" id="requestPayoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Request Payout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="action" value="request_payout">
        
        <div class="modal-body">
          <div class="alert alert-info">
            <strong>Available Balance:</strong> ₦<?php echo number_format($total_pending, 2); ?>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Payout Amount <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="amount" max="<?php echo $total_pending; ?>" min="10000" step="0.01" required>
            <small class="text-muted">Minimum: ₦10,000</small>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="bank_name" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Account Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="account_number" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Account Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="account_name" required>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pharmacy /</span> Doctor Rewards
    </h4>

    <!-- Rewards Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Active Doctors</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $active_doctors = DB::select('select count(distinct doctor_id) as count from doctor_rewards WHERE pharmacy_id='.$user[0]->id.' AND status="active"');
                                    echo $active_doctors[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-primary">(In Program)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-user-plus bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total Rewards</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    ₦<?php 
                                    $total_rewards = DB::select('select sum(reward_amount) as total from doctor_rewards WHERE pharmacy_id='.$user[0]->id.' AND status="paid"');
                                    echo number_format($total_rewards[0]->total ?? 0, 2); 
                                    ?>
                                </h4>
                                <small class="text-success">(Paid)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-dollar bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Pending Rewards</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    ₦<?php 
                                    $pending_rewards = DB::select('select sum(reward_amount) as total from doctor_rewards WHERE pharmacy_id='.$user[0]->id.' AND status="pending"');
                                    echo number_format($pending_rewards[0]->total ?? 0, 2); 
                                    ?>
                                </h4>
                                <small class="text-warning">(Due)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-time bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>This Month</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $month_prescriptions = DB::select('select count(*) as count from doctor_rewards WHERE pharmacy_id='.$user[0]->id.' AND MONTH(created_at) = MONTH(NOW())');
                                    echo $month_prescriptions[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-info">(Prescriptions)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-receipt bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reward Program Settings -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Reward Program Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="hidden" name="action" value="update_reward_settings">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Default Reward Percentage</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="default_percentage" value="5" step="0.1" min="0" max="20">
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted">Percentage of prescription value</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Minimum Reward Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₦</span>
                                    <input type="number" class="form-control" name="minimum_amount" value="1.00" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Maximum Reward Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₦</span>
                                    <input type="number" class="form-control" name="maximum_amount" value="50.00" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Payment Frequency</label>
                                <select class="form-select" name="payment_frequency">
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctor Rewards List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Doctor Rewards</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterRewards(this.value)">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <button class="btn btn-sm btn-success" onclick="processPayments()">
                            <i class="bx bx-credit-card me-1"></i> Process Payments
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Doctor</th>
                                    <th>Prescription</th>
                                    <th>Patient</th>
                                    <th>Prescription Value</th>
                                    <th>Reward Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rewards = DB::select('
                                    SELECT dr.*, 
                                    d.first_name as doctor_fname, d.last_name as doctor_lname, d.specialization,
                                    p.first_name as patient_fname, p.last_name as patient_lname,
                                    ep.prescription_id, ep.medication_name, ep.total_amount
                                    FROM doctor_rewards dr
                                    LEFT JOIN users d ON dr.doctor_id = d.id
                                    LEFT JOIN e_prescriptions ep ON dr.prescription_id = ep.id
                                    LEFT JOIN users p ON ep.patient_id = p.id
                                    WHERE dr.pharmacy_id = '.$user[0]->id.'
                                    ORDER BY dr.created_at DESC
                                    LIMIT 50
                                ');
                                
                                if(!empty($rewards)){
                                    foreach($rewards as $reward){
                                        $status_class = '';
                                        $status_icon = '';
                                        switch($reward->status){
                                            case 'pending':
                                                $status_class = 'bg-warning';
                                                $status_icon = 'bx-time';
                                                break;
                                            case 'paid':
                                                $status_class = 'bg-success';
                                                $status_icon = 'bx-check-circle';
                                                break;
                                            case 'cancelled':
                                                $status_class = 'bg-danger';
                                                $status_icon = 'bx-x-circle';
                                                break;
                                        }
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="../assets/img/avatars/doctor.png" alt="Avatar" class="rounded-circle" />
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo $reward->doctor_fname.' '.$reward->doctor_lname; ?></h6>
                                                <small class="text-muted"><?php echo $reward->specialization ?? 'General'; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium">#<?php echo $reward->prescription_id; ?></span>
                                            <br><small class="text-muted"><?php echo $reward->medication_name; ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span><?php echo $reward->patient_fname.' '.$reward->patient_lname; ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">₦<?php echo number_format($reward->total_amount, 2); ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium text-success">₦<?php echo number_format($reward->reward_amount, 2); ?></span>
                                            <br><small class="text-muted"><?php echo $reward->reward_percentage; ?>%</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <i class="bx <?php echo $status_icon; ?> me-1"></i>
                                            <?php echo ucfirst($reward->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span><?php echo date('M d, Y', strtotime($reward->created_at)); ?></span>
                                        <?php if($reward->paid_date){ ?>
                                        <br><small class="text-success">Paid: <?php echo date('M d, Y', strtotime($reward->paid_date)); ?></small>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" onclick="viewRewardDetails('<?php echo $reward->id; ?>')">
                                                    <i class="bx bx-show me-1"></i> View Details
                                                </a>
                                                <?php if($reward->status == 'pending'){ ?>
                                                <a class="dropdown-item" href="#" onclick="markAsPaid('<?php echo $reward->id; ?>')">
                                                    <i class="bx bx-check me-1"></i> Mark as Paid
                                                </a>
                                                <a class="dropdown-item text-danger" href="#" onclick="cancelReward('<?php echo $reward->id; ?>')">
                                                    <i class="bx bx-x me-1"></i> Cancel Reward
                                                </a>
                                                <?php } ?>
                                                <a class="dropdown-item" href="#" onclick="addNote('<?php echo $reward->id; ?>')">
                                                    <i class="bx bx-note me-1"></i> Add Note
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bx bx-gift bx-lg text-muted"></i>
                                        <p class="text-muted mt-2">No rewards yet</p>
                                        <small class="text-muted">Rewards will appear here when doctors send prescriptions</small>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Virtual Pharmacy Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Virtual Pharmacy Links</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-muted mb-3">Share these links with doctors in your network to enable direct prescription sending:</p>
                            
                            <div class="mb-3">
                                <label class="form-label">General Prescription Link</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="https://myvitalz.ai/pharmacy/<?php echo $user[0]->id; ?>/prescribe" readonly>
                                    <button class="btn btn-outline-secondary" onclick="copyLink(this)">
                                        <i class="bx bx-copy"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Emergency Prescription Link</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="https://myvitalz.ai/pharmacy/<?php echo $user[0]->id; ?>/emergency" readonly>
                                    <button class="btn btn-outline-secondary" onclick="copyLink(this)">
                                        <i class="bx bx-copy"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">API Endpoint for Integration</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="https://api.myvitalz.ai/pharmacy/<?php echo $user[0]->id; ?>/prescriptions" readonly>
                                    <button class="btn btn-outline-secondary" onclick="copyLink(this)">
                                        <i class="bx bx-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="text-white">QR Code for Mobile Access</h6>
                                    <div class="text-center py-3">
                                        <div style="width: 120px; height: 120px; background: white; margin: 0 auto; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bx bx-qr-scan bx-lg text-primary"></i>
                                        </div>
                                    </div>
                                    <small class="text-white-50">Doctors can scan this QR code to access your virtual pharmacy</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsPaid(rewardId) {
    if(confirm('Mark this reward as paid?')) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'mark_reward_paid',
            reward_id: rewardId
        }, function(response) {
            location.reload();
        });
    }
}

function cancelReward(rewardId) {
    if(confirm('Are you sure you want to cancel this reward?')) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'cancel_reward',
            reward_id: rewardId
        }, function(response) {
            location.reload();
        });
    }
}

function processPayments() {
    if(confirm('Process all pending reward payments?')) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'process_bulk_payments'
        }, function(response) {
            alert('Payments processed successfully!');
            location.reload();
        });
    }
}

function viewRewardDetails(rewardId) {
    // Implementation for viewing reward details
    alert('View reward details for ID: ' + rewardId);
}

function addNote(rewardId) {
    let note = prompt('Add a note for this reward:');
    if(note) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'add_reward_note',
            reward_id: rewardId,
            note: note
        }, function(response) {
            alert('Note added successfully!');
        });
    }
}

function copyLink(button) {
    const input = button.parentElement.querySelector('input');
    input.select();
    document.execCommand('copy');
    
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bx bx-check"></i>';
    setTimeout(() => {
        button.innerHTML = originalText;
    }, 2000);
}

function filterRewards(status) {
    if(status) {
        window.location.href = '?pg=rewards&status=' + status;
    } else {
        window.location.href = '?pg=rewards';
    }
}
</script>

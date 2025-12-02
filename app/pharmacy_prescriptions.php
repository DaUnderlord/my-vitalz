<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pharmacy /</span> E-Prescriptions
    </h4>

    <!-- Prescription Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Pending</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2"><?php echo $pending_prescriptions; ?></h4>
                                <small class="text-warning">(New)</small>
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
                            <span>Processing</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $processing = DB::select('select count(*) as count from e_prescriptions WHERE pharmacy_id='.$user[0]->id.' AND status="processing"');
                                    echo $processing[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-info">(Active)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-loader-alt bx-sm"></i>
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
                            <span>Ready</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $ready = DB::select('select count(*) as count from e_prescriptions WHERE pharmacy_id='.$user[0]->id.' AND status="ready"');
                                    echo $ready[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-success">(Pickup)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-check-circle bx-sm"></i>
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
                            <span>Delivered</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $delivered = DB::select('select count(*) as count from e_prescriptions WHERE pharmacy_id='.$user[0]->id.' AND status="delivered"');
                                    echo $delivered[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-primary">(Complete)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-package bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All E-Prescriptions</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterPrescriptions(this.value)">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="ready">Ready</option>
                            <option value="delivered">Delivered</option>
                        </select>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newPrescriptionModal">
                            <i class="bx bx-plus me-1"></i> New Prescription
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Prescription ID</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Medication</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $all_prescriptions = DB::select('
                                    SELECT ep.*, 
                                    p.first_name as patient_fname, p.last_name as patient_lname, p.email as patient_email,
                                    d.first_name as doctor_fname, d.last_name as doctor_lname, d.specialization
                                    FROM e_prescriptions ep
                                    LEFT JOIN users p ON ep.patient_id = p.id
                                    LEFT JOIN users d ON ep.doctor_id = d.id
                                    WHERE ep.pharmacy_id = '.$user[0]->id.'
                                    ORDER BY ep.created_at DESC
                                    LIMIT 20
                                ');
                                
                                if(!empty($all_prescriptions)){
                                    foreach($all_prescriptions as $prescription){
                                        $status_class = '';
                                        $status_icon = '';
                                        switch($prescription->status){
                                            case 'pending':
                                                $status_class = 'bg-warning';
                                                $status_icon = 'bx-time';
                                                break;
                                            case 'processing':
                                                $status_class = 'bg-info';
                                                $status_icon = 'bx-loader-alt';
                                                break;
                                            case 'ready':
                                                $status_class = 'bg-success';
                                                $status_icon = 'bx-check-circle';
                                                break;
                                            case 'delivered':
                                                $status_class = 'bg-primary';
                                                $status_icon = 'bx-package';
                                                break;
                                            case 'cancelled':
                                                $status_class = 'bg-danger';
                                                $status_icon = 'bx-x-circle';
                                                break;
                                        }
                                ?>
                                <tr>
                                    <td>
                                        <span class="fw-medium text-primary">#<?php echo $prescription->prescription_id; ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="../assets/img/avatars/user.png" alt="Avatar" class="rounded-circle" />
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo $prescription->patient_fname.' '.$prescription->patient_lname; ?></h6>
                                                <small class="text-muted"><?php echo $prescription->patient_email; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium"><?php echo $prescription->doctor_fname.' '.$prescription->doctor_lname; ?></span>
                                            <br><small class="text-muted"><?php echo $prescription->specialization ?? 'General'; ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium"><?php echo $prescription->medication_name; ?></span>
                                            <br><small class="text-muted"><?php echo $prescription->dosage.' - '.$prescription->frequency; ?></small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-label-secondary"><?php echo $prescription->quantity; ?></span></td>
                                    <td>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <i class="bx <?php echo $status_icon; ?> me-1"></i>
                                            <?php echo ucfirst($prescription->status); ?>
                                        </span>
                                    </td>
                                    <td><small><?php echo date('M d, Y H:i', strtotime($prescription->created_at)); ?></small></td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" onclick="viewPrescription('<?php echo $prescription->id; ?>')">
                                                    <i class="bx bx-show me-1"></i> View Details
                                                </a>
                                                <?php if($prescription->status == 'pending'){ ?>
                                                <a class="dropdown-item" href="#" onclick="updateStatus('<?php echo $prescription->id; ?>', 'processing')">
                                                    <i class="bx bx-play me-1"></i> Start Processing
                                                </a>
                                                <?php } ?>
                                                <?php if($prescription->status == 'processing'){ ?>
                                                <a class="dropdown-item" href="#" onclick="updateStatus('<?php echo $prescription->id; ?>', 'ready')">
                                                    <i class="bx bx-check me-1"></i> Mark Ready
                                                </a>
                                                <?php } ?>
                                                <?php if($prescription->status == 'ready'){ ?>
                                                <a class="dropdown-item" href="#" onclick="updateStatus('<?php echo $prescription->id; ?>', 'delivered')">
                                                    <i class="bx bx-package me-1"></i> Mark Delivered
                                                </a>
                                                <?php } ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="updateStatus('<?php echo $prescription->id; ?>', 'cancelled')">
                                                    <i class="bx bx-x me-1"></i> Cancel
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
                                        <i class="bx bx-receipt bx-lg text-muted"></i>
                                        <p class="text-muted mt-2">No prescriptions yet</p>
                                        <small class="text-muted">Prescriptions from your network doctors will appear here</small>
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
</div>

<!-- New Prescription Modal -->
<div class="modal fade" id="newPrescriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New E-Prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="action" value="add_prescription">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Patient</label>
                            <select class="form-select" name="patient_id" required>
                                <option value="">Select Patient</option>
                                <?php
                                $network_patients = DB::select('
                                    SELECT u.* FROM users u 
                                    JOIN pharmacy_networks pn ON u.id = pn.member_id 
                                    WHERE pn.pharmacy_id = '.$user[0]->id.' AND pn.member_type = "patient" AND pn.status = "active"
                                ');
                                foreach($network_patients as $patient){
                                ?>
                                <option value="<?php echo $patient->id; ?>"><?php echo $patient->first_name.' '.$patient->last_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Doctor</label>
                            <select class="form-select" name="doctor_id" required>
                                <option value="">Select Doctor</option>
                                <?php
                                $network_doctors = DB::select('
                                    SELECT u.* FROM users u 
                                    JOIN pharmacy_networks pn ON u.id = pn.member_id 
                                    WHERE pn.pharmacy_id = '.$user[0]->id.' AND pn.member_type = "doctor" AND pn.status = "active"
                                ');
                                foreach($network_doctors as $doctor){
                                ?>
                                <option value="<?php echo $doctor->id; ?>"><?php echo $doctor->first_name.' '.$doctor->last_name.' ('.$doctor->specialization.')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Medication Name</label>
                            <input type="text" class="form-control" name="medication_name" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dosage</label>
                            <input type="text" class="form-control" name="dosage" placeholder="e.g., 500mg" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Frequency</label>
                            <input type="text" class="form-control" name="frequency" placeholder="e.g., Twice daily" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea class="form-control" name="instructions" rows="3" placeholder="Special instructions for the patient"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Delivery Method</label>
                            <select class="form-select" name="delivery_method">
                                <option value="pickup">Pickup</option>
                                <option value="delivery">Home Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Amount</label>
                            <input type="number" class="form-control" name="total_amount" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(prescriptionId, newStatus) {
    if(confirm('Are you sure you want to update this prescription status?')) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'update_prescription_status',
            prescription_id: prescriptionId,
            status: newStatus
        }, function(response) {
            location.reload();
        });
    }
}

function viewPrescription(prescriptionId) {
    // Implementation for viewing prescription details
    alert('View prescription details for ID: ' + prescriptionId);
}

function filterPrescriptions(status) {
    // Implementation for filtering prescriptions
    if(status) {
        window.location.href = '?pg=prescriptions&status=' + status;
    } else {
        window.location.href = '?pg=prescriptions';
    }
}
</script>

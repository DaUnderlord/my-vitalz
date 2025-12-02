<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pharmacy /</span> Patient Monitoring
    </h4>

    <!-- Monitoring Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Active Patients</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $active_patients = DB::select('select count(distinct patient_id) as count from medication_monitoring WHERE pharmacy_id='.$user[0]->id.' AND status="active"');
                                    echo $active_patients[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-primary">(Monitored)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-user-check bx-sm"></i>
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
                            <span>Due Refills</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $due_refills = DB::select('select count(*) as count from medication_monitoring WHERE pharmacy_id='.$user[0]->id.' AND next_refill_date <= CURDATE() AND status="active"');
                                    echo $due_refills[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-warning">(Today)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-calendar-exclamation bx-sm"></i>
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
                            <span>Compliance Rate</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $compliance = DB::select('select avg(compliance_percentage) as rate from medication_monitoring WHERE pharmacy_id='.$user[0]->id.' AND status="active"');
                                    echo round($compliance[0]->rate ?? 0, 1); 
                                    ?>%
                                </h4>
                                <small class="text-success">(Average)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-check-shield bx-sm"></i>
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
                            <span>Overdue</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    <?php 
                                    $overdue = DB::select('select count(*) as count from medication_monitoring WHERE pharmacy_id='.$user[0]->id.' AND next_refill_date < DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND status="active"');
                                    echo $overdue[0]->count ?? 0; 
                                    ?>
                                </h4>
                                <small class="text-danger">(7+ days)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-error-circle bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Monitoring List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Patient Medication Monitoring</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterMonitoring(this.value)">
                            <option value="">All Patients</option>
                            <option value="due">Due for Refill</option>
                            <option value="overdue">Overdue</option>
                            <option value="compliant">High Compliance</option>
                            <option value="non_compliant">Low Compliance</option>
                        </select>
                        <button class="btn btn-sm btn-success" onclick="sendRefillReminders()">
                            <i class="bx bx-bell me-1"></i> Send Reminders
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Medication</th>
                                    <th>Last Refill</th>
                                    <th>Next Refill</th>
                                    <th>Compliance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $monitoring = DB::select('
                                    SELECT mm.*, 
                                    u.first_name, u.last_name, u.email, u.phone,
                                    ep.medication_name, ep.dosage, ep.frequency
                                    FROM medication_monitoring mm
                                    LEFT JOIN users u ON mm.patient_id = u.id
                                    LEFT JOIN e_prescriptions ep ON mm.prescription_id = ep.id
                                    WHERE mm.pharmacy_id = '.$user[0]->id.'
                                    ORDER BY mm.next_refill_date ASC
                                    LIMIT 50
                                ');
                                
                                if(!empty($monitoring)){
                                    foreach($monitoring as $monitor){
                                        $days_until_refill = (strtotime($monitor->next_refill_date) - time()) / (60 * 60 * 24);
                                        
                                        $refill_status = '';
                                        $refill_class = '';
                                        if($days_until_refill < -7){
                                            $refill_status = 'Overdue';
                                            $refill_class = 'bg-danger';
                                        } elseif($days_until_refill < 0){
                                            $refill_status = 'Due';
                                            $refill_class = 'bg-warning';
                                        } elseif($days_until_refill <= 3){
                                            $refill_status = 'Due Soon';
                                            $refill_class = 'bg-info';
                                        } else {
                                            $refill_status = 'On Track';
                                            $refill_class = 'bg-success';
                                        }
                                        
                                        $compliance_class = '';
                                        if($monitor->compliance_percentage >= 80){
                                            $compliance_class = 'text-success';
                                        } elseif($monitor->compliance_percentage >= 60){
                                            $compliance_class = 'text-warning';
                                        } else {
                                            $compliance_class = 'text-danger';
                                        }
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="../assets/img/avatars/user.png" alt="Avatar" class="rounded-circle" />
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo $monitor->first_name.' '.$monitor->last_name; ?></h6>
                                                <small class="text-muted"><?php echo $monitor->email; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium"><?php echo $monitor->medication_name; ?></span>
                                            <br><small class="text-muted"><?php echo $monitor->dosage.' - '.$monitor->frequency; ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span><?php echo date('M d, Y', strtotime($monitor->last_refill_date)); ?></span>
                                        <br><small class="text-muted">Qty: <?php echo $monitor->last_refill_quantity; ?></small>
                                    </td>
                                    <td>
                                        <span><?php echo date('M d, Y', strtotime($monitor->next_refill_date)); ?></span>
                                        <?php if($days_until_refill <= 0){ ?>
                                        <br><small class="text-danger"><?php echo abs(floor($days_until_refill)); ?> days <?php echo $days_until_refill < 0 ? 'overdue' : 'due'; ?></small>
                                        <?php } else { ?>
                                        <br><small class="text-muted">In <?php echo floor($days_until_refill); ?> days</small>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="<?php echo $compliance_class; ?> fw-medium"><?php echo $monitor->compliance_percentage; ?>%</span>
                                            <div class="progress ms-2" style="width: 60px; height: 6px;">
                                                <div class="progress-bar <?php echo str_replace('text-', 'bg-', $compliance_class); ?>" 
                                                     style="width: <?php echo $monitor->compliance_percentage; ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $refill_class; ?>"><?php echo $refill_status; ?></span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" onclick="viewPatientHistory('<?php echo $monitor->patient_id; ?>')">
                                                    <i class="bx bx-history me-1"></i> View History
                                                </a>
                                                <a class="dropdown-item" href="#" onclick="sendReminder('<?php echo $monitor->id; ?>')">
                                                    <i class="bx bx-bell me-1"></i> Send Reminder
                                                </a>
                                                <a class="dropdown-item" href="#" onclick="updateCompliance('<?php echo $monitor->id; ?>')">
                                                    <i class="bx bx-edit me-1"></i> Update Compliance
                                                </a>
                                                <a class="dropdown-item" href="#" onclick="scheduleRefill('<?php echo $monitor->id; ?>')">
                                                    <i class="bx bx-calendar-plus me-1"></i> Schedule Refill
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="stopMonitoring('<?php echo $monitor->id; ?>')">
                                                    <i class="bx bx-stop-circle me-1"></i> Stop Monitoring
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
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bx bx-user-check bx-lg text-muted"></i>
                                        <p class="text-muted mt-2">No patients being monitored yet</p>
                                        <small class="text-muted">Patient monitoring will appear here when prescriptions are processed</small>
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

    <!-- Refill Notifications Panel -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Refill Notifications</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-warning">
                                        <i class="bx bx-bell"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Auto Reminders</h6>
                                    <small class="text-muted">Send 3 days before due date</small>
                                </div>
                                <div class="form-check form-switch ms-auto">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-info">
                                        <i class="bx bx-envelope"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Email Notifications</h6>
                                    <small class="text-muted">Send via email</small>
                                </div>
                                <div class="form-check form-switch ms-auto">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-success">
                                        <i class="bx bx-message"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">SMS Notifications</h6>
                                    <small class="text-muted">Send via SMS</small>
                                </div>
                                <div class="form-check form-switch ms-auto">
                                    <input class="form-check-input" type="checkbox">
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
function viewPatientHistory(patientId) {
    // Implementation for viewing patient medication history
    alert('View history for patient ID: ' + patientId);
}

function sendReminder(monitorId) {
    if(confirm('Send refill reminder to this patient?')) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'send_reminder',
            monitor_id: monitorId
        }, function(response) {
            alert('Reminder sent successfully!');
        });
    }
}

function sendRefillReminders() {
    if(confirm('Send refill reminders to all patients due for refills?')) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'send_bulk_reminders'
        }, function(response) {
            alert('Reminders sent successfully!');
        });
    }
}

function updateCompliance(monitorId) {
    let compliance = prompt('Enter compliance percentage (0-100):');
    if(compliance && !isNaN(compliance) && compliance >= 0 && compliance <= 100) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'update_compliance',
            monitor_id: monitorId,
            compliance_percentage: compliance
        }, function(response) {
            location.reload();
        });
    }
}

function scheduleRefill(monitorId) {
    let refillDate = prompt('Enter next refill date (YYYY-MM-DD):');
    if(refillDate) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'schedule_refill',
            monitor_id: monitorId,
            next_refill_date: refillDate
        }, function(response) {
            location.reload();
        });
    }
}

function stopMonitoring(monitorId) {
    if(confirm('Are you sure you want to stop monitoring this patient?')) {
        $.post('/dashboard-pharmacy', {
            _token: '<?php echo csrf_token(); ?>',
            action: 'stop_monitoring',
            monitor_id: monitorId
        }, function(response) {
            location.reload();
        });
    }
}

function filterMonitoring(filter) {
    if(filter) {
        window.location.href = '?pg=monitoring&filter=' + filter;
    } else {
        window.location.href = '?pg=monitoring';
    }
}
</script>

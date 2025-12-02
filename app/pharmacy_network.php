<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pharmacy /</span> My Network
    </h4>

    <!-- Network Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total Doctors</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2"><?php echo $total_network_doctors; ?></h4>
                                <small class="text-success">(Active)</small>
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
                            <span>Total Hospitals</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2"><?php echo $total_network_hospitals; ?></h4>
                                <small class="text-info">(Active)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-building-house bx-sm"></i>
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
                            <span>Total Patients</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2"><?php echo $total_network_patients; ?></h4>
                                <small class="text-success">(Active)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-group bx-sm"></i>
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
                            <span>Network Score</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2"><?php echo min(100, ($total_network_doctors * 10) + ($total_network_hospitals * 15) + ($total_network_patients * 2)); ?>%</h4>
                                <small class="text-warning">(Growing)</small>
                            </div>
                        </div>
                        <span class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-network-chart bx-sm"></i>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Network Member -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Network Member</h5>
                    <small class="text-muted">Invite doctors, hospitals, or patients to your network</small>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="hidden" name="action" value="add_network_member">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Member Type</label>
                                <select class="form-select" name="member_type" required>
                                    <option value="">Select Type</option>
                                    <option value="doctor">Doctor</option>
                                    <option value="hospital">Hospital</option>
                                    <option value="patient">Patient</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="member_email" placeholder="Enter member's email" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block w-100">
                                    <i class="bx bx-plus me-1"></i> Add Member
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Network Members Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Network Members</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-doctors-tab" data-bs-toggle="pill" data-bs-target="#pills-doctors" type="button" role="tab">
                                <i class="bx bx-user-plus me-1"></i> Doctors (<?php echo $total_network_doctors; ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-hospitals-tab" data-bs-toggle="pill" data-bs-target="#pills-hospitals" type="button" role="tab">
                                <i class="bx bx-building-house me-1"></i> Hospitals (<?php echo $total_network_hospitals; ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-patients-tab" data-bs-toggle="pill" data-bs-target="#pills-patients" type="button" role="tab">
                                <i class="bx bx-group me-1"></i> Patients (<?php echo $total_network_patients; ?>)
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
                        <!-- Doctors Tab -->
                        <div class="tab-pane fade show active" id="pills-doctors" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Doctor</th>
                                            <th>Specialization</th>
                                            <th>License</th>
                                            <th>Prescriptions Sent</th>
                                            <th>Joined Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $network_doctors = DB::select('
                                            SELECT u.*, pn.status as network_status, pn.created_at as joined_at,
                                            (SELECT COUNT(*) FROM e_prescriptions WHERE doctor_id = u.id AND pharmacy_id = '.$user[0]->id.') as prescription_count
                                            FROM users u 
                                            JOIN pharmacy_networks pn ON u.id = pn.member_id 
                                            WHERE pn.pharmacy_id = '.$user[0]->id.' AND pn.member_type = "doctor" 
                                            ORDER BY pn.created_at DESC
                                        ');
                                        
                                        if(!empty($network_doctors)){
                                            foreach($network_doctors as $doctor){
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <img src="../assets/img/avatars/user.png" alt="Avatar" class="rounded-circle" />
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo $doctor->first_name.' '.$doctor->last_name; ?></h6>
                                                        <small class="text-muted"><?php echo $doctor->email; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-label-info"><?php echo $doctor->specialization ?? 'General'; ?></span></td>
                                            <td><small><?php echo $doctor->license_type ?? 'N/A'; ?></small></td>
                                            <td><span class="badge bg-label-primary"><?php echo $doctor->prescription_count; ?></span></td>
                                            <td><small><?php echo date('M d, Y', strtotime($doctor->joined_at)); ?></small></td>
                                            <td>
                                                <span class="badge bg-<?php echo $doctor->network_status == 'active' ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($doctor->network_status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i class="bx bx-show me-1"></i> View Profile</a>
                                                        <a class="dropdown-item" href="#"><i class="bx bx-receipt me-1"></i> View Prescriptions</a>
                                                        <a class="dropdown-item" href="#"><i class="bx bx-gift me-1"></i> View Rewards</a>
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
                                                <i class="bx bx-user-plus bx-lg text-muted"></i>
                                                <p class="text-muted mt-2">No doctors in your network yet</p>
                                                <small class="text-muted">Add doctors to start receiving e-prescriptions</small>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Hospitals Tab -->
                        <div class="tab-pane fade" id="pills-hospitals" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Hospital</th>
                                            <th>Contact</th>
                                            <th>Prescriptions Sent</th>
                                            <th>Joined Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $network_hospitals = DB::select('
                                            SELECT u.*, pn.status as network_status, pn.created_at as joined_at,
                                            (SELECT COUNT(*) FROM e_prescriptions WHERE hospital_id = u.id AND pharmacy_id = '.$user[0]->id.') as prescription_count
                                            FROM users u 
                                            JOIN pharmacy_networks pn ON u.id = pn.member_id 
                                            WHERE pn.pharmacy_id = '.$user[0]->id.' AND pn.member_type = "hospital" 
                                            ORDER BY pn.created_at DESC
                                        ');
                                        
                                        if(!empty($network_hospitals)){
                                            foreach($network_hospitals as $hospital){
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <i class="bx bx-building-house"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo $hospital->first_name.' '.$hospital->last_name; ?></h6>
                                                        <small class="text-muted"><?php echo $hospital->email; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><small><?php echo $hospital->phone ?? 'N/A'; ?></small></td>
                                            <td><span class="badge bg-label-primary"><?php echo $hospital->prescription_count; ?></span></td>
                                            <td><small><?php echo date('M d, Y', strtotime($hospital->joined_at)); ?></small></td>
                                            <td>
                                                <span class="badge bg-<?php echo $hospital->network_status == 'active' ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($hospital->network_status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i class="bx bx-show me-1"></i> View Profile</a>
                                                        <a class="dropdown-item" href="#"><i class="bx bx-receipt me-1"></i> View Prescriptions</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="bx bx-building-house bx-lg text-muted"></i>
                                                <p class="text-muted mt-2">No hospitals in your network yet</p>
                                                <small class="text-muted">Add hospitals to expand your network</small>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Patients Tab -->
                        <div class="tab-pane fade" id="pills-patients" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Patient</th>
                                            <th>Contact</th>
                                            <th>Medications</th>
                                            <th>Last Visit</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $network_patients = DB::select('
                                            SELECT u.*, pn.status as network_status, pn.created_at as joined_at,
                                            (SELECT COUNT(DISTINCT medication_name) FROM medication_monitoring WHERE patient_id = u.id AND pharmacy_id = '.$user[0]->id.') as medication_count
                                            FROM users u 
                                            JOIN pharmacy_networks pn ON u.id = pn.member_id 
                                            WHERE pn.pharmacy_id = '.$user[0]->id.' AND pn.member_type = "patient" 
                                            ORDER BY pn.created_at DESC
                                        ');
                                        
                                        if(!empty($network_patients)){
                                            foreach($network_patients as $patient){
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <img src="../assets/img/avatars/user.png" alt="Avatar" class="rounded-circle" />
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo $patient->first_name.' '.$patient->last_name; ?></h6>
                                                        <small class="text-muted"><?php echo $patient->email; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><small><?php echo $patient->phone ?? 'N/A'; ?></small></td>
                                            <td><span class="badge bg-label-info"><?php echo $patient->medication_count; ?></span></td>
                                            <td><small><?php echo date('M d, Y', strtotime($patient->joined_at)); ?></small></td>
                                            <td>
                                                <span class="badge bg-<?php echo $patient->network_status == 'active' ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($patient->network_status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i class="bx bx-show me-1"></i> View Profile</a>
                                                        <a class="dropdown-item" href="?pg=monitoring&patient=<?php echo $patient->id; ?>"><i class="bx bx-pulse me-1"></i> View Monitoring</a>
                                                        <a class="dropdown-item" href="#"><i class="bx bx-receipt me-1"></i> Medication History</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="bx bx-group bx-lg text-muted"></i>
                                                <p class="text-muted mt-2">No patients in your network yet</p>
                                                <small class="text-muted">Add patients to start monitoring their medications</small>
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
    </div>
</div>

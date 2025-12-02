<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pharmacy /</span> Dashboard
    </h4>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Welcome <?php echo $user[0]->first_name; ?>! 🎉</h5>
                            <p class="mb-4">
                                Your pharmacy network is growing. You have <span class="fw-bold"><?php echo $total_network_doctors + $total_network_patients + $total_network_hospitals; ?></span> total network members and <span class="fw-bold"><?php echo $pending_prescriptions; ?></span> pending prescriptions.
                            </p>
                            <a href="?pg=prescriptions" class="btn btn-sm btn-outline-primary">View Prescriptions</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img
                                src="../assets/img/illustrations/man-with-laptop-light.png"
                                height="140"
                                alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="bx bx-receipt bg-color-pharmacy text-white p-2 rounded"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Pending Prescriptions</span>
                            <h3 class="card-title mb-2"><?php echo $pending_prescriptions; ?></h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> New</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="bx bx-package bg-color-orange text-white p-2 rounded"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Low Stock Items</span>
                            <h3 class="card-title mb-2"><?php echo $low_stock_items; ?></h3>
                            <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> Reorder</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Network Overview -->
    <div class="row">
        <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Network Overview</h5>
                        <small class="text-muted">Your healthcare network</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <i class="bx bx-user-plus bg-color-blue text-white p-2 rounded"></i>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Doctors</h6>
                                    <small class="text-muted">Network members</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold"><?php echo $total_network_doctors; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <i class="bx bx-building-house bg-color-purple text-white p-2 rounded"></i>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Hospitals</h6>
                                    <small class="text-muted">Network members</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold"><?php echo $total_network_hospitals; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <i class="bx bx-group bg-color-green text-white p-2 rounded"></i>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Patients</h6>
                                    <small class="text-muted">Network members</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold"><?php echo $total_network_patients; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Prescriptions -->
        <div class="col-md-6 col-lg-8 order-1 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Recent E-Prescriptions</h5>
                    <div class="dropdown">
                        <button
                            class="btn p-0"
                            type="button"
                            id="recentPrescriptions"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentPrescriptions">
                            <a class="dropdown-item" href="?pg=prescriptions">View All</a>
                            <a class="dropdown-item" href="?pg=prescriptions&filter=pending">Pending Only</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Medication</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php
                                if(!empty($recent_prescriptions)){
                                    foreach($recent_prescriptions as $prescription){
                                        $status_class = '';
                                        $status_text = $prescription->status;
                                        switch($prescription->status){
                                            case 'pending':
                                                $status_class = 'bg-warning';
                                                break;
                                            case 'processing':
                                                $status_class = 'bg-info';
                                                break;
                                            case 'ready':
                                                $status_class = 'bg-success';
                                                break;
                                            case 'delivered':
                                                $status_class = 'bg-primary';
                                                break;
                                        }
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="../assets/img/avatars/user.png" alt="Avatar" class="rounded-circle" />
                                            </div>
                                            <div>
                                                <span class="fw-medium"><?php echo $prescription->patient_name ?? 'N/A'; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="fw-medium"><?php echo $prescription->doctor_name ?? 'N/A'; ?></span></td>
                                    <td><span class="fw-medium"><?php echo $prescription->medication_name ?? 'N/A'; ?></span></td>
                                    <td><span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($status_text); ?></span></td>
                                    <td><small class="text-muted"><?php echo date('M d, Y', strtotime($prescription->created_at)); ?></small></td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="py-4">
                                            <i class="bx bx-receipt bx-lg text-muted"></i>
                                            <p class="text-muted mt-2">No recent prescriptions</p>
                                            <a href="?pg=network" class="btn btn-sm btn-outline-primary">Build Your Network</a>
                                        </div>
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

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="?pg=network" class="btn btn-outline-primary d-flex align-items-center w-100 p-3">
                                <i class="bx bx-network-chart me-2 bx-sm"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Manage Network</div>
                                    <small class="text-muted">Add doctors & patients</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="?pg=inventory" class="btn btn-outline-success d-flex align-items-center w-100 p-3">
                                <i class="bx bx-package me-2 bx-sm"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Add Inventory</div>
                                    <small class="text-muted">Stock medications</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="?pg=prescriptions" class="btn btn-outline-info d-flex align-items-center w-100 p-3">
                                <i class="bx bx-receipt me-2 bx-sm"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Process Prescriptions</div>
                                    <small class="text-muted">Handle orders</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="?pg=monitoring" class="btn btn-outline-warning d-flex align-items-center w-100 p-3">
                                <i class="bx bx-pulse me-2 bx-sm"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Patient Monitoring</div>
                                    <small class="text-muted">Track compliance</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

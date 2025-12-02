<!-- This file contains the UI portion - will be appended to doctor_patient_vitals_detail.php -->

<div class="container-xxl flex-grow-1 container-p-y">

<!-- Back Button -->
<div class="mb-3">
    <a href="?pg=monitoring" class="btn btn-sm btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to Vitals Monitoring
    </a>
</div>

<!-- Patient Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <?php if($patient->photo){ ?>
                                <img src="/assets/users/<?php echo $patient->photo; ?>" alt="<?php echo $patient->first_name; ?>" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <?php } else { ?>
                                <div class="avatar avatar-xl me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 2rem;">
                                        <?php echo strtoupper(substr($patient->first_name, 0, 1)); ?>
                                    </span>
                                </div>
                            <?php } ?>
                            <div>
                                <h4 class="mb-1"><?php echo $patient->first_name.' '.$patient->last_name; ?></h4>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-label-secondary"><i class="bx bx-envelope me-1"></i><?php echo $patient->email; ?></span>
                                    <?php if($patient->phone){ ?>
                                        <span class="badge bg-label-secondary"><i class="bx bx-phone me-1"></i><?php echo $patient->phone; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <?php
                        $status_config = [
                            'critical' => ['badge' => 'danger', 'icon' => 'error', 'label' => 'Critical'],
                            'high' => ['badge' => 'warning', 'icon' => 'alarm', 'label' => 'High Risk'],
                            'moderate' => ['badge' => 'info', 'icon' => 'info-circle', 'label' => 'Moderate'],
                            'normal' => ['badge' => 'success', 'icon' => 'check-circle', 'label' => 'Normal']
                        ];
                        $config = $status_config[$overall_status];
                        ?>
                        <div class="mb-2">
                            <span class="badge bg-<?php echo $config['badge']; ?> p-2" style="font-size: 1rem;">
                                <i class="bx bx-<?php echo $config['icon']; ?> me-1"></i><?php echo $config['label']; ?>
                            </span>
                        </div>
                        <div class="text-muted small">
                            Risk Score: <strong><?php echo $risk_score; ?>/100</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap justify-content-center">
                    <button class="btn btn-primary" onclick="openPrescriptionModal()">
                        <i class="bx bx-plus-medical me-1"></i> Create Prescription
                    </button>
                    <button class="btn btn-info" onclick="openAppointmentModal()">
                        <i class="bx bx-calendar-plus me-1"></i> Schedule Appointment
                    </button>
                    <button class="btn btn-success" onclick="openMessageModal()">
                        <i class="bx bx-message-dots me-1"></i> Send Message
                    </button>
                    <button class="btn btn-warning" onclick="openAlertModal()">
                        <i class="bx bx-bell me-1"></i> Send Alert
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vitals Cards Grid -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3"><i class="bx bx-pulse me-2"></i>Current Vitals</h5>
    </div>
    
    <?php if(empty($vitals_by_type)){ ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bx bx-line-chart bx-lg text-muted mb-3"></i>
                    <h5 class="text-muted">No Vital Readings</h5>
                    <p class="text-muted">This patient hasn't logged any vitals in the last 90 days.</p>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <?php foreach($vitals_by_type as $vital){ ?>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 vital-card vital-<?php echo $vital['status']; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0"><?php echo $vital['vital_name']; ?></h6>
                        <?php
                        $status_badges = [
                            'critical' => 'danger',
                            'abnormal' => 'warning',
                            'normal' => 'success',
                            'unknown' => 'secondary'
                        ];
                        ?>
                        <span class="badge bg-<?php echo $status_badges[$vital['status']]; ?>">
                            <?php echo ucfirst($vital['status']); ?>
                        </span>
                    </div>
                    
                    <div class="vital-value mb-2">
                        <h3 class="mb-0">
                            <?php echo $vital['latest']->reading; ?> 
                            <small class="text-muted"><?php echo $vital['si_unit']; ?></small>
                        </h3>
                    </div>
                    
                    <?php if($vital['threshold']){ ?>
                    <div class="vital-range mb-2">
                        <small class="text-muted">
                            Normal: <?php echo $vital['threshold']->min_normal; ?>-<?php echo $vital['threshold']->max_normal; ?> <?php echo $vital['si_unit']; ?>
                        </small>
                    </div>
                    <?php } ?>
                    
                    <div class="vital-meta">
                        <small class="text-muted">
                            <i class="bx bx-time-five me-1"></i>
                            <?php echo date('M d, Y h:i A', strtotime($vital['latest']->created_at)); ?>
                        </small>
                    </div>
                    
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-primary w-100" onclick="showVitalHistory(<?php echo $vital['vital_id']; ?>, '<?php echo addslashes($vital['vital_name']); ?>')">
                            <i class="bx bx-line-chart me-1"></i> View Trend
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    <?php } ?>
</div>

<!-- History Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#prescriptions-tab" role="tab">
                            <i class="bx bx-receipt me-1"></i> Prescriptions (<?php echo count($prescriptions); ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#appointments-tab" role="tab">
                            <i class="bx bx-calendar me-1"></i> Appointments (<?php echo count($appointments); ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#vitals-history-tab" role="tab">
                            <i class="bx bx-line-chart me-1"></i> Vitals History
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Prescriptions Tab -->
                    <div class="tab-pane fade show active" id="prescriptions-tab" role="tabpanel">
                        <?php if(empty($prescriptions)){ ?>
                            <div class="text-center py-4">
                                <i class="bx bx-receipt bx-lg text-muted mb-2"></i>
                                <p class="text-muted mb-0">No prescriptions yet</p>
                            </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Drug Name</th>
                                            <th>Dosage</th>
                                            <th>Frequency</th>
                                            <th>Duration</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($prescriptions as $rx){ ?>
                                        <tr>
                                            <td><strong><?php echo $rx->drug_name; ?></strong></td>
                                            <td><?php echo $rx->dosage ?? 'N/A'; ?></td>
                                            <td><?php echo $rx->frequency ?? 'N/A'; ?></td>
                                            <td><?php echo $rx->duration ?? 'N/A'; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($rx->created_at)); ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <!-- Appointments Tab -->
                    <div class="tab-pane fade" id="appointments-tab" role="tabpanel">
                        <?php if(empty($appointments)){ ?>
                            <div class="text-center py-4">
                                <i class="bx bx-calendar bx-lg text-muted mb-2"></i>
                                <p class="text-muted mb-0">No appointments scheduled</p>
                            </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Channel</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($appointments as $apt){ ?>
                                        <tr>
                                            <td><?php echo $apt->date ?? 'N/A'; ?></td>
                                            <td>
                                                <?php 
                                                if($apt->start_time){
                                                    echo date('h:i A', $apt->start_time);
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-info">
                                                    <?php echo ucfirst($apt->channel ?? 'N/A'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-<?php echo $apt->status == 'confirmed' ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($apt->status); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <!-- Vitals History Tab -->
                    <div class="tab-pane fade" id="vitals-history-tab" role="tabpanel">
                        <div id="vitalHistoryChart" style="height: 400px;">
                            <div class="text-center py-5">
                                <i class="bx bx-line-chart bx-lg text-muted mb-2"></i>
                                <p class="text-muted">Select a vital from the cards above to view trend</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<style>
.vital-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-left: 4px solid transparent;
}
.vital-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
.vital-critical {
    border-left-color: #ff3e1d;
    background: linear-gradient(135deg, rgba(255,62,29,0.03), transparent);
}
.vital-abnormal {
    border-left-color: #ffab00;
    background: linear-gradient(135deg, rgba(255,171,0,0.03), transparent);
}
.vital-normal {
    border-left-color: #71dd37;
    background: linear-gradient(135deg, rgba(113,221,55,0.03), transparent);
}
.vital-value h3 {
    color: #344054;
    font-weight: 700;
}
</style>

<!-- Include modals file -->
<?php include app_path('doctor_patient_vitals_modals.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const patientId = <?php echo $patient_id; ?>;
const vitalsData = <?php echo json_encode($vitals_by_type); ?>;

function showVitalHistory(vitalId, vitalName) {
    // Find vital data
    const vital = Object.values(vitalsData).find(v => v.vital_id == vitalId);
    if(!vital) return;
    
    // Switch to vitals history tab
    const tab = new bootstrap.Tab(document.querySelector('[href="#vitals-history-tab"]'));
    tab.show();
    
    // Smoothly scroll the chart into view after the tab renders
    setTimeout(() => {
        const chartContainer = document.getElementById('vitalHistoryChart');
        if (chartContainer) {
            try {
                chartContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (e) {
                // Fallback for older browsers
                const y = chartContainer.getBoundingClientRect().top + window.pageYOffset - 20;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        }
    }, 200);
    
    // Prepare chart data
    const history = vital.history.reverse(); // oldest first
    const labels = history.map(h => new Date(h.created_at).toLocaleDateString());
    const values = history.map(h => parseFloat(h.reading));
    
    // Destroy existing chart if any
    if(window.vitalChart) {
        window.vitalChart.destroy();
    }
    
    // Create chart
    const ctx = document.getElementById('vitalHistoryChart');
    ctx.innerHTML = '<canvas id="vitalChartCanvas" style="height: 400px;"></canvas>';
    const canvas = document.getElementById('vitalChartCanvas');
    
    console.log('Creating chart for:', vitalName, 'with', history.length, 'data points');
    
    window.vitalChart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: vitalName + ' (' + vital.si_unit + ')',
                data: values,
                borderColor: '#5a5fc7',
                backgroundColor: 'rgba(90, 95, 199, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: vitalName + ' Trend (Last 90 Days)'
                }
            },
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
}
</script>

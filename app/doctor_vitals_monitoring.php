<?php
// Handle both array and object formats
$user_obj = is_array($user) ? $user[0] : $user;
$uid = isset($user_obj->id) ? (int)$user_obj->id : 0;

// Get filter parameters
$status_filter = isset($_GET['status']) ? strtolower(trim($_GET['status'])) : 'all';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$date_range = isset($_GET['date_range']) ? (int)$_GET['date_range'] : 7; // days

// Build query for patients managed by this doctor
$where_conditions = ['p.doctor = '.$uid, 'p.doctor_approve = 1', 'p.user_approve = 1'];

// Get all patients with their latest vitals
$patients_query = '
    SELECT 
        u.id as patient_id,
        u.first_name,
        u.last_name,
        u.email,
        u.phone,
        u.photo,
        p.created_at as relationship_date,
        (SELECT COUNT(*) FROM vital_readings WHERE user = u.id) as total_readings,
        (SELECT MAX(created_at) FROM vital_readings WHERE user = u.id) as last_reading_date
    FROM patients p
    INNER JOIN users u ON p.user = u.id
    WHERE '.implode(' AND ', $where_conditions);

if($search_query){
    $search_safe = DB::connection()->getPdo()->quote('%'.$search_query.'%');
    $patients_query .= ' AND (u.first_name LIKE '.$search_safe.' OR u.last_name LIKE '.$search_safe.' OR u.email LIKE '.$search_safe.')';
}

$patients_query .= ' ORDER BY last_reading_date DESC';

$patients = DB::select($patients_query);

// Debug: Show query and results
// echo "<!-- Debug: Doctor ID = $uid -->";
// echo "<!-- Debug: Query = $patients_query -->";
// echo "<!-- Debug: Patient count = ".count($patients)." -->";

// Get latest vitals for each patient and calculate risk status
$patients_with_vitals = [];
foreach($patients as $patient){
    // Get latest reading for each vital type
    $latest_vitals = DB::select('
        SELECT vr.*, av.name as vital_name, av.si_unit
        FROM vital_readings vr
        INNER JOIN allvitalz av ON vr.vitalz = av.id
        WHERE vr.user = ?
        AND vr.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ORDER BY vr.created_at DESC
    ', [$patient->patient_id, (int)$date_range]);
    
    // Group by vital type (keep only latest)
    $vitals_by_type = [];
    foreach($latest_vitals as $vital){
        if(!isset($vitals_by_type[$vital->vitalz])){
            $vitals_by_type[$vital->vitalz] = $vital;
        }
    }
    
    // Calculate risk status
    $risk_status = 'normal';
    $abnormal_count = 0;
    $critical_count = 0;
    
    foreach($vitals_by_type as $vital){
        // Get threshold for this vital
        $threshold = DB::select('
            SELECT * FROM vital_thresholds 
            WHERE vital_id = ? AND (doctor_id = ? OR doctor_id IS NULL)
            ORDER BY doctor_id DESC LIMIT 1
        ', [$vital->vitalz, $uid]);
        
        if(!empty($threshold)){
            $t = $threshold[0];
            $reading_value = (float)$vital->reading;
            
            // Check if critical
            if(($t->min_critical && $reading_value < $t->min_critical) || 
               ($t->max_critical && $reading_value > $t->max_critical)){
                $critical_count++;
            }
            // Check if abnormal
            elseif(($t->min_normal && $reading_value < $t->min_normal) || 
                   ($t->max_normal && $reading_value > $t->max_normal)){
                $abnormal_count++;
            }
        }
    }
    
    // Determine overall risk status
    if($critical_count > 0){
        $risk_status = 'critical';
    } elseif($abnormal_count >= 2){
        $risk_status = 'high';
    } elseif($abnormal_count == 1){
        $risk_status = 'moderate';
    }
    
    // Apply status filter
    if($status_filter != 'all' && $risk_status != $status_filter){
        continue;
    }
    
    $patient->vitals = array_values($vitals_by_type);
    $patient->risk_status = $risk_status;
    $patient->abnormal_count = $abnormal_count;
    $patient->critical_count = $critical_count;
    
    $patients_with_vitals[] = $patient;
}

// Get statistics
$total_patients = count($patients);
$critical_patients = count(array_filter($patients_with_vitals, fn($p) => $p->risk_status == 'critical'));
$high_risk_patients = count(array_filter($patients_with_vitals, fn($p) => $p->risk_status == 'high'));
$moderate_patients = count(array_filter($patients_with_vitals, fn($p) => $p->risk_status == 'moderate'));
$normal_patients = count(array_filter($patients_with_vitals, fn($p) => $p->risk_status == 'normal'));
?>

<div class="container-xxl flex-grow-1 container-p-y">

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #5a5fc7 0%, #4a4eb3 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white mb-1"><i class="bx bx-pulse me-2"></i>Vitals Monitoring</h4>
                        <p class="mb-0 opacity-75">Monitor and manage your patients' vital signs in real-time</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="?pg=thresholds" class="btn btn-light btn-sm">
                            <i class="bx bx-slider me-1"></i> Manage Thresholds
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-label-primary mb-2">Total</span>
                        <h3 class="mb-0"><?php echo $total_patients; ?></h3>
                        <small class="text-muted">Patients</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-user bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-label-danger mb-2">Critical</span>
                        <h3 class="mb-0"><?php echo $critical_patients; ?></h3>
                        <small class="text-muted">Require Attention</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="bx bx-error bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-label-warning mb-2">High Risk</span>
                        <h3 class="mb-0"><?php echo $high_risk_patients; ?></h3>
                        <small class="text-muted">Monitor Closely</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-alarm bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-label-success mb-2">Normal</span>
                        <h3 class="mb-0"><?php echo $normal_patients; ?></h3>
                        <small class="text-muted">Stable Patients</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-check-circle bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <input type="hidden" name="pg" value="monitoring">
                    
                    <div class="col-md-3">
                        <label class="form-label small">Search Patient</label>
                        <input type="text" class="form-control" name="search" placeholder="Name or email..." value="<?php echo htmlspecialchars($search_query); ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label small">Status</label>
                        <select class="form-select" name="status">
                            <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="critical" <?php echo $status_filter == 'critical' ? 'selected' : ''; ?>>🔴 Critical</option>
                            <option value="high" <?php echo $status_filter == 'high' ? 'selected' : ''; ?>>🟠 High Risk</option>
                            <option value="moderate" <?php echo $status_filter == 'moderate' ? 'selected' : ''; ?>>🟡 Moderate</option>
                            <option value="normal" <?php echo $status_filter == 'normal' ? 'selected' : ''; ?>>🟢 Normal</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label small">Date Range</label>
                        <select class="form-select" name="date_range">
                            <option value="7" <?php echo $date_range == '7' ? 'selected' : ''; ?>>Last 7 days</option>
                            <option value="14" <?php echo $date_range == '14' ? 'selected' : ''; ?>>Last 14 days</option>
                            <option value="30" <?php echo $date_range == '30' ? 'selected' : ''; ?>>Last 30 days</option>
                            <option value="90" <?php echo $date_range == '90' ? 'selected' : ''; ?>>Last 90 days</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-filter-alt me-1"></i> Apply Filters
                        </button>
                        <a href="?pg=monitoring" class="btn btn-outline-secondary">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Patients Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Patients Overview</h5>
                <span class="badge bg-primary"><?php echo count($patients_with_vitals); ?> patients</span>
            </div>
            <div class="card-body p-0">
                <?php if(empty($patients_with_vitals)){ ?>
                    <div class="text-center py-5">
                        <i class="bx bx-user-x bx-lg text-muted mb-3"></i>
                        <h5 class="text-muted">No Patients Found</h5>
                        <p class="text-muted">No patients match your current filters.</p>
                    </div>
                <?php } else { ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Status</th>
                                <th>Latest Vitals</th>
                                <th>Last Reading</th>
                                <th>Total Readings</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($patients_with_vitals as $patient){ ?>
                            <tr style="cursor: pointer;" onclick="window.location.href='?pg=patient-vitals&patient_id=<?php echo $patient->patient_id; ?>'">
                                <td>
                                    <div>
                                        <strong><?php echo $patient->first_name.' '.$patient->last_name; ?></strong>
                                        <br><small class="text-muted"><?php echo $patient->email; ?></small>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $status_config = [
                                        'critical' => ['badge' => 'danger', 'icon' => 'error', 'label' => 'Critical'],
                                        'high' => ['badge' => 'warning', 'icon' => 'alarm', 'label' => 'High Risk'],
                                        'moderate' => ['badge' => 'info', 'icon' => 'info-circle', 'label' => 'Moderate'],
                                        'normal' => ['badge' => 'success', 'icon' => 'check-circle', 'label' => 'Normal']
                                    ];
                                    $config = $status_config[$patient->risk_status];
                                    ?>
                                    <span class="badge bg-<?php echo $config['badge']; ?>">
                                        <i class="bx bx-<?php echo $config['icon']; ?> me-1"></i><?php echo $config['label']; ?>
                                    </span>
                                    <?php if($patient->abnormal_count > 0){ ?>
                                        <br><small class="text-muted"><?php echo $patient->abnormal_count; ?> abnormal</small>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if(count($patient->vitals) > 0){ ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php 
                                            $display_count = 0;
                                            foreach($patient->vitals as $vital){ 
                                                if($display_count >= 3) break;
                                                $display_count++;
                                            ?>
                                                <span class="badge bg-label-secondary" style="font-size: 0.7rem;">
                                                    <?php echo substr($vital->vital_name, 0, 15); ?>: <?php echo $vital->reading; ?> <?php echo $vital->si_unit; ?>
                                                </span>
                                            <?php } ?>
                                            <?php if(count($patient->vitals) > 3){ ?>
                                                <span class="badge bg-label-info" style="font-size: 0.7rem;">+<?php echo count($patient->vitals) - 3; ?> more</span>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <small class="text-muted">No recent vitals</small>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($patient->last_reading_date){ ?>
                                        <small><?php echo date('M d, Y', strtotime($patient->last_reading_date)); ?></small>
                                        <br><small class="text-muted"><?php echo date('h:i A', strtotime($patient->last_reading_date)); ?></small>
                                    <?php } else { ?>
                                        <small class="text-muted">Never</small>
                                    <?php } ?>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary"><?php echo $patient->total_readings; ?></span>
                                </td>
                                <td>
                                    <a href="?pg=patient-vitals&patient_id=<?php echo $patient->patient_id; ?>" class="btn btn-sm btn-primary" onclick="event.stopPropagation();">
                                        <i class="bx bx-show me-1"></i> View Details
                                    </a>
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

</div>

<style>
.table tbody tr:hover {
    background-color: rgba(105, 108, 255, 0.04);
    transition: background-color 0.2s ease;
}
</style>

<?php
// Handle both array and object formats
$user_obj = is_array($user) ? $user[0] : $user;
$uid = isset($user_obj->id) ? (int)$user_obj->id : 0;

// Get all vitals
$all_vitals = DB::select('SELECT * FROM allvitalz ORDER BY name');

// Get thresholds (standard + doctor's custom)
$thresholds = DB::select('
    SELECT * FROM vital_thresholds 
    WHERE doctor_id IS NULL OR doctor_id = ?
    ORDER BY vital_id, doctor_id DESC
', [$uid]);

// Group thresholds by vital_id
$thresholds_by_vital = [];
foreach($thresholds as $threshold){
    if(!isset($thresholds_by_vital[$threshold->vital_id])){
        $thresholds_by_vital[$threshold->vital_id] = [
            'standard' => null,
            'custom' => null
        ];
    }
    
    if($threshold->doctor_id === null){
        $thresholds_by_vital[$threshold->vital_id]['standard'] = $threshold;
    } else {
        $thresholds_by_vital[$threshold->vital_id]['custom'] = $threshold;
    }
}

// Handle POST - Save custom threshold
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_threshold'){
    $vital_id = (int)$_POST['vital_id'];
    $vital_name = DB::connection()->getPdo()->quote($_POST['vital_name']);
    $min_normal = $_POST['min_normal'] !== '' ? (float)$_POST['min_normal'] : null;
    $max_normal = $_POST['max_normal'] !== '' ? (float)$_POST['max_normal'] : null;
    $min_critical = $_POST['min_critical'] !== '' ? (float)$_POST['min_critical'] : null;
    $max_critical = $_POST['max_critical'] !== '' ? (float)$_POST['max_critical'] : null;
    
    // Check if custom threshold exists
    $existing = DB::select('
        SELECT * FROM vital_thresholds 
        WHERE doctor_id = ? AND vital_id = ?
    ', [$uid, $vital_id]);
    
    if(!empty($existing)){
        // Update
        DB::update('
            UPDATE vital_thresholds 
            SET min_normal = ?, max_normal = ?, min_critical = ?, max_critical = ?, is_custom = 1, updated_at = ?
            WHERE doctor_id = ? AND vital_id = ?
        ', [$min_normal, $max_normal, $min_critical, $max_critical, date('Y-m-d H:i:s'), $uid, $vital_id]);
    } else {
        // Insert
        DB::insert('
            INSERT INTO vital_thresholds (doctor_id, vital_id, vital_name, min_normal, max_normal, min_critical, max_critical, is_custom, created_at, updated_at)
            VALUES (?, ?, '.$vital_name.', ?, ?, ?, ?, 1, ?, ?)
        ', [$uid, $vital_id, $min_normal, $max_normal, $min_critical, $max_critical, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
    }
    
    echo '<script>window.location.href = "?pg=thresholds&success=threshold_saved";</script>';
    exit;
}

// Handle POST - Reset to standard
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_threshold'){
    $vital_id = (int)$_POST['vital_id'];
    
    DB::delete('
        DELETE FROM vital_thresholds 
        WHERE doctor_id = ? AND vital_id = ?
    ', [$uid, $vital_id]);
    
    echo '<script>window.location.href = "?pg=thresholds&success=threshold_reset";</script>';
    exit;
}
?>

<div class="container-xxl flex-grow-1 container-p-y">

<!-- Back Button -->
<div class="mb-3">
    <a href="?pg=monitoring" class="btn btn-sm btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to Vitals Monitoring
    </a>
</div>

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #5a5fc7 0%, #4a4eb3 100%);">
            <div class="card-body text-white">
                <h4 class="text-white mb-1"><i class="bx bx-slider me-2"></i>Vital Threshold Management</h4>
                <p class="mb-0 opacity-75">Customize normal and critical ranges for your patients' vitals</p>
            </div>
        </div>
    </div>
</div>

<!-- Success Message -->
<?php if(isset($_GET['success'])){ ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-2"></i>
    <?php 
    if($_GET['success'] === 'threshold_saved'){
        echo 'Custom threshold saved successfully!';
    } elseif($_GET['success'] === 'threshold_reset'){
        echo 'Threshold reset to standard values!';
    }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php } ?>

<!-- Info Alert -->
<div class="alert alert-info mb-4">
    <i class="bx bx-info-circle me-2"></i>
    <strong>How it works:</strong> Standard thresholds are based on WHO/AHA medical guidelines. You can customize these ranges for specific patient needs. Your custom thresholds will override the standard values for all your patients.
</div>

<!-- Thresholds Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Vital Thresholds</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Vital Sign</th>
                                <th>Unit</th>
                                <th>Normal Range</th>
                                <th>Critical Range</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($all_vitals as $vital){ ?>
                            <?php
                            $threshold_data = $thresholds_by_vital[$vital->id] ?? ['standard' => null, 'custom' => null];
                            $active_threshold = $threshold_data['custom'] ?? $threshold_data['standard'];
                            $is_custom = $threshold_data['custom'] !== null;
                            ?>
                            <tr>
                                <td><strong><?php echo $vital->name; ?></strong></td>
                                <td><?php echo $vital->si_unit; ?></td>
                                <td>
                                    <?php if($active_threshold){ ?>
                                        <span class="badge bg-label-success">
                                            <?php echo $active_threshold->min_normal; ?> - <?php echo $active_threshold->max_normal; ?>
                                        </span>
                                    <?php } else { ?>
                                        <span class="text-muted">Not set</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($active_threshold){ ?>
                                        <span class="badge bg-label-danger">
                                            <?php if($active_threshold->min_critical){ ?>
                                                &lt; <?php echo $active_threshold->min_critical; ?>
                                            <?php } ?>
                                            <?php if($active_threshold->min_critical && $active_threshold->max_critical){ ?>
                                                or
                                            <?php } ?>
                                            <?php if($active_threshold->max_critical){ ?>
                                                &gt; <?php echo $active_threshold->max_critical; ?>
                                            <?php } ?>
                                        </span>
                                    <?php } else { ?>
                                        <span class="text-muted">Not set</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($is_custom){ ?>
                                        <span class="badge bg-primary">Custom</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">Standard</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editThreshold(<?php echo $vital->id; ?>, '<?php echo addslashes($vital->name); ?>', '<?php echo $vital->si_unit; ?>', <?php echo json_encode($active_threshold); ?>, <?php echo $is_custom ? 'true' : 'false'; ?>)">
                                        <i class="bx bx-edit me-1"></i> <?php echo $is_custom ? 'Edit' : 'Customize'; ?>
                                    </button>
                                    <?php if($is_custom){ ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="reset_threshold">
                                        <input type="hidden" name="vital_id" value="<?php echo $vital->id; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Reset to standard threshold?')">
                                            <i class="bx bx-reset me-1"></i> Reset
                                        </button>
                                    </form>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Edit Threshold Modal -->
<div class="modal fade" id="editThresholdModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-slider me-2"></i>Customize Threshold - <span id="modalVitalName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="save_threshold">
                <input type="hidden" name="vital_id" id="modalVitalId">
                <input type="hidden" name="vital_name" id="modalVitalNameInput">
                
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bx bx-error me-2"></i>
                        <strong>Important:</strong> Custom thresholds will apply to all your patients. Make sure the values are medically appropriate.
                    </div>
                    
                    <h6 class="mb-3">Normal Range</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Minimum Normal <span id="unitLabel1"></span></label>
                            <input type="number" step="0.01" class="form-control" name="min_normal" id="minNormal" placeholder="e.g., 60">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maximum Normal <span id="unitLabel2"></span></label>
                            <input type="number" step="0.01" class="form-control" name="max_normal" id="maxNormal" placeholder="e.g., 100">
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Critical Range</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Minimum Critical <span id="unitLabel3"></span></label>
                            <input type="number" step="0.01" class="form-control" name="min_critical" id="minCritical" placeholder="e.g., 40">
                            <small class="text-muted">Below this value is critical</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maximum Critical <span id="unitLabel4"></span></label>
                            <input type="number" step="0.01" class="form-control" name="max_critical" id="maxCritical" placeholder="e.g., 120">
                            <small class="text-muted">Above this value is critical</small>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i> Save Custom Threshold
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editThreshold(vitalId, vitalName, unit, threshold, isCustom) {
    console.log('editThreshold called:', {vitalId, vitalName, unit, threshold, isCustom});
    
    document.getElementById('modalVitalName').textContent = vitalName;
    document.getElementById('modalVitalId').value = vitalId;
    document.getElementById('modalVitalNameInput').value = vitalName;
    
    // Set unit labels
    const unitText = unit ? '(' + unit + ')' : '';
    document.getElementById('unitLabel1').textContent = unitText;
    document.getElementById('unitLabel2').textContent = unitText;
    document.getElementById('unitLabel3').textContent = unitText;
    document.getElementById('unitLabel4').textContent = unitText;
    
    // Fill in current values - handle both null and object
    if(threshold && typeof threshold === 'object') {
        document.getElementById('minNormal').value = threshold.min_normal || '';
        document.getElementById('maxNormal').value = threshold.max_normal || '';
        document.getElementById('minCritical').value = threshold.min_critical || '';
        document.getElementById('maxCritical').value = threshold.max_critical || '';
    } else {
        document.getElementById('minNormal').value = '';
        document.getElementById('maxNormal').value = '';
        document.getElementById('minCritical').value = '';
        document.getElementById('maxCritical').value = '';
    }
    
    // Show modal
    try {
        const modal = new bootstrap.Modal(document.getElementById('editThresholdModal'));
        modal.show();
    } catch(e) {
        console.error('Error showing modal:', e);
        alert('Error opening modal. Please refresh the page and try again.');
    }
}
</script>

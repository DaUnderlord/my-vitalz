<?php
// Handle both array and object formats
$user_obj = is_array($user) ? $user[0] : $user;
$uid = isset($user_obj->id) ? (int)$user_obj->id : 0;

// Get patient ID from query string
$patient_id = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;

if(!$patient_id){
    echo '<div class="alert alert-danger">Invalid patient ID</div>';
    return;
}

// Verify this patient belongs to this doctor
$relationship = DB::select('
    SELECT * FROM patients 
    WHERE doctor = ? AND user = ? AND doctor_approve = 1 AND user_approve = 1
', [$uid, $patient_id]);

if(empty($relationship)){
    echo '<div class="alert alert-danger">You do not have access to this patient\'s records.</div>';
    return;
}

// Get patient details
$patient = DB::select('SELECT * FROM users WHERE id = ?', [$patient_id]);
if(empty($patient)){
    echo '<div class="alert alert-danger">Patient not found.</div>';
    return;
}
$patient = $patient[0];

// Get all vital readings for this patient (last 90 days)
$vital_readings = DB::select('
    SELECT vr.*, av.name as vital_name, av.si_unit
    FROM vital_readings vr
    INNER JOIN allvitalz av ON vr.vitalz = av.id
    WHERE vr.user = ?
    AND vr.created_at >= DATE_SUB(NOW(), INTERVAL 90 DAY)
    ORDER BY vr.created_at DESC
', [$patient_id]);

// Group vitals by type
$vitals_by_type = [];
foreach($vital_readings as $reading){
    if(!isset($vitals_by_type[$reading->vitalz])){
        $vitals_by_type[$reading->vitalz] = [
            'vital_id' => $reading->vitalz,
            'vital_name' => $reading->vital_name,
            'si_unit' => $reading->si_unit,
            'latest' => $reading,
            'history' => []
        ];
    }
    $vitals_by_type[$reading->vitalz]['history'][] = $reading;
}

// Get thresholds and calculate status for each vital
foreach($vitals_by_type as $vital_id => &$vital_data){
    $threshold = DB::select('
        SELECT * FROM vital_thresholds 
        WHERE vital_id = ? AND (doctor_id = ? OR doctor_id IS NULL)
        ORDER BY doctor_id DESC LIMIT 1
    ', [$vital_id, $uid]);
    
    $vital_data['threshold'] = !empty($threshold) ? $threshold[0] : null;
    
    // Calculate status
    if($vital_data['threshold']){
        $t = $vital_data['threshold'];
        $value = (float)$vital_data['latest']->reading;
        
        if(($t->min_critical && $value < $t->min_critical) || ($t->max_critical && $value > $t->max_critical)){
            $vital_data['status'] = 'critical';
        } elseif(($t->min_normal && $value < $t->min_normal) || ($t->max_normal && $value > $t->max_normal)){
            $vital_data['status'] = 'abnormal';
        } else {
            $vital_data['status'] = 'normal';
        }
    } else {
        $vital_data['status'] = 'unknown';
    }
}

// Get prescription history
$prescriptions = DB::select('
    SELECT * FROM prescriptions 
    WHERE user = ? AND doctor = ?
    ORDER BY created_at DESC
    LIMIT 10
', [$patient_id, $uid]);

// Get appointment history
$appointments = DB::select('
    SELECT * FROM appointments 
    WHERE user = ? AND doctor = ?
    ORDER BY created_at DESC
    LIMIT 10
', [$patient_id, $uid]);

// Calculate overall risk score
$critical_count = count(array_filter($vitals_by_type, fn($v) => $v['status'] == 'critical'));
$abnormal_count = count(array_filter($vitals_by_type, fn($v) => $v['status'] == 'abnormal'));

if($critical_count > 0){
    $overall_status = 'critical';
    $risk_score = 90 + ($critical_count * 2);
} elseif($abnormal_count >= 2){
    $overall_status = 'high';
    $risk_score = 70 + ($abnormal_count * 5);
} elseif($abnormal_count == 1){
    $overall_status = 'moderate';
    $risk_score = 50;
} else {
    $overall_status = 'normal';
    $risk_score = 20;
}
$risk_score = min($risk_score, 100);
?>

<?php include app_path('doctor_patient_vitals_detail_ui.php'); ?>